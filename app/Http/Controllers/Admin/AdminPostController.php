<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DataService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class AdminPostController extends Controller
{
    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function postsIndex(Request $request)
    {
        $query = DB::table('posts');

        $search = $request->input('s');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $category = $request->input('category');
        if ($category) {
            $query->where('category', $category);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        } elseif ($sort === 'title_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($sort === 'title_desc') {
            $query->orderBy('title', 'desc');
        } else {
            $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }

        $totalPosts = $query->count();
        $perPage = 10;
        $totalPages = (int)ceil($totalPosts / $perPage);
        if ($totalPages < 1) $totalPages = 1;

        $currentPage = (int)$request->input('page', 1);
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $perPage;
        $paginatedPosts = $query->skip($offset)->take($perPage)->get()->map(fn($r) => (array) $r)->toArray();

        return view('admin.posts.index', [
            'posts' => $paginatedPosts,
            'search' => $search,
            'category' => $category,
            'sort' => $sort,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function postsCreate()
    {
        return view('admin.posts.form');
    }

    public function postsStore(StorePostRequest $request)
    {
        $slug = Str::slug($request->input('title'));

        if ($this->dataService->getPostBySlug($slug)) {
            $slug .= '-' . Str::lower(Str::random(6));
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80');

        $post = [
            'slug' => $slug,
            'title' => $request->input('title'),
            'date' => date('Y-m-d'),
            'category' => $request->input('category'),
            'image' => $image,
            'content' => $request->input('content')
        ];

        $this->dataService->addPost($post);

        return redirect()->route('admin.posts')->with('success', 'Artikel baru berhasil diterbitkan!');
    }

    public function postsEdit(string $slug)
    {
        $post = $this->dataService->getPostBySlug($slug);
        if (!$post) {
            return redirect()->route('admin.posts')->with('error', 'Artikel tidak ditemukan.');
        }
        return view('admin.posts.form', compact('post'));
    }

    public function postsUpdate(UpdatePostRequest $request, string $slug)
    {
        $post = $this->dataService->getPostBySlug($slug);
        if (!$post) {
            return redirect()->route('admin.posts')->with('error', 'Artikel tidak ditemukan.');
        }

        $newTitle = $request->input('title');
        $newSlug = $post['slug'];
        if ($newTitle !== $post['title']) {
            $newSlug = Str::slug($newTitle);

            if ($newSlug !== $slug && $this->dataService->getPostBySlug($newSlug)) {
                $newSlug .= '-' . Str::lower(Str::random(6));
            }
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $post['image']);

        $updatedPost = [
            'slug' => $newSlug,
            'title' => $newTitle,
            'date' => $post['date'],
            'category' => $request->input('category'),
            'image' => $image,
            'content' => $request->input('content')
        ];

        $this->dataService->updatePost($slug, $updatedPost);

        return redirect()->route('admin.posts')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function postsDestroy(string $slug)
    {
        $this->dataService->deletePost($slug);
        return redirect()->route('admin.posts')->with('success', 'Artikel berhasil dihapus!');
    }

    protected function handleImageUpload(Request $request, string $fileKey, string $urlKey, ?string $fallback = null): ?string
    {
        if ($request->hasFile($fileKey) && $request->file($fileKey)->isValid()) {
            $file = $request->file($fileKey);

            $ext = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowedExtensions, true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fileKey => ['Format file tidak didukung. Harap unggah gambar dengan format: jpg, jpeg, png, gif, webp.']
                ]);
            }

            $mime = $file->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mime, $allowedMimes, true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fileKey => ['Tipe file tidak valid. Harap unggah file gambar yang valid.']
                ]);
            }

            $fileName = time() . '_' . Str::random(8) . '.' . $ext;

            $uploadPath = public_path('uploads');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $fileName);
            return asset('uploads/' . $fileName);
        }

        return $request->input($urlKey, $fallback);
    }
}

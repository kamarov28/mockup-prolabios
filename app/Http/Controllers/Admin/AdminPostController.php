<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\AuditLogger;
use App\Services\DataService;
use App\Traits\HandlesImageUploads;
use App\Traits\PaginatesQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminPostController extends Controller
{
    use HandlesImageUploads, PaginatesQuery;

    protected DataService $dataService;

    /** Number of posts to display per admin list page */
    private const POSTS_PER_PAGE = 10;

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
        $endDate   = $request->input('end_date');
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest'     => $query->orderBy('created_at', 'asc')->orderBy('id', 'asc'),
            'title_asc'  => $query->orderBy('title', 'asc'),
            'title_desc' => $query->orderBy('title', 'desc'),
            default      => $query->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
        };

        ['items' => $posts, 'currentPage' => $currentPage, 'totalPages' => $totalPages]
            = $this->paginateQuery($query, $request, self::POSTS_PER_PAGE);

        return view('admin.posts.index', [
            'posts'       => $posts,
            'search'      => $search,
            'category'    => $category,
            'sort'        => $sort,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'currentPage' => $currentPage,
            'totalPages'  => $totalPages,
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
            $slug .= '-'.Str::lower(Str::random(6));
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80');

        $statusOption = $request->input('status_option', 'online_now');
        $status       = ($statusOption === 'draft') ? 'draft' : 'online';

        $publishDate = date('Y-m-d');
        if ($statusOption === 'scheduled' && $request->filled('publish_date')) {
            $publishDate = date('Y-m-d', strtotime($request->input('publish_date')));
        } elseif ($statusOption === 'draft') {
            $publishDate = date('Y-m-d');
        }

        $isFeatured = $request->input('highlight') == '1' || $request->input('is_featured') == '1';

        $post = [
            'slug'        => $slug,
            'title'       => $request->input('title'),
            'date'        => $publishDate,
            'category'    => $request->input('category'),
            'status'      => $status,
            'is_featured' => $isFeatured,
            'image'       => $image,
            'content'     => $request->input('content'),
        ];

        $this->dataService->addPost($post);
        $savedPost = $this->dataService->getPostBySlug($slug);

        AuditLogger::log('post.create', 'Post', $savedPost['id'] ?? $slug, [
            'title'    => $post['title'],
            'slug'     => $slug,
            'category' => $post['category'],
            'status'   => $post['status'],
        ]);

        return redirect()->route('admin.posts')->with('success', 'Artikel baru berhasil disimpan!');
    }

    public function postsEdit(string $slug)
    {
        $post = $this->dataService->getPostBySlug($slug);
        if (! $post) {
            return redirect()->route('admin.posts')->with('error', 'Artikel tidak ditemukan.');
        }

        return view('admin.posts.form', compact('post'));
    }

    public function postsUpdate(UpdatePostRequest $request, string $slug)
    {
        $post = $this->dataService->getPostBySlug($slug);
        if (! $post) {
            return redirect()->route('admin.posts')->with('error', 'Artikel tidak ditemukan.');
        }

        $oldTitle = $post['title'];
        $newTitle = $request->input('title');
        $newSlug  = $post['slug'];
        if ($newTitle !== $post['title']) {
            $newSlug = Str::slug($newTitle);

            if ($newSlug !== $slug && $this->dataService->getPostBySlug($newSlug)) {
                $newSlug .= '-'.Str::lower(Str::random(6));
            }
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $post['image']);

        $statusOption = $request->input('status_option', 'online_now');
        $status       = ($statusOption === 'draft') ? 'draft' : 'online';

        $publishDate = ! empty($post['date']) ? date('Y-m-d', strtotime($post['date'])) : date('Y-m-d');
        if ($statusOption === 'scheduled' && $request->filled('publish_date')) {
            $publishDate = date('Y-m-d', strtotime($request->input('publish_date')));
        } elseif ($statusOption === 'online_now') {
            $publishDate = date('Y-m-d');
        }

        $isFeatured = $request->input('highlight') == '1' || $request->input('is_featured') == '1';

        $updatedPost = [
            'slug'        => $newSlug,
            'title'       => $newTitle,
            'date'        => $publishDate,
            'category'    => $request->input('category'),
            'status'      => $status,
            'is_featured' => $isFeatured,
            'image'       => $image,
            'content'     => $request->input('content'),
        ];

        $this->dataService->updatePost($slug, $updatedPost);

        AuditLogger::log('post.update', 'Post', $post['id'] ?? $slug, [
            'old_title' => $oldTitle,
            'new_title' => $newTitle,
            'slug'      => $newSlug,
            'status'    => $updatedPost['status'],
        ]);

        return redirect()->route('admin.posts')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function postsDestroy(string $slug)
    {
        $post  = $this->dataService->getPostBySlug($slug);
        $title = $post['title'] ?? null;
        $id    = $post['id'] ?? null;

        $this->dataService->deletePost($slug);

        AuditLogger::log('post.delete', 'Post', $id ?? $slug, [
            'title' => $title,
            'slug'  => $slug,
        ]);

        return redirect()->route('admin.posts')->with('success', 'Artikel berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\AuditLogger;
use App\Services\PostService;
use App\Traits\HandlesImageUploads;
use App\Traits\PaginatesQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPostController extends Controller
{
    use HandlesImageUploads, PaginatesQuery;

    protected PostService $posts;

    private const POSTS_PER_PAGE = 10;

    public function __construct(PostService $posts)
    {
        $this->posts = $posts;
    }

    public function postsIndex(Request $request)
    {
        $search    = $request->input('s');
        $category  = $request->input('category');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $sort      = $request->input('sort', 'newest');

        $query = Post::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate));

        match ($sort) {
            'oldest'     => $query->orderBy('created_at', 'asc')->orderBy('id', 'asc'),
            'title_asc'  => $query->orderBy('title', 'asc'),
            'title_desc' => $query->orderBy('title', 'desc'),
            default      => $query->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
        };

        // PaginatesQuery expects base Query\Builder; toBase() keeps same WHERE/ORDER
        ['items' => $posts, 'currentPage' => $currentPage, 'totalPages' => $totalPages]
            = $this->paginateQuery($query->toBase(), $request, self::POSTS_PER_PAGE);

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
        $title = $request->input('title');
        $slug  = Str::slug($title);

        if ($this->posts->getPostBySlug($slug)) {
            $slug .= '-'.Str::lower(Str::random(6));
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', null);

        $statusOption = $request->input('status_option', 'online_now');
        $status       = ($statusOption === 'draft') ? 'draft' : 'online';

        $publishDate = date('Y-m-d');
        if ($statusOption === 'scheduled' && $request->filled('publish_date')) {
            $publishDate = date('Y-m-d', strtotime($request->input('publish_date')));
        }

        $isFeatured = $request->input('highlight') == '1' || $request->input('is_featured') == '1';

        $post = [
            'slug'        => $slug,
            'title'       => $title,
            'date'        => $publishDate,
            'category'    => $request->input('category'),
            'status'      => $status,
            'is_featured' => $isFeatured,
            'image'       => $image,
            'content'     => $request->input('content'),
        ];

        $this->posts->addPost($post);

        AuditLogger::log('post.create', 'Post', null, [
            'title'  => $title,
            'slug'   => $slug,
            'status' => $status,
        ]);

        return redirect()->route('admin.posts')->with('success', 'Artikel baru berhasil dipublikasikan!');
    }

    public function postsEdit(string $slug)
    {
        $post = $this->posts->getPostBySlug($slug);
        if (! $post) {
            return redirect()->route('admin.posts')->with('error', 'Artikel tidak ditemukan.');
        }

        return view('admin.posts.form', compact('post'));
    }

    public function postsUpdate(UpdatePostRequest $request, string $slug)
    {
        $post = $this->posts->getPostBySlug($slug);
        if (! $post) {
            return redirect()->route('admin.posts')->with('error', 'Artikel tidak ditemukan.');
        }

        $oldTitle = $post['title'];
        $newTitle = $request->input('title');
        $newSlug  = $post['slug'];
        if ($newTitle !== $post['title']) {
            $newSlug = Str::slug($newTitle);

            if ($newSlug !== $slug && $this->posts->getPostBySlug($newSlug)) {
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

        $this->posts->updatePost($slug, $updatedPost);

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
        $post  = $this->posts->getPostBySlug($slug);
        $title = $post['title'] ?? null;
        $id    = $post['id'] ?? null;

        $this->posts->deletePost($slug);

        AuditLogger::log('post.delete', 'Post', $id ?? $slug, [
            'title' => $title,
            'slug'  => $slug,
        ]);

        return redirect()->route('admin.posts')->with('success', 'Artikel berhasil dihapus!');
    }
}

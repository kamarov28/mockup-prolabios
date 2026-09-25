<?php

namespace App\Services;

use App\Enums\PostStatus;
use App\Helpers\HtmlSanitizer;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostService
{
    public static function getPostsCacheVersion(): int
    {
        return (int) Cache::get('posts_cache_version', 1);
    }

    public function clearPostsCache(): void
    {
        Cache::forget('blog_category_counts');
        Cache::forget('sitemap_xml_cache');

        try {
            Cache::increment('posts_cache_version');
        } catch (\Throwable $e) {
            Cache::put('posts_cache_version', time());
        }
    }

    public function getPosts(?array $filters = [], int $limit = 0): array
    {
        $v = self::getPostsCacheVersion();
        $cacheKey = 'posts_list_v1_'.$v.'_'.md5(json_encode($filters).'_'.$limit);

        return Cache::remember($cacheKey, 600, function () use ($filters, $limit) {
            $query = Post::query()->orderByDesc('date')->orderByDesc('id');

            if (empty($filters['include_all_status'])) {
                $query->online();
            }

            if (! empty($filters['category'])) {
                $query->byCategory($filters['category']);
            }

            if ($limit > 0) {
                $query->limit($limit);
            }

            return $query->get()->map(fn (Post $p) => $this->toArray($p))->all();
        });
    }

    public function getPaginatedPosts(?array $filters = [], int $perPage = 4)
    {
        $query = Post::query()->orderByDesc('date')->orderByDesc('id');

        if (empty($filters['include_all_status'])) {
            $query->online();
        }

        if (! empty($filters['category'])) {
            $query->byCategory($filters['category']);
        }

        return $query->paginate($perPage)
            ->through(fn (Post $p) => $this->toArray($p))
            ->withQueryString();
    }

    public function getPostBySlug(string $slug): ?array
    {
        $post = Post::query()->where('slug', $slug)->first();

        return $post ? $this->toArray($post) : null;
    }

    public function addPost(array $post): bool
    {
        Post::create([
            'slug' => $post['slug'],
            'title' => $post['title'],
            'date' => $post['date'],
            'category' => $post['category'],
            'status' => $post['status'] ?? 'online',
            'is_featured' => $post['is_featured'] ?? false,
            'image' => $post['image'] ?? null,
            'content' => HtmlSanitizer::clean($post['content'] ?? null),
        ]);

        $this->clearPostsCache();

        return true;
    }

    public function updatePost(string $slug, array $updatedPost): bool
    {
        $post = Post::query()->where('slug', $slug)->first();
        if (! $post) {
            return false;
        }

        $post->update([
            'slug' => $updatedPost['slug'],
            'title' => $updatedPost['title'],
            'date' => $updatedPost['date'],
            'category' => $updatedPost['category'],
            'status' => $updatedPost['status'] ?? 'online',
            'is_featured' => $updatedPost['is_featured'] ?? false,
            'image' => $updatedPost['image'] ?? null,
            'content' => HtmlSanitizer::clean($updatedPost['content'] ?? null),
        ]);

        $this->clearPostsCache();

        return true;
    }

    public function deletePost(string $slug): bool
    {
        $deleted = Post::query()->where('slug', $slug)->delete();
        $this->clearPostsCache();

        return $deleted > 0;
    }

    public function deletePostsByIds(array $ids): int
    {
        if (empty($ids)) {
            return 0;
        }

        $deleted = Post::query()->whereIn('id', $ids)->delete();
        $this->clearPostsCache();

        return $deleted;
    }

    /**
     * Stable array shape for Blade (keeps $post['title'] access).
     * Date cast as Y-m-d string so views tidak dapat Carbon object.
     */
    private function toArray(Post $post): array
    {
        return [
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'date' => $post->date ? $post->date->format('Y-m-d') : null,
            'category' => $post->category,
            'status' => $post->status instanceof PostStatus ? $post->status->value : $post->status,
            'is_featured' => (bool) $post->is_featured,
            'image' => $post->image,
            'content' => $post->content,
            'created_at' => optional($post->created_at)?->toDateTimeString(),
            'updated_at' => optional($post->updated_at)?->toDateTimeString(),
        ];
    }
}

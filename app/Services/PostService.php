<?php

namespace App\Services;

use App\Helpers\HtmlSanitizer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PostService
{
    public function getPosts(?array $filters = [], int $limit = 0): array
    {
        $query = DB::table('posts')->orderByDesc('id');

        // Only show published / online posts for public facing queries
        if (empty($filters['include_all_status'])) {
            $today = date('Y-m-d');
            $query->where('status', 'online')
                ->where(function ($q) use ($today) {
                    $q->whereNull('date')
                        ->orWhere('date', '<=', $today);
                });
        }

        if (! empty($filters['category'])) {
            $cat = strtolower($filters['category']);
            if ($cat === 'info') {
                $query->where(function ($q) {
                    $q->where('category', 'Info Terkait')->orWhere('category', 'Info');
                });
            } else {
                $query->whereRaw('LOWER(category) = ?', [$cat]);
            }
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get()
            ->map(fn ($r) => (array) $r)
            ->toArray();
    }

    public function getPaginatedPosts(?array $filters = [], int $perPage = 4)
    {
        $query = DB::table('posts')->orderByDesc('id');

        // Only show published / online posts for public facing queries
        if (empty($filters['include_all_status'])) {
            $today = date('Y-m-d');
            $query->where('status', 'online')
                ->where(function ($q) use ($today) {
                    $q->whereNull('date')
                        ->orWhere('date', '<=', $today);
                });
        }

        if (! empty($filters['category'])) {
            $cat = strtolower($filters['category']);
            if ($cat === 'info') {
                $query->where(function ($q) {
                    $q->where('category', 'Info Terkait')->orWhere('category', 'Info');
                });
            } else {
                $query->whereRaw('LOWER(category) = ?', [$cat]);
            }
        }

        return $query->paginate($perPage)
            ->through(fn ($r) => (array) $r)
            ->withQueryString();
    }

    public function getPostBySlug(string $slug): ?array
    {
        $row = DB::table('posts')->where('slug', $slug)->first();

        return $row ? (array) $row : null;
    }

    public function addPost(array $post): bool
    {
        DB::table('posts')->insert([
            'slug'        => $post['slug'],
            'title'       => $post['title'],
            'date'        => $post['date'],
            'category'    => $post['category'],
            'status'      => $post['status'] ?? 'online',
            'is_featured' => $post['is_featured'] ?? false,
            'image'       => $post['image'] ?? null,
            'content'     => HtmlSanitizer::clean($post['content'] ?? null),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        Cache::forget('blog_category_counts');

        return true;
    }

    public function updatePost(string $slug, array $updatedPost): bool
    {
        DB::table('posts')->where('slug', $slug)->update([
            'slug'        => $updatedPost['slug'],
            'title'       => $updatedPost['title'],
            'date'        => $updatedPost['date'],
            'category'    => $updatedPost['category'],
            'status'      => $updatedPost['status'] ?? 'online',
            'is_featured' => $updatedPost['is_featured'] ?? false,
            'image'       => $updatedPost['image'] ?? null,
            'content'     => HtmlSanitizer::clean($updatedPost['content'] ?? null),
            'updated_at'  => now(),
        ]);
        Cache::forget('blog_category_counts');

        return true;
    }

    public function deletePost(string $slug): bool
    {
        DB::table('posts')->where('slug', $slug)->delete();
        Cache::forget('blog_category_counts');

        return true;
    }
}

<?php

namespace Tests\Feature;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_excludes_future_scheduled_posts(): void
    {
        Post::create([
            'title' => 'Published Article',
            'slug' => 'published-article',
            'category' => 'berita',
            'status' => PostStatus::Online,
            'date' => now()->subDay()->toDateString(),
            'content' => '<p>Content</p>',
        ]);

        Post::create([
            'title' => 'Future Scheduled Article',
            'slug' => 'future-scheduled-article',
            'category' => 'berita',
            'status' => PostStatus::Online,
            'date' => now()->addDays(5)->toDateString(),
            'content' => '<p>Content</p>',
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee('published-article');
        $response->assertDontSee('future-scheduled-article');
    }
}

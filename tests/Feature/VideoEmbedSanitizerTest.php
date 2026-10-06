<?php

namespace Tests\Feature;

use App\Helpers\HtmlSanitizer;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoEmbedSanitizerTest extends TestCase
{
    use RefreshDatabase;

    public function test_youtube_embed_iframe_is_preserved_and_sanitized(): void
    {
        $input = '<p>Video demo cara uji:</p><iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560" height="315" frameborder="0" allowfullscreen></iframe>';
        $cleaned = HtmlSanitizer::clean($input);

        $this->assertStringContainsString('https://www.youtube.com/embed/dQw4w9WgXcQ', $cleaned);
        $this->assertStringContainsString('<iframe', $cleaned);
    }

    public function test_youtube_watch_url_in_iframe_is_normalized_to_embed(): void
    {
        $input = '<iframe src="https://www.youtube.com/watch?v=dQw4w9WgXcQ" width="560" height="315"></iframe>';
        $cleaned = HtmlSanitizer::clean($input);

        $this->assertStringContainsString('https://www.youtube.com/embed/dQw4w9WgXcQ', $cleaned);
    }

    public function test_instagram_reel_and_post_iframes_are_preserved_with_embed_path(): void
    {
        $input = '<p>Cuplikan Instagram:</p><iframe src="https://www.instagram.com/reel/DFxyz123/" width="400" height="480" frameborder="0"></iframe>';
        $cleaned = HtmlSanitizer::clean($input);

        $this->assertStringContainsString('https://www.instagram.com/reel/DFxyz123/embed/', $cleaned);
        $this->assertStringContainsString('<iframe', $cleaned);
    }

    public function test_arbitrary_and_malicious_iframes_are_completely_blocked(): void
    {
        $malicious = '<p>Normal text</p><iframe src="https://evil.com/malicious-xss.html"></iframe>';
        $cleaned = HtmlSanitizer::clean($malicious);

        $this->assertStringNotContainsString('evil.com', $cleaned);
        $this->assertStringNotContainsString('malicious-xss', $cleaned);

        $javascriptIframe = '<iframe src="javascript:alert(1)"></iframe>';
        $cleanedJs = HtmlSanitizer::clean($javascriptIframe);
        $this->assertStringNotContainsString('javascript:', $cleanedJs);
    }

    public function test_product_detail_page_renders_safe_youtube_and_instagram_videos(): void
    {
        $product = Product::create([
            'title' => 'Reagen Uji Endotoksin Video',
            'catalog' => 'VID-01',
            'slug' => 'reagen-uji-endotoksin-video',
            'category' => 'microbiology',
            'description' => '<p>Simak cara penggunaan pada video berikut:</p><iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560" height="315"></iframe>',
        ]);

        $response = $this->get('/produk/reagen-uji-endotoksin-video');
        $response->assertOk();
        $response->assertSee('https://www.youtube.com/embed/dQw4w9WgXcQ', false);
    }

    public function test_information_blog_detail_renders_safe_youtube_and_instagram_videos(): void
    {
        $post = Post::create([
            'title' => 'Panduan Deteksi Patogen Cepat',
            'slug' => 'panduan-deteksi-patogen-cepat',
            'category' => 'iptek',
            'status' => 'online',
            'date' => date('Y-m-d'),
            'content' => '<p>Simak penjelasan lengkap:</p><iframe src="https://www.instagram.com/reel/C8xyz123/embed/" width="400" height="480"></iframe>',
        ]);

        $response = $this->get('/informasi/panduan-deteksi-patogen-cepat');
        $response->assertOk();
        $response->assertSee('https://www.instagram.com/reel/C8xyz123/embed/', false);
    }
}

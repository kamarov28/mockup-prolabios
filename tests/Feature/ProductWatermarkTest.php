<?php

namespace Tests\Feature;

use App\Helpers\HtmlSanitizer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductWatermarkTest extends TestCase
{
    use RefreshDatabase;

    public function test_html_sanitizer_wraps_images_with_product_watermark_container(): void
    {
        $rawHtml = '<p>Berikut ini adalah ilustrasi reagen:</p><p><img src="/storage/products/test-agar.jpg" alt="Test Agar"></p>';

        $output = HtmlSanitizer::cleanWithProductWatermark($rawHtml);

        $this->assertStringContainsString('product-watermark-wrap', $output);
        $this->assertStringContainsString('product-watermark-overlay', $output);
        $this->assertStringContainsString('src="/storage/products/test-agar.jpg"', $output);
        $this->assertStringContainsString('alt="Test Agar"', $output);
    }

    public function test_html_sanitizer_leaves_text_only_descriptions_intact(): void
    {
        $rawHtml = '<p>Reagen kultur mikrobiologi kualitas standar farmasi.</p>';

        $output = HtmlSanitizer::cleanWithProductWatermark($rawHtml);

        $this->assertStringNotContainsString('product-watermark-wrap', $output);
        $this->assertStringContainsString('Reagen kultur mikrobiologi', $output);
    }

    public function test_product_detail_page_renders_watermark_elements(): void
    {
        $product = Product::create([
            'title' => 'Buffered Peptone Water',
            'catalog' => 'BPW-01',
            'slug' => 'buffered-peptone-water',
            'category' => 'microbiology',
            'description' => '<p>Deskripsi dengan <img src="/storage/products/sample.jpg" alt="Sample"></p>',
        ]);

        $response = $this->get('/produk/' . $product->slug);

        $response->assertOk();
        $response->assertSee('detail-product-img-wrap');
        $response->assertSee('product-watermark-wrap');
        $response->assertSee('product-watermark-overlay');
    }
}

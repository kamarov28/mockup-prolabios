<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_auto_generates_slug_on_create(): void
    {
        $product = Product::create([
            'title' => 'Nutrient Agar 500g',
            'catalog' => 'NA-500',
            'category' => 'Culture Media',
            'price' => 100000,
            'stock' => 5,
        ]);

        $this->assertNotEmpty($product->slug);
        $this->assertEquals('nutrient-agar-500g', $product->slug);
    }

    public function test_detail_page_resolves_by_slug(): void
    {
        $product = Product::create([
            'title' => 'MRS Agar',
            'catalog' => 'MRS-01',
            'category' => 'Culture Media',
            'description' => 'Selective medium for lactobacilli.',
            'price' => 150000,
            'stock' => 3,
        ]);

        $response = $this->get(route('produk.detail', ['slug' => $product->slug]));

        $response->assertOk();
        $response->assertSee('MRS Agar');
        $response->assertSee('MRS-01');
    }

    public function test_unknown_slug_returns_404(): void
    {
        $this->get(route('produk.detail', ['slug' => 'does-not-exist-xyz']))
            ->assertNotFound();
    }

    public function test_legacy_id_query_redirects_to_slug(): void
    {
        $product = Product::create([
            'title' => 'Blood Agar Base',
            'catalog' => 'BAB-01',
            'category' => 'Culture Media',
            'price' => 200000,
            'stock' => 2,
        ]);

        $response = $this->get(route('produk.detail.legacy', ['id' => $product->id]));

        $response->assertRedirect(route('produk.detail', ['slug' => $product->slug]));
        $this->assertEquals(301, $response->headers->get('Location') ? $response->getStatusCode() : 0);
        $response->assertStatus(301);
    }

    public function test_product_url_helper_prefers_slug(): void
    {
        $product = Product::create([
            'title' => 'Petri Dish 90mm',
            'catalog' => 'PD-90',
            'category' => 'Consumables',
            'price' => 50000,
            'stock' => 100,
        ]);

        $url = product_url($product);
        $this->assertStringContainsString('/produk/'.$product->slug, $url);
        $this->assertStringNotContainsString('detail?id=', $url);

        $urlArr = product_url([
            'id' => $product->id,
            'slug' => $product->slug,
            'title' => $product->title,
        ]);
        $this->assertStringContainsString('/produk/'.$product->slug, $urlArr);
    }
}

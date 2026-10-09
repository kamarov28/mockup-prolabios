<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class FeaturedProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_featured_products_prioritizes_featured_and_falls_back(): void
    {
        $service = app(ProductService::class);

        // Create 2 normal products
        $p1 = Product::create([
            'title' => 'Product Normal 1',
            'category' => 'microbiology',
            'is_featured' => false,
        ]);
        $p2 = Product::create([
            'title' => 'Product Normal 2',
            'category' => 'microbiology',
            'is_featured' => false,
        ]);

        // Create 1 featured product
        $featured1 = Product::create([
            'title' => 'Product Featured 1',
            'category' => 'microbiology',
            'is_featured' => true,
        ]);

        $featuredList = $service->getFeaturedProducts(4);

        $this->assertCount(3, $featuredList);
        $this->assertEquals($featured1->id, $featuredList->first()->id);
    }

    public function test_admin_can_toggle_featured_status(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $product = Product::create([
            'title' => 'Featured Candidate',
            'category' => 'microbiology',
            'is_featured' => false,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.products.toggle-featured', $product->id));

        $response->assertRedirect();
        $this->assertTrue($product->fresh()->is_featured);

        // Toggle back
        $this->actingAs($admin)
            ->post(route('admin.products.toggle-featured', $product->id));

        $this->assertFalse($product->fresh()->is_featured);
    }

    public function test_homepage_shows_featured_products(): void
    {
        Product::create([
            'title' => 'Hero Lab Reagent',
            'category' => 'microbiology',
            'is_featured' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Hero Lab Reagent');
    }

    public function test_get_featured_products_prioritizes_most_searched_when_featured_slots_available(): void
    {
        $service = app(ProductService::class);

        // 1 manual featured product
        $manual = Product::create([
            'title' => 'Manual Starred Reagent',
            'category' => 'microbiology',
            'is_featured' => true,
        ]);

        // 1 normal product with high search hits (trending)
        $trending = Product::create([
            'title' => 'Viral Culture Broth',
            'category' => 'microbiology',
            'is_featured' => false,
            'search_hits' => 45,
        ]);

        // 1 normal product with low search hits
        $lowTrending = Product::create([
            'title' => 'Standard Pipette Tip',
            'category' => 'consumables',
            'is_featured' => false,
            'search_hits' => 5,
        ]);

        // 1 product with 0 search hits
        $unsearched = Product::create([
            'title' => 'Unsearched Beaker',
            'category' => 'instruments',
            'is_featured' => false,
            'search_hits' => 0,
        ]);

        $list = $service->getFeaturedProducts(3);

        $this->assertCount(3, $list);
        $ids = $list->pluck('id')->all();

        // 1st slot: manual featured product
        $this->assertEquals($manual->id, $ids[0]);
        // 2nd slot: highest searched product
        $this->assertEquals($trending->id, $ids[1]);
        // 3rd slot: next highest searched product
        $this->assertEquals($lowTrending->id, $ids[2]);
    }

    public function test_searching_and_viewing_product_increments_search_hits(): void
    {
        $product = Product::create([
            'title' => 'Bismuth Sulfite Agar',
            'slug' => 'bismuth-sulfite-agar',
            'catalog' => 'BSA-01',
            'category' => 'microbiology',
            'search_hits' => 0,
        ]);

        // Search catalog
        $this->get('/produk?s=bismuth');
        $this->assertEquals(1, $product->fresh()->search_hits);

        // View detail page
        $this->get('/produk/bismuth-sulfite-agar');
        $this->assertEquals(2, $product->fresh()->search_hits);
    }

    public function test_searching_and_viewing_product_does_not_bust_global_catalog_cache(): void
    {
        $product = Product::create([
            'title' => 'Stable Cache Agar',
            'slug' => 'stable-cache-agar',
            'catalog' => 'SCA-01',
            'category' => 'microbiology',
            'search_hits' => 0,
        ]);

        Cache::put('categories_structure', ['dummy' => 'cached'], 3600);
        $vBefore = ProductService::getProductsCacheVersion();

        // Search catalog
        $this->get('/produk?s=stable');
        $this->assertEquals($vBefore, ProductService::getProductsCacheVersion());
        $this->assertEquals(['dummy' => 'cached'], Cache::get('categories_structure'));

        // View detail page
        $this->get('/produk/stable-cache-agar');
        $this->assertEquals($vBefore, ProductService::getProductsCacheVersion());
        $this->assertEquals(['dummy' => 'cached'], Cache::get('categories_structure'));
    }
}

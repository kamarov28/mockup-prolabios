<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}

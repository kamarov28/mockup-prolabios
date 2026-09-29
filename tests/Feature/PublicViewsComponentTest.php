<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicViewsComponentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        ProductCategory::create([
            'name' => 'Microbiology',
            'key' => 'microbiology',
        ]);

        Product::create([
            'title' => 'Test Petri Dish',
            'catalog' => 'PD-01',
            'category' => 'microbiology',
            'price' => 25000,
            'stock' => 50,
            'is_featured' => true,
        ]);
    }

    public function test_all_public_pages_render_with_blade_components(): void
    {
        $routes = [
            '/',
            '/produk',
            '/sektor',
            '/profil',
            '/layanan',
            '/kebijakan-privasi',
            '/syarat-ketentuan',
            '/kontak',
            '/informasi',
        ];

        foreach ($routes as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
        }
    }
}

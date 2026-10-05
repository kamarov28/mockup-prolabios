<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_search_matches_substrings_and_partial_words(): void
    {
        Product::create([
            'title' => 'Integral System Yeasts Plus',
            'catalog' => '71822',
            'category' => 'microbiology',
            'description' => 'Biochemical test for yeasts and fungi identification',
            'packaging' => '20 tests',
            'function' => 'Identifikasi ragi dan jamur mikrobiologi',
            'reference_method' => 'ISO 11133',
            'price' => 750000,
            'stock' => 10,
        ]);

        // 1. Partial word / stemming match ('yeast' matches 'Yeasts')
        $this->assertEquals(1, Product::search('yeast')->count());
        $this->assertEquals(1, Product::search('yeasts')->count());

        // 2. Character-level prefix/substring match ('yea' matches 'Yeasts')
        $this->assertEquals(1, Product::search('yea')->count());

        // 3. Catalog number substring ('718' matches '71822')
        $this->assertEquals(1, Product::search('718')->count());

        // 4. Function & reference method search
        $this->assertEquals(1, Product::search('jamur')->count());
        $this->assertEquals(1, Product::search('11133')->count());

        // 5. Multi-word search in non-consecutive / different order
        $this->assertEquals(1, Product::search('yeast integral')->count());
    }

    public function test_public_catalog_search_endpoint_returns_substring_results(): void
    {
        $product = Product::create([
            'title' => 'Integral System Yeasts Plus',
            'catalog' => '71822',
            'category' => 'microbiology',
            'description' => 'Biochemical test for yeasts',
            'price' => 750000,
            'stock' => 10,
        ]);

        // Public catalog search with partial word 'yeast'
        $response = $this->get('/produk?s=yeast');
        $response->assertOk();
        $response->assertSee('Integral System Yeasts Plus');
        $response->assertSee('71822');
    }
}

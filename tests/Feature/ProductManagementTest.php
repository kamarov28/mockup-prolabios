<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin@prolabios.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );

        ProductCategory::create([
            'name' => 'Microbiology',
            'key'  => 'microbiology',
        ]);
    }

    public function test_admin_can_view_products_index_with_pagination_and_filters(): void
    {
        Product::create([
            'title'    => 'Culture Media Kit',
            'catalog'  => 'CM-01',
            'category' => 'microbiology',
            'price'    => 500000,
            'stock'    => 10,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.products', ['s' => 'Culture']));
        $response->assertStatus(200);
        $response->assertSee('Culture Media Kit');
    }

    public function test_admin_can_create_update_and_delete_product(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'title'       => 'Antimicrobial Disc Set',
            'catalog'     => 'ADS-99',
            'category'    => 'microbiology',
            'price'       => 750000,
            'stock'       => 20,
            'description' => 'Detailed test description',
        ]);

        $response->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'title'   => 'Antimicrobial Disc Set',
            'catalog' => 'ADS-99',
        ]);

        $product = Product::where('title', 'Antimicrobial Disc Set')->first();
        $this->assertNotNull($product);

        // 2. Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.products.update', ['id' => $product->id]), [
            'title'       => 'Antimicrobial Disc Set v2',
            'catalog'     => 'ADS-100',
            'category'    => 'microbiology',
            'price'       => 800000,
            'stock'       => 15,
            'description' => 'Updated test description',
        ]);

        $updateResponse->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'id'      => $product->id,
            'title'   => 'Antimicrobial Disc Set v2',
            'catalog' => 'ADS-100',
            'price'   => 800000,
        ]);

        // 3. Delete
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.products.destroy', ['id' => $product->id]));
        $deleteResponse->assertRedirect(route('admin.products'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_bulk_store_products(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store-bulk'), [
            'title'    => ['Bulk Prod 1', 'Bulk Prod 2'],
            'catalog'  => ['BP-01', 'BP-02'],
            'category' => ['microbiology', 'microbiology'],
        ]);

        $response->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', ['title' => 'Bulk Prod 1']);
        $this->assertDatabaseHas('products', ['title' => 'Bulk Prod 2']);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Principal;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );

        ProductCategory::create([
            'name' => 'Microbiology',
            'key' => 'microbiology',
        ]);
    }

    public function test_admin_can_view_products_index_with_pagination_and_filters(): void
    {
        Product::create([
            'title' => 'Culture Media Kit',
            'catalog' => 'CM-01',
            'category' => 'microbiology',
            'price' => 500000,
            'stock' => 10,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.products', ['s' => 'Culture']));
        $response->assertStatus(200);
        $response->assertSee('Culture Media Kit');
    }

    public function test_admin_can_create_update_and_delete_product(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'title' => 'Antimicrobial Disc Set',
            'catalog' => 'ADS-99',
            'category' => 'microbiology',
            'price' => 750000,
            'stock' => 20,
            'description' => 'Detailed test description',
        ]);

        $response->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'title' => 'Antimicrobial Disc Set',
            'catalog' => 'ADS-99',
        ]);

        $product = Product::where('title', 'Antimicrobial Disc Set')->first();
        $this->assertNotNull($product);

        // 2. Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.products.update', ['id' => $product->id]), [
            'title' => 'Antimicrobial Disc Set v2',
            'catalog' => 'ADS-100',
            'category' => 'microbiology',
            'price' => 800000,
            'stock' => 15,
            'description' => 'Updated test description',
        ]);

        $updateResponse->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'Antimicrobial Disc Set v2',
            'catalog' => 'ADS-100',
            'price' => 800000,
        ]);

        // 3. Delete
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.products.destroy', ['id' => $product->id]));
        $deleteResponse->assertRedirect(route('admin.products'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_bulk_store_products(): void
    {
        $principal = Principal::create([
            'name' => 'Merck KGaA',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store-bulk'), [
            'title' => ['Bulk Prod 1', 'Bulk Prod 2'],
            'catalog' => ['BP-01', 'BP-02'],
            'category' => ['microbiology', 'microbiology'],
            'price' => ['1.500.000', '250000'],
            'stock' => [15, 30],
            'principal_id' => [$principal->id, null],
            'description' => ['<p>Deskripsi bulk 1</p>', 'Deskripsi bulk 2'],
        ]);

        $response->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'title' => 'Bulk Prod 1',
            'catalog' => 'BP-01',
            'price' => 1500000,
            'stock' => 15,
            'principal_id' => $principal->id,
        ]);
        $this->assertDatabaseHas('products', [
            'title' => 'Bulk Prod 2',
            'catalog' => 'BP-02',
            'price' => 250000,
            'stock' => 30,
        ]);
    }

    public function test_admin_can_create_product_without_subcategory(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'title' => 'Standalone Microbiology Kit',
            'catalog' => 'SMK-10',
            'category' => 'microbiology',
            'sub_category' => null,
            'price' => 250000,
            'stock' => 5,
        ]);

        $response->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'title' => 'Standalone Microbiology Kit',
            'sub_category' => null,
        ]);
    }

    public function test_admin_can_create_product_with_empty_or_null_gallery_files(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'title' => 'Kit Without Gallery',
            'catalog' => 'KWG-01',
            'category' => 'microbiology',
            'gallery_files' => [null],
            'price' => 120000,
            'stock' => 10,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'title' => 'Kit Without Gallery',
        ]);
    }

    public function test_admin_can_create_product_with_uploaded_gallery_images(): void
    {
        Storage::fake('public');

        $img1 = UploadedFile::fake()->image('gallery1.jpg', 600, 600);
        $img2 = UploadedFile::fake()->image('gallery2.png', 600, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'title' => 'Multi Gallery Device',
            'catalog' => 'MGD-99',
            'category' => 'microbiology',
            'price' => 1500000,
            'stock' => 8,
            'gallery_files' => [$img1, $img2],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products'));

        $product = Product::where('title', 'Multi Gallery Device')->first();
        $this->assertNotNull($product);
        $this->assertIsArray($product->gallery_images);
        $this->assertCount(2, $product->gallery_images);
    }

    public function test_admin_can_bulk_delete_products(): void
    {
        $p1 = Product::create([
            'title' => 'Bulk Product 1',
            'catalog' => 'BP-01',
            'category' => 'microbiology',
            'price' => 100000,
            'stock' => 5,
        ]);
        $p2 = Product::create([
            'title' => 'Bulk Product 2',
            'catalog' => 'BP-02',
            'category' => 'microbiology',
            'price' => 200000,
            'stock' => 10,
        ]);
        $p3 = Product::create([
            'title' => 'Keep Product 3',
            'catalog' => 'KP-03',
            'category' => 'microbiology',
            'price' => 300000,
            'stock' => 15,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.products.bulk-destroy'), [
            'ids' => [$p1->id, $p2->id],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.products'));
        $this->assertDatabaseMissing('products', ['id' => $p1->id]);
        $this->assertDatabaseMissing('products', ['id' => $p2->id]);
        $this->assertDatabaseHas('products', ['id' => $p3->id]);
    }
}

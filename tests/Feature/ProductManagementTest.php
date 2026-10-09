<?php

namespace Tests\Feature;

use App\Models\Principal;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Sector;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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
            'packaging' => '50 cartridges / pack',
            'function' => 'Uji sensitivitas antibiotik bakteri',
            'reference_method' => 'CLSI M100 / EUCAST',
            'price' => 750000,
            'stock' => 20,
            'description' => 'Detailed test description',
        ]);

        $response->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'title' => 'Antimicrobial Disc Set',
            'catalog' => 'ADS-99',
            'packaging' => '50 cartridges / pack',
            'function' => 'Uji sensitivitas antibiotik bakteri',
            'reference_method' => 'CLSI M100 / EUCAST',
        ]);

        $product = Product::where('title', 'Antimicrobial Disc Set')->first();
        $this->assertNotNull($product);

        // 2. Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.products.update', ['id' => $product->id]), [
            'title' => 'Antimicrobial Disc Set v2',
            'catalog' => 'ADS-100',
            'category' => 'microbiology',
            'packaging' => '100 cartridges / pack',
            'function' => 'Uji sensitivitas mikrobiologi mutakhir',
            'reference_method' => 'ISO 20776-1',
            'price' => 800000,
            'stock' => 15,
            'description' => 'Updated test description',
        ]);

        $updateResponse->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'Antimicrobial Disc Set v2',
            'catalog' => 'ADS-100',
            'packaging' => '100 cartridges / pack',
            'function' => 'Uji sensitivitas mikrobiologi mutakhir',
            'reference_method' => 'ISO 20776-1',
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
            'packaging' => ['500 g', '100 test'],
            'function' => ['Kultur bakteri', 'Deteksi endotoksin'],
            'reference_method' => ['ISO 11133', 'USP <85>'],
            'price' => ['1.500.000', '250000'],
            'stock' => [15, 30],
            'principal_id' => [$principal->id, null],
            'description' => ['<p>Deskripsi bulk 1</p>', 'Deskripsi bulk 2'],
            'is_featured' => ['1', '0'],
        ]);

        $response->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'title' => 'Bulk Prod 1',
            'catalog' => 'BP-01',
            'packaging' => '500 g',
            'function' => 'Kultur bakteri',
            'reference_method' => 'ISO 11133',
            'price' => 1500000,
            'stock' => 15,
            'principal_id' => $principal->id,
            'is_featured' => true,
        ]);
        $this->assertDatabaseHas('products', [
            'title' => 'Bulk Prod 2',
            'catalog' => 'BP-02',
            'packaging' => '100 test',
            'function' => 'Deteksi endotoksin',
            'reference_method' => 'USP <85>',
            'price' => 250000,
            'stock' => 30,
            'is_featured' => false,
        ]);
    }

    public function test_product_detail_page_renders_specifications_table(): void
    {
        $sector = Sector::create([
            'id' => 'pharma',
            'name' => 'Pharmaceutical & Biotech',
            'slug' => 'pharma',
        ]);

        $product = Product::create([
            'title' => 'Liofilchem MRS Agar Spec Test',
            'catalog' => 'SPEC-610025',
            'category' => 'microbiology',
            'packaging' => 'Botol 500 g',
            'function' => 'Media isolasi dan enumerasi Lactobacillus',
            'reference_method' => 'ISO 11133:2014 & BAM Ch. 5',
            'price' => 450000,
            'stock' => 10,
        ]);
        $product->sectors()->attach($sector->id);

        $response = $this->get('/produk/'.$product->slug);
        $response->assertStatus(200);

        // Check 6 rows of the specification table
        $response->assertSee('Kemasan');
        $response->assertSee('Botol 500 g');

        $response->assertSee('Kategori');
        $response->assertSee('Microbiology');

        $response->assertSee('Sub-kategori');

        $response->assertSee('Sektor');
        $response->assertSee('Pharmaceutical &amp; Biotech', false);

        $response->assertSee('Fungsi');
        $response->assertSee('Media isolasi dan enumerasi Lactobacillus');

        $response->assertSee('Metode Referensi');
        $response->assertSee('ISO 11133:2014 &amp; BAM Ch. 5', false);
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

    public function test_admin_can_save_and_update_product_with_local_datasheet_url(): void
    {
        $createResponse = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'title' => 'Product With Local Datasheet',
            'catalog' => 'PWL-01',
            'category' => 'microbiology',
            'price' => 500000,
            'stock' => 10,
            'datasheet_url' => '/storage/datasheets/datasheet_sample.pdf',
        ]);

        $createResponse->assertSessionHasNoErrors();
        $createResponse->assertRedirect(route('admin.products'));

        $product = Product::where('title', 'Product With Local Datasheet')->first();
        $this->assertNotNull($product);
        $this->assertEquals('/storage/datasheets/datasheet_sample.pdf', $product->datasheet_url);

        $updateResponse = $this->actingAs($this->admin)->put(route('admin.products.update', ['id' => $product->id]), [
            'title' => 'Product With Local Datasheet Updated',
            'catalog' => 'PWL-01',
            'category' => 'microbiology',
            'price' => 550000,
            'stock' => 12,
            'datasheet_url' => '/storage/datasheets/datasheet_sample.pdf',
        ]);

        $updateResponse->assertSessionHasNoErrors();
        $updateResponse->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'Product With Local Datasheet Updated',
            'datasheet_url' => '/storage/datasheets/datasheet_sample.pdf',
        ]);
    }

    public function test_bulk_product_delete_cleans_up_product_sector_pivot(): void
    {
        $sector = Sector::create([
            'id' => 'pharma',
            'name' => 'Farmasi',
            'description' => ['tag' => 'PHARMA'],
        ]);

        $product = Product::create([
            'title' => 'Product For Bulk Delete',
            'catalog' => 'PFBD-01',
            'category' => 'microbiology',
            'price' => 100000,
            'stock' => 5,
        ]);
        $product->sectors()->attach($sector->id);

        $this->assertDatabaseHas('product_sector', [
            'product_id' => $product->id,
            'sector_id' => $sector->id,
        ]);

        $service = app(ProductService::class);
        $deleted = $service->deleteProductsByIds([$product->id]);

        $this->assertEquals(1, $deleted);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('product_sector', ['product_id' => $product->id]);
    }

    public function test_creating_product_with_duplicate_title_fails_validation(): void
    {
        Product::create([
            'title' => 'Unique Lab Reagent',
            'catalog' => 'ULR-01',
            'category' => 'microbiology',
            'price' => 500000,
            'stock' => 10,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'title' => 'Unique Lab Reagent',
            'catalog' => 'ULR-02',
            'category' => 'microbiology',
        ]);

        $response->assertSessionHasErrors(['title']);
    }

    public function test_updating_product_with_another_product_title_fails_validation(): void
    {
        $p1 = Product::create([
            'title' => 'First Product Alpha',
            'catalog' => 'FPA-01',
            'category' => 'microbiology',
            'price' => 100000,
            'stock' => 5,
        ]);

        $p2 = Product::create([
            'title' => 'Second Product Beta',
            'catalog' => 'SPB-02',
            'category' => 'microbiology',
            'price' => 200000,
            'stock' => 10,
        ]);

        // Attempt to rename p2 to p1's title
        $response = $this->actingAs($this->admin)->put(route('admin.products.update', ['id' => $p2->id]), [
            'title' => 'First Product Alpha',
            'category' => 'microbiology',
        ]);

        $response->assertSessionHasErrors(['title']);

        // Keeping own title succeeds
        $okResponse = $this->actingAs($this->admin)->put(route('admin.products.update', ['id' => $p2->id]), [
            'title' => 'Second Product Beta',
            'category' => 'microbiology',
        ]);

        $okResponse->assertSessionHasNoErrors();
    }

    public function test_get_product_by_slug_reconstitutes_relations_from_cache(): void
    {
        $principal = Principal::create(['name' => 'Difco Labs']);
        $sector = Sector::create(['id' => 'food', 'name' => 'Food & Beverage']);
        $product = Product::create([
            'title' => 'Cached Relation Agar',
            'slug' => 'cached-relation-agar',
            'catalog' => 'CRA-99',
            'category' => 'microbiology',
            'principal_id' => $principal->id,
            'price' => 150000,
            'stock' => 10,
        ]);
        $product->sectors()->attach('food');

        $service = app(ProductService::class);

        // First call populates cache
        $loaded1 = $service->getProductBySlug('cached-relation-agar');
        $this->assertNotNull($loaded1);
        $this->assertEquals('Difco Labs', $loaded1->principal?->name);
        $this->assertTrue($loaded1->sectors->contains('id', 'food'));

        // Second call retrieves from cache without DB relation queries
        DB::flushQueryLog();
        DB::enableQueryLog();

        $loaded2 = $service->getProductBySlug('cached-relation-agar');
        $this->assertNotNull($loaded2);
        $this->assertEquals('Difco Labs', $loaded2->principal?->name);
        $this->assertTrue($loaded2->sectors->contains('id', 'food'));

        $this->assertCount(0, DB::getQueryLog());
    }
}

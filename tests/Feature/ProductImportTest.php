<?php

namespace Tests\Feature;

use App\Models\Principal;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class ProductImportTest extends TestCase
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

        Sector::create([
            'id' => 'pharma-biotech',
            'name' => 'Pharma & Biotech',
        ]);
    }

    public function test_guest_cannot_download_template_or_import(): void
    {
        $this->get(route('admin.products.import.template'))
            ->assertRedirect(route('admin.login'));

        $this->post(route('admin.products.import'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_download_import_template(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.products.import.template'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_admin_can_import_products_from_valid_spreadsheet(): void
    {
        $principal = Principal::create(['name' => 'Merck KGaA']);

        // Create in-memory spreadsheet
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Produk');

        // Headers
        $sheet->fromArray([
            'Nomor Katalog', 'Nama Produk *', 'Kategori *', 'Subkategori',
            'Harga (Rp)', 'Stok', 'Prinsipal', 'Sektor Industri',
            'URL Cover Gambar', 'URL Datasheet PDF', 'Deskripsi Produk',
        ], null, 'A1');

        // Data rows
        $sheet->fromArray([
            'CT-01', 'Reagent A 500ml', 'microbiology', 'reagents',
            '250000', '40', 'Merck KGaA', 'Pharma & Biotech',
            '', '', 'Deskripsi reagent penting',
        ], null, 'A2');

        $sheet->fromArray([
            'CT-02', 'Nutrient Broth 100g', 'Microbiology', '',
            '150000', '20', '', '',
            '', '', 'Broth umum',
        ], null, 'A3');

        $tempFile = tempnam(sys_get_temp_dir(), 'test_import_').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'test-products.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($this->admin)->post(route('admin.products.import'), [
            'excel_file' => $uploadedFile,
        ]);

        $response->assertRedirect(route('admin.products'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'title' => 'Reagent A 500ml',
            'catalog' => 'CT-01',
            'category' => 'microbiology',
            'price' => 250000,
            'stock' => 40,
            'principal_id' => $principal->id,
        ]);

        $this->assertDatabaseHas('products', [
            'title' => 'Nutrient Broth 100g',
            'catalog' => 'CT-02',
            'category' => 'microbiology',
            'price' => 150000,
            'stock' => 20,
        ]);

        if (file_exists($tempFile)) {
            @unlink($tempFile);
        }
    }

    public function test_import_validation_fails_without_file(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.import'), []);

        $response->assertSessionHasErrors('excel_file');
    }

    public function test_admin_can_import_products_with_local_storage_paths(): void
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Produk');

        $sheet->fromArray([
            'Nomor Katalog', 'Nama Produk *', 'Kategori *', 'Subkategori',
            'Harga (Rp)', 'Stok', 'Prinsipal', 'Sektor Industri',
            'URL Cover Gambar', 'URL Datasheet PDF', 'Deskripsi Produk',
        ], null, 'A1');

        $sheet->fromArray([
            'LOCAL-01', 'Local Asset Product', 'microbiology', '',
            '500000', '15', '', '',
            '/storage/uploads/products/local-image.webp', '/storage/uploads/datasheets/local-doc.pdf', 'Deskripsi produk lokal',
        ], null, 'A2');

        $tempFile = tempnam(sys_get_temp_dir(), 'test_import_local_').'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'test-local-paths.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($this->admin)->post(route('admin.products.import'), [
            'excel_file' => $uploadedFile,
        ]);

        $response->assertRedirect(route('admin.products'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'title' => 'Local Asset Product',
            'catalog' => 'LOCAL-01',
            'image' => '/storage/uploads/products/local-image.webp',
            'datasheet_url' => '/storage/uploads/datasheets/local-doc.pdf',
        ]);

        if (file_exists($tempFile)) {
            @unlink($tempFile);
        }
    }

    public function test_admin_can_import_custom_airtable_headers_with_specifications(): void
    {
        Principal::create(['name' => 'Liofilchem']);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Produk');

        // Headers exactly as shown in user's Airtable / Lark Base
        $sheet->fromArray([
            'Catalogue', 'Description', 'Kemasan', 'Principal', 'Price List',
            'Category', 'Sub-Category', 'Sector', 'Function', 'Method Reference',
        ], null, 'A1');

        $sheet->fromArray([
            '611014',
            'Buffered Peptone Water',
            '500 g',
            'Liofilchem',
            '1.493.000',
            'Microbiology Culture Media',
            'Dehydrated Culture Medium',
            'Pharma & Biotech',
            'Uji Salmonella spp',
            'ISO 6579',
        ], null, 'A2');

        $tempFile = sys_get_temp_dir().'/test_airtable_'.time().'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'test-airtable.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($this->admin)->post(route('admin.products.import'), [
            'excel_file' => $uploadedFile,
        ]);

        $response->assertRedirect(route('admin.products'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'title' => 'Buffered Peptone Water',
            'catalog' => '611014',
            'packaging' => '500 g',
            'price' => 1493000,
            'function' => 'Uji Salmonella spp',
            'reference_method' => 'ISO 6579',
            'category' => 'microbiology-culture-media',
            'sub_category' => 'dehydrated-culture-medium',
        ]);

        $this->assertDatabaseHas('product_categories', [
            'key' => 'microbiology-culture-media',
            'name' => 'Microbiology Culture Media',
            'parent_id' => null,
        ]);

        $this->assertDatabaseHas('product_categories', [
            'key' => 'dehydrated-culture-medium',
            'name' => 'Dehydrated Culture Medium',
        ]);

        if (file_exists($tempFile)) {
            @unlink($tempFile);
        }
    }

    public function test_admin_import_skips_duplicate_products_based_on_title_and_catalog(): void
    {
        // 1. Existing product in DB
        Product::create([
            'title' => 'Existing Lab Reagent',
            'catalog' => 'EX-99',
            'category' => 'microbiology',
            'price' => 100000,
            'stock' => 5,
        ]);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Produk');

        $sheet->fromArray([
            'Nomor Katalog', 'Nama Produk *', 'Kategori *', 'Subkategori', 'Harga (Rp)', 'Stok',
        ], null, 'A1');

        $sheet->fromArray([
            // Duplicate title (should be skipped, price should NOT be updated)
            ['DIFF-01', 'Existing Lab Reagent', 'microbiology', '', '999000', '10'],
            // Duplicate catalog (should be skipped)
            ['EX-99', 'Unique Name But Duplicate Catalog', 'microbiology', '', '500000', '10'],
            // Fresh new product (should be imported)
            ['FRESH-01', 'Completely New Reagent Item', 'microbiology', '', '250000', '15'],
        ], null, 'A2');

        $tempFile = sys_get_temp_dir().'/test_skip_duplicates_'.time().'.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'test-skip-dups.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($this->admin)->post(route('admin.products.import'), [
            'excel_file' => $uploadedFile,
        ]);

        $response->assertRedirect(route('admin.products'));
        $response->assertSessionHas('success');

        // New product was imported
        $this->assertDatabaseHas('products', [
            'title' => 'Completely New Reagent Item',
            'catalog' => 'FRESH-01',
            'price' => 250000,
        ]);

        // Duplicate title was NOT overwritten (price remains 100000)
        $this->assertDatabaseHas('products', [
            'title' => 'Existing Lab Reagent',
            'price' => 100000,
        ]);

        // Duplicate catalog was skipped
        $this->assertDatabaseMissing('products', [
            'title' => 'Unique Name But Duplicate Catalog',
        ]);

        if (file_exists($tempFile)) {
            @unlink($tempFile);
        }
    }
}

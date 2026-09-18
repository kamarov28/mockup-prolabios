<?php

namespace Tests\Feature;

use App\Models\Principal;
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
}

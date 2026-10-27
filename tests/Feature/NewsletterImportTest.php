<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Models\Sector;
use App\Models\User;
use App\Services\NewsletterImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class NewsletterImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Sector $sectorFood;
    protected Sector $sectorClinical;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $this->sectorFood = Sector::create([
            'id' => 'food-beverage',
            'name' => 'Food & Beverage',
            'description' => 'Sektor pangan dan minuman',
        ]);

        $this->sectorClinical = Sector::create([
            'id' => 'clinical-diagnostics',
            'name' => 'Clinical Diagnostics',
            'description' => 'Sektor diagnostik medis',
        ]);
    }

    public function test_can_download_newsletter_import_template(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.newsletter.import.template'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_can_import_subscribers_from_excel_file(): void
    {
        // 1. Build in-memory spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pelanggan');

        $sheet->fromArray([
            ['Email Pelanggan *', 'Nama Depan *', 'Nama Belakang', 'Perusahaan / Instansi *', 'Kota', 'Negara', 'Sektor Minat', 'Status Aktif'],
            ['analis.lab@mayora.co.id', 'Budi', 'Santoso', 'PT. Mayora Indah Tbk', 'Tangerang', 'Indonesia', 'Food & Beverage', 'Aktif'],
            ['qc.micro@kalbe.com', 'Siti', 'Rahmawati', 'PT. Kalbe Farma Tbk', 'Cikarang', 'Indonesia', 'food-beverage, Clinical Diagnostics', 'Aktif'],
            ['invalid-email-format', 'John', 'Doe', 'Dummy Corp', 'Jakarta', 'Indonesia', '', 'Aktif'],
        ]);

        $tempPath = tempnam(sys_get_temp_dir(), 'newsletter_import_test_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $uploadedFile = new UploadedFile($tempPath, 'subscribers.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        // 2. Submit import request
        $response = $this->actingAs($this->admin)->post(route('admin.newsletter.import'), [
            'excel_file' => $uploadedFile,
        ]);

        $response->assertRedirect(route('admin.newsletter.index'));
        $response->assertSessionHas('success');

        // 3. Assert subscribers created in DB
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'analis.lab@mayora.co.id',
            'first_name' => 'Budi',
            'last_name' => 'Santoso',
            'company' => 'PT. Mayora Indah Tbk',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'qc.micro@kalbe.com',
            'first_name' => 'Siti',
            'company' => 'PT. Kalbe Farma Tbk',
        ]);

        // Assert sectors attached
        $subBudi = NewsletterSubscriber::where('email', 'analis.lab@mayora.co.id')->first();
        $this->assertTrue($subBudi->sectors->contains('id', 'food-beverage'));

        $subSiti = NewsletterSubscriber::where('email', 'qc.micro@kalbe.com')->first();
        $this->assertEquals(2, $subSiti->sectors->count());

        // 4. Test duplicate prevention on re-import
        $reImportResponse = $this->actingAs($this->admin)->post(route('admin.newsletter.import'), [
            'excel_file' => $uploadedFile,
        ]);

        $reImportResponse->assertRedirect(route('admin.newsletter.index'));
        $this->assertEquals(2, NewsletterSubscriber::count()); // Still 2, no duplicates created

        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }
    }
}

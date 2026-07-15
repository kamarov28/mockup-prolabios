<?php

// Jalankan script ini via CLI: php scratch/test_sheets.php
// Untuk menguji apakah Google Sheets Integration sudah terhubung dengan benar.

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\GoogleSheetsService;

echo "=== Memulai Tes Google Sheets Integration ===\n";

$spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
$jsonPath = env('GOOGLE_SERVICE_ACCOUNT_JSON');

echo "Spreadsheet ID: " . ($spreadsheetId ?: "KOSONG (Harap isi di .env)") . "\n";
echo "Kredensial Path: " . ($jsonPath ?: "KOSONG (Harap isi di .env)") . "\n";

if (!$spreadsheetId || !$jsonPath) {
    echo "ERROR: Harap lengkapi konfigurasi GOOGLE_SPREADSHEET_ID dan GOOGLE_SERVICE_ACCOUNT_JSON di file .env terlebih dahulu!\n";
    exit(1);
}

$absolutePath = base_path($jsonPath);
if (!file_exists($absolutePath)) {
    echo "ERROR: File kredensial JSON tidak ditemukan di path: $absolutePath\n";
    exit(1);
}

echo "Mencoba menghubungkan ke Google API...\n";
$service = new GoogleSheetsService();

$dummyData = [
    'nama' => 'Tester Prolabios',
    'email' => 'test@prolabios.com',
    'telepon' => '021-123456',
    'perusahaan' => 'PT Prolabios Test',
    'subjek_label' => 'Uji Coba Sistem (Test)',
    'pesan' => 'Ini adalah pesan uji coba sistem otomatis untuk memverifikasi Google Sheets API.'
];

$success = $service->appendInquiry($dummyData);

if ($success) {
    echo "SUCCESS: Data berhasil dikirim dan dicatat ke Google Sheets!\n";
    echo "Silakan buka spreadsheet Anda untuk memastikan baris baru dengan nama 'Tester Prolabios' sudah masuk.\n";
} else {
    echo "FAILED: Gagal mengirim data ke Google Sheets. Silakan periksa file laravel.log di storage/logs/laravel.log untuk detail kesalahannya.\n";
}

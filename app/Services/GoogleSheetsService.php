<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = config('contact.google_spreadsheet_id');
        $jsonPath = config('contact.google_service_account_json');

        // Jika kredensial belum dikonfigurasi, skip inisialisasi
        if (!$this->spreadsheetId || !$jsonPath) {
            return;
        }

        // Tentukan path absolut untuk file kredensial JSON
        $absolutePath = base_path($jsonPath);

        if (!file_exists($absolutePath)) {
            Log::warning("File kredensial Google Service Account tidak ditemukan di: " . $absolutePath);
            return;
        }

        try {
            $this->client = new Client();
            $this->client->setAuthConfig($absolutePath);
            $this->client->addScope(Sheets::SPREADSHEETS);
            $this->client->setAccessType('offline');

            $this->service = new Sheets($this->client);
        } catch (\Exception $e) {
            Log::error("Gagal menginisialisasi Google Sheets Client: " . $e->getMessage());
        }
    }

    /**
     * Menambahkan baris data inquiry baru ke Google Sheets
     */
    public function appendInquiry(array $data)
    {
        // Jika service belum siap/belum dikonfigurasi, skip secara aman
        if (!$this->service || !$this->spreadsheetId) {
            Log::info("Google Sheets Service dilewati karena konfigurasi belum lengkap di .env");
            return false;
        }

        try {
            // Urutan kolom: Waktu (WIB), Nama, Email, Telepon, Perusahaan, Subjek, Pesan
            $values = [
                [
                    now()->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    $data['nama'],
                    $data['email'],
                    $data['telepon'] ?? '-',
                    $data['perusahaan'] ?? '-',
                    $data['subjek_label'] ?? $data['subjek'],
                    $data['pesan']
                ]
            ];

            // Tentukan range penulisan (Sheet1 adalah nama default sheet pertama)
            // Range "Sheet1!A:G" akan otomatis mencari baris kosong terbawah dari kolom A sampai G
            $body = new ValueRange([
                'values' => $values
            ]);

            $params = [
                'valueInputOption' => 'RAW'
            ];

            $range = 'Sheet1!A:G';

            $this->service->spreadsheets_values->append(
                $this->spreadsheetId,
                $range,
                $body,
                $params
            );

            return true;
        } catch (\Exception $e) {
            Log::error("Gagal menambahkan data ke Google Sheets: " . $e->getMessage());
            return false;
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncProductsFromSheets extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'database:sync-sheets';

    /**
     * The console command description.
     */
    protected $description = 'Synchronize catalog products from Google Sheets to the local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Google Sheets → Local DB product synchronization...');

        $spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
        $jsonPath = env('GOOGLE_SERVICE_ACCOUNT_JSON');

        if (!$spreadsheetId || !$jsonPath) {
            $this->error('ERROR: Google Sheets config is missing in .env! Please set GOOGLE_SPREADSHEET_ID and GOOGLE_SERVICE_ACCOUNT_JSON.');
            return 1;
        }

        $absolutePath = base_path($jsonPath);
        if (!file_exists($absolutePath)) {
            $this->error("ERROR: Google Service Account JSON file not found at: " . $absolutePath);
            return 1;
        }

        try {
            $client = new Client();
            $client->setAuthConfig($absolutePath);
            $client->addScope(Sheets::SPREADSHEETS_READONLY);
            $client->setAccessType('offline');

            $service = new Sheets($client);

            // Kita membaca dari tab bernama "Products" baris A2 sampai G (mengasumsikan baris 1 adalah header)
            // Jika tab "Products" tidak ada, silakan buat tab baru dengan nama tersebut.
            $range = 'Products!A2:G';
            
            $this->info("Fetching data from Spreadsheet: {$spreadsheetId}...");
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $rows = $response->getValues();

            if (empty($rows)) {
                $this->warn("No data found or sheet 'Products' is empty (make sure headers are in row 1, data starts from row 2).");
                return 0;
            }

            $this->info('Found ' . count($rows) . ' rows in Google Sheets. Synchronizing...');

            // Mulai transaksi database agar proses aman
            DB::beginTransaction();

            // Kosongkan tabel produk saat ini sebelum menimpa dengan data baru
            DB::table('products')->truncate();

            $insertedCount = 0;
            $seenTitles = [];

            foreach ($rows as $index => $row) {
                // Pastikan kolom Title (Kolom B / indeks 1) dan Category (Kolom D / indeks 3) terisi
                $title = isset($row[1]) ? trim($row[1]) : '';
                $category = isset($row[3]) ? trim($row[3]) : '';

                if (empty($title) || empty($category)) {
                    $this->warn("Row " . ($index + 2) . " skipped: Title or Category is empty.");
                    continue;
                }

                // Hindari duplikasi title di dalam sheet
                if (in_array($title, $seenTitles, true)) {
                    $this->warn("Row " . ($index + 2) . " skipped: Duplicate title '{$title}'.");
                    continue;
                }
                $seenTitles[] = $title;

                DB::table('products')->insert([
                    'catalog'      => isset($row[0]) ? trim($row[0]) : null,
                    'title'        => $title,
                    'description'  => isset($row[2]) ? trim($row[2]) : null,
                    'category'     => $category,
                    'sub_category' => isset($row[4]) ? trim($row[4]) : null,
                    'sector'       => isset($row[5]) ? trim($row[5]) : null,
                    'image'        => isset($row[6]) ? trim($row[6]) : null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                $insertedCount++;
            }

            DB::commit();
            $this->info("✓ Sync completed successfully! Imported {$insertedCount} products to local database.");

            // Output juga ke file JSON agar seeder default ter-update
            $jsonProducts = DB::table('products')->orderBy('id')->get()->map(fn($r) => (array)$r)->toArray();
            $jsonPathToWrite = storage_path('app/private/data/products.json');
            file_put_contents($jsonPathToWrite, json_encode($jsonProducts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info("✓ Updated local storage backup at: storage/app/private/data/products.json");

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("ERROR: Failed to sync from Google Sheets: " . $e->getMessage());
            Log::error("Google Sheets sync failed: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return 1;
        }
    }
}

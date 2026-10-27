<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;
use App\Models\Sector;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NewsletterImportService
{
    /**
     * Generate standard Excel (.xlsx) template for bulk newsletter subscriber import.
     */
    public function generateTemplate(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $spreadsheet->getProperties()
            ->setCreator('PT. Prolabios Mitra Analitika')
            ->setTitle('Template Import Pelanggan Buletin Sektor Prolabios')
            ->setSubject('Basis Data Pelanggan Buletin');

        // -------------------------------------------------------------
        // Sheet 1: Data Pelanggan
        // -------------------------------------------------------------
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pelanggan');

        $headers = [
            'A' => ['Email Pelanggan *', 32],
            'B' => ['Nama Depan *', 22],
            'C' => ['Nama Belakang', 22],
            'D' => ['Perusahaan / Instansi *', 35],
            'E' => ['Kota', 20],
            'F' => ['Negara', 18],
            'G' => ['Sektor Minat', 35],
            'H' => ['Status Aktif', 16],
        ];

        foreach ($headers as $col => [$title, $width]) {
            $sheet->setCellValue("{$col}1", $title);
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Header Styling (Ruby Red Prolabios accent)
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10.5,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'A6171C'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Fetch registered sectors from DB for sample data
        $sectors = Sector::orderBy('name')->get();
        $sampleSector1 = $sectors->first()?->name ?? 'Food & Beverage';
        $sampleSector2 = $sectors->skip(1)->first()?->name ?? 'Clinical Diagnostics';

        // Sample Rows
        $samples = [
            [
                'analis.lab@mayora.co.id',
                'Budi',
                'Santoso',
                'PT. Mayora Indah Tbk',
                'Tangerang',
                'Indonesia',
                $sampleSector1,
                'Aktif',
            ],
            [
                'qc.micro@kalbefarma.com',
                'Siti',
                'Rahmawati',
                'PT. Kalbe Farma Tbk',
                'Cikarang',
                'Indonesia',
                "{$sampleSector1}, {$sampleSector2}",
                'Aktif',
            ],
        ];

        $rowIdx = 2;
        foreach ($samples as $data) {
            foreach ($data as $colIdx => $val) {
                $colLetter = chr(65 + $colIdx);
                $sheet->setCellValueExplicit("{$colLetter}{$rowIdx}", $val, DataType::TYPE_STRING);
            }
            $rowIdx++;
        }

        // Data Styling
        $sheet->getStyle('A2:H3')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // -------------------------------------------------------------
        // Sheet 2: Panduan & Referensi Sektor
        // -------------------------------------------------------------
        $refSheet = $spreadsheet->createSheet();
        $refSheet->setTitle('Panduan & Referensi');

        $refSheet->setCellValue('A1', 'ID Sektor');
        $refSheet->setCellValue('B1', 'Nama Resmi Sektor Industri');
        $refSheet->setCellValue('C1', 'Catatan Pengisian');

        $refSheet->getColumnDimension('A')->setWidth(20);
        $refSheet->getColumnDimension('B')->setWidth(35);
        $refSheet->getColumnDimension('C')->setWidth(50);

        $refSheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E293B'],
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $refSheet->getRowDimension(1)->setRowHeight(24);

        $rIdx = 2;
        foreach ($sectors as $s) {
            $refSheet->setCellValue("A{$rIdx}", $s->id);
            $refSheet->setCellValue("B{$rIdx}", $s->name);
            $refSheet->setCellValue("C{$rIdx}", "Gunakan ID '{$s->id}' atau Nama '{$s->name}'. Pisahkan koma jika multi-sektor.");
            $rIdx++;
        }

        // Return to first sheet
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * Import newsletter subscribers from spreadsheet.
     *
     * @return array{imported: int, skipped: int, errors: array<string>}
     */
    public function import(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        if (empty($rows) || count($rows) < 2) {
            return [
                'imported' => 0,
                'skipped' => 0,
                'errors' => ['File spreadsheet kosong atau tidak memiliki baris data setelah header.'],
            ];
        }

        // Header mapping
        $headerRow = array_shift($rows);
        $colMap = $this->buildColumnMap($headerRow);

        // Pre-fetch sector dictionary: slug/id => Sector model, lowercase name => Sector model
        $allSectors = Sector::all();
        $sectorMap = [];
        foreach ($allSectors as $sec) {
            $sectorMap[strtolower(trim($sec->id))] = $sec;
            $sectorMap[strtolower(trim($sec->name))] = $sec;
            $sectorMap[Str::slug($sec->name)] = $sec;
        }

        // Pre-fetch existing subscriber emails
        $existingEmails = NewsletterSubscriber::pluck('email')
            ->map(fn ($e) => strtolower(trim($e)))
            ->flip()
            ->toArray();

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $batchEmailsInFile = [];

        foreach ($rows as $rowIndex => $row) {
            $email = trim((string) ($row[$colMap['email'] ?? 'A'] ?? ''));
            if ($email === '') {
                continue; // Skip entirely blank rows
            }

            $normEmail = strtolower($email);

            // Validation: Email format
            if (!filter_var($normEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Baris {$rowIndex}: Format email '{$email}' tidak valid.";
                continue;
            }

            // Duplicate detection: Already in DB or duplicate within same sheet
            if (isset($existingEmails[$normEmail]) || isset($batchEmailsInFile[$normEmail])) {
                $skipped++;
                continue;
            }

            $firstName = trim((string) ($row[$colMap['first_name'] ?? 'B'] ?? ''));
            if ($firstName === '') {
                $firstName = explode('@', $normEmail)[0]; // Fallback to email local-part
            }

            $lastName = trim((string) ($row[$colMap['last_name'] ?? 'C'] ?? '')) ?: null;
            $company = trim((string) ($row[$colMap['company'] ?? 'D'] ?? '')) ?: 'Institusi / Laboratorium';
            $city = trim((string) ($row[$colMap['city'] ?? 'E'] ?? '')) ?: null;
            $country = trim((string) ($row[$colMap['country'] ?? 'F'] ?? '')) ?: 'Indonesia';

            $rawStatus = strtolower(trim((string) ($row[$colMap['status'] ?? 'H'] ?? '')));
            $isActive = !in_array($rawStatus, ['0', 'false', 'nonaktif', 'unsubscribed', 'tidak', 'off']);

            // Parse sectors
            $rawSectors = trim((string) ($row[$colMap['sectors'] ?? 'G'] ?? ''));
            $matchedSectorIds = $this->resolveSectorIds($rawSectors, $sectorMap);

            // Create subscriber
            $subscriber = NewsletterSubscriber::create([
                'email' => $normEmail,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'company' => $company,
                'city' => $city,
                'country' => $country,
                'password' => Hash::make(Str::random(16)), // Secure randomized password
                'is_active' => $isActive,
                'email_verified_at' => now(), // Admin imported users are pre-verified
                'unsubscribe_token' => Str::random(40),
            ]);

            if (!empty($matchedSectorIds)) {
                $subscriber->sectors()->sync($matchedSectorIds);
            }

            $existingEmails[$normEmail] = true;
            $batchEmailsInFile[$normEmail] = true;
            $imported++;
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * Build dynamic header mapping supporting diverse naming conventions.
     */
    protected function buildColumnMap(array $headerRow): array
    {
        $map = [
            'email' => null,
            'first_name' => null,
            'last_name' => null,
            'company' => null,
            'city' => null,
            'country' => null,
            'sectors' => null,
            'status' => null,
        ];

        foreach ($headerRow as $colLetter => $rawHeader) {
            $header = strtolower(trim((string) $rawHeader));
            if ($header === '') continue;

            if (str_contains($header, 'email') || str_contains($header, 'surel') || $header === 'mail') {
                $map['email'] ??= $colLetter;
            } elseif (str_contains($header, 'nama depan') || str_contains($header, 'first name') || str_contains($header, 'firstname')) {
                $map['first_name'] ??= $colLetter;
            } elseif (str_contains($header, 'nama belakang') || str_contains($header, 'last name') || str_contains($header, 'lastname')) {
                $map['last_name'] ??= $colLetter;
            } elseif (str_contains($header, 'perusahaan') || str_contains($header, 'instansi') || str_contains($header, 'company') || str_contains($header, 'organization') || str_contains($header, 'institusi')) {
                $map['company'] ??= $colLetter;
            } elseif (str_contains($header, 'kota') || str_contains($header, 'city')) {
                $map['city'] ??= $colLetter;
            } elseif (str_contains($header, 'negara') || str_contains($header, 'country')) {
                $map['country'] ??= $colLetter;
            } elseif (str_contains($header, 'sektor') || str_contains($header, 'sector') || str_contains($header, 'bidang') || str_contains($header, 'industri')) {
                $map['sectors'] ??= $colLetter;
            } elseif (str_contains($header, 'status') || str_contains($header, 'aktif') || str_contains($header, 'active')) {
                $map['status'] ??= $colLetter;
            }
        }

        // Positional fallbacks if zero headers mapped
        $map['email'] ??= 'A';
        $map['first_name'] ??= 'B';
        $map['last_name'] ??= 'C';
        $map['company'] ??= 'D';
        $map['city'] ??= 'E';
        $map['country'] ??= 'F';
        $map['sectors'] ??= 'G';
        $map['status'] ??= 'H';

        return $map;
    }

    /**
     * Resolve sector IDs from comma-separated or raw string against pre-fetched lookup map.
     */
    protected function resolveSectorIds(string $rawSectors, array $sectorMap): array
    {
        if ($rawSectors === '') {
            return [];
        }

        $tokens = preg_split('/[,;\/|]+/', $rawSectors);
        $resolved = [];

        foreach ($tokens as $token) {
            $t = trim($token);
            if ($t === '') continue;

            $norm = strtolower($t);
            $slug = Str::slug($t);

            // Direct map lookup
            if (isset($sectorMap[$norm])) {
                $resolved[$sectorMap[$norm]->id] = true;
                continue;
            }
            if (isset($sectorMap[$slug])) {
                $resolved[$sectorMap[$slug]->id] = true;
                continue;
            }

            // Substring fuzzy match
            foreach ($sectorMap as $key => $sec) {
                if (strlen($norm) >= 3 && (str_contains($key, $norm) || str_contains($norm, $key))) {
                    $resolved[$sec->id] = true;
                    break;
                }
            }
        }

        return array_keys($resolved);
    }
}

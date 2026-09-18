<?php

namespace App\Services;

use App\Helpers\HtmlSanitizer;
use App\Models\Principal;
use App\Models\ProductCategory;
use App\Models\Sector;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductImportService
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Generate standard Excel (.xlsx) template for bulk product import.
     */
    public function generateTemplate(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $spreadsheet->getProperties()
            ->setCreator('PT. Prolabios Mitra Analitika')
            ->setTitle('Template Import Produk Katalog Prolabios')
            ->setSubject('Katalog Produk');

        // -------------------------------------------------------------
        // Sheet 1: Data Produk
        // -------------------------------------------------------------
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Produk');

        $headers = [
            'A' => ['Nomor Katalog', 18],
            'B' => ['Nama Produk *', 35],
            'C' => ['Kategori *', 22],
            'D' => ['Subkategori', 25],
            'E' => ['Harga (Rp)', 18],
            'F' => ['Stok', 12],
            'G' => ['Prinsipal', 22],
            'H' => ['Sektor Industri', 25],
            'I' => ['URL Cover Gambar', 30],
            'J' => ['URL Datasheet PDF', 30],
            'K' => ['Deskripsi Produk', 45],
        ];

        foreach ($headers as $col => [$title, $width]) {
            $sheet->setCellValue("{$col}1", $title);
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Header Styling (Ruby Red accent, white text, bold)
        $sheet->getStyle('A1:K1')->applyFromArray([
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
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Contoh baris sampel
        $samples = [
            [
                'A' => '610152',
                'B' => 'Nutrient Agar 500g',
                'C' => 'microbiology',
                'D' => 'dehydrated-culture-media',
                'E' => 1500000,
                'F' => 50,
                'G' => 'Merck KGaA',
                'H' => 'Pharma & Biotech, Food & Beverage',
                'I' => '',
                'J' => '',
                'K' => 'Nutrient Agar digunakan untuk isolasi dan kultivasi umum mikroorganisme di laboratorium.',
            ],
            [
                'A' => 'PD-90',
                'B' => 'Petri Dish 90mm Sterile (Pack of 20)',
                'C' => 'microbiology',
                'D' => 'consumables',
                'E' => 125000,
                'F' => 200,
                'G' => '',
                'H' => 'Semua Sektor',
                'I' => '',
                'J' => '',
                'K' => 'Cawan petri steril diameter 90mm bahan polistiren bening berkualitas tinggi.',
            ],
        ];

        $r = 2;
        foreach ($samples as $sample) {
            foreach ($sample as $col => $val) {
                if ($col === 'E' || $col === 'F') {
                    $sheet->setCellValue("{$col}{$r}", $val);
                } else {
                    $sheet->setCellValueExplicit("{$col}{$r}", (string) $val, DataType::TYPE_STRING);
                }
            }
            $r++;
        }

        // Format angka harga di kolom E
        $sheet->getStyle('E2:E3')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('F2:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('A2:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Border halus pada baris contoh
        $sheet->getStyle('A2:K3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E5E7EB');

        // -------------------------------------------------------------
        // Sheet 2: Panduan & Referensi
        // -------------------------------------------------------------
        $refSheet = $spreadsheet->createSheet();
        $refSheet->setTitle('Panduan & Referensi');

        // Judul Panduan
        $refSheet->setCellValue('A1', 'PANDUAN & DAFTAR REFERENSI IMPORT PRODUK');
        $refSheet->getStyle('A1')->getFont()->setBold(true)->setSize(13)->setColor(new Color('A6171C'));
        $refSheet->mergeCells('A1:F1');

        $rules = [
            '1. Kolom Nama Produk (*) dan Kategori (*) adalah kolom wajib diisi. Baris tanpa nama atau kategori akan otomatis dilewati.',
            '2. Format Harga: Tulis hanya angka murni (contoh: 1500000). Jangan menambahkan teks "Rp", titik, atau spasi.',
            '3. Format Stok: Tulis angka bulat (contoh: 25). Jika kosong, otomatis bernilai 0.',
            '4. Penimpaan Data (Upsert): Jika Nama Produk sudah ada di database katalog, produk tersebut akan otomatis diperbarui datanya.',
            '5. Kolom Kategori dapat diisi dengan Kunci Kategori (contoh: microbiology) atau Nama Kategori (contoh: Microbiology).',
            '6. Kolom Sektor dapat diisi dengan nama sektor dipisah koma (contoh: Pharma & Biotech, Food & Beverage).',
        ];

        $ruleRow = 3;
        foreach ($rules as $rule) {
            $refSheet->setCellValue("A{$ruleRow}", $rule);
            $refSheet->getStyle("A{$ruleRow}")->getFont()->setSize(9.5)->setColor(new Color('334155'));
            $ruleRow++;
        }

        // Tabel Referensi Kategori
        $startCatRow = $ruleRow + 2;
        $refSheet->setCellValue("A{$startCatRow}", 'KODE KATEGORI');
        $refSheet->setCellValue("B{$startCatRow}", 'NAMA KATEGORI RESMI');
        $refSheet->getStyle("A{$startCatRow}:B{$startCatRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
        ]);

        $categories = ProductCategory::whereNull('parent_id')->orderBy('name')->get();
        $catRow = $startCatRow + 1;
        foreach ($categories as $cat) {
            $refSheet->setCellValue("A{$catRow}", $cat->key);
            $refSheet->setCellValue("B{$catRow}", $cat->name);
            $catRow++;
        }

        // Tabel Referensi Sektor
        $startSecRow = $startCatRow;
        $refSheet->setCellValue("D{$startSecRow}", 'ID');
        $refSheet->setCellValue("E{$startSecRow}", 'NAMA SEKTOR INDUSTRI');
        $refSheet->getStyle("D{$startSecRow}:E{$startSecRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
        ]);

        $sectors = Sector::orderBy('id')->get();
        $secRow = $startSecRow + 1;
        foreach ($sectors as $sec) {
            $refSheet->setCellValue("D{$secRow}", $sec->id);
            $refSheet->setCellValue("E{$secRow}", $sec->name);
            $secRow++;
        }

        // Tabel Referensi Prinsipal
        $startPrRow = max($catRow, $secRow) + 2;
        $refSheet->setCellValue("A{$startPrRow}", 'ID');
        $refSheet->setCellValue("B{$startPrRow}", 'NAMA PRINSIPAL TERDAFTAR');
        $refSheet->getStyle("A{$startPrRow}:B{$startPrRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
        ]);

        $principals = Principal::orderBy('name')->get();
        $prRow = $startPrRow + 1;
        if ($principals->isEmpty()) {
            $refSheet->setCellValue("A{$prRow}", '-');
            $refSheet->setCellValue("B{$prRow}", '(Belum ada data prinsipal khusus)');
        } else {
            foreach ($principals as $pr) {
                $refSheet->setCellValue("A{$prRow}", $pr->id);
                $refSheet->setCellValue("B{$prRow}", $pr->name);
                $prRow++;
            }
        }

        $refSheet->getColumnDimension('A')->setWidth(25);
        $refSheet->getColumnDimension('B')->setWidth(35);
        $refSheet->getColumnDimension('D')->setWidth(10);
        $refSheet->getColumnDimension('E')->setWidth(30);

        // Kembalikan fokus ke Sheet 1
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * Parse and import products from uploaded Excel or CSV file.
     *
     * @return array{success: bool, imported: int, skipped: int, errors: array<string>}
     */
    public function import(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        if (count($rows) <= 1) {
            return [
                'success' => false,
                'imported' => 0,
                'skipped' => 0,
                'errors' => ['File spreadsheet kosong atau hanya berisi baris header.'],
            ];
        }

        // Build lookup tables
        $categoryLookup = [];
        foreach (ProductCategory::all() as $cat) {
            $categoryLookup[strtolower(trim($cat->key))] = $cat->key;
            $categoryLookup[strtolower(trim($cat->name))] = $cat->key;
        }

        $principalLookup = [];
        foreach (Principal::all() as $pr) {
            $principalLookup[(string) $pr->id] = $pr->id;
            $principalLookup[strtolower(trim($pr->name))] = $pr->id;
        }

        $sectorLookup = [];
        foreach (Sector::all() as $sec) {
            $sectorLookup[(string) $sec->id] = $sec->id;
            $sectorLookup[strtolower(trim($sec->name))] = $sec->id;
        }

        $productsToStore = [];
        $skipped = 0;
        $errors = [];
        $rowIndex = 1;

        foreach ($rows as $row) {
            if ($rowIndex === 1) {
                $rowIndex++;

                continue; // Skip header
            }
            $rowIndex++;

            $catalog = trim((string) ($row['A'] ?? ''));
            $title = trim((string) ($row['B'] ?? ''));
            $rawCategory = trim((string) ($row['C'] ?? ''));

            // Abaikan baris kosong total
            if ($title === '' && $catalog === '' && $rawCategory === '') {
                continue;
            }

            if ($title === '') {
                $skipped++;
                $errors[] = "Baris {$rowIndex}: Nama produk kosong.";

                continue;
            }

            $catKey = $categoryLookup[strtolower($rawCategory)] ?? null;
            if (! $catKey) {
                $skipped++;
                $errors[] = "Baris {$rowIndex} ('{$title}'): Kategori '{$rawCategory}' tidak dikenali.";

                continue;
            }

            $subCategory = trim((string) ($row['D'] ?? ''));

            // Parse harga (bersihkan titik, koma, spasi, Rp)
            $rawPrice = (string) ($row['E'] ?? '0');
            $cleanPrice = preg_replace('/[^\d]/', '', $rawPrice);
            $price = $cleanPrice !== '' ? (float) $cleanPrice : 0;

            // Parse stok
            $rawStock = (string) ($row['F'] ?? '0');
            $cleanStock = preg_replace('/[^\d]/', '', $rawStock);
            $stock = $cleanStock !== '' ? (int) $cleanStock : 0;

            // Parse prinsipal
            $rawPrincipal = trim((string) ($row['G'] ?? ''));
            $principalId = null;
            if ($rawPrincipal !== '') {
                $principalId = $principalLookup[strtolower($rawPrincipal)] ?? null;
            }

            // Parse sektor industri
            $rawSector = trim((string) ($row['H'] ?? ''));
            $matchedSectorIds = [];
            if ($rawSector !== '') {
                $sectorParts = array_map('trim', explode(',', $rawSector));
                foreach ($sectorParts as $part) {
                    $normPart = strtolower($part);
                    if (isset($sectorLookup[$normPart])) {
                        $matchedSectorIds[] = $sectorLookup[$normPart];
                    }
                }
            }
            $sectorCsv = ! empty($matchedSectorIds) ? implode(',', array_unique($matchedSectorIds)) : null;

            // URL Cover & PDF Datasheet
            $imageUrl = trim((string) ($row['I'] ?? ''));
            $datasheetUrl = trim((string) ($row['J'] ?? ''));
            $description = trim((string) ($row['K'] ?? ''));

            $productsToStore[] = [
                'catalog' => Str::limit($catalog, 255, ''),
                'title' => Str::limit($title, 255, ''),
                'category' => $catKey,
                'sub_category' => Str::limit($subCategory, 255, ''),
                'sector' => $sectorCsv,
                'principal_id' => $principalId,
                'datasheet_url' => filter_var($datasheetUrl, FILTER_VALIDATE_URL) ? $datasheetUrl : null,
                'description' => HtmlSanitizer::clean($description),
                'image' => filter_var($imageUrl, FILTER_VALIDATE_URL) ? $imageUrl : '/images/placeholder.svg',
                'price' => $price,
                'stock' => $stock,
            ];
        }

        $savedCount = count($productsToStore);
        if ($savedCount > 0) {
            $this->productService->upsertProducts($productsToStore);
        }

        return [
            'success' => $savedCount > 0,
            'imported' => $savedCount,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }
}

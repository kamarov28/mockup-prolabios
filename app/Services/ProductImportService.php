<?php

namespace App\Services;

use App\Helpers\HtmlSanitizer;
use App\Models\Principal;
use App\Models\Product;
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
            'I' => ['Kemasan', 18],
            'J' => ['Fungsi', 30],
            'K' => ['Metode Referensi', 25],
            'L' => ['URL Cover Gambar', 30],
            'M' => ['URL Datasheet PDF', 30],
            'N' => ['Deskripsi Produk', 45],
        ];

        foreach ($headers as $col => [$title, $width]) {
            $sheet->setCellValue("{$col}1", $title);
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Header Styling (Ruby Red accent, white text, bold)
        $sheet->getStyle('A1:N1')->applyFromArray([
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
                'I' => '500 g',
                'J' => 'Isolasi dan kultivasi umum mikroorganisme',
                'K' => 'ISO 11133',
                'L' => '',
                'M' => '',
                'N' => 'Nutrient Agar digunakan untuk isolasi dan kultivasi umum mikroorganisme di laboratorium.',
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
                'I' => 'Pack of 20',
                'J' => 'Wadah pembiakan kultur mikrobiologi',
                'K' => 'Standar ISO 9001',
                'L' => '',
                'M' => '',
                'N' => 'Cawan petri steril diameter 90mm bahan polistiren bening berkualitas tinggi.',
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
        $this->productService->ensureSpecificationColumnsExist();

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

        // Cache daftar judul dan katalog yang sudah ada untuk skip duplikat secara otomatis
        $existingTitles = array_fill_keys(
            Product::query()->pluck('title')->map(fn ($t) => strtolower(trim((string) $t)))->filter()->all(),
            true
        );
        $existingCatalogs = array_fill_keys(
            Product::query()->whereNotNull('catalog')->where('catalog', '!=', '')->pluck('catalog')->map(fn ($c) => strtolower(trim((string) $c)))->filter()->all(),
            true
        );
        $seenTitlesInBatch = [];
        $seenCatalogsInBatch = [];

        // Build dynamic header column mapping from row 1 to support custom/exported sheets
        $headerRow = reset($rows);
        $colMap = [];
        if (is_array($headerRow)) {
            foreach ($headerRow as $colLetter => $headerText) {
                $norm = strtolower(trim((string) $headerText));
                if (in_array($norm, ['nomor katalog', 'katalog', 'catalog', 'catalogue', 'sku'], true)) {
                    $colMap['catalog'] = $colLetter;
                } elseif (in_array($norm, ['nama produk', 'nama produk *', 'title', 'nama', 'product name'], true)) {
                    $colMap['title'] = $colLetter;
                } elseif (str_starts_with($norm, 'kategori') || $norm === 'category') {
                    $colMap['category'] = $colLetter;
                } elseif (str_starts_with($norm, 'subkategori') || str_starts_with($norm, 'sub-kategori') || str_starts_with($norm, 'sub category') || str_starts_with($norm, 'sub-category') || $norm === 'subcategory' || $norm === 'sub-category') {
                    $colMap['sub_category'] = $colLetter;
                } elseif (in_array($norm, ['kemasan', 'packaging', 'satuan', 'package', 'pack'], true)) {
                    $colMap['packaging'] = $colLetter;
                } elseif (in_array($norm, ['fungsi', 'function', 'aplikasi', 'application'], true)) {
                    $colMap['function'] = $colLetter;
                } elseif (in_array($norm, ['metode referensi', 'method reference', 'metode', 'reference method', 'standard'], true)) {
                    $colMap['reference_method'] = $colLetter;
                } elseif (in_array($norm, ['harga', 'harga (rp)', 'price', 'price list', 'harga produk'], true)) {
                    $colMap['price'] = $colLetter;
                } elseif (in_array($norm, ['stok', 'stock', 'qty'], true)) {
                    $colMap['stock'] = $colLetter;
                } elseif (in_array($norm, ['prinsipal', 'principal', 'brand', 'manufaktur'], true)) {
                    $colMap['principal'] = $colLetter;
                } elseif (in_array($norm, ['sektor industri', 'sektor', 'sector'], true)) {
                    $colMap['sector'] = $colLetter;
                } elseif (in_array($norm, ['url cover gambar', 'cover', 'image', 'gambar', 'foto'], true)) {
                    $colMap['image'] = $colLetter;
                } elseif (in_array($norm, ['url datasheet pdf', 'datasheet', 'pdf', 'dokumen'], true)) {
                    $colMap['datasheet'] = $colLetter;
                } elseif (in_array($norm, ['deskripsi produk', 'deskripsi', 'rincian'], true)) {
                    $colMap['description'] = $colLetter;
                } elseif ($norm === 'description' && ! isset($colMap['title'])) {
                    $colMap['title'] = $colLetter;
                }
            }
        }

        $hasMappedHeaders = ! empty($colMap);

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

            $catalog = trim((string) ($row[$colMap['catalog'] ?? 'A'] ?? ''));
            $title = trim((string) ($row[$colMap['title'] ?? 'B'] ?? ''));
            $rawCategory = trim((string) ($row[$colMap['category'] ?? 'C'] ?? ''));

            // Abaikan baris kosong total
            if ($title === '' && $catalog === '' && $rawCategory === '') {
                continue;
            }

            if ($title === '') {
                $skipped++;
                $errors[] = "Baris {$rowIndex}: Nama produk kosong.";

                continue;
            }

            $normTitle = strtolower($title);
            $normCatalog = strtolower($catalog);

            // Cek duplikat judul terhadap database & file yang sedang diproses
            if (isset($existingTitles[$normTitle]) || isset($seenTitlesInBatch[$normTitle])) {
                $skipped++;
                $errors[] = "Baris {$rowIndex} ('{$title}'): Dilewati karena judul produk sudah ada di database/file (duplikat).";

                continue;
            }

            // Cek duplikat nomor katalog jika diisi
            if ($catalog !== '' && (isset($existingCatalogs[$normCatalog]) || isset($seenCatalogsInBatch[$normCatalog]))) {
                $skipped++;
                $errors[] = "Baris {$rowIndex} ('{$title}'): Dilewati karena nomor katalog '{$catalog}' sudah ada di database/file (duplikat).";

                continue;
            }

            $seenTitlesInBatch[$normTitle] = true;
            if ($catalog !== '') {
                $seenCatalogsInBatch[$normCatalog] = true;
            }

            $catKey = $categoryLookup[strtolower($rawCategory)] ?? null;
            $parentCat = null;

            if (! $catKey) {
                // Auto-create kategori baru jika belum terdaftar di database
                $catSlug = Str::slug($rawCategory);
                if ($catSlug === '') {
                    $catSlug = 'kategori-'.time();
                }

                $parentCat = ProductCategory::whereNull('parent_id')
                    ->where(function ($q) use ($rawCategory, $catSlug) {
                        $q->where('key', $catSlug)
                            ->orWhereRaw('LOWER(name) = ?', [strtolower($rawCategory)]);
                    })->first();

                if (! $parentCat) {
                    $uniqueKey = $catSlug;
                    $counter = 1;
                    while (ProductCategory::where('key', $uniqueKey)->exists()) {
                        $uniqueKey = $catSlug.'-'.$counter++;
                    }

                    $parentCat = ProductCategory::create([
                        'key' => $uniqueKey,
                        'name' => $rawCategory,
                        'parent_id' => null,
                        'sort_order' => (ProductCategory::whereNull('parent_id')->max('sort_order') ?? 0) + 1,
                    ]);
                }

                $catKey = $parentCat->key;
                $categoryLookup[strtolower($rawCategory)] = $catKey;
                $categoryLookup[strtolower($parentCat->name)] = $catKey;
                $categoryLookup[strtolower($parentCat->key)] = $catKey;
            } else {
                $parentCat = ProductCategory::whereNull('parent_id')->where('key', $catKey)->first();
            }

            // Auto-create sub-kategori jika belum ada di bawah kategori induk
            $subCategory = trim((string) ($row[$colMap['sub_category'] ?? 'D'] ?? ''));
            $subCategoryKey = null;

            if ($subCategory !== '') {
                $subSlug = Str::slug($subCategory);
                if ($subSlug === '') {
                    $subSlug = 'sub-'.time();
                }

                $childCat = null;
                if ($parentCat) {
                    $childCat = ProductCategory::where('parent_id', $parentCat->id)
                        ->where(function ($q) use ($subCategory, $subSlug) {
                            $q->where('key', $subSlug)
                                ->orWhereRaw('LOWER(name) = ?', [strtolower($subCategory)]);
                        })->first();

                    if (! $childCat) {
                        $uniqueSubKey = $subSlug;
                        $counter = 1;
                        while (ProductCategory::where('key', $uniqueSubKey)->exists()) {
                            $uniqueSubKey = $subSlug.'-'.$counter++;
                        }

                        $childCat = ProductCategory::create([
                            'key' => $uniqueSubKey,
                            'name' => $subCategory,
                            'parent_id' => $parentCat->id,
                            'sort_order' => (ProductCategory::where('parent_id', $parentCat->id)->max('sort_order') ?? 0) + 1,
                        ]);
                    }
                }

                $subCategoryKey = $childCat ? $childCat->key : $subSlug;
            }

            // Parse harga (bersihkan titik, koma, spasi, Rp)
            $rawPrice = isset($colMap['price']) ? (string) ($row[$colMap['price']] ?? '0') : (! $hasMappedHeaders ? (string) ($row['E'] ?? '0') : '0');
            $cleanPrice = preg_replace('/[^\d]/', '', $rawPrice);
            $price = $cleanPrice !== '' ? (float) $cleanPrice : 0;

            // Parse stok
            $rawStock = isset($colMap['stock']) ? (string) ($row[$colMap['stock']] ?? '0') : (! $hasMappedHeaders ? (string) ($row['F'] ?? '0') : '0');
            $cleanStock = preg_replace('/[^\d]/', '', $rawStock);
            $stock = $cleanStock !== '' ? (int) $cleanStock : 0;

            // Parse prinsipal
            $rawPrincipal = isset($colMap['principal']) ? trim((string) ($row[$colMap['principal']] ?? '')) : (! $hasMappedHeaders ? trim((string) ($row['G'] ?? '')) : '');
            $principalId = null;
            if ($rawPrincipal !== '') {
                $principalId = $principalLookup[strtolower($rawPrincipal)] ?? null;
                if (! $principalId) {
                    foreach ($principalLookup as $lookupKey => $pId) {
                        if (str_contains(strtolower($rawPrincipal), $lookupKey) || str_contains($lookupKey, strtolower($rawPrincipal))) {
                            $principalId = $pId;
                            break;
                        }
                    }
                }
            }

            // Parse sektor industri
            $rawSector = isset($colMap['sector']) ? trim((string) ($row[$colMap['sector']] ?? '')) : (! $hasMappedHeaders ? trim((string) ($row['H'] ?? '')) : '');
            $matchedSectorIds = [];
            if ($rawSector !== '') {
                $sectorParts = array_map('trim', explode(',', $rawSector));
                foreach ($sectorParts as $part) {
                    if ($part === '') {
                        continue;
                    }
                    $normPart = strtolower($part);
                    if (isset($sectorLookup[$normPart])) {
                        $matchedSectorIds[] = $sectorLookup[$normPart];
                    } else {
                        $foundId = null;
                        foreach ($sectorLookup as $lookupKey => $sectorId) {
                            if (str_starts_with($normPart, $lookupKey) || str_starts_with($lookupKey, explode(' ', $normPart)[0])) {
                                $foundId = $sectorId;
                                break;
                            }
                        }

                        if ($foundId) {
                            $matchedSectorIds[] = $foundId;
                        } else {
                            // Auto-create new sector if it does not exist in database yet
                            $baseId = Str::slug($part);
                            if ($baseId === '') {
                                $baseId = 'sektor-'.time();
                            }
                            $sectorId = $baseId;
                            $counter = 1;
                            while (Sector::where('id', $sectorId)->exists()) {
                                $sectorId = $baseId.'-'.$counter++;
                            }

                            Sector::create([
                                'id' => $sectorId,
                                'name' => $part,
                                'description' => null,
                                'image' => null,
                            ]);

                            $matchedSectorIds[] = $sectorId;
                            $sectorLookup[$normPart] = $sectorId;
                            $sectorLookup[strtolower($sectorId)] = $sectorId;
                            $sectorLookup[strtolower($part)] = $sectorId;
                        }
                    }
                }
            }
            $sectorCsv = ! empty($matchedSectorIds) ? implode(',', array_unique($matchedSectorIds)) : null;

            // Spesifikasi Baru: Kemasan, Fungsi, Metode Referensi
            $packaging = isset($colMap['packaging']) ? trim((string) ($row[$colMap['packaging']] ?? '')) : (! $hasMappedHeaders && isset($row['I']) && ! preg_match('/^(\/|https?:\/\/|storage)/i', trim((string) $row['I'])) ? trim((string) $row['I']) : '');
            $function = isset($colMap['function']) ? trim((string) ($row[$colMap['function']] ?? '')) : (! $hasMappedHeaders && isset($row['J']) && ! preg_match('/^(\/|https?:\/\/|storage)/i', trim((string) $row['J'])) ? trim((string) $row['J']) : '');
            $refMethod = isset($colMap['reference_method']) ? trim((string) ($row[$colMap['reference_method']] ?? '')) : (! $hasMappedHeaders && isset($row['K']) && ! str_contains(trim((string) $row['K']), '<') && strlen(trim((string) $row['K'])) < 100 ? trim((string) $row['K']) : '');

            // URL Cover & PDF Datasheet
            $imageUrl = isset($colMap['image']) ? trim((string) ($row[$colMap['image']] ?? '')) : (! $hasMappedHeaders ? (isset($row['L']) ? trim((string) $row['L']) : (isset($row['I']) && preg_match('/^(\/|https?:\/\/|storage)/i', trim((string) $row['I'])) ? trim((string) $row['I']) : '')) : '');
            $datasheetUrl = isset($colMap['datasheet']) ? trim((string) ($row[$colMap['datasheet']] ?? '')) : (! $hasMappedHeaders ? (isset($row['M']) ? trim((string) $row['M']) : (isset($row['J']) && preg_match('/^(\/|https?:\/\/|storage)/i', trim((string) $row['J'])) ? trim((string) $row['J']) : '')) : '');
            $description = isset($colMap['description']) ? trim((string) ($row[$colMap['description']] ?? '')) : (! $hasMappedHeaders ? (isset($row['N']) ? trim((string) $row['N']) : (isset($row['K']) ? trim((string) $row['K']) : '')) : '');

            if (str_starts_with($imageUrl, 'storage/')) {
                $imageUrl = '/'.$imageUrl;
            }
            if (str_starts_with($datasheetUrl, 'storage/')) {
                $datasheetUrl = '/'.$datasheetUrl;
            }

            $isUrlOrPath = fn (string $val) => $val !== '' && (preg_match('/^(\/|https?:\/\/)/i', $val) === 1);

            $productsToStore[] = [
                'catalog' => Str::limit($catalog, 255, ''),
                'title' => Str::limit($title, 255, ''),
                'category' => $catKey,
                'sub_category' => $subCategoryKey ?: (Str::limit($subCategory, 255, '') ?: null),
                'packaging' => Str::limit($packaging, 255, '') ?: null,
                'function' => ! empty($function) ? HtmlSanitizer::clean($function) : null,
                'reference_method' => Str::limit($refMethod, 500, '') ?: null,
                'sector' => $sectorCsv,
                'principal_id' => $principalId,
                'datasheet_url' => $isUrlOrPath($datasheetUrl) ? $datasheetUrl : null,
                'description' => HtmlSanitizer::clean($description),
                'image' => $isUrlOrPath($imageUrl) ? $imageUrl : '/images/placeholder.svg',
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

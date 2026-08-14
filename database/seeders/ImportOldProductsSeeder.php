<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportOldProductsSeeder extends Seeder
{
    public function run(): void
    {
        $sqlPath = base_path('prolabio_web.sql');
        if (! file_exists($sqlPath)) {
            $this->command->error('File prolabio_web.sql tidak ditemukan!');

            return;
        }

        $sqlContent = file_get_contents($sqlPath);

        // Fix encoding issues from Windows-1252 / latin1 to UTF-8
        $cleanUtf8 = function (string $text): string {
            // Replace common Windows-1252 misencoded sequences in UTF-8
            $map = [
                "\xC3\xA2\xE2\x82\xAC\xE2\x80\x9C" => '–', // en-dash
                "\xC3\xA2\xE2\x82\xAC\xE2\x80\x9D" => '—', // em-dash
                "\xC3\xA2\xE2\x82\xAC\xC5\x93" => '"', // left double quote
                "\xC3\xA2\xE2\x82\xAC\x9D" => '"', // right double quote
                "\xC3\xA2\xE2\x82\xAC\xE2\x84\xA2" => "'", // apostrophe
                'â€“' => '–',
                'â€”' => '—',
                'â€œ' => '"',
                'â€' => '"',
                'â€™' => "'",
                'â„ƒ' => '°C',
                'â‰' => '≥',
                'ï½ž' => '~',
                'ï¼ˆ' => '(',
                'ï¼‰' => ')',
                'â' => '',
            ];
            $text = strtr($text, $map);

            // Strip old email footer mentions
            $text = preg_replace("/<p[^>]*>.*?For inquiry please kindly email to\s*:.*?<\/p>/is", '', $text);
            $text = preg_replace("/For inquiry please kindly email to\s*:\s*[a-zA-Z0-9\._%+-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}/i", '', $text);

            return trim($text);
        };

        // 1. Build web_channel category lookup map
        $channels = [];
        if (preg_match_all('/INSERT INTO `web_channel`[^;]+;/s', $sqlContent, $channelBlocks)) {
            foreach ($channelBlocks[0] as $stmt) {
                if (preg_match_all("/\((\d+),\s*(\d+),\s*'([^']*)',\s*'([^']*)',\s*'([^']*)',\s*(\d+),\s*(\d+),\s*'([^']*)'\)/", $stmt, $rows, PREG_SET_ORDER)) {
                    foreach ($rows as $r) {
                        $channels[(int) $r[1]] = [
                            'id' => (int) $r[1],
                            'parent_id' => (int) $r[2],
                            'menu' => trim($r[3]),
                            'type' => trim($r[8]),
                        ];
                    }
                }
            }
        }

        $resolveCategory = function (int $channelId) use ($channels) {
            if (! isset($channels[$channelId])) {
                return ['Microbiology', 'General'];
            }

            $current = $channels[$channelId];
            $name = $current['menu'];

            if ($current['parent_id'] == 0 || $current['parent_id'] == 12) {
                return [$name, 'General'];
            }

            $parent = $channels[$current['parent_id']] ?? null;
            if ($parent) {
                $parentName = $parent['menu'];
                if ($parentName === 'Microbiology') {
                    return ['Microbiology', $name];
                }

                return [$parentName, $name];
            }

            return [$name, 'General'];
        };

        // 2. Extract ALL crm_product blocks
        preg_match_all("/INSERT INTO `crm_product`[^\n]+\n(.*?;\n)/s", $sqlContent, $productBlocks);

        if (empty($productBlocks[1])) {
            $this->command->error('Tabel crm_product tidak ditemukan dalam SQL dump!');

            return;
        }

        $importedCount = 0;

        foreach ($productBlocks[1] as $rawData) {
            $tuples = preg_split("/\),\s*[\r\n]+\s*\(/s", trim($rawData));

            foreach ($tuples as $tuple) {
                $tuple = preg_replace("/^\s*\(/", '', $tuple);
                $tuple = preg_replace("/\);\s*$/", '', $tuple);
                $tuple = preg_replace("/\)\s*$/", '', $tuple);

                // Tokenizer for SQL tuple values
                $tokens = [];
                $len = strlen($tuple);
                $i = 0;
                while ($i < $len) {
                    while ($i < $len && (ctype_space($tuple[$i]) || $tuple[$i] === ',')) {
                        $i++;
                    }
                    if ($i >= $len) {
                        break;
                    }

                    if ($tuple[$i] === "'") {
                        $i++;
                        $str = '';
                        while ($i < $len) {
                            if ($tuple[$i] === '\\') {
                                $nextChar = $tuple[$i + 1] ?? '';
                                if ($nextChar === "'") {
                                    $str .= "'";
                                    $i += 2;
                                } elseif ($nextChar === '\\') {
                                    $str .= '\\';
                                    $i += 2;
                                } elseif ($nextChar === 'r') {
                                    $str .= "\r";
                                    $i += 2;
                                } elseif ($nextChar === 'n') {
                                    $str .= "\n";
                                    $i += 2;
                                } elseif ($nextChar === 't') {
                                    $str .= "\t";
                                    $i += 2;
                                } else {
                                    $str .= $nextChar;
                                    $i += 2;
                                }
                            } elseif ($tuple[$i] === "'") {
                                if (($i + 1 < $len) && $tuple[$i + 1] === "'") {
                                    $str .= "'";
                                    $i += 2;
                                } else {
                                    $i++;
                                    break;
                                }
                            } else {
                                $str .= $tuple[$i];
                                $i++;
                            }
                        }
                        $tokens[] = $str;
                    } else {
                        $val = '';
                        while ($i < $len && $tuple[$i] !== ',' && ! ctype_space($tuple[$i])) {
                            $val .= $tuple[$i];
                            $i++;
                        }
                        $tokens[] = $val;
                    }
                }

                if (count($tokens) < 14) {
                    continue;
                }

                $productId = (int) ($tokens[0] ?? 0);
                $channelId = (int) ($tokens[2] ?? 0);
                $rawSector = trim($tokens[4] ?? '');
                $title = $cleanUtf8(trim($tokens[5] ?? ''));
                $description = $cleanUtf8(trim($tokens[8] ?? ''));
                $imageName = trim($tokens[10] ?? '');
                $price = (float) ($tokens[11] ?? 0);
                $stock = (int) ($tokens[12] ?? 0);

                if (empty($title)) {
                    continue;
                }

                [$category, $subCategory] = $resolveCategory($channelId);

                $catalog = null;
                if (preg_match("/\((?:Ref\.\s*)?([A-Z0-9\-\.]+)\)/i", $title, $catMatch)) {
                    $catalog = $catMatch[1];
                }

                $sectorList = [];
                if (! empty($rawSector)) {
                    $parts = array_filter(explode('|', $rawSector));
                    foreach ($parts as $p) {
                        $p = trim($p);
                        if ($p !== '') {
                            $sectorList[] = Str::slug($p);
                        }
                    }
                }
                $sectorStr = implode(',', $sectorList);

                $imagePath = ! empty($imageName) ? 'products/'.$imageName : 'images/placeholder-product.svg';

                $product = Product::updateOrCreate(
                    ['title' => $title],
                    [
                        'catalog' => $catalog,
                        'description' => $description,
                        'category' => $category,
                        'sub_category' => $subCategory,
                        'sector' => $sectorStr,
                        'image' => $imagePath,
                        'price' => $price,
                        'stock' => $stock,
                    ]
                );

                foreach ($sectorList as $secId) {
                    DB::table('product_sector')->updateOrInsert([
                        'product_id' => $product->id,
                        'sector_id' => $secId,
                    ], [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $importedCount++;
            }
        }

        $this->command->info("Berhasil mengimpor {$importedCount} produk dari prolabio_web.sql!");
        Product::clearCategoriesCache();
    }
}

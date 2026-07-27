<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DataService
{
    // JSON files kept as fallback seed sources only (no longer primary storage)
    protected string $productsFile = 'data/products.json';
    protected string $postsFile    = 'data/posts.json';
    protected string $sectorsFile  = 'data/sectors.json';
    protected string $homepageFile = 'data/homepage.json';

    // ----------------------------------------------------
    // JSON Helper (seed / backup only)
    // ----------------------------------------------------
    protected function getJsonData(string $file, array $default = []): array
    {
        if (Storage::disk('local')->exists($file)) {
            $content = Storage::disk('local')->get($file);
            return json_decode($content, true) ?: $default;
        }
        return $default;
    }

    /**
     * Get the category and subcategory hierarchy structure.
     */
    public function getCategoriesStructure(): array
    {
        $fallback = [
            'microbiology' => [
                'name' => 'Microbiology',
                'subs' => [
                    'food-safety' => 'Food Safety',
                    'antimicrobial' => 'Antimicrobial Susceptibility Testing',
                    'identification' => 'Microbiological Identification',
                    'preservation' => 'Microorganisms Preservation System (BactoBank)',
                    'staining' => 'Microbial Staining & Fixatives',
                    'consumables' => 'Consumables',
                    'mic-test' => 'MIC Test Strip',
                    'qc-organisms' => 'QC Organisms',
                    'dip-slide' => 'Dip slide',
                    'chemical-indicator' => 'Chemical Indicator',
                    'latex-agglutination' => 'Latex Agglutination Kits',
                    'ready-culture' => 'Ready To Use Culture Media',
                    'biological-indicators' => 'Biological Indicators',
                    'dehydrated-culture' => 'Dehydrated Culture Media',
                    'immunology' => 'Immunology',
                    'endotoxin' => 'Endotoxin'
                ]
            ],
            'reference-standards' => [
                'name' => 'Reference Standards',
                'subs' => [
                    'pharmaceutical' => 'Pharmaceutical Reference Standards',
                    'green-standards' => 'Green Standards',
                    'environmental' => 'Environmental Standards',
                    'food-beverages' => 'Food and Beverages Standards',
                    'agro-chemical' => 'Agro Chemical Standards'
                ]
            ],
            'device' => [
                'name' => 'Device',
                'subs' => [
                    'bsc-lfc' => 'Bio Safety Cabinet (BSC) and Laminar Flow Cabinet (LFC)',
                    'microbiological-instruments' => 'Microbiological Instruments',
                    'liquid-handling' => 'Liquid Handling',
                    'thermometer' => 'Thermometer'
                ]
            ],
            'instruments' => [
                'name' => 'Instruments',
                'subs' => [
                    'liofilchem-giotto-2' => 'Liofilchem® Giotto 2',
                    'agar-filler' => 'Agar Filler',
                    'agar-preparator' => 'Agar Preparator',
                    'kinetic-incubating-reader' => 'Kinetic Incubating Microplate Reader',
                    'mica-diamidex' => 'MICA® Diamidex - Counting Microorganisms Faster'
                ]
            ]
        ];

        return Cache::remember('categories_structure', 600, function () use ($fallback) {
            try {
                $dbItems = DB::table('products')
                    ->select('category', 'sub_category')
                    ->whereNotNull('category')
                    ->distinct()
                    ->get();

                if ($dbItems->isEmpty()) {
                    return $fallback;
                }

                $structure = [];
                foreach ($dbItems as $item) {
                    $catSlug = Str::slug((string) $item->category);
                    $subSlug = !empty($item->sub_category) ? Str::slug((string) $item->sub_category) : null;

                    if (empty($catSlug)) {
                        continue;
                    }

                    if (!isset($structure[$catSlug])) {
                        $catName = $fallback[$catSlug]['name'] ?? ucwords(str_replace('-', ' ', $catSlug));
                        $structure[$catSlug] = [
                            'name' => $catName,
                            'subs' => []
                        ];
                    }

                    if (!empty($subSlug)) {
                        $subName = $fallback[$catSlug]['subs'][$subSlug] ?? ucwords(str_replace('-', ' ', $subSlug));
                        $structure[$catSlug]['subs'][$subSlug] = $subName;
                    }
                }

                return empty($structure) ? $fallback : $structure;
            } catch (\Exception $e) {
                Log::warning('Gagal memuat kategori dinamis, menggunakan fallback: ' . $e->getMessage());
                return $fallback;
            }
        });
    }

    // ----------------------------------------------------
    // Products Service  (MySQL)
    // ----------------------------------------------------
    public function getProducts(?array $filters = []): array
    {
        $query = DB::table('products')->orderBy('id');

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['sub_category'])) {
            $query->where('sub_category', $filters['sub_category']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('catalog', 'like', "%{$search}%");
            });
        }

        return $query->get()
            ->map(fn($r) => (array) $r)
            ->toArray();
    }

    public function getProductByTitle(string $title): ?array
    {
        $row = DB::table('products')->where('title', $title)->first();
        return $row ? (array) $row : null;
    }

    public function saveProducts(array $products): bool
    {
        // Full replace – wrapped in transaction to prevent partial data loss
        DB::transaction(function () use ($products) {
            DB::table('products')->delete();
            foreach ($products as $p) {
                DB::table('products')->insert([
                    'catalog'      => $p['catalog']      ?? null,
                    'title'        => $p['title'],
                    'description'  => $p['description']  ?? null,
                    'category'     => $p['category'],
                    'sub_category' => $p['sub_category'] ?? null,
                    'sector'       => $p['sector']       ?? null,
                    'image'        => $p['image']        ?? null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        });
        \App\Models\Product::clearCategoriesCache();
        return true;
    }

    public function addProduct(array $product): bool
    {
        DB::table('products')->insert([
            'catalog'      => $product['catalog']      ?? null,
            'title'        => $product['title'],
            'description'  => self::sanitizeHtml($product['description'] ?? null),
            'category'     => $product['category'],
            'sub_category' => $product['sub_category'] ?? null,
            'sector'       => $product['sector']       ?? null,
            'image'        => $product['image']        ?? null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
        \App\Models\Product::clearCategoriesCache();
        return true;
    }

    public function updateProduct(string $oldTitle, array $updatedProduct): bool
    {
        DB::table('products')->where('title', $oldTitle)->update([
            'catalog'      => $updatedProduct['catalog']      ?? null,
            'title'        => $updatedProduct['title'],
            'description'  => self::sanitizeHtml($updatedProduct['description'] ?? null),
            'category'     => $updatedProduct['category'],
            'sub_category' => $updatedProduct['sub_category'] ?? null,
            'sector'       => $updatedProduct['sector']       ?? null,
            'image'        => $updatedProduct['image']        ?? null,
            'updated_at'   => now(),
        ]);
        \App\Models\Product::clearCategoriesCache();
        return true;
    }

    public function deleteProduct(string $title): bool
    {
        DB::table('products')->where('title', $title)->delete();
        \App\Models\Product::clearCategoriesCache();
        return true;
    }

    /**
     * Bulk upsert products in a single database transaction and clear cache once.
     */
    public function upsertProducts(array $products): bool
    {
        if (empty($products)) {
            return false;
        }

        DB::transaction(function () use ($products) {
            foreach ($products as $p) {
                $existing = DB::table('products')->where('title', $p['title'])->first();
                if ($existing) {
                    DB::table('products')->where('title', $p['title'])->update([
                        'catalog'      => $p['catalog']      ?? null,
                        'description'  => $p['description']  ?? null,
                        'category'     => $p['category'],
                        'sub_category' => $p['sub_category'] ?? null,
                        'sector'       => $p['sector']       ?? null,
                        'image'        => $p['image']        ?? null,
                        'updated_at'   => now(),
                    ]);
                } else {
                    DB::table('products')->insert([
                        'catalog'      => $p['catalog']      ?? null,
                        'title'        => $p['title'],
                        'description'  => $p['description']  ?? null,
                        'category'     => $p['category'],
                        'sub_category' => $p['sub_category'] ?? null,
                        'sector'       => $p['sector']       ?? null,
                        'image'        => $p['image']        ?? null,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
            }
        });

        \App\Models\Product::clearCategoriesCache();
        return true;
    }

    // ----------------------------------------------------
    // Posts / Articles Service  (MySQL)
    // ----------------------------------------------------
    public function getPosts(): array
    {
        return DB::table('posts')
            ->orderByDesc('id')
            ->get()
            ->map(fn($r) => (array) $r)
            ->toArray();
    }

    public function getPostBySlug(string $slug): ?array
    {
        $row = DB::table('posts')->where('slug', $slug)->first();
        return $row ? (array) $row : null;
    }

    public function savePosts(array $posts): bool
    {
        DB::transaction(function () use ($posts) {
            DB::table('posts')->delete();
            foreach ($posts as $post) {
                DB::table('posts')->insert([
                    'slug'       => $post['slug'],
                    'title'      => $post['title'],
                    'date'       => $post['date'],
                    'category'   => $post['category'],
                    'image'      => $post['image']   ?? null,
                    'content'    => $post['content'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
        return true;
    }

    public function addPost(array $post): bool
    {
        DB::table('posts')->insert([
            'slug'       => $post['slug'],
            'title'      => $post['title'],
            'date'       => $post['date'],
            'category'   => $post['category'],
            'image'      => $post['image']   ?? null,
            'content'    => self::sanitizeHtml($post['content'] ?? null),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return true;
    }

    public function updatePost(string $slug, array $updatedPost): bool
    {
        DB::table('posts')->where('slug', $slug)->update([
            'slug'       => $updatedPost['slug'],
            'title'      => $updatedPost['title'],
            'date'       => $updatedPost['date'],
            'category'   => $updatedPost['category'],
            'image'      => $updatedPost['image']   ?? null,
            'content'    => self::sanitizeHtml($updatedPost['content'] ?? null),
            'updated_at' => now(),
        ]);
        return true;
    }

    public function deletePost(string $slug): bool
    {
        DB::table('posts')->where('slug', $slug)->delete();
        return true;
    }

    // ----------------------------------------------------
    // Sectors Service  (MySQL)
    // ----------------------------------------------------
    public function getSectors(): array
    {
        return DB::table('sectors')
            ->orderBy('name')
            ->get()
            ->map(function ($r) {
                $row = (array) $r;
                // description stored as JSON string in DB, decode back to array
                $row['description'] = is_string($row['description'])
                    ? (json_decode($row['description'], true) ?? [])
                    : ($row['description'] ?? []);
                return $row;
            })
            ->toArray();
    }

    public function getSectorById(string $id): ?array
    {
        $row = DB::table('sectors')->where('id', $id)->first();
        if (!$row) return null;
        $row = (array) $row;
        $row['description'] = is_string($row['description'])
            ? (json_decode($row['description'], true) ?? [])
            : ($row['description'] ?? []);
        return $row;
    }

    public function saveSectors(array $sectors): bool
    {
        DB::transaction(function () use ($sectors) {
            DB::table('sectors')->delete();
            foreach ($sectors as $sec) {
                DB::table('sectors')->insert([
                    'id'          => $sec['id'],
                    'name'        => $sec['name'],
                    'description' => json_encode($sec['description'] ?? []),
                    'image'       => $sec['image'] ?? null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        });
        return true;
    }

    public function addSector(array $sector): bool
    {
        DB::table('sectors')->insert([
            'id'          => $sector['id'],
            'name'        => $sector['name'],
            'description' => json_encode($sector['description'] ?? []),
            'image'       => $sector['image'] ?? null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        return true;
    }

    public function updateSector(string $id, array $updatedSector): bool
    {
        DB::table('sectors')->where('id', $id)->update([
            'name'        => $updatedSector['name'],
            'description' => json_encode($updatedSector['description'] ?? []),
            'image'       => $updatedSector['image'] ?? null,
            'updated_at'  => now(),
        ]);
        return true;
    }

    public function deleteSector(string $id): bool
    {
        DB::table('sectors')->where('id', $id)->delete();
        return true;
    }

    // ----------------------------------------------------
    // Homepage Config Service  (MySQL — key/value)
    // ----------------------------------------------------
    public function getHomepageData(): array
    {
        $default = $this->getDefaultHomepageData();

        try {
            $rows = DB::table('homepage_settings')->pluck('value', 'key')->toArray();
        } catch (\Exception $e) {
            // Table not yet created (first boot before migrate) – fall back to defaults
            return $default;
        }

        if (empty($rows)) {
            return $default;
        }

        // Decode JSON values back to arrays where needed
        $decoded = [];
        foreach ($rows as $key => $val) {
            $decoded[$key] = $this->decodeSettingValue($val);
        }

        return array_merge($default, $decoded);
    }

    public function saveHomepageData(array $data): bool
    {
        foreach ($data as $key => $val) {
            $encoded = is_array($val) ? json_encode($val) : $val;
            DB::table('homepage_settings')->upsert(
                [
                    'key'        => $key,
                    'value'      => $encoded,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
                ['key'],
                ['value', 'updated_at']
            );
        }
        return true;
    }

    // ----------------------------------------------------
    // Private Helpers
    // ----------------------------------------------------
    private function decodeSettingValue(mixed $val): mixed
    {
        if (!is_string($val)) return $val;
        $decoded = json_decode($val, true);
        // Return decoded array only if it actually IS an array/object; otherwise keep raw string
        return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : $val;
    }

    public function getDefaultHomepageData(): array
    {
        return [
            'hero_title'       => 'Solusi Analitika & Mikrobiologi Terpercaya',
            'hero_subtitle'    => 'Kami menyediakan media kultur, instrumen lab, dan perlengkapan pengujian dengan kualitas terbaik untuk mendukung berbagai kebutuhan industri di Indonesia.',
            'hero_images'      => [
                'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1581093458791-9f3c3900df4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ],
            'focus_title'      => 'Fokus Industri Kami',
            'focus_cards'      => [
                [
                    'title'       => 'Farmasi & Klinis',
                    'description' => 'Menyediakan perangkat uji endotoksin dan instrumen kinetik untuk kebutuhan sterilisasi dan pengecekan klinis.',
                    'image'       => 'https://images.unsplash.com/photo-1587854692152-cbe660dbde88?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                ],
                [
                    'title'       => 'Food & Beverage',
                    'description' => 'HACCP System Plus untuk total bacterial count dan identifikasi kuman patogen langsung dari permukaan kerja.',
                    'image'       => 'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                ],
                [
                    'title'       => 'Mikrobiologi Umum',
                    'description' => 'Menyediakan MICA® Diamidex dan media kultur selektif untuk isolasi mikroorganisme yang presisi.',
                    'image'       => 'https://images.unsplash.com/photo-1576086213369-97a306d36557?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
                ],
            ],
            'about_title'       => 'Tentang Prolabios',
            'about_description' => 'Prolabios Mitra Analitika (PMA) dibangun untuk menjadi salah satu distributor terkemuka di Indonesia dengan semangat memenuhi kebutuhan produk atau layanan serta peningkatan keterampilan bagi pengguna laboratorium.',
            'hotline_label'     => 'Layanan Pelanggan 24/7',
            'hotline_number'    => '0821-8792-9433',
            'hotline_description' => 'Hubungi kami via telepon atau WhatsApp untuk konsultasi produk dan layanan perbaikan alat laboratorium Anda.',

            // Site-wide contact info
            'contact_phone'            => '0821-8792-9433',
            'contact_phone_marketing'  => '021-3874-1447',
            'contact_phone_finance'    => '021-8792-9433',
            'contact_phone_technician' => '0812-837-4867',
            'contact_email'            => 'marketing@prolabios.com',
            'contact_address'          => 'Ruko Plaza de Lumina Blok B No. 27, Semanan, Kalideres, Jakarta Barat, DKI Jakarta 11850',
            'catalog_pdf_url'          => 'https://drive.google.com/open?id=1ijNKezGnKAa8JlQs2L8NFJjeHDjfd3YC&usp=drive_fs',

            // General & Social Media settings
            'company_name'             => 'PT. Prolabios Mitra Analitika',
            'site_logo'                => '',
            'operational_hours'        => 'Senin - Jumat: 08.00 - 17.00',
            'social_instagram'         => 'https://instagram.com/prolabios',
            'social_facebook'          => 'https://facebook.com/prolabios',
            'social_linkedin'          => 'https://linkedin.com/company/prolabios',
            'social_twitter'           => 'https://twitter.com/prolabios',

            // Page: Products
            'products_title'        => 'Semua Produk',
            'products_subtitle'     => 'Menampilkan semua produk kami',
            'products_banner_image' => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            // Page: Sectors
            'sectors_title'        => 'Sektor Industri',
            'sectors_subtitle'     => 'Kami melayani berbagai macam sektor industri di Indonesia',
            'sectors_banner_image' => 'https://images.unsplash.com/photo-1574585141047-92e105e4d9eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            // Page: Services
            'services_title'        => 'Layanan Kami',
            'services_subtitle'     => 'Dukungan purnajual dan layanan konsultasi terpadu',
            'services_banner_image' => 'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            // Page: Information
            'info_title'        => 'News & Activity',
            'info_subtitle'     => 'Ikuti berita terkini, tips laboratorium, dan artikel ilmiah',
            'info_banner_image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            // Page: Contact
            'contact_title'        => 'Hubungi Kami',
            'contact_subtitle'     => 'Ada pertanyaan atau butuh konsultasi? Tim kami siap melayani Anda.',
            'contact_banner_image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        ];
    }

    /**
     * Safely sanitize HTML to allow specific layout tags and strip unsafe attributes/scripts.
     */
    public static function sanitizeHtml(?string $html): string
    {
        if (empty($html)) {
            return '';
        }
        
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        // Wrap the snippet inside <div> to prevent DOMDocument from adding automatic wrapper tags
        $dom->loadHTML('<?xml encoding="utf-8" ?><div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        
        $allowedTags = ['p', 'b', 'i', 'strong', 'em', 'a', 'ul', 'ol', 'li', 'br', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'img', 'span', 'div'];
        $allowedAttributes = [
            'a' => ['href', 'title', 'class', 'target'],
            'img' => ['src', 'alt', 'class', 'style', 'width', 'height'],
            'span' => ['class', 'style'],
            'div' => ['class', 'style'],
            'p' => ['class', 'style'],
        ];

        $sanitizeNode = function (\DOMNode $node) use (&$sanitizeNode, $allowedTags, $allowedAttributes) {
            if ($node instanceof \DOMElement) {
                $tagName = strtolower($node->nodeName);
                if (!in_array($tagName, $allowedTags, true)) {
                    while ($node->hasChildNodes()) {
                        $node->parentNode->insertBefore($node->firstChild, $node);
                    }
                    $node->parentNode->removeChild($node);
                    return;
                }
                
                if ($node->hasAttributes()) {
                    $attrsToRemove = [];
                    foreach ($node->attributes as $attr) {
                        $attrName = strtolower($attr->name);
                        $allowedAttrsForTag = $allowedAttributes[$tagName] ?? [];
                        if (!in_array($attrName, $allowedAttrsForTag, true)) {
                            $attrsToRemove[] = $attr->name;
                            continue;
                        }
                        
                        if ($attrName === 'href' || $attrName === 'src') {
                            $val = trim($attr->value);
                            if (preg_match('/^(javascript|data|vbscript):/i', $val)) {
                                $attrsToRemove[] = $attr->name;
                            }
                        }
                    }
                    foreach ($attrsToRemove as $attrName) {
                        $node->removeAttribute($attrName);
                    }
                }
            }
            
            if ($node->hasChildNodes()) {
                for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
                    $child = $node->childNodes->item($i);
                    if ($child) {
                        $sanitizeNode($child);
                    }
                }
            }
        };
        
        $wrapper = $dom->getElementsByTagName('div')->item(0);
        if ($wrapper) {
            for ($i = $wrapper->childNodes->length - 1; $i >= 0; $i--) {
                $node = $wrapper->childNodes->item($i);
                if ($node) {
                    $sanitizeNode($node);
                }
            }
        }
        
        $outputHtml = '';
        if ($wrapper) {
            foreach ($wrapper->childNodes as $child) {
                $outputHtml .= $dom->saveHTML($child);
            }
        } else {
            $outputHtml = $dom->saveHTML();
        }
        
        return trim($outputHtml);
    }
}

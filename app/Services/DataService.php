<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DataService
{
    /**
     * Get the current products cache version integer.
     */
    public static function getProductsCacheVersion(): int
    {
        return (int) Cache::get('products_cache_version', 1);
    }

    /**
     * Clear all product-related cache entries including individual product lookups.
     * Increments the cache version to instantly invalidate getProductById and getProductByTitle keys.
     */
    protected function clearProductsCache(): void
    {
        Cache::forget('categories_structure');
        Cache::forget('products_list_global');
        Cache::forget('search_suggestions_v2');
        Cache::forget('search_suggestions');

        try {
            Cache::increment('products_cache_version');
        } catch (\Throwable $e) {
            Cache::put('products_cache_version', time());
        }
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
                    'food-safety'               => 'Food Safety',
                    'antimicrobial'             => 'Antimicrobial Susceptibility Testing',
                    'identification'            => 'Microbiological Identification',
                    'preservation'              => 'Microorganisms Preservation System (BactoBank)',
                    'staining'                  => 'Microbial Staining & Fixatives',
                    'consumables'               => 'Consumables',
                    'mic-test'                  => 'MIC Test Strip',
                    'qc-organisms'              => 'QC Organisms',
                    'dip-slide'                 => 'Dip slide',
                    'chemical-indicator'        => 'Chemical Indicator',
                    'latex-agglutination'       => 'Latex Agglutination Kits',
                    'ready-to-use-culture-media' => 'Ready To Use Culture Media',
                    'biological-indicators'     => 'Biological Indicators',
                    'dehydrated-culture-media'  => 'Dehydrated Culture Media',
                    'immunology'                => 'Immunology',
                    'endotoxin'                 => 'Endotoxin',
                ],
            ],
            'reference-standards' => [
                'name' => 'Reference Standards',
                'subs' => [
                    'pharmaceutical'  => 'Pharmaceutical Reference Standards',
                    'green-standards' => 'Green Standards',
                    'environmental'   => 'Environmental Standards',
                    'food-beverages'  => 'Food and Beverages Standards',
                    'agro-chemical'   => 'Agro Chemical Standards',
                ],
            ],
            'device' => [
                'name' => 'Device',
                'subs' => [
                    'bsc-lfc'                    => 'Bio Safety Cabinet (BSC) and Laminar Flow Cabinet (LFC)',
                    'microbiological-instruments' => 'Microbiological Instruments',
                    'liquid-handling'            => 'Liquid Handling',
                    'thermometer'                => 'Thermometer',
                ],
            ],
            'instruments' => [
                'name' => 'Instruments',
                'subs' => [
                    'liofilchem-giotto-2'       => 'Liofilchem® Giotto 2',
                    'agar-filler'               => 'Agar Filler',
                    'agar-preparator'           => 'Agar Preparator',
                    'kinetic-incubating-reader' => 'Kinetic Incubating Microplate Reader',
                    'mica-diamidex'             => 'MICA® Diamidex - Counting Microorganisms Faster',
                ],
            ],
        ];

        return Cache::remember('categories_structure', 600, function () use ($fallback) {
            try {
                // ── Read from product_categories table (dynamic) ──────────────
                $tableExists = DB::getSchemaBuilder()->hasTable('product_categories');

                if (! $tableExists) {
                    return $fallback;
                }

                $parents = DB::table('product_categories')
                    ->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();

                if ($parents->isEmpty()) {
                    return $fallback;
                }

                $allChildren = DB::table('product_categories')
                    ->whereNotNull('parent_id')
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get()
                    ->groupBy('parent_id');

                $structure = [];
                foreach ($parents as $parent) {
                    $subs = [];
                    foreach ($allChildren->get($parent->id, collect()) as $child) {
                        $subs[$child->key] = $child->name;
                    }
                    $structure[$parent->key] = [
                        'name' => $parent->name,
                        'subs' => $subs,
                    ];
                }

                return empty($structure) ? $fallback : $structure;

            } catch (\Exception $e) {
                Log::warning('Gagal memuat kategori dari product_categories, menggunakan fallback: ' . $e->getMessage());

                return $fallback;
            }
        });
    }


    // ----------------------------------------------------
    // Products Service  (MySQL)
    // ----------------------------------------------------
    public function getProducts(?array $filters = [], int $limit = 0): Collection
    {
        $cacheKey = 'products_list_'.md5(json_encode($filters).'_'.$limit);

        $cached = Cache::get($cacheKey);
        if ($cached instanceof \__PHP_Incomplete_Class || ($cached !== null && ! ($cached instanceof Collection))) {
            Cache::forget($cacheKey);
            $cached = null;
        }

        if ($cached instanceof Collection) {
            return $cached;
        }

        $query = Product::query()->orderBy('id');

        if (! empty($filters['category'])) {
            $cat = $filters['category'];
            $catSlug = Str::slug($cat);
            if ($catSlug === 'culture-media') {
                $query->where(function ($q) use ($cat) {
                    $q->where('category', $cat)
                        ->orWhere('category', 'LIKE', '%Culture Media%')
                        ->orWhere('sub_category', 'LIKE', '%Culture Media%');
                });
            } else {
                $query->where('category', $cat);
            }
        }

        if (! empty($filters['sub_category'])) {
            $subCat = $filters['sub_category'];
            $query->where(function ($q) use ($subCat) {
                $q->where('sub_category', $subCat)
                    ->orWhere('sub_category', 'LIKE', "%{$subCat}%");
            });
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('catalog', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['sector'])) {
            $sector = $filters['sector'];
            $query->whereRaw('FIND_IN_SET(?, sector) > 0', [$sector]);
        }

        if ($limit > 0) {
            $query->limit($limit);
        }


        $result = $query->get();

        Cache::put($cacheKey, $result, 300);

        return $result;
    }

    public function getPaginatedProducts(?array $filters = [], int $perPage = 12)
    {
        $page = (int) request()->input('page', 1);
        $cacheKey = 'products_paginated_'.md5(json_encode($filters).'_'.$perPage.'_p'.$page);

        $cached = Cache::get($cacheKey);
        if ($cached instanceof \__PHP_Incomplete_Class) {
            Cache::forget($cacheKey);
            $cached = null;
        }

        if ($cached) {
            return $cached;
        }

        $query = Product::query()->orderBy('id');

        if (! empty($filters['category'])) {
            $cat = $filters['category'];
            $catSlug = Str::slug($cat);
            if ($catSlug === 'culture-media') {
                $query->where(function ($q) use ($cat) {
                    $q->where('category', $cat)
                        ->orWhere('category', 'LIKE', '%Culture Media%')
                        ->orWhere('sub_category', 'LIKE', '%Culture Media%');
                });
            } else {
                $query->where('category', $cat);
            }
        }

        if (! empty($filters['sub_category'])) {
            $subCat = $filters['sub_category'];
            $query->where(function ($q) use ($subCat) {
                $q->where('sub_category', $subCat)
                    ->orWhere('sub_category', 'LIKE', "%{$subCat}%");
            });
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('catalog', 'like', "%{$search}%");
            });
        }

        $result = $query->paginate($perPage)->withQueryString();

        Cache::put($cacheKey, $result, 300);

        return $result;
    }

    public function getProductByTitle(string $title): ?Product
    {
        $v = self::getProductsCacheVersion();
        $cacheKey = "product_by_title_{$v}_".md5($title);
        $cached = Cache::get($cacheKey);
        if ($cached instanceof \__PHP_Incomplete_Class) {
            Cache::forget($cacheKey);
            $cached = null;
        }

        if ($cached instanceof Product) {
            return $cached;
        }

        $product = Product::where('title', $title)->first();
        if ($product) {
            Cache::put($cacheKey, $product, 600);
        }

        return $product;
    }

    public function getProductById(int $id): ?Product
    {
        $v = self::getProductsCacheVersion();
        $cacheKey = "product_by_id_{$v}_".$id;
        $cached = Cache::get($cacheKey);
        if ($cached instanceof \__PHP_Incomplete_Class) {
            Cache::forget($cacheKey);
            $cached = null;
        }

        if ($cached instanceof Product) {
            return $cached;
        }

        $product = Product::find($id);
        if ($product) {
            Cache::put($cacheKey, $product, 600);
        }

        return $product;
    }

    /**
     * Safely decode the `gallery_images` JSON column into a plain array of
     * relative image paths, tolerating null/empty/malformed stored values.
     */
    protected static function decodeGalleryImages($raw): array
    {
        if (empty($raw)) {
            return [];
        }
        if (is_array($raw)) {
            return array_values(array_filter($raw));
        }
        $decoded = json_decode((string) $raw, true);

        return is_array($decoded) ? array_values(array_filter($decoded)) : [];
    }

    public function addProduct(array $product): bool
    {
        DB::table('products')->insert([
            'catalog' => $product['catalog'] ?? null,
            'title' => $product['title'],
            'description' => self::sanitizeHtml($product['description'] ?? null),
            'category' => $product['category'],
            'sub_category' => $product['sub_category'] ?? null,
            'sector' => $product['sector'] ?? null,
            'image' => $product['image'] ?? null,
            'gallery_images' => ! empty($product['gallery_images']) ? json_encode(array_values($product['gallery_images'])) : null,
            'price' => $product['price'] ?? 0,
            'stock' => $product['stock'] ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Product::clearCategoriesCache();
        $this->clearProductsCache();

        return true;
    }

    public function updateProductById(int $id, array $updatedProduct): bool
    {
        DB::table('products')->where('id', $id)->update([
            'catalog' => $updatedProduct['catalog'] ?? null,
            'title' => $updatedProduct['title'],
            'description' => self::sanitizeHtml($updatedProduct['description'] ?? null),
            'category' => $updatedProduct['category'],
            'sub_category' => $updatedProduct['sub_category'] ?? null,
            'sector' => $updatedProduct['sector'] ?? null,
            'image' => $updatedProduct['image'] ?? null,
            'gallery_images' => ! empty($updatedProduct['gallery_images']) ? json_encode(array_values($updatedProduct['gallery_images'])) : null,
            'price' => $updatedProduct['price'] ?? 0,
            'stock' => $updatedProduct['stock'] ?? 0,
            'updated_at' => now(),
        ]);
        Product::clearCategoriesCache();
        $this->clearProductsCache();

        return true;
    }

    public function updateProduct(string $oldTitle, array $updatedProduct): bool
    {
        DB::table('products')->where('title', $oldTitle)->update([
            'catalog' => $updatedProduct['catalog'] ?? null,
            'title' => $updatedProduct['title'],
            'description' => self::sanitizeHtml($updatedProduct['description'] ?? null),
            'category' => $updatedProduct['category'],
            'sub_category' => $updatedProduct['sub_category'] ?? null,
            'sector' => $updatedProduct['sector'] ?? null,
            'image' => $updatedProduct['image'] ?? null,
            'price' => $updatedProduct['price'] ?? 0,
            'stock' => $updatedProduct['stock'] ?? 0,
            'updated_at' => now(),
        ]);
        Product::clearCategoriesCache();
        $this->clearProductsCache();

        return true;
    }

    public function decrementStock(int|string $productIdOrTitle, int $quantity = 1): bool
    {
        $query = is_numeric($productIdOrTitle)
            ? DB::table('products')->where('id', $productIdOrTitle)
            : DB::table('products')->where('title', $productIdOrTitle);

        $affected = (clone $query)->where('stock', '>=', $quantity)->decrement('stock', $quantity);

        if ($affected > 0) {
            $this->clearProductsCache();
        }

        return $affected > 0;
    }

    public function deleteProductById(int $id): bool
    {
        DB::table('products')->where('id', $id)->delete();
        $this->clearProductsCache();
        Product::clearCategoriesCache();

        return true;
    }

    public function deleteProduct(string $title): bool
    {
        DB::table('products')->where('title', $title)->delete();
        $this->clearProductsCache();
        Product::clearCategoriesCache();

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

        // Sanitize descriptions first (outside transaction for performance)
        $now = now();
        $rows = [];
        foreach ($products as $p) {
            if (empty(trim($p['title'] ?? ''))) {
                continue;
            }
            $rows[] = [
                'catalog' => $p['catalog'] ?? null,
                'title' => $p['title'],
                'description' => self::sanitizeHtml($p['description'] ?? null),
                'category' => $p['category'],
                'sub_category' => $p['sub_category'] ?? null,
                'sector' => $p['sector'] ?? null,
                'image' => $p['image'] ?? null,
                'price' => $p['price'] ?? 0,
                'stock' => $p['stock'] ?? 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (empty($rows)) {
            return false;
        }

        // Single upsert call — vastly more efficient than N+1 select+insert/update pattern
        // MySQL: INSERT ... ON DUPLICATE KEY UPDATE (requires unique key on 'title')
        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('products')->upsert(
                $chunk,
                ['title'],                  // unique key to match on
                ['catalog', 'description', 'category', 'sub_category', 'sector', 'image', 'price', 'stock', 'updated_at']
            );
        }

        Product::clearCategoriesCache();
        $this->clearProductsCache();

        return true;
    }

    // ----------------------------------------------------
    // Posts / Articles Service  (MySQL)
    // ----------------------------------------------------
    public function getPosts(?array $filters = [], int $limit = 0): array
    {
        $query = DB::table('posts')->orderByDesc('id');

        // Only show published / online posts for public facing queries
        if (empty($filters['include_all_status'])) {
            $today = date('Y-m-d');
            $query->where('status', 'online')
                ->where(function ($q) use ($today) {
                    $q->whereNull('date')
                        ->orWhere('date', '<=', $today);
                });
        }

        if (! empty($filters['category'])) {
            $cat = strtolower($filters['category']);
            if ($cat === 'info') {
                $query->where(function ($q) {
                    $q->where('category', 'Info Terkait')->orWhere('category', 'Info');
                });
            } else {
                $query->whereRaw('LOWER(category) = ?', [$cat]);
            }
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get()
            ->map(fn ($r) => (array) $r)
            ->toArray();
    }

    public function getPaginatedPosts(?array $filters = [], int $perPage = 4)
    {
        $query = DB::table('posts')->orderByDesc('id');

        // Only show published / online posts for public facing queries
        if (empty($filters['include_all_status'])) {
            $today = date('Y-m-d');
            $query->where('status', 'online')
                ->where(function ($q) use ($today) {
                    $q->whereNull('date')
                        ->orWhere('date', '<=', $today);
                });
        }

        if (! empty($filters['category'])) {
            $cat = strtolower($filters['category']);
            if ($cat === 'info') {
                $query->where(function ($q) {
                    $q->where('category', 'Info Terkait')->orWhere('category', 'Info');
                });
            } else {
                $query->whereRaw('LOWER(category) = ?', [$cat]);
            }
        }

        return $query->paginate($perPage)
            ->through(fn ($r) => (array) $r)
            ->withQueryString();
    }

    public function getPostBySlug(string $slug): ?array
    {
        $row = DB::table('posts')->where('slug', $slug)->first();

        return $row ? (array) $row : null;
    }

    public function addPost(array $post): bool
    {
        DB::table('posts')->insert([
            'slug' => $post['slug'],
            'title' => $post['title'],
            'date' => $post['date'],
            'category' => $post['category'],
            'status' => $post['status'] ?? 'online',
            'is_featured' => $post['is_featured'] ?? false,
            'image' => $post['image'] ?? null,
            'content' => self::sanitizeHtml($post['content'] ?? null),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Cache::forget('blog_category_counts');

        return true;
    }

    public function updatePost(string $slug, array $updatedPost): bool
    {
        DB::table('posts')->where('slug', $slug)->update([
            'slug' => $updatedPost['slug'],
            'title' => $updatedPost['title'],
            'date' => $updatedPost['date'],
            'category' => $updatedPost['category'],
            'status' => $updatedPost['status'] ?? 'online',
            'is_featured' => $updatedPost['is_featured'] ?? false,
            'image' => $updatedPost['image'] ?? null,
            'content' => self::sanitizeHtml($updatedPost['content'] ?? null),
            'updated_at' => now(),
        ]);
        Cache::forget('blog_category_counts');

        return true;
    }

    public function deletePost(string $slug): bool
    {
        DB::table('posts')->where('slug', $slug)->delete();
        Cache::forget('blog_category_counts');

        return true;
    }

    // ----------------------------------------------------
    // Sectors Service  (MySQL)
    // ----------------------------------------------------
    public function getSectors(): array
    {
        return Cache::remember('sectors_list_v2', 3600, function () {
            return DB::table('sectors')
                ->orderBy('name')
                ->get()
                ->map(function ($r) {
                    $row = (array) $r;
                    $row['description'] = is_string($row['description'])
                        ? (json_decode($row['description'], true) ?? [])
                        : ($row['description'] ?? []);

                    return $row;
                })
                ->toArray();
        });
    }

    public function getSectorById(string $id): ?array
    {
        $row = DB::table('sectors')->where('id', $id)->first();
        if (! $row) {
            return null;
        }
        $row = (array) $row;
        $row['description'] = is_string($row['description'])
            ? (json_decode($row['description'], true) ?? [])
            : ($row['description'] ?? []);

        return $row;
    }

    public function addSector(array $sector): bool
    {
        DB::table('sectors')->insert([
            'id' => $sector['id'],
            'name' => $sector['name'],
            'description' => json_encode($sector['description'] ?? []),
            'image' => $sector['image'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Cache::forget('sectors_list_v2');

        return true;
    }

    public function updateSector(string $id, array $updatedSector): bool
    {
        DB::table('sectors')->where('id', $id)->update([
            'name' => $updatedSector['name'],
            'description' => json_encode($updatedSector['description'] ?? []),
            'image' => $updatedSector['image'] ?? null,
            'updated_at' => now(),
        ]);
        Cache::forget('sectors_list_v2');

        return true;
    }

    public function deleteSector(string $id): bool
    {
        DB::table('sectors')->where('id', $id)->delete();
        Cache::forget('sectors_list_v2');

        return true;
    }

    // ----------------------------------------------------
    // Homepage Config Service  (MySQL — key/value)
    // ----------------------------------------------------
    /**
     * Clear all website settings and homepage cache entries.
     */
    public static function clearSettingsCache(): void
    {
        Cache::forget('homepage_data_v1');
        Cache::forget('homepage_settings_v3');
        Cache::forget('site_settings_global');
    }

    public function getHomepageData(): array
    {
        $default = $this->getDefaultHomepageData();

        return Cache::remember('homepage_data_v1', 3600, function () use ($default) {
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
        });
    }

    public function saveHomepageData(array $data): bool
    {
        foreach ($data as $key => $val) {
            $encoded = is_array($val) ? json_encode($val) : $val;
            DB::table('homepage_settings')->upsert(
                [
                    'key' => $key,
                    'value' => $encoded,
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
                ['key'],
                ['value', 'updated_at']
            );
        }
        // Invalidate all cached settings data across the entire application
        self::clearSettingsCache();

        return true;
    }

    // ----------------------------------------------------
    // Private Helpers
    // ----------------------------------------------------
    private function decodeSettingValue(mixed $val): mixed
    {
        if (! is_string($val)) {
            return $val;
        }
        $decoded = json_decode($val, true);

        // Return decoded array only if it actually IS an array/object; otherwise keep raw string
        return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : $val;
    }

    public function getDefaultHomepageData(): array
    {
        return [
            // 1. Hero Section
            'hero_badge' => 'PRECISION LABORATORY SOLUTIONS',
            'hero_title' => 'Uncompromised <span class="text-accent">Testing Accuracy</span> for Research & Industry.',
            'hero_subtitle' => 'Official provider of analytical instruments, culture media, and laboratory reagents meeting strict international quality standards.',
            'hero_cta_text' => 'Explore Product Catalog',
            'hero_cta_link' => '/produk',
            'hero_images' => [
                'https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
            ],

            // 2. Bento Infrastructure Grid
            'bento_title' => 'Infrastructure & Reliability Standards',
            'bento_subtitle' => 'Engineered to fulfill strict regulatory compliance and ensure seamless laboratory testing continuity.',
            'bento_cards' => [
                [
                    'icon' => 'bi-patch-check',
                    'title' => 'ISO & AKL Certified Products',
                    'desc' => 'Over 1,000+ officially accredited reagents and instruments, guaranteeing distribution legality for BPOM and ISO 17025 audit compliance.',
                ],
                [
                    'icon' => 'bi-file-earmark-code',
                    'title' => 'Instant COA & MSDS Access',
                    'desc' => 'Every batch of reagents and culture media comes with official Certificate of Analysis (COA) and MSDS ready for lab validation download.',
                ],
                [
                    'icon' => 'bi-snow',
                    'title' => 'Safe Cold-Chain Logistics',
                    'desc' => 'Tested cold-chain infrastructure ensuring temperature-sensitive reagents remain stable and active upon arrival at your laboratory.',
                ],
                [
                    'icon' => 'bi-tools',
                    'title' => 'Integrated After-Sales & Calibration',
                    'desc' => 'Comprehensive equipment qualification (IQ/OQ/PQ), routine calibration services, and technical training by application specialists.',
                ],
            ],

            // 3. Interactive Sector Finder
            'sector_title' => 'Interactive Sector Finder',
            'sector_subtitle' => 'Select your industry sector to explore tailored testing workflows and relevant products.',
            'sector_panels' => [
                'pharma' => [
                    'tag' => 'PHARMACEUTICAL & COSMETICS',
                    'title' => 'Endotoxin Testing & Sterilization Validation',
                    'desc' => 'LAL Endotoxin Test Kits (Bioendo), SCBI Biological Indicators (Terragene), and Pharmacopoeia-grade culture media for drug & cosmetic QC compliance.',
                    'link' => '/sektor?s=pharmaceutical#sektor-nav',
                ],
                'fnb' => [
                    'tag' => 'FOOD & BEVERAGE INDUSTRY',
                    'title' => 'Rapid Pathogen Detection & Hygiene Monitoring',
                    'desc' => 'Rapid pathogen detection (Salmonella, Listeria, E. coli) and ATP hygiene indicators ensuring food safety compliance for HACCP & BPOM.',
                    'link' => '/sektor?s=food#sektor-nav',
                ],
                'healthcare' => [
                    'tag' => 'HEALTHCARE & HOSPITAL CSSD',
                    'title' => 'Diagnostics & Sterilization Indicators',
                    'desc' => 'Microbial identification, MIC antibiotic susceptibility testing, and chemical/biological indicators for hospital CSSD sterilizers.',
                    'link' => '/sektor?s=hospital-clinic#sektor-nav',
                ],
                'brewing' => [
                    'tag' => 'BREWING & RESEARCH LABS',
                    'title' => 'Spoilage Control & Fermentation Quality',
                    'desc' => 'Specific media for beer spoilage bacteria (Lactobacillus, Pediococcus) and precision liquid handling for R&D molecular biology.',
                    'link' => '/sektor?s=brewing#sektor-nav',
                ],
            ],

            // 4. Bottom Conversion CTA Banner
            'cta_banner_badge' => 'TECHNICAL PROCUREMENT SUPPORT',
            'cta_banner_title' => 'Require Custom Procurement or Project Quote?',
            'cta_banner_sub' => 'Our application specialists and technical sales team assist with instrument specifications, bulk availability, and compliance documentation.',
            'cta_banner_btn_text' => 'Contact Sales / Request Quote',
            'cta_banner_btn_url' => '/kontak',

            // Legacy fallbacks kept for compatibility
            'focus_title' => 'Interactive Sector Finder',
            'focus_cards' => [],
            'about_title' => 'Tentang Prolabios',
            'about_description' => 'Prolabios Mitra Analitika (PMA) dibangun untuk menjadi distributor terkemuka produk mikrobiologi di Indonesia.',
            'hotline_label' => 'Layanan Pelanggan 24/7',
            'hotline_number' => '0821-8792-9433',
            'hotline_description' => 'Hubungi kami via telepon atau WhatsApp untuk konsultasi produk.',

            // Site-wide contact info
            'contact_phone' => '0821-8792-9433',
            'contact_phone_marketing' => '021-3874-1447',
            'contact_phone_finance' => '021-8792-9433',
            'contact_phone_technician' => '0812-837-4867',
            'whatsapp_default_message' => 'Halo Prolabios, saya ingin berkonsultasi mengenai produk dan penawaran alat laboratorium.',
            'contact_email' => 'marketing@prolabios.com',
            'contact_address' => 'GRGC+V7V, Jl. KSR Dadi Kusmayadi, Tengah, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16914',
            'catalog_pdf_url' => 'https://drive.google.com/open?id=1ijNKezGnKAa8JlQs2L8NFJjeHDjfd3YC&usp=drive_fs',
            'google_maps_embed_url' => 'https://maps.google.com/maps?q=PT.+Prolabios+Mitra+Analitika&t=&z=17&ie=UTF8&iwloc=&output=embed',

            // General & Social Media settings
            'company_name' => 'PT. Prolabios Mitra Analitika',
            'site_logo' => '',
            'site_favicon' => '',
            'meta_default_description' => 'PROLABIOS Mitra Analitika : Professional, Robust, Offering the best. Distributor alat laboratorium, media kultur mikrobiologi, dan instrumen ilmiah di Indonesia.',
            'meta_default_keywords' => 'prolabios, alat laboratorium, mikrobiologi, instrumen lab, media kultur, bioendo, terragene',
            'google_search_console_id' => '',
            'operational_hours' => 'Senin - Jumat: 08.00 - 17.00',
            'social_instagram' => 'https://instagram.com/prolabios',
            'social_facebook' => 'https://facebook.com/prolabios',
            'social_linkedin' => 'https://linkedin.com/company/prolabios',
            'social_twitter' => 'https://twitter.com/prolabios',

            // Page: Products
            'products_title' => 'Semua Produk',
            'products_subtitle' => 'Menampilkan semua produk kami',
            'products_banner_image' => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            // Page: Sectors
            'sectors_title' => 'Sektor Industri',
            'sectors_subtitle' => 'Kami melayani berbagai macam sektor industri di Indonesia',
            'sectors_banner_image' => 'https://images.unsplash.com/photo-1574585141047-92e105e4d9eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            // Page: Services
            'services_title' => 'Layanan Kami',
            'services_subtitle' => 'Dukungan purnajual dan layanan konsultasi terpadu',
            'services_banner_image' => 'https://images.unsplash.com/photo-1581093588401-fbb62a02f120?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            // Page: Information
            'info_title' => 'News & Activity',
            'info_subtitle' => 'Ikuti berita terkini, tips laboratorium, dan artikel ilmiah',
            'info_banner_image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',

            // Page: Contact
            'contact_title' => 'Hubungi Kami',
            'contact_subtitle' => 'Ada pertanyaan atau butuh konsultasi? Tim kami siap melayani Anda.',
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

        // Clean up literal string '\r\n' or '\n' or '\r' if stored as text literals
        $html = str_replace(['\r\n', '\r', '\n'], "\n", $html);

        $dom = new \DOMDocument;
        libxml_use_internal_errors(true);
        // Wrap the snippet inside <div> to prevent DOMDocument from adding automatic wrapper tags
        $dom->loadHTML('<?xml encoding="utf-8" ?><div>'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $allowedTags = ['p', 'b', 'i', 'strong', 'em', 'a', 'ul', 'ol', 'li', 'br', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'img', 'span', 'div', 'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'blockquote', 'code', 'pre', 'hr', 'sub', 'sup', 'u', 's', 'strike', 'del'];
        $allowedAttributes = [
            'a' => ['href', 'title', 'class', 'target', 'style'],
            'img' => ['src', 'alt', 'class', 'style', 'width', 'height'],
            'span' => ['class', 'style'],
            'div' => ['class', 'style', 'align'],
            'p' => ['class', 'style', 'align'],
            'table' => ['class', 'style', 'border', 'cellpadding', 'cellspacing', 'width', 'align'],
            'tr' => ['class', 'style', 'align', 'valign'],
            'td' => ['class', 'style', 'colspan', 'rowspan', 'align', 'valign', 'width', 'height'],
            'th' => ['class', 'style', 'colspan', 'rowspan', 'align', 'valign', 'width', 'height'],
            'blockquote' => ['class', 'style'],
            'code' => ['class', 'style'],
            'pre' => ['class', 'style'],
            'h1' => ['class', 'style', 'align'],
            'h2' => ['class', 'style', 'align'],
            'h3' => ['class', 'style', 'align'],
            'h4' => ['class', 'style', 'align'],
            'h5' => ['class', 'style', 'align'],
            'h6' => ['class', 'style', 'align'],
        ];

        $sanitizeNode = function (\DOMNode $node) use (&$sanitizeNode, $allowedTags, $allowedAttributes) {
            if ($node instanceof \DOMElement) {
                $tagName = strtolower($node->nodeName);
                if (! in_array($tagName, $allowedTags, true)) {
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
                        if (! in_array($attrName, $allowedAttrsForTag, true)) {
                            $attrsToRemove[] = $attr->name;

                            continue;
                        }

                        if ($attrName === 'href' || $attrName === 'src') {
                            $val = trim($attr->value);
                            if (preg_match('/^(javascript|vbscript):/i', $val)) {
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

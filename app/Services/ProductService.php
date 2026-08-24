<?php

namespace App\Services;

use App\Helpers\HtmlSanitizer;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
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
    public function clearProductsCache(): void
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
            'food-beverage' => [
                'name' => 'Food & Beverage',
                'subs' => [
                    'food-safety' => 'Food Safety',
                ],
            ],
            'veterinary' => [
                'name' => 'Veterinary',
                'subs' => [
                    'veterinary-diagnostics' => 'Veterinary Diagnostics',
                ],
            ],
            'chemistry' => [
                'name' => 'Chemistry',
                'subs' => [
                    'chemical-indicator' => 'Chemical Indicator',
                ],
            ],
            'consumable' => [
                'name' => 'Consumable',
                'subs' => [
                    'consumables' => 'Consumables',
                ],
            ],
        ];

        return Cache::remember('categories_structure', 3600, function () use ($fallback) {
            try {
                $categories = \App\Models\ProductCategory::with('children')
                    ->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();

                if ($categories->isEmpty()) {
                    return $fallback;
                }

                $structure = [];
                foreach ($categories as $category) {
                    $slug = $category->slug ?: Str::slug($category->name);
                    $subs = [];
                    foreach ($category->children as $child) {
                        $childSlug = $child->slug ?: Str::slug($child->name);
                        $subs[$childSlug] = $child->name;
                    }

                    $structure[$slug] = [
                        'name' => $category->name,
                        'subs' => $subs,
                    ];
                }

                return $structure;
            } catch (\Throwable $e) {
                return $fallback;
            }
        });
    }

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
            if (DB::connection()->getDriverName() === 'sqlite') {
                $query->whereRaw("',' || sector || ',' LIKE ?", ["%,{$sector},%"]);
            } else {
                $query->whereRaw('FIND_IN_SET(?, sector) > 0', [$sector]);
            }
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

        if (! empty($filters['sector'])) {
            $sector = $filters['sector'];
            if (DB::connection()->getDriverName() === 'sqlite') {
                $query->whereRaw("',' || sector || ',' LIKE ?", ["%,{$sector},%"]);
            } else {
                $query->whereRaw('FIND_IN_SET(?, sector) > 0', [$sector]);
            }
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

    public function addProduct(array $product): ?Product
    {
        $created = Product::create([
            'catalog'        => $product['catalog'] ?? null,
            'title'          => $product['title'],
            'description'    => HtmlSanitizer::clean($product['description'] ?? null),
            'category'       => $product['category'],
            'sub_category'   => $product['sub_category'] ?? null,
            'sector'         => $product['sector'] ?? null,
            'image'          => $product['image'] ?? null,
            'gallery_images' => ! empty($product['gallery_images']) ? array_values($product['gallery_images']) : null,
            'price'          => $product['price'] ?? 0,
            'stock'          => $product['stock'] ?? 0,
        ]);

        Product::clearCategoriesCache();
        $this->clearProductsCache();

        return $created;
    }

    public function updateProductById(int $id, array $updatedProduct): bool
    {
        $product = Product::find($id);
        if (! $product) {
            return false;
        }

        $product->update([
            'catalog'        => $updatedProduct['catalog'] ?? null,
            'title'          => $updatedProduct['title'],
            'description'    => HtmlSanitizer::clean($updatedProduct['description'] ?? null),
            'category'       => $updatedProduct['category'],
            'sub_category'   => $updatedProduct['sub_category'] ?? null,
            'sector'         => $updatedProduct['sector'] ?? null,
            'image'          => $updatedProduct['image'] ?? null,
            'gallery_images' => ! empty($updatedProduct['gallery_images']) ? array_values($updatedProduct['gallery_images']) : null,
            'price'          => $updatedProduct['price'] ?? 0,
            'stock'          => $updatedProduct['stock'] ?? 0,
        ]);

        Product::clearCategoriesCache();
        $this->clearProductsCache();

        return true;
    }

    public function updateProduct(string $oldTitle, array $updatedProduct): bool
    {
        $product = Product::where('title', $oldTitle)->first();
        if (! $product) {
            return false;
        }

        $product->update([
            'catalog'      => $updatedProduct['catalog'] ?? null,
            'title'        => $updatedProduct['title'],
            'description'  => HtmlSanitizer::clean($updatedProduct['description'] ?? null),
            'category'     => $updatedProduct['category'],
            'sub_category' => $updatedProduct['sub_category'] ?? null,
            'sector'       => $updatedProduct['sector'] ?? null,
            'image'        => $updatedProduct['image'] ?? null,
            'price'        => $updatedProduct['price'] ?? 0,
            'stock'        => $updatedProduct['stock'] ?? 0,
        ]);

        Product::clearCategoriesCache();
        $this->clearProductsCache();

        return true;
    }

    public function decrementStock(int|string $productIdOrTitle, int $quantity = 1): bool
    {
        $query = is_numeric($productIdOrTitle)
            ? Product::where('id', $productIdOrTitle)
            : Product::where('title', $productIdOrTitle);

        $affected = (clone $query)->where('stock', '>=', $quantity)->decrement('stock', $quantity);

        if ($affected > 0) {
            $this->clearProductsCache();
        }

        return $affected > 0;
    }

    public function deleteProductById(int $id): bool
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
        }

        $this->clearProductsCache();
        Product::clearCategoriesCache();

        return true;
    }

    public function deleteProduct(string $title): bool
    {
        $product = Product::where('title', $title)->first();
        if ($product) {
            $product->delete();
        }

        $this->clearProductsCache();
        Product::clearCategoriesCache();

        return true;
    }

    public function upsertProducts(array $products): bool
    {
        if (empty($products)) {
            return false;
        }

        $now = now();
        $rows = [];
        foreach ($products as $p) {
            if (empty(trim($p['title'] ?? ''))) {
                continue;
            }
            $rows[] = [
                'catalog'      => $p['catalog'] ?? null,
                'title'        => $p['title'],
                'description'  => HtmlSanitizer::clean($p['description'] ?? null),
                'category'     => $p['category'],
                'sub_category' => $p['sub_category'] ?? null,
                'sector'       => $p['sector'] ?? null,
                'image'        => $p['image'] ?? null,
                'price'        => $p['price'] ?? 0,
                'stock'        => $p['stock'] ?? 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        if (empty($rows)) {
            return false;
        }

        DB::transaction(function () use ($rows) {
            Product::upsert(
                $rows,
                ['title'],
                ['catalog', 'description', 'category', 'sub_category', 'sector', 'image', 'price', 'stock', 'updated_at']
            );
        });

        Product::clearCategoriesCache();
        $this->clearProductsCache();

        return true;
    }
}

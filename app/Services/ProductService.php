<?php

namespace App\Services;

use App\Helpers\HtmlSanitizer;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Columns for public catalog / sector / home cards.
     * Intentionally excludes description & gallery_images (heavy HTML / JSON).
     * Detail pages use getProductById / getProductBySlug (full row).
     */
    protected function listColumns(): array
    {
        return [
            'id',
            'catalog',
            'title',
            'slug',
            'category',
            'sub_category',
            'sector',
            'principal_id',
            'image',
            'price',
            'stock',
            'created_at',
            'updated_at',
        ];
    }

    public static function getProductsCacheVersion(): int
    {
        return (int) Cache::get('products_cache_version', 1);
    }

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
     * @param  list<array<string, mixed>>  $rows
     * @return Collection<int, Product>
     */
    protected function hydrateProducts(array $rows): Collection
    {
        return collect($rows)->map(
            fn (array $attrs) => (new Product)->newFromBuilder($attrs)
        );
    }

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
                    'ready-to-use-culture-media' => 'Ready To Use Culture Media',
                    'biological-indicators' => 'Biological Indicators',
                    'dehydrated-culture-media' => 'Dehydrated Culture Media',
                    'immunology' => 'Immunology',
                    'endotoxin' => 'Endotoxin',
                ],
            ],
            'reference-standards' => [
                'name' => 'Reference Standards',
                'subs' => [
                    'pharmaceutical' => 'Pharmaceutical Reference Standards',
                    'green-standards' => 'Green Standards',
                    'environmental' => 'Environmental Standards',
                    'food-beverages' => 'Food and Beverages Standards',
                    'agro-chemical' => 'Agro Chemical Standards',
                ],
            ],
            'device' => [
                'name' => 'Device',
                'subs' => [
                    'bsc-lfc' => 'Bio Safety Cabinet (BSC) and Laminar Flow Cabinet (LFC)',
                    'microbiological-instruments' => 'Microbiological Instruments',
                    'liquid-handling' => 'Liquid Handling',
                    'thermometer' => 'Thermometer',
                ],
            ],
            'instruments' => [
                'name' => 'Instruments',
                'subs' => [
                    'liofilchem-giotto-2' => 'Liofilchem® Giotto 2',
                    'agar-filler' => 'Agar Filler',
                    'agar-preparator' => 'Agar Preparator',
                    'kinetic-incubating-reader' => 'Kinetic Incubating Microplate Reader',
                    'mica-diamidex' => 'MICA® Diamidex - Counting Microorganisms Faster',
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
                    $key = $category->key ?: Str::slug($category->name);
                    $subs = [];
                    foreach ($category->children as $child) {
                        $childKey = $child->key ?: Str::slug($child->name);
                        $subs[$childKey] = $child->name;
                    }
                    $structure[$key] = [
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

    protected function applyProductFilters($query, ?array $filters = []): void
    {
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
                    ->orWhere('sub_category', 'LIKE', $subCat.'%');
            });
        }

        if (! empty($filters['search'])) {
            $query->search((string) $filters['search']);
        }

        if (! empty($filters['sector'])) {
            $query->bySector($filters['sector']);
        }
    }

    public function getProducts(?array $filters = [], int $limit = 0): Collection
    {
        $v = self::getProductsCacheVersion();
        // Bump key suffix so old cache entries that still hold description are ignored
        $cacheKey = 'products_list_v2_'.$v.'_'.md5(json_encode($filters).'_'.$limit);

        $cached = Cache::get($cacheKey);
        if (is_array($cached)) {
            return $this->hydrateProducts($cached);
        }

        $query = Product::query()
            ->select($this->listColumns())
            ->orderBy('id');

        $this->applyProductFilters($query, $filters);

        if ($limit > 0) {
            $query->limit($limit);
        }

        $result = $query->get();

        Cache::put(
            $cacheKey,
            $result->map(fn (Product $p) => $p->getAttributes())->all(),
            300
        );

        return $result;
    }

    public function getPaginatedProducts(?array $filters = [], int $perPage = 12)
    {
        $v = self::getProductsCacheVersion();
        $page = max(1, (int) request()->input('page', 1));
        $cacheKey = 'products_paginated_v2_'.$v.'_'.md5(json_encode($filters).'_'.$perPage.'_p'.$page);

        $cached = Cache::get($cacheKey);
        if (is_array($cached) && isset($cached['total'], $cached['items']) && is_array($cached['items'])) {
            $items = $this->hydrateProducts($cached['items']);

            return (new LengthAwarePaginator(
                $items,
                (int) $cached['total'],
                $perPage,
                $page,
                [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]
            ))->withQueryString();
        }

        $query = Product::query()
            ->select($this->listColumns())
            ->orderBy('id');

        $this->applyProductFilters($query, $filters);

        $paginator = $query->paginate($perPage, $this->listColumns(), 'page', $page)->withQueryString();

        Cache::put($cacheKey, [
            'total' => $paginator->total(),
            'items' => $paginator->getCollection()->map(fn (Product $p) => $p->getAttributes())->all(),
        ], 300);

        return $paginator;
    }

    public function getProductByTitle(string $title): ?Product
    {
        $v = self::getProductsCacheVersion();
        $cacheKey = "product_by_title_{$v}_".md5($title);
        $cached = Cache::get($cacheKey);

        if (is_array($cached)) {
            return (new Product)->newFromBuilder($cached);
        }

        if ($cached instanceof Product) {
            return $cached;
        }

        $product = Product::where('title', $title)->first();
        if ($product) {
            Cache::put($cacheKey, $product->getAttributes(), 600);
        }

        return $product;
    }

    public function getProductById(int $id): ?Product
    {
        $v = self::getProductsCacheVersion();
        $cacheKey = "product_by_id_{$v}_".$id;
        $cached = Cache::get($cacheKey);

        if (is_array($cached)) {
            return (new Product)->newFromBuilder($cached);
        }

        if ($cached instanceof Product) {
            return $cached;
        }

        $product = Product::find($id);
        if ($product) {
            Cache::put($cacheKey, $product->getAttributes(), 600);
        }

        return $product;
    }

    public function getProductBySlug(string $slug): ?Product
    {
        $slug = trim($slug);
        if ($slug === '') {
            return null;
        }

        $v = self::getProductsCacheVersion();
        $cacheKey = "product_by_slug_{$v}_".md5($slug);
        $cached = Cache::get($cacheKey);

        if (is_array($cached)) {
            return (new Product)->newFromBuilder($cached);
        }

        if ($cached instanceof Product) {
            return $cached;
        }

        $product = Product::where('slug', $slug)->first();
        if ($product) {
            Cache::put($cacheKey, $product->getAttributes(), 600);
        }

        return $product;
    }

    public function addProduct(array $product): ?Product
    {
        $created = Product::create([
            'catalog' => $product['catalog'] ?? null,
            'title' => $product['title'],
            'description' => HtmlSanitizer::clean($product['description'] ?? null),
            'category' => $product['category'],
            'sub_category' => $product['sub_category'] ?? null,
            'sector' => $product['sector'] ?? null,
            'principal_id' => $product['principal_id'] ?? null,
            'image' => $product['image'] ?? null,
            'gallery_images' => ! empty($product['gallery_images']) ? array_values($product['gallery_images']) : null,
            'price' => $product['price'] ?? 0,
            'stock' => $product['stock'] ?? 0,
        ]);

        $created->syncSectorsFromCsv($created->sector);

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
            'catalog' => $updatedProduct['catalog'] ?? null,
            'title' => $updatedProduct['title'],
            'description' => HtmlSanitizer::clean($updatedProduct['description'] ?? null),
            'category' => $updatedProduct['category'],
            'sub_category' => $updatedProduct['sub_category'] ?? null,
            'sector' => $updatedProduct['sector'] ?? null,
            'principal_id' => $updatedProduct['principal_id'] ?? null,
            'image' => $updatedProduct['image'] ?? null,
            'gallery_images' => ! empty($updatedProduct['gallery_images']) ? array_values($updatedProduct['gallery_images']) : null,
            'price' => $updatedProduct['price'] ?? 0,
            'stock' => $updatedProduct['stock'] ?? 0,
        ]);

        $product->syncSectorsFromCsv($product->sector);

        Product::clearCategoriesCache();
        $this->clearProductsCache();

        return true;
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
                'catalog' => $p['catalog'] ?? null,
                'title' => $p['title'],
                'description' => HtmlSanitizer::clean($p['description'] ?? null),
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

        DB::transaction(function () use ($rows) {
            Product::upsert(
                $rows,
                ['title'],
                ['catalog', 'description', 'category', 'sub_category', 'sector', 'image', 'price', 'stock', 'updated_at']
            );

            $titles = array_column($rows, 'title');
            Product::whereIn('title', $titles)->orderBy('id')->chunkById(100, function ($chunk) {
                foreach ($chunk as $product) {
                    if (empty($product->slug)) {
                        $product->slug = Product::uniqueSlugFrom((string) $product->title, $product->id);
                        $product->saveQuietly();
                    }
                    $product->syncSectorsFromCsv($product->sector);
                }
            });
        });

        Product::clearCategoriesCache();
        $this->clearProductsCache();

        return true;
    }
}

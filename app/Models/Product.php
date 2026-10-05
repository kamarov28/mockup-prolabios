<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $catalog
 * @property string $title
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $datasheet_url
 * @property string|null $category
 * @property string|null $sub_category
 * @property string|null $packaging
 * @property string|null $function
 * @property string|null $reference_method
 * @property string|null $sector
 * @property int|null $principal_id
 * @property string|null $image
 * @property array<string>|null $gallery_images
 * @property float $price
 * @property int $stock
 * @property bool $is_featured
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Product extends Model
{
    protected $fillable = [
        'catalog',
        'title',
        'slug',
        'description',
        'datasheet_url',
        'category',
        'sub_category',
        'packaging',
        'function',
        'reference_method',
        'sector',
        'principal_id',
        'image',
        'gallery_images',
        'price',
        'stock',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'stock' => 'integer',
            'is_featured' => 'boolean',
            'gallery_images' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Public product URLs use slug: /produk/{slug}
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getTitleAttribute($value): string
    {
        return $this->cleanMojibake($value) ?? '';
    }

    public function getDescriptionAttribute($value): ?string
    {
        return $this->cleanMojibake($value);
    }

    public static function stockBadgeClass(?int $stock): string
    {
        return ($stock ?? 0) > 0 ? 'admin-badge-success' : 'admin-badge-danger';
    }

    public function getStockBadgeClassAttribute(): string
    {
        return self::stockBadgeClass($this->stock);
    }

    private function cleanMojibake(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $replacements = [
            'â€"' => '–',
            'â€“' => '–',
            'â€”' => '—',
            'â„¢' => '™',
            'â€œ' => '"',
            'â€ ' => '"',
            'â€™' => "'",
            'â€˜' => "'",
            'Â®' => '®',
            'â€¢' => '•',
        ];

        return strtr($value, $replacements);
    }

    // ----------------------------------------------------
    // Relationships
    // ----------------------------------------------------
    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category', 'key');
    }

    public function subCategoryRelation(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'sub_category', 'key');
    }

    public function principal(): BelongsTo
    {
        return $this->belongsTo(Principal::class, 'principal_id');
    }

    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class, 'product_sector', 'product_id', 'sector_id')
            ->withTimestamps();
    }

    public function rfqItems(): HasMany
    {
        return $this->hasMany(RfqItem::class, 'product_id');
    }

    // ----------------------------------------------------
    // Query Scopes
    // ----------------------------------------------------
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Filter by sector via product_sector pivot only (source of truth).
     * Admin may still store CSV on products.sector; that is synced to pivot on save.
     */
    public function scopeBySector(Builder $query, string $sector): Builder
    {
        $sector = trim($sector);
        if ($sector === '') {
            return $query;
        }

        return $query->whereExists(function ($sub) use ($sector) {
            $sub->select(DB::raw(1))
                ->from('product_sector')
                ->whereColumn('product_sector.product_id', 'products.id')
                ->where('product_sector.sector_id', $sector);
        });
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);
        if ($term === '') {
            return $query;
        }

        $words = array_values(array_filter(explode(' ', $term), fn ($w) => trim($w) !== ''));

        // Searchable columns down to substring/characters
        $searchableColumns = ['title', 'catalog', 'description'];
        if (Schema::hasColumn('products', 'function')) {
            $searchableColumns[] = 'function';
        }
        if (Schema::hasColumn('products', 'reference_method')) {
            $searchableColumns[] = 'reference_method';
        }
        if (Schema::hasColumn('products', 'packaging')) {
            $searchableColumns[] = 'packaging';
        }

        return $query->where(function (Builder $q) use ($term, $words, $searchableColumns) {
            // 1. Direct character/substring match on full search term
            $q->where(function (Builder $sub) use ($term, $searchableColumns) {
                foreach ($searchableColumns as $i => $col) {
                    if ($i === 0) {
                        $sub->where($col, 'like', "%{$term}%");
                    } else {
                        $sub->orWhere($col, 'like', "%{$term}%");
                    }
                }
            });

            // 2. Multi-word search: match all individual keywords anywhere across columns
            if (count($words) > 1) {
                $q->orWhere(function (Builder $allWordsSub) use ($words, $searchableColumns) {
                    foreach ($words as $word) {
                        $allWordsSub->where(function (Builder $wordSub) use ($word, $searchableColumns) {
                            foreach ($searchableColumns as $j => $col) {
                                if ($j === 0) {
                                    $wordSub->where($col, 'like', "%{$word}%");
                                } else {
                                    $wordSub->orWhere($col, 'like', "%{$word}%");
                                }
                            }
                        });
                    }
                });
            }
        });
    }

    // ----------------------------------------------------
    // Slug helpers
    // ----------------------------------------------------

    public static function uniqueSlugFrom(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'product';
        }

        $slug = $base;
        $i = 2;
        while (true) {
            $q = static::query()->where('slug', $slug);
            if ($ignoreId !== null) {
                $q->where('id', '!=', $ignoreId);
            }
            if (! $q->exists()) {
                return $slug;
            }
            $slug = $base.'-'.$i;
            $i++;
        }
    }

    /**
     * @return list<string>
     */
    public static function parseSectorIds(?string $sectorCsv): array
    {
        if ($sectorCsv === null || $sectorCsv === '') {
            return [];
        }

        $ids = array_map('trim', explode(',', $sectorCsv));

        return array_values(array_unique(array_filter($ids, fn (string $id) => $id !== '')));
    }

    /**
     * Keep product_sector pivot in sync with the admin CSV `sector` column.
     */
    public function syncSectorsFromCsv(?string $sectorCsv = null): void
    {
        $this->sectors()->sync(self::parseSectorIds($sectorCsv ?? $this->sector));
    }

    public function isAvailable(): bool
    {
        return (int) $this->stock > 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->price > 0
            ? 'Rp '.number_format($this->price, 0, ',', '.')
            : 'Est. Penawaran';
    }

    public function getUrlAttribute(): string
    {
        $slug = $this->slug ?: static::uniqueSlugFrom((string) $this->title, $this->id);

        return url('/produk/'.$slug);
    }

    public static function clearCategoriesCache(): void
    {
        Cache::forget('categories_structure');
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (empty($product->slug) || $product->isDirty('title')) {
                $product->slug = static::uniqueSlugFrom(
                    (string) ($product->title ?: 'product'),
                    $product->id
                );
            }
        });

        static::saved(function (Product $product) {
            // CSV sector column is input-only; pivot is source of truth for reads
            if ($product->wasRecentlyCreated || $product->wasChanged('sector')) {
                $product->syncSectorsFromCsv($product->sector);
            }

            Cache::forget('categories_structure');
            try {
                Cache::increment('products_cache_version');
            } catch (\Throwable $e) {
                Cache::put('products_cache_version', time());
            }
        });

        static::deleted(function (Product $product) {
            $product->sectors()->detach();

            Cache::forget('categories_structure');
            try {
                Cache::increment('products_cache_version');
            } catch (\Throwable $e) {
                Cache::put('products_cache_version', time());
            }
        });
    }
}

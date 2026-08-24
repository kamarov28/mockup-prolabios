<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $fillable = [
        'catalog',
        'title',
        'description',
        'category',
        'sub_category',
        'sector',
        'image',
        'gallery_images',
        'price',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'price'          => 'float',
            'stock'          => 'integer',
            'gallery_images' => 'array',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    // ----------------------------------------------------
    // Relationships
    // products.category / sub_category store ProductCategory.key (slug), not name
    // products.sector CSV + product_sector pivot (both checked on read)
    // ----------------------------------------------------
    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category', 'key');
    }

    public function subCategoryRelation(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'sub_category', 'key');
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
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Match sector via pivot (preferred) OR legacy CSV column.
     * Same semantics as the old client-side: sector.split(',').includes(id)
     */
    public function scopeBySector(Builder $query, string $sector): Builder
    {
        $sector = trim($sector);
        if ($sector === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($sector) {
            // 1) product_sector pivot
            $q->whereExists(function ($sub) use ($sector) {
                $sub->select(DB::raw(1))
                    ->from('product_sector')
                    ->whereColumn('product_sector.product_id', 'products.id')
                    ->where('product_sector.sector_id', $sector);
            });

            // 2) Legacy CSV on products.sector (exact, or token in list)
            $driver = $q->getConnection()->getDriverName();
            if ($driver === 'sqlite') {
                $q->orWhere('sector', $sector)
                    ->orWhereRaw("',' || COALESCE(sector, '') || ',' LIKE ?", ["%,{$sector},%"]);
            } else {
                // MySQL / MariaDB
                $q->orWhere('sector', $sector)
                    ->orWhereRaw('FIND_IN_SET(?, sector) > 0', [$sector]);
            }
        });
    }

    /**
     * Full-text on MySQL/MariaDB; LIKE fallback elsewhere (SQLite dev).
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);
        if ($term === '') {
            return $query;
        }

        $driver = $query->getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            return $query->where(function (Builder $q) use ($term) {
                $q->whereFullText(['title', 'description'], $term)
                    ->orWhere('catalog', 'like', $term.'%')
                    ->orWhere('title', 'like', $term.'%');
            });
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('catalog', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    // ----------------------------------------------------
    // Helpers
    // ----------------------------------------------------

    /**
     * Parse CSV sector string into unique non-empty sector ids.
     *
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
     * Keep product_sector pivot in sync with the legacy CSV `sector` column.
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
            ? 'Rp ' . number_format($this->price, 0, ',', '.')
            : 'Est. Penawaran';
    }

    // ----------------------------------------------------
    // Cache Eviction Lifecycle
    // ----------------------------------------------------
    public static function clearCategoriesCache(): void
    {
        Cache::forget('categories_structure');
    }

    protected static function booted(): void
    {
        static::saved(function () {
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

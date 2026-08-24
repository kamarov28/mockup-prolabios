<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'catalog',
        'title',
        'slug',
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

    /**
     * Public product URLs use slug: /produk/{slug}
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
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

    public function scopeBySector(Builder $query, string $sector): Builder
    {
        $sector = trim($sector);
        if ($sector === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($sector) {
            $q->whereExists(function ($sub) use ($sector) {
                $sub->select(DB::raw(1))
                    ->from('product_sector')
                    ->whereColumn('product_sector.product_id', 'products.id')
                    ->where('product_sector.sector_id', $sector);
            });

            $driver = $q->getConnection()->getDriverName();
            if ($driver === 'sqlite') {
                $q->orWhere('sector', $sector)
                    ->orWhereRaw("',' || COALESCE(sector, '') || ',' LIKE ?", ["%,{$sector},%"]);
            } else {
                $q->orWhere('sector', $sector)
                    ->orWhereRaw('FIND_IN_SET(?, sector) > 0', [$sector]);
            }
        });
    }

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
    // Slug helpers
    // ----------------------------------------------------

    /**
     * Build a unique slug from a title (or other base string).
     */
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

    /** Canonical public URL path segment. */
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

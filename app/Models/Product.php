<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

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
    // ----------------------------------------------------
    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category', 'name');
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
        return $query->whereRaw('FIND_IN_SET(?, sector) > 0', [$sector]);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('catalog', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    // ----------------------------------------------------
    // Accessors & Helper Methods
    // ----------------------------------------------------
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

        static::deleted(function () {
            Cache::forget('categories_structure');
            try {
                Cache::increment('products_cache_version');
            } catch (\Throwable $e) {
                Cache::put('products_cache_version', time());
            }
        });
    }
}

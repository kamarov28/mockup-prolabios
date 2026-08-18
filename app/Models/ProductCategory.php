<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class ProductCategory extends Model
{
    protected $fillable = ['key', 'name', 'parent_id', 'sort_order'];

    protected $casts = [
        'parent_id'  => 'integer',
        'sort_order' => 'integer',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    // ── Cache Invalidation ─────────────────────────────────────────────────

    protected static function booted(): void
    {
        $clear = fn () => Cache::forget('categories_structure');
        static::saved($clear);
        static::deleted($clear);
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    public function isChild(): bool
    {
        return ! is_null($this->parent_id);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'date',
        'category',
        'status',
        'is_featured',
        'image',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'date'        => 'date',
            'is_featured' => 'boolean',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }

    // ----------------------------------------------------
    // Query Scopes
    // ----------------------------------------------------
    public function scopeOnline(Builder $query): Builder
    {
        $today = date('Y-m-d');

        return $query->where('status', 'online')
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('date')
                    ->orWhere('date', '<=', $today);
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        $cat = strtolower($category);
        if ($cat === 'info') {
            return $query->where(function (Builder $q) {
                $q->where('category', 'Info Terkait')->orWhere('category', 'Info');
            });
        }

        return $query->whereRaw('LOWER(category) = ?', [$cat]);
    }
}

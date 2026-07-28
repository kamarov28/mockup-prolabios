<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['catalog', 'title', 'description', 'category', 'sub_category', 'sector', 'image', 'price', 'stock'];

    /**
     * Clear the categories structure cache.
     */
    public static function clearCategoriesCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget('categories_structure');
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saved(fn () => static::clearCategoriesCache());
        static::deleted(fn () => static::clearCategoriesCache());
    }
}

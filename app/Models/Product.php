<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['catalog', 'title', 'description', 'category', 'sub_category', 'sector', 'image'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget(config('app.name') . ':categories_structure');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget(config('app.name') . ':categories_structure');
        });
    }
}

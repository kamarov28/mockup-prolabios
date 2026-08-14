<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    protected $fillable = ['catalog', 'title', 'description', 'category', 'sub_category', 'sector', 'image', 'gallery_images', 'price', 'stock'];

    /**
     * Clear the categories structure and product caches.
     */
    protected static function clearProductCaches(Product $product): void
    {
        Cache::forget('categories_structure');
        Cache::forget('product_by_title_'.md5($product->title));
        Cache::forget('product_by_id_'.$product->id);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saved(fn (Product $product) => static::clearProductCaches($product));
        static::deleted(fn (Product $product) => static::clearProductCaches($product));
    }
}

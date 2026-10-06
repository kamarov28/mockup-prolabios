<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Sector extends Model
{
    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'description',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'description' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_sector', 'sector_id', 'product_id')
            ->withTimestamps();
    }

    protected static function booted(): void
    {
        $clear = fn () => Cache::forget('sectors_list_v2');
        static::saved($clear);
        static::deleted($clear);
    }
}

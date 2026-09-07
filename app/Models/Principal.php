<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Principal extends Model
{
    protected $fillable = [
        'name',
        'address',
        'logo',
        'status',
    ];

    /**
     * Only principals marked online (homepage marquee, public lists).
     */
    public function scopeOnline(Builder $query): Builder
    {
        return $query->where('status', 'online');
    }

    public function isOnline(): bool
    {
        return $this->status === 'online';
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'principal_id');
    }
}

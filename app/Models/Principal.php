<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
}

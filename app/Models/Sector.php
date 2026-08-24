<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }
}

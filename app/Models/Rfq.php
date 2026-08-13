<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rfq extends Model
{
    use HasFactory;

    protected $table = 'rfqs';

    protected $fillable = [
        'rfq_number',
        'name',
        'email',
        'company_name',
        'phone_wa',
        'notes',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(RfqItem::class, 'rfq_id');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RfqItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rfq_items';

    protected $fillable = [
        'rfq_id',
        'product_id',
        'product_title',
        'catalog_no',
        'original_price',
        'quantity',
    ];

    public function rfq(): BelongsTo
    {
        return $this->belongsTo(Rfq::class, 'rfq_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}


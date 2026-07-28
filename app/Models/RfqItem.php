<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqItem extends Model
{
    use HasFactory;

    protected $table = 'rfq_items';

    protected $fillable = [
        'rfq_id',
        'product_id',
        'product_title',
        'catalog_no',
        'original_price',
        'offered_price',
        'quantity',
        'subtotal',
    ];

    public function rfq()
    {
        return $this->belongsTo(Rfq::class, 'rfq_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

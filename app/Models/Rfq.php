<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfq extends Model
{
    use HasFactory;

    protected $table = 'rfqs';

    protected $fillable = [
        'rfq_number',
        'access_token',
        'company_name',
        'company_tax_id',
        'pic_name',
        'pic_position',
        'email',
        'phone_wa',
        'address',
        'notes',
        'status',
        'total_offered_amount',
        'admin_response_notes',
        'valid_until',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'total_offered_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(RfqItem::class, 'rfq_id');
    }
}

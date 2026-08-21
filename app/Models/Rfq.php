<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rfq extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rfqs';

    protected $fillable = [
        'rfq_number',
        'name',
        'email',
        'company_name',
        'phone_wa',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(RfqItem::class, 'rfq_id');
    }

    public function getTotalItemsCountAttribute(): int
    {
        return (int) $this->items->sum('quantity');
    }

    public function getEstimatedGrandTotalAttribute(): float
    {
        return (float) $this->items->sum(function (RfqItem $item) {
            return ($item->original_price ?? 0) * ($item->quantity ?? 1);
        });
    }

    public function getFormattedGrandTotalAttribute(): string
    {
        $total = $this->estimated_grand_total;

        return $total > 0
            ? 'Rp ' . number_format($total, 0, ',', '.')
            : 'Est. Penawaran';
    }
}

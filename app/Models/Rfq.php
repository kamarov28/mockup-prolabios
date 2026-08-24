<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rfq extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_NEW = 'new';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_QUOTED = 'quoted';

    public const STATUS_CLOSED = 'closed';

    protected $table = 'rfqs';

    protected $fillable = [
        'rfq_number',
        'name',
        'email',
        'company_name',
        'phone_wa',
        'notes',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_NEW => 'Baru',
            self::STATUS_CONTACTED => 'Dihubungi',
            self::STATUS_QUOTED => 'Quoted',
            self::STATUS_CLOSED => 'Selesai',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? ($this->status ?: 'Baru');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        // Pakai admin-badge-* (dark-theme aware) supaya teks selalu terbaca
        return match ($this->status) {
            self::STATUS_CONTACTED => 'admin-badge-info',
            self::STATUS_QUOTED => 'admin-badge-accent',
            self::STATUS_CLOSED => 'admin-badge-muted',
            default => 'admin-badge-warning', // Baru — teks kuning + bg soft
        };
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

    /** WhatsApp deep-link with prefilled sales intro. */
    public function getWhatsappUrlAttribute(): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $this->phone_wa);
        $text = sprintf(
            'Halo %s dari %s, kami dari Tim Sales Prolabios terkait pengajuan penawaran %s',
            $this->name,
            $this->company_name,
            $this->rfq_number
        );

        return 'https://wa.me/'.$digits.'?text='.rawurlencode($text);
    }
}

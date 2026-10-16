<?php

namespace App\Models;

use App\Enums\RfqStatus;
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
            'status' => RfqStatus::class,
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
        $options = [];
        foreach (RfqStatus::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status instanceof RfqStatus) {
            return $this->status->label();
        }

        return self::statusOptions()[$this->status] ?? ($this->status ?: 'Baru');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        if ($this->status instanceof RfqStatus) {
            return $this->status->badgeClass();
        }

        // Pakai admin-badge-* (dark-theme aware) supaya teks selalu terbaca
        return match ($this->status) {
            self::STATUS_CONTACTED => 'admin-badge-info',
            self::STATUS_QUOTED => 'admin-badge-accent',
            self::STATUS_CLOSED => 'admin-badge-muted',
            default => 'admin-badge-warning', // Baru — teks kuning + bg soft
        };
    }

    /**
     * @return HasMany<RfqItem, $this>
     */
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
        return (float) $this->items->sum(function ($item) {
            return ($item->original_price ?? 0) * ($item->quantity ?? 1);
        });
    }

    public function getFormattedGrandTotalAttribute(): string
    {
        $total = $this->estimated_grand_total;

        return $total > 0
            ? 'Rp '.number_format($total, 0, ',', '.')
            : 'Est. Penawaran';
    }

    /**
     * Normalized WhatsApp number in international E.164-compatible format without plus.
     * Converts Indonesian local format (08... / 8...) to 628...
     */
    public function getNormalizedWhatsappNumberAttribute(): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $this->phone_wa);

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            return '62'.$digits;
        }

        return $digits;
    }

    /** WhatsApp deep-link with prefilled sales intro. */
    public function getWhatsappUrlAttribute(): string
    {
        $number = $this->normalized_whatsapp_number;
        if ($number === '') {
            return '#';
        }

        $text = sprintf(
            'Halo %s dari %s, kami dari Tim Sales Prolabios terkait pengajuan penawaran %s',
            $this->name,
            $this->company_name,
            $this->rfq_number
        );

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }

    /**
     * Formatted text recap for sales reps to copy and paste into WhatsApp/Email.
     */
    public function getWhatsappFollowupTextAttribute(): string
    {
        $lines = [
            "Yth. {$this->name}",
            "{$this->company_name}",
            '',
            'Terima kasih atas pengajuan penawaran melalui website PT Prolabios Mitra Analitika.',
            "Berikut rincian kebutuhan pengadaan (No. RFQ: {$this->rfq_number}):",
            '',
        ];

        foreach ($this->items as $i => $item) {
            $num = $i + 1;
            $cat = $item->catalog_no ? " (Cat. {$item->catalog_no})" : '';
            $lines[] = "{$num}. {$item->product_title}{$cat} - {$item->quantity} Unit";
        }

        if (! empty($this->notes)) {
            $lines[] = '';
            $lines[] = "Catatan Khusus: {$this->notes}";
        }

        $lines[] = '';
        $lines[] = 'Tim Sales kami siap membantu proses penawaran harga resmi & konfirmasi ketersediaan stok. Mohon konfirmasinya. Terima kasih.';

        return implode("\n", $lines);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsletterBroadcast extends Model
{
    protected $fillable = [
        'subject',
        'target_sector_id',
        'type',
        'content',
        'recipients_count',
        'sent_by_user_id',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'recipients_count' => 'integer',
        ];
    }

    public function targetSector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'target_sector_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by_user_id');
    }
}

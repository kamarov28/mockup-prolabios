<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'company',
        'city',
        'country',
        'password',
        'is_active',
        'email_verified_at',
        'unsubscribe_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class, 'newsletter_subscriber_sector', 'subscriber_id', 'sector_id')
            ->withTimestamps();
    }

    protected static function booted(): void
    {
        static::creating(function ($subscriber) {
            if (empty($subscriber->unsubscribe_token)) {
                $subscriber->unsubscribe_token = Str::random(40);
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'date',
        'category',
        'status',
        'is_featured',
        'image',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }

    public function getDateAttribute($value): ?\Carbon\Carbon
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof \Carbon\CarbonInterface) {
            return \Carbon\Carbon::instance($value);
        }

        $indoMonths = [
            'Januari' => 'January', 'Jan' => 'Jan',
            'Februari' => 'February', 'Feb' => 'Feb',
            'Maret' => 'March', 'Mar' => 'Mar',
            'April' => 'April', 'Apr' => 'Apr',
            'Mei' => 'May',
            'Juni' => 'June', 'Jun' => 'Jun',
            'Juli' => 'July', 'Jul' => 'Jul',
            'Agustus' => 'August', 'Agu' => 'Aug', 'Agt' => 'Aug',
            'September' => 'September', 'Sep' => 'Sep',
            'Oktober' => 'October', 'Okt' => 'Oct',
            'November' => 'November', 'Nov' => 'Nov',
            'Desember' => 'December', 'Des' => 'Dec',
        ];

        try {
            $normalized = str_ireplace(array_keys($indoMonths), array_values($indoMonths), (string) $value);

            return \Carbon\Carbon::parse($normalized);
        } catch (\Throwable $e) {
            return null;
        }
    }

    // ----------------------------------------------------
    // Query Scopes
    // ----------------------------------------------------
    public function scopeOnline(Builder $query): Builder
    {
        $today = date('Y-m-d');

        return $query->where('status', 'online')
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('date')
                    ->orWhere('date', '<=', $today);
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        $cat = strtolower($category);
        if ($cat === 'info') {
            return $query->where(function (Builder $q) {
                $q->where('category', 'Info Terkait')->orWhere('category', 'Info');
            });
        }

        return $query->whereRaw('LOWER(category) = ?', [$cat]);
    }
}

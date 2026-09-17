<?php

namespace App\Enums;

enum RfqStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Quoted = 'quoted';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Baru',
            self::Contacted => 'Dihubungi',
            self::Quoted => 'Quoted',
            self::Closed => 'Selesai',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Contacted => 'admin-badge-info',
            self::Quoted => 'admin-badge-accent',
            self::Closed => 'admin-badge-muted',
            self::New => 'admin-badge-warning',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Quoted => '0369A1',
            self::Contacted => 'D97706',
            self::Closed => '475569',
            self::New => '1E293B',
        };
    }
}

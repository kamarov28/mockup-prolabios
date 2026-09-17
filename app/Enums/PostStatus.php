<?php

namespace App\Enums;

enum PostStatus: string
{
    case Online = 'online';
    case Draft = 'draft';

    public function label(): string
    {
        return match ($this) {
            self::Online => 'Online',
            self::Draft => 'Draft',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Online => 'admin-badge-success',
            self::Draft => 'admin-badge-warning',
        };
    }
}

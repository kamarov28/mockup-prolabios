<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_SALES = 'sales';

    public const ROLE_CATALOG = 'catalog';

    public const ROLE_CONTENT = 'content';

    public const ALL_ROLES = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_SALES,
        self::ROLE_CATALOG,
        self::ROLE_CONTENT,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Check if the user has administrator privileges.
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin || ! empty($this->role);
    }

    /**
     * Check if user is Super Admin (full system & settings control).
     */
    public function isSuperAdmin(): bool
    {
        if (! $this->isAdmin()) {
            return false;
        }

        // Default legacy admin users without explicit role to super_admin
        return empty($this->role) || $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user can manage RFQs and buyer inquiries.
     */
    public function canManageRfqs(): bool
    {
        return $this->isSuperAdmin() || $this->role === self::ROLE_SALES;
    }

    /**
     * Check if user can create, update, or delete products and catalog taxonomy.
     */
    public function canManageCatalog(): bool
    {
        return $this->isSuperAdmin() || $this->role === self::ROLE_CATALOG;
    }

    /**
     * Check if user can view product catalog in admin cockpit (Read-only for Sales).
     */
    public function canViewCatalog(): bool
    {
        return $this->canManageCatalog() || $this->role === self::ROLE_SALES;
    }

    /**
     * Check if user can publish news, articles, and media uploads.
     */
    public function canManagePosts(): bool
    {
        return $this->isSuperAdmin() || $this->role === self::ROLE_CONTENT;
    }

    /**
     * Human-friendly label for current role.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_SALES => 'Sales & RFQ Admin',
            self::ROLE_CATALOG => 'Product Specialist',
            self::ROLE_CONTENT => 'Content Writer',
            default => 'Super Admin',
        };
    }

    /**
     * Color styling for role badge in admin UI.
     */
    public function getRoleBadgeStyleAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_SALES => 'background: #EFF6FF; color: #1D4ED8; border: 1px solid #DBEAFE;',
            self::ROLE_CATALOG => 'background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A;',
            self::ROLE_CONTENT => 'background: #F0FDF4; color: #15803D; border: 1px solid #DCFCE7;',
            default => 'background: #FEF2F2; color: #991B1B; border: 1px solid #FEE2E2;',
        };
    }

    /**
     * Auto-heal role column on shared hosting environments without terminal access.
     */
    public static function ensureRoleColumnExists(): void
    {
        if (! Schema::hasTable('users') || Schema::hasColumn('users', 'role')) {
            return;
        }

        try {
            Schema::table('users', function ($table) {
                if (! Schema::hasColumn('users', 'role')) {
                    $table->string('role', 30)->default('super_admin')->after('is_admin')->index();
                }
            });

            DB::table('users')->where('is_admin', true)->update(['role' => 'super_admin']);
        } catch (\Throwable $e) {
            // Silently recover if column was added concurrently
        }
    }
}

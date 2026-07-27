<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            $adminEmail = env('ADMIN_EMAIL', 'admin@prolabios.com');
            $adminUsername = env('ADMIN_USERNAME', 'admin');
            $adminPassword = env('ADMIN_PASSWORD', 'prolabios2026');

            if (!User::where('email', $adminEmail)->orWhere('name', $adminUsername)->exists()) {
                User::create([
                    'name' => $adminUsername,
                    'email' => $adminEmail,
                    'password' => Hash::make($adminPassword),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            User::where('email', env('ADMIN_EMAIL', 'admin@prolabios.com'))->delete();
        }
    }
};

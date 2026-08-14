<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
            $adminPassword = env('ADMIN_PASSWORD');

            if (empty($adminPassword)) {
                $adminPassword = Str::random(16);
                if (app()->runningInConsole()) {
                    echo "\n[SECURITY WARNING] ADMIN_PASSWORD environment variable not set. Generated initial admin password: {$adminPassword}\n";
                }
            }

            if (! User::where('email', $adminEmail)->orWhere('name', $adminUsername)->exists()) {
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

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
            $adminEmail = config('app.admin_seed.email', 'admin@prolabios.com');
            $adminUsername = config('app.admin_seed.username', 'admin');
            $adminPassword = config('app.admin_seed.password');

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
            User::where('email', config('app.admin_seed.email', 'admin@prolabios.com'))->delete();
        }
    }
};

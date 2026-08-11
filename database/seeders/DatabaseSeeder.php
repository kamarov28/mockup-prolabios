<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // SECURITY: Since AdminAuthenticate only checks Auth::check() (no role/permission
        // column on the users table), ANY row in `users` has full admin panel access.
        // The default Laravel "Test User" (test@example.com / password) must never be
        // created outside local development, or it becomes a public backdoor account
        // whenever `php artisan migrate --seed` is run against a production database.
        if (app()->environment('local')) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
    }
}

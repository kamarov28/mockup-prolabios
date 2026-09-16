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
        // SECURITY: AdminAuthenticate checks both Auth::check() AND Auth::user()->isAdmin()
        // (enforced via the `is_admin` column on the `users` table added in a later migration).
        // The default Laravel "Test User" must never be created outside local development —
        // it would otherwise expose admin access on production databases seeded with `--seed`.
        if (app()->environment('local')) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
    }
}

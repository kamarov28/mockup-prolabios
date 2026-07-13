<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@prolabios.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@prolabios.com',
                'password' => Hash::make('prolabios2026'),
                'is_admin' => true,
            ]
        );
    }
}

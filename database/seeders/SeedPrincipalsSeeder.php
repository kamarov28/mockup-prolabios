<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedPrincipalsSeeder extends Seeder
{
    public function run(): void
    {
        $principals = [
            ['name' => 'Liofilchem', 'address' => 'Italy', 'logo' => '/images/vendor/liofilchem.png', 'status' => 'online'],
            ['name' => 'Bioendo', 'address' => 'China', 'logo' => '/images/vendor/Bioendo-labs.png', 'status' => 'online'],
            ['name' => 'Terragene', 'address' => 'Argentina', 'logo' => '/images/vendor/terragene.png', 'status' => 'online'],
            ['name' => 'Biotool', 'address' => 'Switzerland', 'logo' => '/images/vendor/biotool.png', 'status' => 'online'],
            ['name' => 'IFM Quality Services', 'address' => 'Australia', 'logo' => '/images/vendor/ifm.png', 'status' => 'online'],
            ['name' => 'BNF Korea', 'address' => 'South Korea', 'logo' => '/images/vendor/bnf_korea.png', 'status' => 'online'],
            ['name' => 'Leadfluid', 'address' => 'China', 'logo' => '/images/vendor/leadfluid.png', 'status' => 'online'],
            ['name' => 'Meizheng Group', 'address' => 'China', 'logo' => '/images/vendor/meizheng.png', 'status' => 'online'],
            ['name' => 'KSL Pulse Scientific', 'address' => 'India', 'logo' => '/images/vendor/ksl_pulse.png', 'status' => 'online'],
            ['name' => 'Diamidex', 'address' => 'France', 'logo' => '/images/vendor/diamidex.png', 'status' => 'online'],
            ['name' => 'Lumeley', 'address' => 'United States', 'logo' => '/images/vendor/lumeley.png', 'status' => 'online'],
            ['name' => 'Ratel Systems', 'address' => 'South Korea', 'logo' => '/images/vendor/ratel.png', 'status' => 'online'],
            ['name' => 'Solus Scientific', 'address' => 'United Kingdom', 'logo' => '/images/vendor/solus_scientific.png', 'status' => 'online'],
            ['name' => 'Vecverse', 'address' => 'Japan', 'logo' => '/images/vendor/vecverse.png', 'status' => 'online'],
            ['name' => 'Vision Med', 'address' => 'Germany', 'logo' => '/images/vendor/vision_med.png', 'status' => 'online'],
        ];

        DB::table('principals')->truncate();

        foreach ($principals as $p) {
            DB::table('principals')->insert([
                'name' => $p['name'],
                'address' => $p['address'],
                'logo' => $p['logo'],
                'status' => $p['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Seed 15 data lengkap Prinsipal / Mitra Laboratorium berhasil.');
    }
}

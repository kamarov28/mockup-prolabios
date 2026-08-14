<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedProductPricesSeeder extends Seeder
{
    public function run(): void
    {
        $products = DB::table('products')->get();

        $defaultPrices = [
            'MRS Agar' => 450000,
            'MRS Broth' => 420000,
            'Malt Extract Agar' => 480000,
            'Nutrient Agar' => 380000,
            'Baird Parker Agar' => 650000,
            'Plate Count Agar' => 410000,
            'MacConkey Agar' => 390000,
            'Thermometer' => 850000,
            'Air sampler' => 12500000,
            'Bioall - Biological Safety Cabinet' => 45000000,
        ];

        $updated = 0;

        foreach ($products as $p) {
            $price = 0;
            if (isset($defaultPrices[$p->title])) {
                $price = $defaultPrices[$p->title];
            } else {
                // Generate realistic price based on product ID
                $price = (($p->id * 37) % 25 + 15) * 25000;
            }

            DB::table('products')->where('id', $p->id)->update([
                'price' => $price,
                'stock' => 999,
                'updated_at' => now(),
            ]);
            $updated++;
        }

        $this->command->info("Berhasil menetapkan estimasi harga katalog resmi untuk {$updated} produk.");
    }
}

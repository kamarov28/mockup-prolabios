<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedProductCategoriesSeeder extends Seeder
{
    /**
     * Seed semua kategori & sub-kategori yang sebelumnya hardcoded di DataService.
     * Aman dijalankan berulang (upsert by key).
     */
    public function run(): void
    {
        $categories = [
            [
                'key'        => 'microbiology',
                'name'       => 'Microbiology',
                'sort_order' => 1,
                'subs'       => [
                    ['key' => 'food-safety',               'name' => 'Food Safety',                                    'sort_order' => 1],
                    ['key' => 'antimicrobial',              'name' => 'Antimicrobial Susceptibility Testing',           'sort_order' => 2],
                    ['key' => 'identification',             'name' => 'Microbiological Identification',                 'sort_order' => 3],
                    ['key' => 'preservation',               'name' => 'Microorganisms Preservation System (BactoBank)', 'sort_order' => 4],
                    ['key' => 'staining',                   'name' => 'Microbial Staining & Fixatives',                 'sort_order' => 5],
                    ['key' => 'consumables',                'name' => 'Consumables',                                   'sort_order' => 6],
                    ['key' => 'mic-test',                   'name' => 'MIC Test Strip',                                'sort_order' => 7],
                    ['key' => 'qc-organisms',               'name' => 'QC Organisms',                                  'sort_order' => 8],
                    ['key' => 'dip-slide',                  'name' => 'Dip slide',                                     'sort_order' => 9],
                    ['key' => 'chemical-indicator',         'name' => 'Chemical Indicator',                            'sort_order' => 10],
                    ['key' => 'latex-agglutination',        'name' => 'Latex Agglutination Kits',                      'sort_order' => 11],
                    ['key' => 'ready-to-use-culture-media', 'name' => 'Ready To Use Culture Media',                    'sort_order' => 12],
                    ['key' => 'biological-indicators',      'name' => 'Biological Indicators',                         'sort_order' => 13],
                    ['key' => 'dehydrated-culture-media',   'name' => 'Dehydrated Culture Media',                      'sort_order' => 14],
                    ['key' => 'immunology',                 'name' => 'Immunology',                                    'sort_order' => 15],
                    ['key' => 'endotoxin',                  'name' => 'Endotoxin',                                     'sort_order' => 16],
                ],
            ],
            [
                'key'        => 'reference-standards',
                'name'       => 'Reference Standards',
                'sort_order' => 2,
                'subs'       => [
                    ['key' => 'pharmaceutical',   'name' => 'Pharmaceutical Reference Standards', 'sort_order' => 1],
                    ['key' => 'green-standards',  'name' => 'Green Standards',                    'sort_order' => 2],
                    ['key' => 'environmental',    'name' => 'Environmental Standards',             'sort_order' => 3],
                    ['key' => 'food-beverages',   'name' => 'Food and Beverages Standards',        'sort_order' => 4],
                    ['key' => 'agro-chemical',    'name' => 'Agro Chemical Standards',             'sort_order' => 5],
                ],
            ],
            [
                'key'        => 'device',
                'name'       => 'Device',
                'sort_order' => 3,
                'subs'       => [
                    ['key' => 'bsc-lfc',                      'name' => 'Bio Safety Cabinet (BSC) and Laminar Flow Cabinet (LFC)', 'sort_order' => 1],
                    ['key' => 'microbiological-instruments',   'name' => 'Microbiological Instruments',                            'sort_order' => 2],
                    ['key' => 'liquid-handling',               'name' => 'Liquid Handling',                                       'sort_order' => 3],
                    ['key' => 'thermometer',                   'name' => 'Thermometer',                                           'sort_order' => 4],
                ],
            ],
            [
                'key'        => 'instruments',
                'name'       => 'Instruments',
                'sort_order' => 4,
                'subs'       => [
                    ['key' => 'liofilchem-giotto-2',         'name' => 'Liofilchem® Giotto 2',                        'sort_order' => 1],
                    ['key' => 'agar-filler',                 'name' => 'Agar Filler',                                 'sort_order' => 2],
                    ['key' => 'agar-preparator',             'name' => 'Agar Preparator',                             'sort_order' => 3],
                    ['key' => 'kinetic-incubating-reader',   'name' => 'Kinetic Incubating Microplate Reader',         'sort_order' => 4],
                    ['key' => 'mica-diamidex',               'name' => 'MICA® Diamidex - Counting Microorganisms Faster', 'sort_order' => 5],
                ],
            ],
        ];

        $now = now();

        foreach ($categories as $cat) {
            // Upsert parent
            DB::table('product_categories')->upsert(
                [['key' => $cat['key'], 'name' => $cat['name'], 'parent_id' => null, 'sort_order' => $cat['sort_order'], 'created_at' => $now, 'updated_at' => $now]],
                ['key'],
                ['name', 'sort_order', 'updated_at']
            );

            $parentId = DB::table('product_categories')->where('key', $cat['key'])->value('id');

            // Upsert children
            foreach ($cat['subs'] as $sub) {
                DB::table('product_categories')->upsert(
                    [['key' => $sub['key'], 'name' => $sub['name'], 'parent_id' => $parentId, 'sort_order' => $sub['sort_order'], 'created_at' => $now, 'updated_at' => $now]],
                    ['key'],
                    ['name', 'parent_id', 'sort_order', 'updated_at']
                );
            }
        }

        $this->command->info('✅ Product categories seeded successfully (' . count($categories) . ' parent categories).');
    }
}

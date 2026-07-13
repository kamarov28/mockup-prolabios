<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sectors
        DB::table('sectors')->insert([
            ['id' => 'farmasi', 'name' => 'Farmasi', 'description' => 'Industri farmasi dan obat-obatan'],
            ['id' => 'food-beverage', 'name' => 'Food & Beverage', 'description' => 'Industri makanan dan minuman'],
            ['id' => 'mikrobiologi', 'name' => 'Mikrobiologi', 'description' => 'Laboratorium mikrobiologi'],
        ]);

        // Create products
        DB::table('products')->insert([
            [
                'catalog' => 'MK-001',
                'title' => 'Media Kultur',
                'description' => 'Media kultur untuk kebutuhan laboratorium',
                'category' => 'consumables',
                'image' => 'https://via.placeholder.com/400x300',
            ],
            [
                'catalog' => 'IA-001',
                'title' => 'Instrumen Analitika',
                'description' => 'Instrumen analitika presisi tinggi',
                'category' => 'instruments',
                'image' => 'https://via.placeholder.com/400x300',
            ],
        ]);

        // Create posts
        DB::table('posts')->insert([
            [
                'title' => 'Training Laboratorium Terbaru',
                'content' => 'Kami mengadakan training laboratorium untuk meningkatkan kompetensi staf.',
                'category' => 'training',
                'slug' => 'training-laboratorium-terbaru',
                'date' => now()->format('Y-m-d'),
                'image' => 'https://via.placeholder.com/800x400',
            ],
            [
                'title' => 'Produk Baru: Media Kultur Premium',
                'content' => 'Kami meluncurkan produk media kultur premium dengan kualitas terjamin.',
                'category' => 'product',
                'slug' => 'produk-baru-media-kultur-premium',
                'date' => now()->format('Y-m-d'),
                'image' => 'https://via.placeholder.com/800x400',
            ],
        ]);

        // Create homepage settings (key-value pairs)
        DB::table('homepage_settings')->insert([
            ['key' => 'hero_title', 'value' => 'PROLABIOS Mitra Analitika'],
            ['key' => 'hero_subtitle', 'value' => 'Professional, Robust, Offering the best'],
            ['key' => 'contact_phone', 'value' => '0821-8792-9433'],
            ['key' => 'contact_email', 'value' => 'info@prolabios.com'],
            ['key' => 'contact_address', 'value' => 'Jakarta, Indonesia'],
            ['key' => 'contact_phone_technician', 'value' => '0812-837-4867'],
            ['key' => 'products_title', 'value' => 'Produk & Instrumen'],
            ['key' => 'products_subtitle', 'value' => 'Katalog lengkap produk laboratorium'],
            ['key' => 'sectors_title', 'value' => 'Sektor Industri'],
            ['key' => 'sectors_subtitle', 'value' => 'Solusi spesifik untuk berbagai sektor'],
            ['key' => 'services_title', 'value' => 'Layanan Kami'],
            ['key' => 'services_subtitle', 'value' => 'Dukungan purnajual dan konsultasi'],
            ['key' => 'info_title', 'value' => 'Berita & Kegiatan'],
            ['key' => 'info_subtitle', 'value' => 'Update terbaru dari Prolabios'],
            ['key' => 'contact_title', 'value' => 'Hubungi Kami'],
            ['key' => 'contact_subtitle', 'value' => 'Kami siap melayani kebutuhan Anda'],
        ]);
    }
}

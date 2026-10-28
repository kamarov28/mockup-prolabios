<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ImageWatermarkService;
use Illuminate\Console\Command;

class BakeWatermarkCommand extends Command
{
    protected $signature = 'prolabios:watermark-images {--force : Re-apply watermark to all product and editor images}';

    protected $description = 'Bake permanent Prolabios logo watermark directly into product and editor image pixels';

    public function handle(): int
    {
        $this->info('Memulai proses baking watermark Prolabios ke piksel gambar produk...');

        $processed = 0;
        $failed = 0;

        // 1. Process all product images and gallery images
        $products = Product::whereNotNull('image')->get();

        foreach ($products as $p) {
            $filesToProcess = [];
            if (! empty($p->image) && str_starts_with($p->image, '/storage/')) {
                $filesToProcess[] = public_path(ltrim($p->image, '/'));
            }

            if (! empty($p->gallery_images) && is_array($p->gallery_images)) {
                foreach ($p->gallery_images as $gImg) {
                    if (! empty($gImg) && str_starts_with($gImg, '/storage/')) {
                        $filesToProcess[] = public_path(ltrim($gImg, '/'));
                    }
                }
            }

            foreach (array_unique($filesToProcess) as $filePath) {
                if (file_exists($filePath) && is_file($filePath)) {
                    if (ImageWatermarkService::applyToFile($filePath)) {
                        $processed++;
                        $this->line(" <info>[OK]</info> Watermark baked: " . basename($filePath));
                    } else {
                        $failed++;
                        $this->warn(" [FAIL] Gagal watermark: " . basename($filePath));
                    }
                }
            }
        }

        // 2. Process all images in storage/app/public/uploads/editor
        $editorDir = storage_path('app/public/uploads/editor');
        if (is_dir($editorDir)) {
            $editorFiles = glob($editorDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
            foreach ($editorFiles as $eFile) {
                if (ImageWatermarkService::applyToFile($eFile)) {
                    $processed++;
                    $this->line(" <info>[OK Editor]</info> Watermark baked: " . basename($eFile));
                }
            }
        }

        $this->info("Selesai! {$processed} gambar berhasil diberi watermark permanen pada pikselnya. ({$failed} gagal)");

        return self::SUCCESS;
    }
}

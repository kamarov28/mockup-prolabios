<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class SyncProductSectors extends Command
{
    protected $signature = 'products:sync-sectors
                            {--chunk=100 : Rows per chunk}';

    protected $description = 'Sync product_sector pivot from legacy products.sector CSV for all products';

    public function handle(): int
    {
        $chunk = max(10, (int) $this->option('chunk'));
        $total = Product::query()->count();

        if ($total === 0) {
            $this->info('No products found.');

            return self::SUCCESS;
        }

        $this->info("Syncing sectors for {$total} products (chunk {$chunk})...");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        Product::query()->orderBy('id')->chunkById($chunk, function ($products) use ($bar) {
            foreach ($products as $product) {
                $product->syncSectorsFromCsv($product->sector);
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info('Done. product_sector pivot is in sync with CSV sector column.');

        return self::SUCCESS;
    }
}

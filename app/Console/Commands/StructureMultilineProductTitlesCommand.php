<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class StructureMultilineProductTitlesCommand extends Command
{
    protected $signature = 'prolabios:structure-titles';

    protected $description = 'Clean and structure multiline product titles by moving list items and compositions into description';

    public function handle(): int
    {
        $this->info('Memeriksa produk dengan judul multiline di basis data...');

        $products = Product::where('title', 'like', "%\n%")->get();

        if ($products->isEmpty()) {
            $this->info('Tidak ditemukan produk dengan judul multiline.');

            return self::SUCCESS;
        }

        $count = 0;
        foreach ($products as $p) {
            $rawTitle = (string) $p->title;
            $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $rawTitle)), fn ($l) => $l !== ''));

            if (count($lines) <= 1) {
                continue;
            }

            $firstLine = rtrim($lines[0], " ,:\t\n\r\0\x0B");
            $descLines = array_values(array_filter(array_slice($lines, 1), fn ($l) => ! str_starts_with(strtolower($l), 'consist of')));

            $extraHtml = '<div class="product-composition-list"><p><strong>Komposisi / Rincian:</strong></p><ul>';
            foreach ($descLines as $d) {
                $cleanD = preg_replace('/^\d+[\.\)]\s*/', '', $d);
                $extraHtml .= '<li>' . e($cleanD) . '</li>';
            }
            $extraHtml .= '</ul></div>';

            $newDescription = $extraHtml;

            $p->title = $firstLine;
            $p->description = $newDescription;
            $p->save();

            $count++;
            $this->line(" <info>[STRUKTUR]</info> CAT. {$p->catalog} -> '{$firstLine}'");
        }

        $this->info("Selesai! {$count} produk berhasil distrukturkan judul dan deskripsinya.");

        return self::SUCCESS;
    }
}

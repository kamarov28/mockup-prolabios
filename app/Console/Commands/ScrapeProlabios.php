<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use DOMDocument;
use DOMXPath;

class ScrapeProlabios extends Command
{
    protected $signature = 'scrape:prolabios';
    protected $description = 'Scrape old prolabios website for sectors and products';

    public function handle()
    {
        $this->info("Starting scraping process...");

        $sectors = [
            'brewing' => 'Brewing',
            'biomolecular' => 'Biomolecular',
            'hospital-clinic' => 'Clinical',
            'cosmetic' => 'Cosmetic',
            'dairy' => 'Dairy',
            'general-purpose' => 'General Purpose',
            'food' => 'Food & Beverage',
            'pharmaceutical' => 'Pharmaceutical',
            'water' => 'Water Treatment',
        ];

        $scrapedSectors = [];
        $allProducts = [];

        foreach ($sectors as $slug => $name) {
            $this->info("Scraping sector: $name");
            $url = "https://www.prolabios.com/sector/$slug/";
            $html = $this->fetchHtml($url);
            
            if (!$html) continue;

            $xpath = $this->getXpath($html);
            
            // Extract paragraphs for description
            $paragraphs = $xpath->query('//div[contains(@class, "post-body")]/p');
            $descriptionText = [];
            foreach ($paragraphs as $p) {
                if (trim($p->textContent) != '') {
                    $descriptionText[] = trim($p->textContent);
                }
            }

            $scrapedSectors[] = [
                'id' => $slug,
                'name' => $name,
                'description' => $descriptionText
            ];

            // Extract table products
            $rows = $xpath->query('//div[contains(@class, "post-body")]//table//tr');
            if ($rows->length > 0) {
                foreach ($rows as $index => $row) {
                    if ($index === 0) continue; // skip header
                    $cols = $xpath->query('td', $row);
                    if ($cols->length >= 3) {
                        $cat = trim($cols->item(0)->textContent);
                        $title = trim($cols->item(1)->textContent);
                        $desc = trim($cols->item(2)->textContent);
                        if ($cat && $title && $cat != 'Catalogue') {
                            $allProducts[] = [
                                'catalog' => $cat,
                                'title' => $title,
                                'description' => $desc,
                                'category' => 'culture-media microbiology',
                                'sector' => $slug
                            ];
                        }
                    }
                }
            }
        }

        // Pre-defined images for lab products from unsplash
        $images = [
            "https://images.unsplash.com/photo-1579154204601-01588f351e67?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
            "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
            "https://images.unsplash.com/photo-1614935151651-0bea6508abb0?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
            "https://images.unsplash.com/photo-1576086213369-97a306d36557?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
            "https://images.unsplash.com/photo-1581093450021-4a7360e9a6b5?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
            "https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80",
            "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
        ];

        // Assign images
        foreach ($allProducts as $i => &$prod) {
            $prod['image'] = $images[$i % count($images)];
        }

        // Save data
        if (!Storage::disk('local')->exists('data')) {
            Storage::disk('local')->makeDirectory('data');
        }
        Storage::disk('local')->put('data/sectors.json', json_encode($scrapedSectors, JSON_PRETTY_PRINT));
        Storage::disk('local')->put('data/products.json', json_encode($allProducts, JSON_PRETTY_PRINT));

        $this->info("Scraping completed! Found " . count($allProducts) . " products and " . count($scrapedSectors) . " sectors.");
    }

    private function fetchHtml($url)
    {
        try {
            $response = Http::timeout(10)->get($url);
            return $response->body();
        } catch (\Exception $e) {
            $this->error("Failed to fetch $url: " . $e->getMessage());
            return null;
        }
    }

    private function getXpath($html)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        return new DOMXPath($dom);
    }
}

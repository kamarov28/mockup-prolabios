<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectorPivotFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_sektor_page_only_lists_products_attached_via_pivot(): void
    {
        Sector::create([
            'id' => 'pharma',
            'name' => 'Pharmaceutical',
            'description' => ['Pharma sector'],
            'image' => '',
        ]);

        Sector::create([
            'id' => 'food',
            'name' => 'Food & Beverage',
            'description' => ['Food sector'],
            'image' => '',
        ]);

        $inPharma = Product::create([
            'title' => 'Endotoxin Kit Pharma',
            'catalog' => 'ETX-PH',
            'category' => 'Assay',
            'sector' => 'pharma',
            'price' => 1000000,
            'stock' => 5,
        ]);

        $inFood = Product::create([
            'title' => 'Petrifilm Food',
            'catalog' => 'PF-01',
            'category' => 'Assay',
            'sector' => 'food',
            'price' => 300000,
            'stock' => 8,
        ]);

        // CSV column should have synced pivot on save
        $this->assertTrue($inPharma->sectors()->where('sectors.id', 'pharma')->exists());
        $this->assertTrue($inFood->sectors()->where('sectors.id', 'food')->exists());

        $response = $this->get('/sektor?s=pharma');
        $response->assertOk();
        $response->assertSee('Endotoxin Kit Pharma');
        $response->assertDontSee('Petrifilm Food');

        $responseFood = $this->get('/sektor?s=food');
        $responseFood->assertOk();
        $responseFood->assertSee('Petrifilm Food');
        $responseFood->assertDontSee('Endotoxin Kit Pharma');
    }

    public function test_scope_by_sector_ignores_csv_without_pivot(): void
    {
        Sector::create([
            'id' => 'micro',
            'name' => 'Microbiology',
            'description' => ['Micro'],
            'image' => '',
        ]);

        // Create without sector CSV so pivot stays empty
        $orphan = Product::create([
            'title' => 'Orphan Product',
            'catalog' => 'ORP-1',
            'category' => 'Other',
            'price' => 1,
            'stock' => 1,
        ]);

        // Manually set CSV without triggering sync path that would attach
        // (wasChanged sector only on save after change — force CSV only in DB)
        Product::where('id', $orphan->id)->update(['sector' => 'micro']);
        $orphan->refresh();

        $this->assertFalse($orphan->sectors()->where('sectors.id', 'micro')->exists());

        $ids = Product::query()->bySector('micro')->pluck('id')->all();
        $this->assertNotContains($orphan->id, $ids);

        $orphan->syncSectorsFromCsv('micro');
        $idsAfter = Product::query()->bySector('micro')->pluck('id')->all();
        $this->assertContains($orphan->id, $idsAfter);
    }
}

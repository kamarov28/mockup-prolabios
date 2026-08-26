<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProductSectorFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::forceCreate([
            'name' => 'Admin Filter',
            'email' => 'admin-sector-filter@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => true,
        ]);

        ProductCategory::create([
            'name' => 'Assay',
            'key' => 'assay',
        ]);

        Sector::create([
            'id' => 'pharma',
            'name' => 'Pharmaceutical',
            'description' => ['Pharma'],
            'image' => '',
        ]);

        Sector::create([
            'id' => 'food',
            'name' => 'Food',
            'description' => ['Food'],
            'image' => '',
        ]);
    }

    public function test_admin_product_list_filters_by_pivot_sector(): void
    {
        $inPharma = Product::create([
            'title' => 'Pharma Only Kit',
            'catalog' => 'PH-1',
            'category' => 'assay',
            'sector' => 'pharma',
            'price' => 1,
            'stock' => 1,
        ]);

        $inFood = Product::create([
            'title' => 'Food Only Kit',
            'catalog' => 'FD-1',
            'category' => 'assay',
            'sector' => 'food',
            'price' => 1,
            'stock' => 1,
        ]);

        $this->assertTrue($inPharma->sectors()->where('sectors.id', 'pharma')->exists());

        $response = $this->actingAs($this->admin)
            ->get(route('admin.products', ['sector' => 'pharma']));

        $response->assertOk();
        $response->assertSee('Pharma Only Kit');
        $response->assertDontSee('Food Only Kit');
    }

    public function test_admin_sector_filter_ignores_csv_without_pivot(): void
    {
        $orphan = Product::create([
            'title' => 'CSV Only No Pivot',
            'catalog' => 'CSV-1',
            'category' => 'assay',
            'price' => 1,
            'stock' => 1,
        ]);

        // CSV set without going through model save sync
        Product::where('id', $orphan->id)->update(['sector' => 'pharma']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.products', ['sector' => 'pharma']));

        $response->assertOk();
        $response->assertDontSee('CSV Only No Pivot');
    }
}

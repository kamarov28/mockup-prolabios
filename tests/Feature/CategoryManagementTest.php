<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin@prolabios.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );
    }

    public function test_admin_can_view_categories_index(): void
    {
        ProductCategory::create([
            'name' => 'General Lab Equipment',
            'key' => 'general-lab',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('General Lab Equipment');
    }

    public function test_admin_can_create_parent_and_child_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Molecular Biology',
            'key' => 'molecular-biology',
            'sort_order' => 1,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $parent = ProductCategory::where('key', 'molecular-biology')->first();
        $this->assertNotNull($parent);

        $childResponse = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'PCR Reagents',
            'key' => 'pcr-reagents',
            'parent_id' => $parent->id,
            'sort_order' => 1,
        ]);

        $childResponse->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('product_categories', [
            'key' => 'pcr-reagents',
            'parent_id' => $parent->id,
        ]);
    }

    public function test_subcategory_cannot_be_parent_category(): void
    {
        $parent = ProductCategory::create([
            'name' => 'Parent Cat',
            'key' => 'parent-cat',
        ]);

        $child = ProductCategory::create([
            'name' => 'Child Cat',
            'key' => 'child-cat',
            'parent_id' => $parent->id,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Grandchild Cat',
            'key' => 'grandchild-cat',
            'parent_id' => $child->id,
        ]);

        $response->assertSessionHasErrors('parent_id');
        $this->assertDatabaseMissing('product_categories', [
            'key' => 'grandchild-cat',
        ]);
    }

    public function test_category_cannot_be_deleted_if_used_by_products(): void
    {
        $category = ProductCategory::create([
            'name' => 'Chemicals',
            'key' => 'chemicals',
        ]);

        Product::create([
            'title' => 'Ethanol 96%',
            'catalog' => 'ETH-96',
            'category' => 'chemicals',
            'price' => 150000,
            'stock' => 10,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', ['id' => $category->id]));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('product_categories', [
            'id' => $category->id,
        ]);
    }

    public function test_updating_category_key_synchronizes_products_table(): void
    {
        $category = ProductCategory::create([
            'name' => 'Old Category',
            'key' => 'old-category',
        ]);

        $product = Product::create([
            'title' => 'Sample Product',
            'catalog' => 'SMP-01',
            'category' => 'old-category',
            'price' => 50000,
            'stock' => 5,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', ['id' => $category->id]), [
            'name' => 'Updated Category',
            'key' => 'updated-category',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'category' => 'updated-category',
        ]);
    }
}

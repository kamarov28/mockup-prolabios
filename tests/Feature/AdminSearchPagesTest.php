<?php

namespace Tests\Feature;

use App\Models\ProductCategory;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSearchPagesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::forceCreate([
            'name' => 'Admin Search Tester',
            'email' => 'admin-search@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);

        ProductCategory::create([
            'name' => 'Microbiology Test',
            'key' => 'microbiology-test',
        ]);

        Sector::create([
            'id' => 'pharma',
            'name' => 'Pharmaceutical Test',
        ]);
    }

    public function test_all_admin_index_pages_render_standard_search_input(): void
    {
        $routes = [
            'admin.sectors',
            'admin.categories.index',
            'admin.principals',
            'admin.posts',
            'admin.products',
            'admin.rfqs.index',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($this->admin)->get(route($route));
            $response->assertStatus(200);
            $response->assertSee('admin-search-input');
            $response->assertSee('admin-page-title');
            $response->assertSee('admin-page-label');
        }
    }
}

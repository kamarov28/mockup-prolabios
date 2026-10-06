<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    private User $salesAdmin;

    private User $catalogAdmin;

    private User $contentAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::forceCreate([
            'name' => 'Super Administrator',
            'email' => 'super@prolabios.com',
            'password' => Hash::make('Secret123!'),
            'is_admin' => true,
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $this->salesAdmin = User::forceCreate([
            'name' => 'Sales Officer',
            'email' => 'sales@prolabios.com',
            'password' => Hash::make('Secret123!'),
            'is_admin' => true,
            'role' => User::ROLE_SALES,
        ]);

        $this->catalogAdmin = User::forceCreate([
            'name' => 'Catalog Specialist',
            'email' => 'catalog@prolabios.com',
            'password' => Hash::make('Secret123!'),
            'is_admin' => true,
            'role' => User::ROLE_CATALOG,
        ]);

        $this->contentAdmin = User::forceCreate([
            'name' => 'Content Writer',
            'email' => 'content@prolabios.com',
            'password' => Hash::make('Secret123!'),
            'is_admin' => true,
            'role' => User::ROLE_CONTENT,
        ]);
    }

    public function test_super_admin_can_access_all_modules_and_user_management(): void
    {
        $this->actingAs($this->superAdmin);

        $this->get('/admin')->assertOk();
        $this->get('/admin/rfqs')->assertOk();
        $this->get('/admin/home?section=homepage')->assertOk();
        $this->get('/admin/users')->assertOk();
        $this->get('/admin/products')->assertOk();
        $this->get('/admin/categories')->assertOk();
        $this->get('/admin/posts')->assertOk();
        $this->get('/admin/sectors')->assertOk();
        $this->get('/admin/principals')->assertOk();
    }

    public function test_sales_admin_can_access_rfqs_and_view_catalog_but_forbidden_from_editing_and_system(): void
    {
        $this->actingAs($this->salesAdmin);

        // Allowed
        $this->get('/admin')->assertOk();
        $this->get('/admin/rfqs')->assertOk();
        $this->get('/admin/products')->assertOk();

        // Forbidden (403)
        $this->get('/admin/products/create')->assertForbidden();
        $this->get('/admin/categories')->assertForbidden();
        $this->get('/admin/sectors')->assertForbidden();
        $this->get('/admin/principals')->assertForbidden();
        $this->get('/admin/posts')->assertForbidden();
        $this->get('/admin/home?section=homepage')->assertForbidden();
        $this->get('/admin/users')->assertForbidden();
    }

    public function test_catalog_admin_can_manage_products_but_forbidden_from_rfqs_and_settings(): void
    {
        $this->actingAs($this->catalogAdmin);

        // Allowed
        $this->get('/admin')->assertOk();
        $this->get('/admin/products')->assertOk();
        $this->get('/admin/products/create')->assertOk();
        $this->get('/admin/categories')->assertOk();
        $this->get('/admin/sectors')->assertOk();
        $this->get('/admin/principals')->assertOk();

        // Forbidden (403)
        $this->get('/admin/rfqs')->assertForbidden();
        $this->get('/admin/posts')->assertForbidden();
        $this->get('/admin/home?section=homepage')->assertForbidden();
        $this->get('/admin/users')->assertForbidden();
    }

    public function test_content_admin_can_manage_posts_but_forbidden_from_products_and_rfqs(): void
    {
        $this->actingAs($this->contentAdmin);

        // Allowed
        $this->get('/admin')->assertOk();
        $this->get('/admin/posts')->assertOk();
        $this->get('/admin/posts/create')->assertOk();

        // Forbidden (403)
        $this->get('/admin/rfqs')->assertForbidden();
        $this->get('/admin/products')->assertForbidden();
        $this->get('/admin/products/create')->assertForbidden();
        $this->get('/admin/categories')->assertForbidden();
        $this->get('/admin/home?section=homepage')->assertForbidden();
        $this->get('/admin/users')->assertForbidden();
    }

    public function test_super_admin_cannot_delete_own_account_or_last_super_admin(): void
    {
        $this->actingAs($this->superAdmin);

        // Attempting to delete own account
        $response = $this->delete('/admin/users/'.$this->superAdmin->id);
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->superAdmin->id]);

        // Attempting to delete another admin succeeds
        $response2 = $this->delete('/admin/users/'.$this->salesAdmin->id);
        $response2->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $this->salesAdmin->id]);
    }

    public function test_super_admin_can_create_new_role_specific_admin(): void
    {
        $this->actingAs($this->superAdmin);

        $response = $this->post('/admin/users', [
            'name' => 'New Product Specialist',
            'email' => 'specialist@prolabios.com',
            'role' => User::ROLE_CATALOG,
            'password' => 'SecurePass123',
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New Product Specialist',
            'email' => 'specialist@prolabios.com',
            'role' => User::ROLE_CATALOG,
            'is_admin' => true,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_valid_credentials()
    {
        $admin = User::factory()->create([
            'email' => 'admin@prolabios.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@prolabios.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_non_admin_cannot_access_admin_panel()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_logout()
    {
        $admin = User::factory()->create([
            'email' => 'admin@prolabios.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->post('/admin/logout')
            ->assertRedirect('/admin/login');

        $this->assertGuest();
    }

    public function test_login_requires_valid_email()
    {
        $response = $this->post('/admin/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_login_requires_password()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@prolabios.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors();
    }
}

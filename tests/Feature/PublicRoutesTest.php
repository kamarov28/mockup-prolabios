<?php

namespace Tests\Feature;

use App\Services\DataService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\TestDataSeeder::class);
    }

    public function test_homepage_loads_successfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
    }

    public function test_profil_page_loads_successfully()
    {
        $response = $this->get('/profil');

        $response->assertStatus(200);
        $response->assertViewIs('profil');
    }

    public function test_produk_page_loads_successfully()
    {
        $response = $this->get('/produk');

        $response->assertStatus(200);
        $response->assertViewIs('produk');
    }

    public function test_sektor_page_loads_successfully()
    {
        $response = $this->get('/sektor');

        $response->assertStatus(200);
        $response->assertViewIs('sektor');
    }

    public function test_layanan_page_loads_successfully()
    {
        $response = $this->get('/layanan');

        $response->assertStatus(200);
        $response->assertViewIs('layanan');
    }

    public function test_informasi_page_loads_successfully()
    {
        $response = $this->get('/informasi');

        $response->assertStatus(200);
        $response->assertViewIs('informasi');
    }

    public function test_kontak_page_loads_successfully()
    {
        $response = $this->get('/kontak');

        $response->assertStatus(200);
        $response->assertViewIs('kontak');
    }

    public function test_admin_login_page_loads_successfully()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertViewIs('admin.login');
    }

    public function test_admin_dashboard_requires_authentication()
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }
}

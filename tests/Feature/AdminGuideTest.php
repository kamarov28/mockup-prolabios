<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminGuideTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::forceCreate([
            'name' => 'Admin Guide Test',
            'email' => 'admin-guide@example.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);
    }

    public function test_guest_cannot_access_admin_guide(): void
    {
        $response = $this->get(route('admin.guide'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_guide_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.guide'));

        $response->assertStatus(200);
        $response->assertSee('Panduan &amp; Dokumentasi Admin Prolabios', false);
        $response->assertSee('Manajemen Pengajuan RFQ');
        $response->assertSee('Tampilan Kanban &amp; Tabel', false);
        $response->assertSee('Ekspor Spreadsheet (.xlsx)');
        $response->assertSee('Impor Massal Excel');
        $response->assertSee('Template Excel');
        $response->assertSee('Hierarki Kategori &amp; Sektor Industri', false);
        $response->assertSee('Live Search Instan');
        $response->assertSee('Artikel Berita &amp; Prinsipal Laboratorium', false);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SectorManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin@prolabios.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );
    }

    public function test_public_sector_page_displays_sectors(): void
    {
        Sector::create([
            'id'          => 'pharma',
            'name'        => 'Pharmaceutical',
            'description' => ['Paragraph 1', 'Paragraph 2'],
            'image'       => 'https://example.com/pharma.jpg',
        ]);

        $response = $this->get('/sektor?s=pharma');
        $response->assertStatus(200);
        $response->assertSee('Pharmaceutical');
        $response->assertSee('Paragraph 1');
    }

    public function test_admin_can_create_update_and_delete_sector(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.sectors.store'), [
            'id'          => 'biotech',
            'name'        => 'Biotechnology',
            'description' => "Intro to biotech\nApplications in labs",
        ]);

        $response->assertRedirect(route('admin.sectors'));
        $this->assertDatabaseHas('sectors', [
            'id'   => 'biotech',
            'name' => 'Biotechnology',
        ]);

        $sector = Sector::find('biotech');
        $this->assertIsArray($sector->description);
        $this->assertEquals(['Intro to biotech', 'Applications in labs'], $sector->description);

        // 2. Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.sectors.update', ['id' => 'biotech']), [
            'name'        => 'Biotechnology & Genomics',
            'description' => 'Updated description line',
        ]);

        $updateResponse->assertRedirect(route('admin.sectors'));
        $this->assertDatabaseHas('sectors', [
            'id'   => 'biotech',
            'name' => 'Biotechnology & Genomics',
        ]);

        // 3. Delete
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.sectors.destroy', ['id' => 'biotech']));
        $deleteResponse->assertRedirect(route('admin.sectors'));
        $this->assertDatabaseMissing('sectors', ['id' => 'biotech']);
    }
}

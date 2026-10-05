<?php

namespace Tests\Feature;

use App\Models\Principal;
use App\Models\User;
use Database\Seeders\SeedPrincipalsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PrincipalManagementTest extends TestCase
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

    public function test_seed_principals_seeder_populates_accurate_countries(): void
    {
        $this->seed(SeedPrincipalsSeeder::class);

        $expected = [
            'Liofilchem' => 'Italy',
            'Bioendo' => 'China',
            'Terragene' => 'Argentina',
            'Biotool' => 'Switzerland',
            'IFM Quality Services' => 'Australia',
            'BNF Korea' => 'South Korea',
            'Leadfluid' => 'China',
            'Meizheng Group' => 'China',
            'KSL Pulse Scientific' => 'Canada',
            'Diamidex' => 'France',
            'Lumeley' => 'China',
            'Ratel Systems' => 'South Korea',
            'Solus Scientific' => 'United Kingdom',
            'Vecverse' => 'China',
            'Vision Med' => 'China',
        ];

        foreach ($expected as $name => $country) {
            $this->assertDatabaseHas('principals', [
                'name' => $name,
                'address' => $country,
                'status' => 'online',
            ]);
        }
    }

    public function test_admin_can_view_principals_page(): void
    {
        Principal::create([
            'name' => 'Test Principal',
            'address' => 'Germany',
            'status' => 'online',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.principals'));
        $response->assertStatus(200);
        $response->assertSee('Test Principal');
        $response->assertSee('Germany');
    }
}

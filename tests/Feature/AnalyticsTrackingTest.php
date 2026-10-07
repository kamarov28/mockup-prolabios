<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AnalyticsTrackingTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::forceCreate([
            'name' => 'Super Administrator',
            'email' => 'super@prolabios.com',
            'password' => Hash::make('Secret123!'),
            'is_admin' => true,
            'role' => User::ROLE_SUPER_ADMIN,
        ]);
    }

    public function test_google_analytics_renders_when_configured_via_config(): void
    {
        Config::set('services.google_analytics_id', 'G-GV3C1L8QVZ');

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('https://www.googletagmanager.com/gtag/js?id=G-GV3C1L8QVZ', false);
        $response->assertSee("gtag('config', 'G-GV3C1L8QVZ');", false);
    }

    public function test_google_analytics_does_not_render_when_empty(): void
    {
        Config::set('services.google_analytics_id', null);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertDontSee('https://www.googletagmanager.com/gtag/js', false);
    }

    public function test_admin_can_update_google_analytics_measurement_id(): void
    {
        Config::set('services.google_analytics_id', null);

        $response = $this->actingAs($this->admin)->post('/admin/home', [
            'section' => 'general',
            'google_analytics_id' => 'G-GV3C1L8QVZ',
            'company_name' => 'PT. Prolabios Mitra Analitika',
        ]);

        $response->assertRedirect('/admin/home?section=general');
        $response->assertSessionHas('success');

        // Check in public homepage
        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSee('https://www.googletagmanager.com/gtag/js?id=G-GV3C1L8QVZ', false);
        $homeResponse->assertSee("gtag('config', 'G-GV3C1L8QVZ');", false);

        // Check in admin settings view
        $editResponse = $this->actingAs($this->admin)->get('/admin/home?section=general');
        $editResponse->assertOk();
        $editResponse->assertSee('value="G-GV3C1L8QVZ"', false);
    }
}

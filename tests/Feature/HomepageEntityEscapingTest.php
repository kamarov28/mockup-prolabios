<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HomepageEntityEscapingTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::forceCreate([
            'name' => 'Admin User',
            'email' => 'admin-editor@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
    }

    public function test_sector_title_with_ampersand_does_not_render_as_amp_entity_on_homepage(): void
    {
        // 1. Admin saves title containing a plain ampersand
        $response = $this->actingAs($this->admin)->post('/admin/home', [
            'section' => 'homepage',
            'sector_title_pharma' => 'Uji Endotoksin & Validasi Sterilisasi',
            'sector_tag_pharma' => 'FARMASI & KOSMETIK',
            'sector_desc_pharma' => 'Deskripsi pengujian farmasi & kosmetik.',
            'sector_link_pharma' => '/sektor',
        ]);

        $response->assertRedirect();

        // 2. Public homepage should display literal & and NOT &amp; in the title
        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSee('Uji Endotoksin &amp; Validasi Sterilisasi', false); // In raw HTML, &amp; is decoded by browser to &
        $homeResponse->assertDontSee('Uji Endotoksin &amp;amp; Validasi Sterilisasi', false);

        // 3. Admin editor input field should display 'Uji Endotoksin & Validasi Sterilisasi' and NOT &amp;
        $adminResponse = $this->actingAs($this->admin)->get('/admin/home?section=homepage&tab=sector');
        $adminResponse->assertOk();
        $adminResponse->assertSee('value="Uji Endotoksin &amp; Validasi Sterilisasi"', false); // In HTML input value attribute, & is properly encoded to &amp; once, so browser renders &
        $adminResponse->assertDontSee('value="Uji Endotoksin &amp;amp; Validasi Sterilisasi"', false);
    }

    public function test_sector_title_normalizes_double_encoded_amp_entities(): void
    {
        // 1. If admin submits title that already contains &amp;
        $response = $this->actingAs($this->admin)->post('/admin/home', [
            'section' => 'homepage',
            'sector_title_pharma' => 'Uji Endotoksin &amp; Validasi Sterilisasi',
            'sector_tag_pharma' => 'FARMASI & KOSMETIK',
            'sector_desc_pharma' => 'Deskripsi pengujian.',
            'sector_link_pharma' => '/sektor',
        ]);

        $response->assertRedirect();

        // 2. Public homepage must not contain &amp;amp;
        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertDontSee('&amp;amp;', false);

        // 3. Admin editor must not contain &amp;amp;
        $adminResponse = $this->actingAs($this->admin)->get('/admin/home?section=homepage&tab=sector');
        $adminResponse->assertOk();
        $adminResponse->assertDontSee('&amp;amp;', false);
    }

    public function test_sector_title_still_supports_accent_span(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/home', [
            'section' => 'homepage',
            'sector_title_pharma' => 'Pengujian Endotoksin & <span class="text-accent">Validasi Sterilisasi</span>',
            'sector_tag_pharma' => 'FARMASI',
            'sector_desc_pharma' => 'Deskripsi',
            'sector_link_pharma' => '/sektor',
        ]);

        $response->assertRedirect();

        $homeResponse = $this->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSee('<span class="text-accent">Validasi Sterilisasi</span>', false);
    }
}

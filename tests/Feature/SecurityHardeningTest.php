<?php

namespace Tests\Feature;

use App\Enums\PostStatus;
use App\Http\Middleware\ForceHttps;
use App\Jobs\SendContactEmailJob;
use App\Models\Post;
use App\Models\Product;
use App\Models\Rfq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_admin_attribute_is_guarded_against_mass_assignment(): void
    {
        $user = User::create([
            'name' => 'Attacker User',
            'email' => 'attacker@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => true,
        ]);

        // is_admin must NOT be set to true through mass-assignment
        $this->assertFalse((bool) $user->fresh()->is_admin);
    }

    public function test_rfq_honeypot_drops_bot_submissions_without_creating_records(): void
    {
        Queue::fake();

        $product = Product::create([
            'title' => 'Sample Equipment',
            'catalog' => 'EQ-01',
            'category' => 'Equipment',
            'price' => 1500000,
            'stock' => 5,
        ]);

        $response = $this->withSession([
            'cart' => [
                (string) $product->id => [
                    'id' => $product->id,
                    'title' => $product->title,
                    'catalog' => $product->catalog,
                    'price' => $product->price,
                    'quantity' => 1,
                ],
            ],
        ])->post(route('rfq.store'), [
            '_hp_website' => 'http://spam-bot-link.com',
            'name' => 'Bot Spammer',
            'email' => 'bot@spammer.com',
            'company_name' => 'Spam Inc',
            'phone_wa' => '081234567890',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertEquals(0, Rfq::count());
        Queue::assertNothingPushed();
    }

    public function test_contact_honeypot_drops_bot_submissions_without_dispatching_jobs(): void
    {
        Queue::fake();

        $response = $this->postJson(route('contact.submit'), [
            '_hp_website' => 'http://spam-bot-link.com',
            'nama' => 'Spam Bot',
            'email' => 'spambot@example.com',
            'subjek' => 'inquiry',
            'pesan' => 'Buy cheap pills!',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        Queue::assertNothingPushed();
    }

    public function test_contact_form_submission_success_with_personal_credentials_and_institution(): void
    {
        Queue::fake();

        $response = $this->postJson(route('contact.submit'), [
            'nama' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'telepon' => '081234567890',
            'perusahaan' => 'Universitas Indonesia',
            'subjek' => 'inquiry',
            'pesan' => 'Mohon informasi spesifikasi mikroskop.',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        Queue::assertPushed(SendContactEmailJob::class);
    }

    public function test_contact_form_rejects_quotation_and_missing_institution(): void
    {
        Queue::fake();

        // Quotation is prohibited on general contact form
        $responseQuot = $this->postJson(route('contact.submit'), [
            'nama' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'perusahaan' => 'PT Lab',
            'subjek' => 'quotation',
            'pesan' => 'Minta penawaran.',
        ]);
        $responseQuot->assertStatus(422);
        $responseQuot->assertJsonValidationErrors(['subjek']);

        // Missing company/institution is rejected
        $responsePerusahaan = $this->postJson(route('contact.submit'), [
            'nama' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'subjek' => 'inquiry',
            'pesan' => 'Tanya produk.',
        ]);
        $responsePerusahaan->assertStatus(422);
        $responsePerusahaan->assertJsonValidationErrors(['perusahaan']);
    }

    public function test_security_headers_include_csp_and_exclude_deprecated_headers(): void
    {
        $response = $this->get(route('home'));

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Content-Security-Policy');
        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringNotContainsString("'unsafe-eval'", $csp);

        // Verify that script-src uses a cryptographic nonce and no longer permits 'unsafe-inline'
        $this->assertMatchesRegularExpression("/script-src[^;]*'nonce-[A-Za-z0-9+\/]+=*'/", $csp);
        $this->assertDoesNotMatchRegularExpression("/script-src[^;]*'unsafe-inline'/", $csp);

        $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
        $this->assertNull($response->headers->get('X-XSS-Protection'));
    }

    public function test_json_ld_and_all_scripts_on_product_detail_page_include_csp_nonce(): void
    {
        $product = Product::create([
            'title' => 'Sample Spectrophotometer',
            'catalog' => 'SPEC-100',
            'category' => 'Instruments',
            'price' => 5000000,
            'stock' => 10,
        ]);

        $response = $this->get(route('produk.detail', ['slug' => $product->slug]));
        $response->assertStatus(200);

        $html = $response->getContent();

        // Extract CSP nonce from response header
        $csp = $response->headers->get('Content-Security-Policy');
        preg_match("/'nonce-([A-Za-z0-9+\/]+=*)'/", $csp, $matches);
        $this->assertNotEmpty($matches[1], 'CSP header must contain a nonce');
        $expectedNonce = $matches[1];

        // Ensure every <script tag has nonce="..." matching the header
        preg_match_all('/<script\b(?![^>]*\btype=["\']text\/html["\'])[^>]*>/i', $html, $scriptTags);
        $this->assertNotEmpty($scriptTags[0], 'Product detail page must contain script tags');

        foreach ($scriptTags[0] as $tag) {
            $this->assertStringContainsString(
                'nonce="'.$expectedNonce.'"',
                $tag,
                "Script tag is missing or has incorrect CSP nonce: {$tag}"
            );
        }
    }

    public function test_custom_404_error_page_renders_cleanly_without_information_disclosure(): void
    {
        $response = $this->get('/non-existent-route-for-testing-404-handling');

        $response->assertStatus(404);
        $response->assertSeeText('Halaman Tidak Ditemukan');
        $response->assertSeeText('Error 404');
        $response->assertDontSeeText('Whoops');
        $response->assertDontSeeText('Stack trace');
    }

    public function test_system_health_endpoint_returns_operational_status(): void
    {
        $response = $this->get(route('system.health'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'environment',
            'timestamp',
            'checks' => [
                'database',
                'queue',
                'cache',
                'storage',
            ],
        ]);
        $this->assertEquals('healthy', $response->json('status'));
        $this->assertEquals('connected', $response->json('checks.database'));
    }

    public function test_force_https_prevents_protocol_relative_open_redirect(): void
    {
        $middleware = new ForceHttps;
        $this->app['env'] = 'production';

        $request = Request::create('http://localhost//evil.com/phish', 'GET');
        $response = $middleware->handle($request, fn () => response('ok'));

        $this->assertTrue($response->isRedirection());
        $targetUrl = $response->headers->get('Location');
        $this->assertEquals(url('/evil.com/phish', [], true), $targetUrl);
    }

    public function test_admin_login_rate_limiter_normalizes_username(): void
    {
        RateLimiter::clear('admin-login');

        for ($i = 0; $i < 5; $i++) {
            $this->from(route('admin.login'))->post(route('admin.login'), [
                'username' => 'Admin '.str_repeat(' ', $i),
                'password' => 'wrong-password',
            ]);
        }

        // 6th attempt with uppercase and leading whitespace should hit the same throttle limit
        $response = $this->from(route('admin.login'))->post(route('admin.login'), [
            'username' => '  ADMIN',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertStringContainsString('Terlalu banyak percobaan login', session('errors')->first('login'));
    }

    public function test_admin_login_rate_limiter_cannot_be_bypassed_with_spoofed_x_forwarded_for(): void
    {
        RateLimiter::clear('admin-login');

        // Attacker rotates spoofed IP headers on each request
        for ($i = 1; $i <= 5; $i++) {
            $this->from(route('admin.login'))
                ->withHeaders(['X-Forwarded-For' => "203.0.113.{$i}"])
                ->post(route('admin.login'), [
                    'username' => 'target-admin',
                    'password' => 'wrong-pass',
                ]);
        }

        // 6th attempt with another spoofed IP must still be blocked by username rate limit
        $response = $this->from(route('admin.login'))
            ->withHeaders(['X-Forwarded-For' => '198.51.100.99'])
            ->post(route('admin.login'), [
                'username' => 'target-admin',
                'password' => 'wrong-pass',
            ]);

        $response->assertSessionHasErrors('login');
        $this->assertStringContainsString('Terlalu banyak percobaan login', session('errors')->first('login'));
    }

    public function test_unauthenticated_user_cannot_access_draft_or_scheduled_post(): void
    {
        $draftPost = Post::create([
            'slug' => 'confidential-internal-draft',
            'title' => 'Internal Draft Announcement',
            'date' => now()->subDay()->toDateString(),
            'category' => 'Berita',
            'status' => PostStatus::Draft,
            'content' => '<p>Confidential content</p>',
        ]);

        $scheduledPost = Post::create([
            'slug' => 'future-press-release',
            'title' => 'Embargoed Press Release',
            'date' => now()->addDays(5)->toDateString(),
            'category' => 'Berita',
            'status' => PostStatus::Online,
            'content' => '<p>Future content</p>',
        ]);

        // Unauthenticated guest must receive 404
        $this->get('/informasi/confidential-internal-draft')->assertStatus(404);
        $this->get('/informasi?detail=confidential-internal-draft')->assertStatus(404);
        $this->get('/informasi/future-press-release')->assertStatus(404);

        // Authenticated admin can preview draft and scheduled posts
        $admin = User::forceCreate([
            'name' => 'Admin User',
            'email' => 'admin-preview@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin)->get('/informasi/confidential-internal-draft')->assertStatus(200);
        $this->actingAs($admin)->get('/informasi/future-press-release')->assertStatus(200);
    }

    public function test_force_https_does_not_trust_untrusted_x_forwarded_proto_header(): void
    {
        $middleware = new ForceHttps;
        $this->app['env'] = 'production';

        // Direct request with untrusted spoofed X-Forwarded-Proto: https
        $request = Request::create('http://localhost/admin/login', 'GET');
        $request->headers->set('X-Forwarded-Proto', 'https');

        $response = $middleware->handle($request, fn () => response('ok'));

        $this->assertTrue($response->isRedirection());
        $this->assertStringStartsWith('https://', $response->headers->get('Location'));
    }

    public function test_homepage_sector_title_is_sanitized_against_xss(): void
    {
        $admin = User::forceCreate([
            'name' => 'Admin User',
            'email' => 'admin-xss@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $xssPayload = '<img src=x onerror=alert(1)>Judul Sektor';

        $response = $this->actingAs($admin)->post('/admin/home', [
            'section' => 'homepage',
            'sector_title_pharma' => $xssPayload,
            'sector_tag_pharma' => 'FARMASI',
            'sector_desc_pharma' => 'Deskripsi pharma',
            'sector_link_pharma' => '/sektor',
        ]);

        $response->assertRedirect();

        // Check homepage rendered output: script/img tags must not be executed unescaped
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertDontSee('<img src=x onerror=alert(1)>', false);
    }
}

<?php

namespace Tests\Feature;

use App\Jobs\SendContactEmailJob;
use App\Jobs\SendRfqSubmittedEmailJob;
use App\Models\Product;
use App\Models\Rfq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
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

    public function test_security_headers_include_csp_and_exclude_deprecated_headers(): void
    {
        $response = $this->get(route('home'));

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Content-Security-Policy');
        $this->assertStringContainsString("default-src 'self'", $response->headers->get('Content-Security-Policy'));
        $this->assertNull($response->headers->get('X-XSS-Protection'));
    }
}

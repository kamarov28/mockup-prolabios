<?php

namespace Tests\Feature;

use App\Jobs\SendNewsletterBroadcastJob;
use App\Mail\NewsletterBroadcastMail;
use App\Models\NewsletterBroadcast;
use App\Models\NewsletterSubscriber;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NewsletterSectorBroadcastTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Sector $clinical;
    private Sector $pharma;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin@prolabios.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'role' => 'admin',
            ]
        );

        $this->clinical = Sector::create([
            'id' => 'clinical-hospital',
            'name' => 'Clinical & Hospital Diagnostics',
        ]);

        $this->pharma = Sector::create([
            'id' => 'biopharma',
            'name' => 'Biopharma & Biotech',
        ]);
    }

    public function test_guest_can_signup_for_newsletter_with_targeted_sectors(): void
    {
        $response = $this->post(route('newsletter.signup.submit'), [
            'email' => 'analyst.clinical@hospital.co.id',
            'first_name' => 'Budi',
            'last_name' => 'Santoso',
            'company' => 'RSUD Sentra Diagnostika',
            'city' => 'Jakarta',
            'country' => 'Indonesia',
            'sectors' => [$this->clinical->id],
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'privacy_consent' => '1',
        ]);

        $response->assertRedirect(route('newsletter.account'));
        $this->assertTrue(Auth::guard('subscriber')->check());

        $subscriber = NewsletterSubscriber::where('email', 'analyst.clinical@hospital.co.id')->first();
        $this->assertNotNull($subscriber);
        $this->assertEquals('Budi Santoso', $subscriber->full_name);
        $this->assertTrue($subscriber->sectors->contains('id', 'clinical-hospital'));
        $this->assertFalse($subscriber->sectors->contains('id', 'biopharma'));
        $this->assertNotEmpty($subscriber->unsubscribe_token);
    }

    public function test_subscriber_can_login_and_update_sector_preferences(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'qc.lead@pharma.com',
            'first_name' => 'Siti',
            'last_name' => 'Rahma',
            'company' => 'PT. Bio Farma Analitika',
            'password' => Hash::make('Secret123!'),
            'is_active' => true,
        ]);
        $subscriber->sectors()->sync([$this->pharma->id]);

        // Login
        $loginResponse = $this->post(route('newsletter.login.submit'), [
            'email' => 'qc.lead@pharma.com',
            'password' => 'Secret123!',
        ]);

        $loginResponse->assertRedirect(route('newsletter.account'));
        $this->assertTrue(Auth::guard('subscriber')->check());

        // Update preferences to include Clinical as well
        $updateResponse = $this->actingAs($subscriber, 'subscriber')
            ->post(route('newsletter.account.update'), [
                'first_name' => 'Siti',
                'last_name' => 'Rahmawati',
                'company' => 'PT. Bio Farma Analitika Tbk',
                'sectors' => [$this->pharma->id, $this->clinical->id],
            ]);

        $updateResponse->assertSessionHas('success');

        $subscriber->refresh();
        $this->assertEquals('Siti Rahmawati', $subscriber->full_name);
        $this->assertTrue($subscriber->sectors->contains('id', 'clinical-hospital'));
        $this->assertTrue($subscriber->sectors->contains('id', 'biopharma'));
    }

    public function test_subscriber_can_unsubscribe_via_safe_token(): void
    {
        $subscriber = NewsletterSubscriber::create([
            'email' => 'optout@lab.com',
            'first_name' => 'John',
            'company' => 'Private Lab',
            'password' => Hash::make('Secret123!'),
            'is_active' => true,
        ]);

        $this->assertTrue($subscriber->is_active);

        $response = $this->get(route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]));
        $response->assertStatus(200);

        $subscriber->refresh();
        $this->assertFalse($subscriber->is_active);
    }

    public function test_admin_can_broadcast_targeted_newsletter_to_specific_sector(): void
    {
        Queue::fake();

        $subClinical = NewsletterSubscriber::create([
            'email' => 'dr.clinical@rs.com',
            'first_name' => 'Dr. Andi',
            'company' => 'RS Paru',
            'password' => Hash::make('Pass1234!'),
            'is_active' => true,
        ]);
        $subClinical->sectors()->sync([$this->clinical->id]);

        $response = $this->actingAs($this->admin)->post(route('admin.newsletter.send'), [
            'subject' => 'Pembaruan Reagen Diagnostik Clinical 2026',
            'target_sector_id' => $this->clinical->id,
            'type' => 'product_release',
            'content' => '<p>Telah hadir kit diagnostik terbaru untuk mikrobiologi klinis.</p>',
        ]);

        $response->assertRedirect(route('admin.newsletter.index'));
        $response->assertSessionHas('success');

        $broadcast = NewsletterBroadcast::where('subject', 'Pembaruan Reagen Diagnostik Clinical 2026')->first();
        $this->assertNotNull($broadcast);
        $this->assertEquals($this->clinical->id, $broadcast->target_sector_id);

        Queue::assertPushed(SendNewsletterBroadcastJob::class, function ($job) use ($broadcast) {
            return $job->broadcast->id === $broadcast->id;
        });
    }

    public function test_broadcast_queue_job_delivers_mail_only_to_targeted_sector(): void
    {
        Mail::fake();

        // 1. Clinical subscriber
        $subClinical = NewsletterSubscriber::create([
            'email' => 'target.clinical@rs.com',
            'first_name' => 'Analyst Clinical',
            'company' => 'RS Klinis',
            'password' => Hash::make('Pass1234!'),
            'is_active' => true,
        ]);
        $subClinical->sectors()->sync([$this->clinical->id]);

        // 2. Pharma subscriber (should NOT receive clinical broadcast)
        $subPharma = NewsletterSubscriber::create([
            'email' => 'other.pharma@pharma.com',
            'first_name' => 'Analyst Pharma',
            'company' => 'PT Pharma',
            'password' => Hash::make('Pass1234!'),
            'is_active' => true,
        ]);
        $subPharma->sectors()->sync([$this->pharma->id]);

        $broadcast = NewsletterBroadcast::create([
            'subject' => 'Exclusive Clinical Bulletin',
            'target_sector_id' => $this->clinical->id,
            'type' => 'regulatory_update',
            'content' => '<p>Clinical update only.</p>',
        ]);

        $job = new SendNewsletterBroadcastJob($broadcast);
        $job->handle();

        // Mail sent to clinical
        Mail::assertSent(NewsletterBroadcastMail::class, function ($mail) {
            return $mail->hasTo('target.clinical@rs.com');
        });

        // Mail NOT sent to pharma
        Mail::assertNotSent(NewsletterBroadcastMail::class, function ($mail) {
            return $mail->hasTo('other.pharma@pharma.com');
        });

        $broadcast->refresh();
        $this->assertEquals(1, $broadcast->recipients_count);
        $this->assertNotNull($broadcast->sent_at);
    }
}

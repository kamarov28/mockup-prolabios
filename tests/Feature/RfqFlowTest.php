<?php

namespace Tests\Feature;

use App\Jobs\SendRfqCustomerReceiptEmailJob;
use App\Jobs\SendRfqSubmittedEmailJob;
use App\Models\Product;
use App\Models\Rfq;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RfqFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'title' => 'Nutrient Agar 500g',
            'catalog' => 'NA-500',
            'description' => 'Standard nutrient agar.',
            'category' => 'Culture Media',
            'sub_category' => 'Dehydrated Culture Media',
            'sector' => 'Microbiology',
            'price' => 250000,
            'stock' => 10,
            'image' => 'https://example.com/na.jpg',
        ]);
    }

    public function test_checkout_page_redirects_when_cart_is_empty(): void
    {
        $response = $this->get(route('rfq.checkout'));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', 'Keranjang belanja Anda masih kosong.');
    }

    public function test_checkout_page_loads_with_cart_items(): void
    {
        $id = $this->product->id;

        $response = $this->withSession([
            'cart' => [
                (string) $id => [
                    'id' => $id,
                    'title' => 'Nutrient Agar 500g',
                    'catalog' => 'NA-500',
                    'image' => 'https://example.com/na.jpg',
                    'price' => 250000,
                    'stock' => 10,
                    'quantity' => 2,
                ],
            ],
        ])->get(route('rfq.checkout'));

        $response->assertStatus(200);
        $response->assertSee('Nutrient Agar 500g');
        $response->assertSee('500.000');
    }

    public function test_can_submit_rfq_successfully_and_dispatch_jobs(): void
    {
        Queue::fake();

        $id = $this->product->id;

        $response = $this->withSession([
            'cart' => [
                (string) $id => [
                    'id' => $id,
                    'title' => 'Nutrient Agar 500g',
                    'catalog' => 'NA-500',
                    'image' => 'https://example.com/na.jpg',
                    'price' => 250000,
                    'stock' => 10,
                    'quantity' => 3,
                ],
            ],
        ])->post(route('rfq.store'), [
            'name' => 'Dr. Budi Santoso',
            'email' => 'budi@lab-indonesia.com',
            'company_name' => 'Lab Diagnostik Utama',
            'phone_wa' => '081234567890',
            'notes' => 'Mohon sertakan Certificate of Analysis (CoA).',
        ]);

        $rfq = Rfq::with('items')->first();
        $this->assertNotNull($rfq);
        $this->assertEquals('Dr. Budi Santoso', $rfq->name);
        $this->assertEquals('budi@lab-indonesia.com', $rfq->email);
        $this->assertEquals(1, $rfq->items->count());
        $this->assertEquals(3, $rfq->items->first()->quantity);

        // Cart should be cleared
        $this->assertEmpty(session('cart'));

        // Success redirect
        $response->assertRedirect(route('rfq.success', ['number' => $rfq->rfq_number]));

        // Check queued email jobs
        Queue::assertPushed(SendRfqSubmittedEmailJob::class);
        Queue::assertPushed(SendRfqCustomerReceiptEmailJob::class);
    }

    public function test_rfq_submission_requires_valid_data(): void
    {
        $id = $this->product->id;

        $response = $this->withSession([
            'cart' => [
                (string) $id => [
                    'id' => $id,
                    'title' => 'Nutrient Agar 500g',
                    'catalog' => 'NA-500',
                    'image' => 'https://example.com/na.jpg',
                    'price' => 250000,
                    'stock' => 10,
                    'quantity' => 1,
                ],
            ],
        ])->post(route('rfq.store'), [
            'name' => '',
            'email' => 'invalid-email',
            'company_name' => '',
            'phone_wa' => '123', // invalid phone length/regex
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'company_name', 'phone_wa']);
        $this->assertEquals(0, Rfq::count());
    }

    public function test_success_page_is_protected_by_session(): void
    {
        $rfq = Rfq::create([
            'rfq_number' => 'RFQ-202608-TEST01',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'company_name' => 'PT Testing',
            'phone_wa' => '08123456789',
        ]);

        // Attempt without session
        $response = $this->get(route('rfq.success', ['number' => 'RFQ-202608-TEST01']));
        $response->assertRedirect(route('home'));

        // Attempt with matching session
        $validResponse = $this->withSession(['submitted_rfq_number' => 'RFQ-202608-TEST01'])
            ->get(route('rfq.success', ['number' => 'RFQ-202608-TEST01']));

        $validResponse->assertStatus(200);
        $validResponse->assertSee('RFQ-202608-TEST01');
    }
}

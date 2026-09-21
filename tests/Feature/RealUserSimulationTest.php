<?php

namespace Tests\Feature;

use App\Jobs\SendRfqCustomerReceiptEmailJob;
use App\Jobs\SendRfqSubmittedEmailJob;
use App\Models\Principal;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Rfq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * End-to-End Strict Real User Journey Simulation:
 * Simulates complete real-world user behaviors across Buyer, Admin, and Adversarial roles.
 */
class RealUserSimulationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Product $inStockProduct;

    private Product $lowStockProduct;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed Category
        ProductCategory::create([
            'name' => 'Microbiology',
            'key' => 'microbiology',
        ]);

        // Seed Admin User
        $this->admin = User::forceCreate([
            'name' => 'Operator Lab',
            'email' => 'operator@prolabios.com',
            'password' => Hash::make('SecretPass123!'),
            'is_admin' => true,
        ]);

        // Seed realistic in-stock product with gallery
        $this->inStockProduct = Product::create([
            'title' => 'Nutrient Agar 500g Media',
            'catalog' => 'NA-500-LAB',
            'category' => 'microbiology',
            'sub_category' => 'food-safety',
            'description' => '<p>Standard nutrient agar culture media for bacterial growth.</p>',
            'price' => 350000,
            'stock' => 20,
            'image' => '/storage/uploads/nutrient_main.webp',
            'gallery_images' => [
                '/storage/uploads/nutrient_thumb1.webp',
                '/storage/uploads/nutrient_thumb2.webp',
            ],
        ]);

        // Seed low stock product (for indent threshold simulation)
        $this->lowStockProduct = Product::create([
            'title' => 'Precision Pipette 100ul',
            'catalog' => 'PIP-100',
            'category' => 'device',
            'sub_category' => 'liquid-handling',
            'price' => 1200000,
            'stock' => 2,
            'image' => '/storage/uploads/pipette.webp',
        ]);
    }

    /**
     * Simulation 1: Complete Buyer Persona Journey
     * Visitor -> Catalog Filter -> Product Detail -> Stepper & Gallery -> Cart -> Checkout -> RFQ Success
     */
    public function test_complete_buyer_real_world_procurement_flow(): void
    {
        Queue::fake();

        // 1. Visitor browses home page
        $home = $this->get('/');
        $home->assertOk();
        $home->assertSee('Katalog');

        // 2. Visitor searches catalog for "Nutrient"
        $catalog = $this->get(route('produk.index', ['s' => 'Nutrient']));
        $catalog->assertOk();
        $catalog->assertSee('Nutrient Agar 500g Media');
        $catalog->assertSee(route('produk.detail', ['slug' => $this->inStockProduct->slug]));

        // 3. Visitor opens Product Detail Page
        $detail = $this->get(route('produk.detail', ['slug' => $this->inStockProduct->slug]));
        $detail->assertOk();

        // Verify Hero & Multi-Image Gallery Rendering
        $detail->assertSee('id="main-product-image"', false);
        $detail->assertSee('/storage/uploads/nutrient_main.webp', false);
        $detail->assertSee('data-img="/storage/uploads/nutrient_thumb1.webp"', false);
        $detail->assertSee('data-img="/storage/uploads/nutrient_thumb2.webp"', false);

        // Verify Stepper Form Controls
        $detail->assertSee('data-step="-1"', false);
        $detail->assertSee('data-step="1"', false);
        $detail->assertSee('id="qty-input"', false);
        $detail->assertSee('data-stock="20"', false);

        // 4. Buyer adjusts quantity to 3 using stepper and adds to cart (AJAX fetch simulation)
        $addToCartResponse = $this->postJson(route('cart.add'), [
            'id' => $this->inStockProduct->id,
            'title' => $this->inStockProduct->title,
            'quantity' => 3,
        ]);

        $addToCartResponse->assertOk()
            ->assertJson([
                'success' => true,
                'cartCount' => 3,
                'isIndent' => false,
            ]);

        // 5. Buyer navigates back to catalog and orders 5 units of low stock product (Stock is 2 -> triggers Indent)
        $addIndentResponse = $this->postJson(route('cart.add'), [
            'id' => $this->lowStockProduct->id,
            'title' => $this->lowStockProduct->title,
            'quantity' => 5,
        ]);

        $addIndentResponse->assertOk()
            ->assertJson([
                'success' => true,
                'cartCount' => 8, // 3 + 5
                'isIndent' => true,
            ]);

        // 6. Buyer navigates to /cart
        $cartPage = $this->get(route('cart.index'));
        $cartPage->assertOk();
        $cartPage->assertSee('Nutrient Agar 500g Media');
        $cartPage->assertSee('Precision Pipette 100ul');

        // Total calculation: (3 * 350.000) + (5 * 1.200.000) = 1.050.000 + 6.000.000 = 7.050.000
        $cartPage->assertSee('7.050.000');

        // 7. Buyer updates quantity of inStockProduct to 4 units via Cart Stepper
        $updateCart = $this->postJson(route('cart.update'), [
            'id' => $this->inStockProduct->id,
            'quantity' => 4,
        ]);

        $updateCart->assertOk()
            ->assertJson([
                'success' => true,
                'cartCount' => 9, // 4 + 5
            ]);

        // 8. Buyer proceeds to Checkout
        $checkout = $this->get(route('rfq.checkout'));
        $checkout->assertOk();
        $checkout->assertSee('Informasi Pemohon');

        // 9. Buyer submits corporate RFQ
        $rfqSubmit = $this->post(route('rfq.store'), [
            'name' => 'Prof. Ahmad Pratama, M.Biomed',
            'email' => 'ahmad.pratama@univ-lab.ac.id',
            'company_name' => 'Laboratorium Mikrobiologi Universitas Nasional',
            'phone_wa' => '081298765432',
            'notes' => 'Harap lampirkan CoA, MSDS, dan faktur pajak resmi per unit.',
        ]);

        $rfq = Rfq::with('items')->latest('id')->first();
        $this->assertNotNull($rfq);
        $this->assertEquals('Prof. Ahmad Pratama, M.Biomed', $rfq->name);
        $this->assertEquals('Laboratorium Mikrobiologi Universitas Nasional', $rfq->company_name);
        $this->assertEquals(2, $rfq->items->count());

        // Verify cart is emptied after submission
        $this->assertEmpty(session('cart', []));

        // 10. Buyer is redirected to dedicated RFQ Success page
        $rfqSubmit->assertRedirect(route('rfq.success', ['number' => $rfq->rfq_number]));

        $successPage = $this->get(route('rfq.success', ['number' => $rfq->rfq_number]));
        $successPage->assertOk();
        $successPage->assertSee($rfq->rfq_number);
        $successPage->assertSee('https://wa.me/', false);

        // Verify queued notification emails
        Queue::assertPushed(SendRfqSubmittedEmailJob::class);
        Queue::assertPushed(SendRfqCustomerReceiptEmailJob::class);
    }

    /**
     * Simulation 2: Admin Persona Complete Lifecycle
     * Authentication -> Create Product with Uploads -> Real-time Public Visibility -> Admin Edit -> Process RFQ
     */
    public function test_complete_admin_real_world_management_flow(): void
    {
        Storage::fake('public');

        // 1. Unauthenticated access is rejected
        $unauth = $this->get(route('admin.products.create'));
        $unauth->assertRedirect(route('admin.login'));

        // 2. Admin logs in
        $login = $this->post(route('admin.login.submit'), [
            'username' => 'operator@prolabios.com',
            'password' => 'SecretPass123!',
        ]);
        $login->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin);

        // 3. Admin loads create product form
        $createForm = $this->get(route('admin.products.create'));
        $createForm->assertOk();
        $createForm->assertSee('Data Produk');

        // 4. Admin submits new product with mock uploaded images & datasheet PDF
        $mainImg = UploadedFile::fake()->image('incubator_main.jpg', 800, 800);
        $thumb1 = UploadedFile::fake()->image('incubator_interior.png', 800, 800);
        $thumb2 = UploadedFile::fake()->image('incubator_panel.webp', 800, 800);
        $datasheet = UploadedFile::fake()->create('spec_incubator.pdf', 1024, 'application/pdf');

        $principal = Principal::create(['name' => 'Binder Instruments GmbH']);

        $storeResponse = $this->post(route('admin.products.store'), [
            'title' => 'Laboratory Shaking Incubator 150L',
            'catalog' => 'LSI-150-PRO',
            'category' => 'microbiology',
            'sub_category' => null, // Storing with optional null subcategory
            'principal_id' => $principal->id,
            'price' => '45.000.000', // Indonesian thousands separator
            'stock' => 4,
            'description' => '<p>Heavy-duty orbital shaking incubator with digital PID controller.</p>',
            'image_file' => $mainImg,
            'gallery_files' => [$thumb1, $thumb2],
            'datasheet_file' => $datasheet,
        ]);

        $storeResponse->assertRedirect(route('admin.products'));

        // 5. Verify stored data in DB
        $newProduct = Product::where('catalog', 'LSI-150-PRO')->first();
        $this->assertNotNull($newProduct);
        $this->assertEquals('Laboratory Shaking Incubator 150L', $newProduct->title);
        $this->assertEquals(45000000.0, (float) $newProduct->price);
        $this->assertNull($newProduct->sub_category);
        $this->assertNotNull($newProduct->image);
        $this->assertCount(2, $newProduct->gallery_images ?? []);
        $this->assertNotNull($newProduct->datasheet_url);

        // 6. Public visitor instantly sees the newly created product (zero cache lag)
        $publicProduct = $this->get(route('produk.detail', ['slug' => $newProduct->slug]));
        $publicProduct->assertOk();
        $publicProduct->assertSee('Laboratory Shaking Incubator 150L');
        $publicProduct->assertSee('LSI-150-PRO');
        $publicProduct->assertSee('45.000.000');

        // 7. Admin updates product (e.g. adjusts stock and price)
        $updateResponse = $this->put(route('admin.products.update', ['id' => $newProduct->id]), [
            'title' => 'Laboratory Shaking Incubator 150L (Gen 2)',
            'catalog' => 'LSI-150-PRO',
            'category' => 'microbiology',
            'sub_category' => null,
            'price' => '42.500.000',
            'stock' => 7,
            'description' => '<p>Updated PID microprocessor v2.</p>',
        ]);

        $updateResponse->assertRedirect(route('admin.products'));
        $this->assertDatabaseHas('products', [
            'id' => $newProduct->id,
            'title' => 'Laboratory Shaking Incubator 150L (Gen 2)',
            'price' => 42500000.0,
            'stock' => 7,
        ]);
    }

    /**
     * Simulation 3: Adversarial Buyer & Tampering Edge Cases
     * Price tampering, XSS injection in notes, negative quantities, boundary conditions
     */
    public function test_adversarial_tampering_and_sanitization_defense(): void
    {
        // 1. Adversary attempts to tamper with price in cart payload
        $tamperedResponse = $this->postJson(route('cart.add'), [
            'id' => $this->inStockProduct->id,
            'title' => $this->inStockProduct->title,
            'quantity' => 1,
            'price' => 100, // Attempting to buy Rp 350.000 item for Rp 100
        ]);

        $tamperedResponse->assertOk();
        // Server MUST query authoritative price from DB (350.000), ignoring client input
        $cart = session('cart', []);
        $this->assertEquals(350000.0, $cart[(string) $this->inStockProduct->id]['price']);

        // 2. Adversary inputs negative or zero quantity
        $this->postJson(route('cart.add'), [
            'id' => $this->inStockProduct->id,
            'quantity' => -10,
        ]);
        // Server clamps quantity to minimum 1
        $cartAfterNegative = session('cart', []);
        $this->assertGreaterThanOrEqual(1, $cartAfterNegative[(string) $this->inStockProduct->id]['quantity']);

        // 3. Adversary attempts XSS injection in RFQ Form
        $xssPayload = "<script>alert('xss')</script><iframe src='javascript:evil()'></iframe><b>Catatan Penting Lab</b>";
        $rfqSubmit = $this->post(route('rfq.store'), [
            'name' => 'Budi <script>alert(1)</script>',
            'email' => 'budi@tester.com',
            'company_name' => 'PT Safe Lab',
            'phone_wa' => '08123456789',
            'notes' => $xssPayload,
        ]);

        $savedRfq = Rfq::latest('id')->first();
        $this->assertNotNull($savedRfq);
        // Assert raw script tag is sanitized or safely handled
        $this->assertStringNotContainsString('<script>', $savedRfq->notes);
        $this->assertStringNotContainsString('javascript:evil', $savedRfq->notes);

        // 4. Unauthorized session tries to view someone else's RFQ confirmation
        $snoopAttempt = $this->flushSession()->get(route('rfq.success', ['number' => $savedRfq->rfq_number]));
        $snoopAttempt->assertRedirect(route('home'));
    }

    /**
     * Simulation 4: Legacy URL & Route Normalization
     * Ensures users landing from old bookmarks or external links are smoothly redirected without breaking
     */
    public function test_legacy_routes_and_slug_fallbacks_redirect_smoothly(): void
    {
        // 1. Legacy /produk/detail?id=123 -> 301 Permanent Redirect to /produk/{slug}
        $legacyDetail = $this->get('/produk/detail?id='.$this->inStockProduct->id);
        $legacyDetail->assertRedirect(route('produk.detail', ['slug' => $this->inStockProduct->slug]));
        $legacyDetail->assertStatus(301);

        // 2. Canonical buy redirect /produk/{slug}/beli -> /produk/{slug}
        $canonicalBuy = $this->get('/produk/'.$this->inStockProduct->slug.'/beli');
        $canonicalBuy->assertRedirect(route('produk.detail', ['slug' => $this->inStockProduct->slug]));
        $canonicalBuy->assertStatus(301);
    }
}

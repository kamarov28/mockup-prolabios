<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Rfq;
use App\Models\RfqItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRfqTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected Rfq $rfq;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'name' => 'Admin Test',
            'email' => 'admin-rfq-test@example.com',
            'password' => Hash::make('secret123'),
            'is_admin' => true,
        ]);

        $product = Product::create([
            'title' => 'Test Media',
            'catalog' => 'TM-01',
            'category' => 'Culture Media',
            'price' => 100000,
            'stock' => 5,
        ]);

        $this->rfq = Rfq::create([
            'rfq_number' => 'RFQ-202608-ADMINTEST',
            'name' => 'Prof. Bambang',
            'email' => 'bambang@univ.ac.id',
            'company_name' => 'Fakultas Farmasi Universitas X',
            'phone_wa' => '081299988877',
            'notes' => 'Kirim ke lab lantai 3.',
        ]);

        RfqItem::create([
            'rfq_id' => $this->rfq->id,
            'product_id' => $product->id,
            'product_title' => $product->title,
            'catalog_no' => $product->catalog,
            'original_price' => $product->price,
            'quantity' => 4,
        ]);
    }

    public function test_admin_can_view_rfqs_index(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.rfqs.index'));

        $response->assertStatus(200);
        $response->assertSee('RFQ-202608-ADMINTEST');
        $response->assertSee('Prof. Bambang');
        $response->assertSee('Fakultas Farmasi Universitas X');
    }

    public function test_admin_can_view_rfq_detail(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.rfqs.show', $this->rfq->id));

        $response->assertStatus(200);
        $response->assertSee('RFQ-202608-ADMINTEST');
        $response->assertSee('Test Media');
        $response->assertSee('Kirim ke lab lantai 3.');
    }

    public function test_admin_can_delete_rfq(): void
    {
        $response = $this->actingAs($this->adminUser)->delete(route('admin.rfqs.destroy', $this->rfq->id));

        $response->assertRedirect(route('admin.rfqs.index'));
        $this->assertSoftDeleted('rfqs', ['id' => $this->rfq->id]);
    }
}

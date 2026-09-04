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

        $this->adminUser = User::forceCreate([
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
            'status' => 'new',
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

    public function test_admin_can_view_rfqs_kanban(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.rfqs.index', ['view' => 'kanban']));

        $response->assertStatus(200);
        $response->assertSee('RFQ-202608-ADMINTEST');
        $response->assertSee('Prof. Bambang');
        $response->assertSee('Baru');
    }

    public function test_admin_can_view_rfq_detail(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.rfqs.show', $this->rfq->id));

        $response->assertStatus(200);
        $response->assertSee('RFQ-202608-ADMINTEST');
        $response->assertSee('Test Media');
        $response->assertSee('Kirim ke lab lantai 3.');
    }

    public function test_admin_can_update_rfq_status_and_notes(): void
    {
        $response = $this->actingAs($this->adminUser)->put(
            route('admin.rfqs.update', $this->rfq->id),
            [
                'status' => 'contacted',
                'admin_notes' => 'Sudah WA ke klien 26 Agu.',
            ]
        );

        $response->assertRedirect(route('admin.rfqs.show', $this->rfq->id));
        $response->assertSessionHas('success');

        $this->rfq->refresh();
        $this->assertEquals('contacted', $this->rfq->status);
        $this->assertEquals('Sudah WA ke klien 26 Agu.', $this->rfq->admin_notes);
    }

    public function test_admin_rfq_status_must_be_valid(): void
    {
        $response = $this->actingAs($this->adminUser)->from(route('admin.rfqs.show', $this->rfq->id))
            ->put(route('admin.rfqs.update', $this->rfq->id), [
                'status' => 'not-a-real-status',
                'admin_notes' => 'x',
            ]);

        $response->assertSessionHasErrors('status');
        $this->rfq->refresh();
        $this->assertEquals('new', $this->rfq->status);
    }

    public function test_admin_can_delete_rfq(): void
    {
        $response = $this->actingAs($this->adminUser)->delete(route('admin.rfqs.destroy', $this->rfq->id));

        $response->assertRedirect(route('admin.rfqs.index'));
        $this->assertSoftDeleted('rfqs', ['id' => $this->rfq->id]);
    }

    public function test_admin_can_export_rfqs(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.rfqs.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_admin_can_filter_and_export_rfqs_by_date_and_product(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.rfqs.export', [
            'start_date' => now()->subDays(7)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'product_name' => 'Mikroskop',
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}

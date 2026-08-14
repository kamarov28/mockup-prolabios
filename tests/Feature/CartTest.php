<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'title'       => 'Test Agar Media',
            'catalog'     => 'AG-101',
            'description' => 'Quality testing agar media.',
            'category'    => 'Culture Media',
            'sub_category'=> 'Dehydrated Culture Media',
            'sector'      => 'Microbiology',
            'price'       => 150000,
            'stock'       => 5,
            'image'       => 'https://example.com/image.jpg',
        ]);
    }

    public function test_can_add_product_to_cart_by_id(): void
    {
        $id = $this->product->id;

        $response = $this->post(route('cart.add'), [
            'id'       => $id,
            'title'    => 'Test Agar Media',
            'quantity' => 2,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('cart.' . $id . '.quantity', 2);
        $response->assertSessionHas('cart.' . $id . '.price', 150000.0);
    }

    public function test_cannot_over_order_beyond_available_stock(): void
    {
        $id = $this->product->id;

        $response = $this->postJson(route('cart.add'), [
            'id'       => $id,
            'title'    => 'Test Agar Media',
            'quantity' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_cart_update_clamps_quantity_to_stock(): void
    {
        $id = $this->product->id;

        // Add 2 items first with session
        $this->withSession([
            'cart' => [
                (string)$id => [
                    'id'       => $id,
                    'title'    => 'Test Agar Media',
                    'catalog'  => 'AG-101',
                    'image'    => 'https://example.com/image.jpg',
                    'price'    => 150000,
                    'stock'    => 5,
                    'quantity' => 2,
                ]
            ]
        ]);

        // Attempt to update quantity to 99
        $response = $this->postJson(route('cart.update'), [
            'id'       => $id,
            'quantity' => 99,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'cartCount' => 5, // Clamped to max stock (5)
            ]);

        $response->assertSessionHas('cart.' . $id . '.quantity', 5);
    }

    public function test_can_remove_item_from_cart(): void
    {
        $id = $this->product->id;

        $this->withSession([
            'cart' => [
                (string)$id => [
                    'id'       => $id,
                    'title'    => 'Test Agar Media',
                    'catalog'  => 'AG-101',
                    'image'    => 'https://example.com/image.jpg',
                    'price'    => 150000,
                    'stock'    => 5,
                    'quantity' => 2,
                ]
            ]
        ]);

        $response = $this->postJson(route('cart.remove'), [
            'id' => $id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'cartCount' => 0,
            ]);

        $response->assertSessionMissing('cart.' . $id);
    }
}


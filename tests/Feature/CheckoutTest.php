<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_place_an_order_from_their_cart(): void
    {
        $this->withoutVite();
        $this->withoutMiddleware(PreventRequestForgery::class);
        $this->actingAs(User::factory()->create());
        $product = Product::factory()->create(['name' => 'Checkout laptop', 'price' => 499.99, 'stock_quantity' => 3]);
        $this->post(route('cart.store', $product));

        $response = $this->post(route('checkout.store'), ['first_name' => 'Ana', 'last_name' => 'Markovic', 'email' => 'ana@example.com', 'phone' => '+38761123456', 'address_line_1' => 'Main Street 1', 'city' => 'Sarajevo', 'postal_code' => '71000', 'country' => 'Bosnia and Herzegovina']);

        $order = Order::query()->firstOrFail();
        $response->assertRedirect(route('checkout.success', $order));
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'email' => 'ana@example.com', 'status' => 'awaiting_payment', 'total' => 499.99]);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 1, 'unit_price' => 499.99]);
        $this->assertDatabaseHas('order_status_histories', ['order_id' => $order->id, 'to_status' => 'awaiting_payment', 'from_status' => null]);
        $response->assertSessionMissing('cart');
        $this->get(route('checkout.success', $order))->assertOk()->assertSee('Order saved successfully')->assertSee('payment details by email')->assertSee('My orders');
    }

    public function test_checkout_requires_contact_and_delivery_information(): void
    {
        $this->withoutVite();
        $this->withoutMiddleware(PreventRequestForgery::class);
        $this->actingAs(User::factory()->create());
        $product = Product::factory()->create(['stock_quantity' => 3]);
        $this->post(route('cart.store', $product));

        $this->post(route('checkout.store'))->assertRedirect()->assertSessionHasErrors(['first_name', 'last_name', 'email', 'phone', 'address_line_1', 'city', 'postal_code', 'country']);
        $this->assertDatabaseCount('orders', 0);
    }
}

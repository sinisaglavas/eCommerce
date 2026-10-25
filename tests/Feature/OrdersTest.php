<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_only_their_orders_and_their_status(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();
        $ownOrder = Order::factory()->for($user)->create(['number' => 'ORD-MY-ORDER', 'status' => 'pending', 'total' => 499.99]);
        Order::factory()->create(['number' => 'ORD-OTHER-ORDER']);

        $this->actingAs($user)->get(route('orders.index'))->assertOk()
            ->assertSee($ownOrder->number)
            ->assertSee('Pending')
            ->assertDontSee('ORD-OTHER-ORDER');
    }

    public function test_orders_page_requires_login(): void
    {
        $this->get(route('orders.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_view_their_order_tracking_details(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create(['status' => 'shipped']);
        OrderItem::factory()->for($order)->create(['product_name' => 'Tracking laptop', 'quantity' => 2]);
        $order->statusHistories()->createMany([
            ['to_status' => 'awaiting_payment', 'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2)],
            ['from_status' => 'awaiting_payment', 'to_status' => 'paid', 'created_at' => now()->subDay(), 'updated_at' => now()->subDay()],
            ['from_status' => 'paid', 'to_status' => 'shipped'],
        ]);

        $this->actingAs($user)->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee($order->number)
            ->assertSee('Tracking laptop')
            ->assertSee('Shipped')
            ->assertSee('Order progress');
    }

    public function test_user_cannot_view_another_users_order_tracking_details(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();
        $order = Order::factory()->create();

        $this->actingAs($user)->get(route('orders.show', $order))->assertNotFound();
    }
}

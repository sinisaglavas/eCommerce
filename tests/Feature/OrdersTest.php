<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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

    public function test_paid_order_shows_payment_confirmation_and_delivery_process_indicator(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create(['status' => 'paid', 'payment_status' => 'paid']);

        foreach ([route('orders.index'), route('orders.show', $order)] as $url) {
            $this->actingAs($user)->get($url)->assertOk()
                ->assertSee('Paid')
                ->assertSee('Payment confirmed · Delivery process started');
        }
    }

    public function test_inactive_or_unpaid_orders_do_not_show_delivery_process_confirmation(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();

        foreach ([['cancelled', 'paid'], ['pending', 'unpaid'], ['payment_failed', 'failed'], ['processing', 'unpaid']] as [$status, $paymentStatus]) {
            $order = Order::factory()->for($user)->create(['status' => $status, 'payment_status' => $paymentStatus]);

            $this->actingAs($user)->get(route('orders.show', $order))->assertOk()
                ->assertDontSee('Payment confirmed · Delivery process started');
        }

        $this->get(route('orders.index'))->assertOk()
            ->assertDontSee('Payment confirmed · Delivery process started');
    }

    public function test_completed_order_shows_delivered_confirmation(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create(['status' => 'completed', 'payment_status' => 'paid']);

        $this->actingAs($user)->get(route('orders.show', $order))->assertOk()
            ->assertSee('Payment confirmed · Delivered')
            ->assertDontSee('Delivery process started');
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
        $order->update([
            'payment_status' => 'paid',
            'tracking_number' => 'TRACK-ORDER-1',
            'shipping_carrier' => 'Voltio Express',
            'estimated_delivery_at' => now()->addDays(2),
            'shipped_at' => now(),
        ]);
        OrderItem::factory()->for($order)->create(['product_name' => 'Tracking laptop', 'quantity' => 2]);
        $order->statusHistories()->createMany([
            ['to_status' => 'paid', 'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2)],
            ['from_status' => 'paid', 'to_status' => 'processing', 'created_at' => now()->subDay(), 'updated_at' => now()->subDay()],
            ['from_status' => 'processing', 'to_status' => 'shipped'],
        ]);

        $this->actingAs($user)->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee($order->number)
            ->assertSee('Tracking laptop')
            ->assertSee('Shipped')
            ->assertSee('Paid')
            ->assertSee('TRACK-ORDER-1')
            ->assertSee('Voltio Express')
            ->assertSee('Order progress');
    }

    public function test_user_cannot_view_another_users_order_tracking_details(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();
        $order = Order::factory()->create();

        $this->actingAs($user)->get(route('orders.show', $order))->assertNotFound();
    }

    public function test_order_stores_separate_payment_and_shipping_data(): void
    {
        $order = Order::factory()->create([
            'payment_status' => 'paid',
            'payment_method' => 'cash_on_delivery',
            'tracking_number' => 'TRACK-123',
            'shipping_carrier' => 'Voltio Express',
            'estimated_delivery_at' => now()->addDays(3),
        ]);

        $this->assertSame('Paid', $order->paymentStatusLabel());
        $this->assertInstanceOf(Carbon::class, $order->estimated_delivery_at);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'tracking_number' => 'TRACK-123',
            'shipping_carrier' => 'Voltio Express',
        ]);
    }
}

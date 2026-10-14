<?php

namespace Tests\Feature;

use App\Models\Order;
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
}

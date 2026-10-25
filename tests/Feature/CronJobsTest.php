<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CronJobsTest extends TestCase
{
    use RefreshDatabase;

    public function test_expire_unpaid_orders_marks_only_orders_past_the_payment_window(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-25 12:00:00'));
        $expiredOrder = Order::factory()->create([
            'status' => 'awaiting_payment',
            'payment_status' => 'unpaid',
            'created_at' => now()->subHours(49),
            'updated_at' => now()->subHours(49),
        ]);
        $recentOrder = Order::factory()->create([
            'status' => 'awaiting_payment',
            'payment_status' => 'unpaid',
            'created_at' => now()->subHours(47),
            'updated_at' => now()->subHours(47),
        ]);

        $this->artisan('orders:expire-unpaid')
            ->expectsOutput('Expired 1 unpaid order(s).')
            ->assertSuccessful();

        $this->assertDatabaseHas('orders', [
            'id' => $expiredOrder->id,
            'status' => 'payment_failed',
            'payment_status' => 'failed',
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $recentOrder->id,
            'status' => 'awaiting_payment',
            'payment_status' => 'unpaid',
        ]);
        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $expiredOrder->id,
            'from_status' => 'awaiting_payment',
            'to_status' => 'payment_failed',
        ]);
    }

    public function test_expire_unpaid_orders_rejects_an_invalid_window(): void
    {
        $this->artisan('orders:expire-unpaid --hours=0')
            ->assertFailed();
    }
}

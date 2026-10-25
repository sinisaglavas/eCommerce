<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('orders:expire-unpaid {--hours=48 : Number of hours before an unpaid order expires}')]
#[Description('Mark old unpaid orders as payment failed')]
class ExpireUnpaidOrders extends Command
{
    public function handle(): int
    {
        $hours = (int) $this->option('hours');

        if ($hours < 1) {
            $this->components->error('The hours option must be at least 1.');

            return self::FAILURE;
        }

        $cutoff = now()->subHours($hours);
        $expired = 0;

        Order::query()
            ->whereIn('status', ['awaiting_payment', 'pending'])
            ->where('payment_status', 'unpaid')
            ->where('created_at', '<=', $cutoff)
            ->eachById(function (Order $order) use (&$expired): void {
                DB::transaction(function () use ($order, &$expired): void {
                    $previousStatus = $order->status;
                    $order->update([
                        'status' => 'payment_failed',
                        'payment_status' => 'failed',
                    ]);
                    $order->statusHistories()->create([
                        'from_status' => $previousStatus,
                        'to_status' => 'payment_failed',
                        'note' => 'Automatically expired after the payment window elapsed.',
                    ]);
                    $expired++;
                });
            });

        $this->info("Expired {$expired} unpaid order(s).");

        return self::SUCCESS;
    }
}

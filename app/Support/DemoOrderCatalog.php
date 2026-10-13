<?php

namespace App\Support;

use Illuminate\Support\Collection;

/** Fictional orders for the admin preview; no customer data or persistence. */
final class DemoOrderCatalog
{
    public function orders(): Collection
    {
        return collect([
            (object) [
                'number' => 'DEMO-1003', 'date' => '2026-10-12', 'status' => 'new',
                'customer' => 'Ana Demo', 'email' => 'ana@example.test', 'address' => 'Primer ulica 1, Beograd',
                'items' => [(object) ['name' => 'UltraView 27', 'quantity' => 1, 'unitPrice' => 42900]],
            ],
            (object) [
                'number' => 'DEMO-1002', 'date' => '2026-10-11', 'status' => 'processing',
                'customer' => 'Marko Demo', 'email' => 'marko@example.test', 'address' => 'Primer ulica 2, Novi Sad',
                'items' => [
                    (object) ['name' => 'AeroBook Pro 14', 'quantity' => 1, 'unitPrice' => 129900],
                    (object) ['name' => 'Studio Buds', 'quantity' => 2, 'unitPrice' => 17900],
                ],
            ],
            (object) [
                'number' => 'DEMO-1001', 'date' => '2026-10-10', 'status' => 'completed',
                'customer' => 'Jelena Demo', 'email' => 'jelena@example.test', 'address' => 'Primer ulica 3, Niš',
                'items' => [(object) ['name' => 'Phantom Console', 'quantity' => 1, 'unitPrice' => 54900]],
            ],
        ])->map(function (object $order): object {
            // Amounts are stored in euro cents; historical item prices are snapshots.
            $order->total = collect($order->items)->sum(fn (object $item) => $item->quantity * $item->unitPrice);

            return $order;
        });
    }

    public function statuses(): array
    {
        return ['new' => 'Nova', 'processing' => 'U obradi', 'completed' => 'Završena', 'cancelled' => 'Otkazana'];
    }
}

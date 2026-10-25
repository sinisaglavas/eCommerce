<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::query()
                ->with('user')
                ->latest('created_at')
                ->latest('id')
                ->paginate(20),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(Order::STATUS_LABELS))],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'shipping_carrier' => ['nullable', 'string', 'max:100'],
            'estimated_delivery_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        if (! $order->canTransitionTo($validated['status'])) {
            return back()->withErrors(['status' => 'Ova promena statusa nije dozvoljena.']);
        }

        $previousStatus = $order->status;
        $updates = [
            'tracking_number' => $validated['tracking_number'] ?? null,
            'shipping_carrier' => $validated['shipping_carrier'] ?? null,
            'estimated_delivery_at' => $validated['estimated_delivery_at'] ?? null,
        ];

        if ($previousStatus !== $validated['status']) {
            $updates['status'] = $validated['status'];

            if ($validated['status'] === 'paid') {
                $updates['payment_status'] = 'paid';
            } elseif ($validated['status'] === 'payment_failed') {
                $updates['payment_status'] = 'failed';
            }

            if ($validated['status'] === 'shipped' && $order->shipped_at === null) {
                $updates['shipped_at'] = now();
            }

            if ($validated['status'] === 'completed' && $order->delivered_at === null) {
                $updates['delivered_at'] = now();
            }

            $order->update($updates);
            $order->statusHistories()->create([
                'from_status' => $previousStatus,
                'to_status' => $order->status,
                'note' => $validated['note'] ?? 'Status updated by administrator',
            ]);
        } else {
            $order->update($updates);
        }

        return back()->with('status', 'Status porudžbine je uspešno izmenjen.');
    }
}

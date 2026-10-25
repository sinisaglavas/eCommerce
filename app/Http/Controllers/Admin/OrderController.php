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
        ]);

        if (! $order->canTransitionTo($validated['status'])) {
            return back()->withErrors(['status' => 'Ova promena statusa nije dozvoljena.']);
        }

        $previousStatus = $order->status;

        if ($previousStatus !== $validated['status']) {
            $updates = ['status' => $validated['status']];

            if ($validated['status'] === 'paid') {
                $updates['payment_status'] = 'paid';
            } elseif ($validated['status'] === 'payment_failed') {
                $updates['payment_status'] = 'failed';
            }

            $order->update($updates);
            $order->statusHistories()->create([
                'from_status' => $previousStatus,
                'to_status' => $order->status,
                'note' => 'Status updated by administrator',
            ]);
        }

        return back()->with('status', 'Status porudžbine je uspešno izmenjen.');
    }
}

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
            return back()->withErrors(['status' => 'This status change is not allowed.']);
        }

        $order->update(['status' => $validated['status']]);

        return back()->with('status', 'Order status updated successfully.');
    }
}

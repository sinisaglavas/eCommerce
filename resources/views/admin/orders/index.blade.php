@extends('layouts.admin')
@section('title', 'Orders')
@section('content')
    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4">
        <div>
            <p class="eyebrow mb-2">ORDER MANAGEMENT</p>
            <h1 class="h2 fw-bold mb-2">Orders</h1>
            <p class="text-body-secondary mb-0">View all customer orders.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">← Dashboard</a>
    </div>

    <section class="panel overflow-hidden" aria-labelledby="orders-title">
        <div class="welcome-panel border-bottom p-4">
            <h2 id="orders-title" class="h5 mb-1">All orders <span class="badge text-bg-secondary ms-2">{{ $orders->total() }}</span></h2>
            <p class="small text-body-secondary mb-0">The most recent orders are shown first.</p>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-hover admin-table align-middle mb-0">
                <caption class="visually-hidden">All customer orders</caption>
                <thead>
                    <tr>
                        <th scope="col" class="px-4 py-3">Order</th>
                        <th scope="col" class="py-3">Customer</th>
                        <th scope="col" class="py-3">Date</th>
                        <th scope="col" class="py-3">Status</th>
                        <th scope="col" class="py-3 text-nowrap">Update status</th>
                        <th scope="col" class="px-4 py-3 text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <th scope="row" class="px-4 py-3 text-nowrap">{{ $order->number }}</th>
                            <td class="py-3">{{ $order->first_name }} {{ $order->last_name }}</td>
                            <td class="py-3 text-body-secondary text-nowrap">{{ $order->created_at->format('d.m.Y. H:i') }}</td>
                            <td class="py-3 text-nowrap">
                                <strong>{{ \App\Models\Order::STATUS_LABELS[$order->status] ?? $order->status }}</strong>
                            </td>
                            <td class="py-3">
                                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="d-flex align-items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <label class="visually-hidden" for="order-status-{{ $order->id }}">New status for order {{ $order->number }}</label>
                                    <select id="order-status-{{ $order->id }}" name="status" class="form-select form-select-sm bg-body" style="width: 140px" aria-label="New status for order {{ $order->number }}">
                                        @foreach (\App\Models\Order::STATUS_LABELS as $status => $label)
                                            <option value="{{ $status }}" @selected($order->status === $status)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-outline-secondary btn-sm text-nowrap">Save</button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-end text-nowrap">€{{ number_format($order->total, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center px-4 py-5">
                                <span class="stat-icon mb-3" aria-hidden="true">▧</span>
                                <h3 class="h6">No orders to display.</h3>
                                <p class="small text-body-secondary mb-0">Orders will appear after checkout is completed.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
            <div class="p-4 border-top">{{ $orders->links('pagination::bootstrap-5') }}</div>
        @endif
    </section>
@endsection

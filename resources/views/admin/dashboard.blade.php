@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4">
        <p class="eyebrow mb-2">STORE OVERVIEW</p>
        <h1 class="h2 fw-bold mb-2">Dashboard</h1>
        <p class="text-body-secondary mb-0">Your catalog and orders in one place.</p>
    </div>

    <section class="panel welcome-panel p-4 mb-4" aria-labelledby="welcome-title">
        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div>
                <h2 id="welcome-title" class="h4 fw-semibold">Welcome{{ auth()->check() ? ', '.auth()->user()->name : '' }}.</h2>
                <p class="text-body-secondary mb-0">Manage your catalog and track customer orders.</p>
            </div>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary">Open store <span aria-hidden="true">↗</span></a>
        </div>
    </section>

    <div class="row g-3 mb-4">
        @foreach ([
            ['label' => 'Products', 'value' => $productsCount ?? null, 'icon' => '◇', 'description' => 'Products in the catalog'],
            ['label' => 'Categories', 'value' => $categoriesCount ?? null, 'icon' => '▤', 'description' => 'Organize your catalog'],
            ['label' => 'Orders', 'value' => $ordersCount ?? null, 'icon' => '▧', 'description' => 'Total number of orders'],
        ] as $stat)
            <div class="col-12 col-md-4">
                <section class="panel p-4 h-100" aria-label="{{ $stat['label'] }}">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                        <h2 class="h6 text-body-secondary mb-0">{{ $stat['label'] }}</h2>
                        <span class="stat-icon" aria-hidden="true">{{ $stat['icon'] }}</span>
                    </div>
                    <div class="stat-value">{{ $stat['value'] === null ? '—' : number_format($stat['value'], 0, ',', '.') }}</div>
                    <p class="small text-body-secondary mb-0">{{ $stat['value'] === null ? 'Data is not available yet' : $stat['description'] }}</p>
                </section>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <section class="panel h-100" aria-labelledby="orders-title">
                <div class="p-4 border-bottom d-flex align-items-center justify-content-between gap-3 flex-wrap">
                    <div>
                        <h2 id="orders-title" class="h5 mb-1">Recent orders</h2>
                        <p class="small text-body-secondary mb-0">Showing up to 5 recent orders.</p>
                    </div>
                    @if (Route::has('admin.orders'))
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary btn-sm">All orders</a>
                    @endif
                </div>
                @if ($recentOrders->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-dark table-hover admin-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Order</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentOrders as $order)
                                    <tr>
                                        <th scope="row" class="text-nowrap">{{ $order->number }}<span class="d-block small text-body-secondary fw-normal">{{ $order->created_at->format('d.m.Y. H:i') }}</span></th>
                                        <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                                        <td>
                                            <span class="badge {{ match ($order->status) {
                                                'completed' => 'text-bg-success',
                                                'cancelled', 'payment_failed' => 'text-bg-danger',
                                                default => 'text-bg-warning',
                                            } }}">
                                                {{ \App\Models\Order::STATUS_LABELS[$order->status] ?? $order->status }}
                                            </span>
                                        </td>
                                        <td class="text-end text-nowrap">€{{ number_format($order->total, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 px-4">
                        <div class="empty-symbol mb-3" aria-hidden="true">▧</div>
                        <h3 class="h6">No orders to display.</h3>
                        <p class="small text-body-secondary mb-0">Your five most recent orders will appear here.</p>
                    </div>
                @endif
            </section>
        </div>
        <div class="col-12 col-xl-4">
            <section class="panel p-4 h-100" aria-labelledby="shortcuts-title">
                <h2 id="shortcuts-title" class="h5 mb-2">Quick actions</h2>
                <p class="small text-body-secondary mb-4">Expand and organize your store catalog.</p>
                <div class="d-grid gap-3">
                    @foreach (['admin.products.create' => 'Add product', 'admin.categories.create' => 'Add category'] as $route => $label)
                        @if (Route::has($route))
                            <a href="{{ route($route) }}" class="btn {{ $loop->first ? 'btn-primary' : 'btn-outline-secondary' }} py-2">+ {{ $label }}</a>
                        @else
                            <button type="button" class="btn btn-outline-secondary py-2" disabled>+ {{ $label }}</button>
                        @endif
                    @endforeach
                </div>
            </section>
        </div>
    </div>
@endsection

@extends('layouts.storefront')

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <a href="{{ route('orders.index') }}" class="small text-secondary text-decoration-none">← My orders</a>
                    <h1 class="h2 fw-bold section-title mt-2 mb-1">Track order {{ $order->number }}</h1>
                    <p class="text-secondary mb-0">Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="d-flex flex-wrap justify-content-end gap-2">
                    <span class="badge rounded-pill text-bg-warning fs-6 px-3 py-2">{{ $order->statusLabel() }}</span>
                    <span class="badge rounded-pill text-bg-secondary fs-6 px-3 py-2">{{ $order->paymentStatusLabel() }}</span>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <section class="card border p-4 h-100" aria-labelledby="tracking-title">
                        <h2 id="tracking-title" class="h4 fw-semibold mb-4">Order progress</h2>
                        <ol class="list-unstyled mb-0">
                            @forelse ($order->statusHistories as $history)
                                <li class="d-flex gap-3 pb-4">
                                    <span class="rounded-circle bg-primary flex-shrink-0" style="width: .75rem; height: .75rem; margin-top: .35rem" aria-hidden="true"></span>
                                    <div>
                                        <h3 class="h6 mb-1">{{ $statusLabels[$history->to_status] ?? str($history->to_status)->headline() }}</h3>
                                        <p class="small text-secondary mb-1">{{ $history->created_at->format('d M Y, H:i') }}</p>
                                        @if ($history->note)
                                            <p class="small text-secondary mb-0">{{ $history->note }}</p>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="d-flex gap-3">
                                    <span class="rounded-circle bg-primary flex-shrink-0" style="width: .75rem; height: .75rem; margin-top: .35rem" aria-hidden="true"></span>
                                    <div>
                                        <h3 class="h6 mb-1">{{ $order->statusLabel() }}</h3>
                                        <p class="small text-secondary mb-0">Current order status</p>
                                    </div>
                                </li>
                            @endforelse
                        </ol>
                    </section>
                </div>

                <div class="col-lg-5">
                    <section class="card border p-4 mb-4" aria-labelledby="shipping-title">
                        <h2 id="shipping-title" class="h4 fw-semibold mb-3">Shipping</h2>
                        @if ($order->tracking_number)
                            <dl class="row small mb-0">
                                <dt class="col-5 text-secondary">Carrier</dt>
                                <dd class="col-7">{{ $order->shipping_carrier ?: '—' }}</dd>
                                <dt class="col-5 text-secondary">Tracking number</dt>
                                <dd class="col-7">{{ $order->tracking_number }}</dd>
                                @if ($order->estimated_delivery_at)
                                    <dt class="col-5 text-secondary">Estimated delivery</dt>
                                    <dd class="col-7">{{ $order->estimated_delivery_at->format('d M Y') }}</dd>
                                @endif
                                @if ($order->shipped_at)
                                    <dt class="col-5 text-secondary">Shipped</dt>
                                    <dd class="col-7">{{ $order->shipped_at->format('d M Y, H:i') }}</dd>
                                @endif
                                @if ($order->delivered_at)
                                    <dt class="col-5 text-secondary">Delivered</dt>
                                    <dd class="col-7 mb-0">{{ $order->delivered_at->format('d M Y, H:i') }}</dd>
                                @endif
                            </dl>
                        @else
                            <p class="text-secondary small mb-0">Tracking details will appear here after the order is shipped.</p>
                        @endif
                    </section>
                    <section class="card border p-4 mb-4" aria-labelledby="summary-title">
                        <h2 id="summary-title" class="h4 fw-semibold mb-3">Order summary</h2>
                        @foreach ($order->items as $item)
                            <div class="d-flex justify-content-between gap-3 small mb-3">
                                <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                                <strong>€{{ number_format($item->line_total, 2, ',', '.') }}</strong>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between gap-3">
                            <span>Total</span>
                            <strong>€{{ number_format($order->total, 2, ',', '.') }}</strong>
                        </div>
                        <p class="small text-secondary mt-3 mb-0">Payment status: {{ $order->paymentStatusLabel() }}</p>
                    </section>

                    <section class="card border p-4" aria-labelledby="delivery-title">
                        <h2 id="delivery-title" class="h4 fw-semibold mb-3">Delivery address</h2>
                        <address class="text-secondary mb-0">
                            {{ $order->first_name }} {{ $order->last_name }}<br>
                            {{ $order->address_line_1 }}<br>
                            @if ($order->address_line_2){{ $order->address_line_2 }}<br>@endif
                            {{ $order->postal_code }} {{ $order->city }}<br>
                            {{ $order->country }}
                        </address>
                    </section>
                </div>
            </div>
        </div>
    </main>
@endsection

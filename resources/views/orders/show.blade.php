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
                <span class="badge rounded-pill text-bg-warning fs-6 px-3 py-2">{{ $order->statusLabel() }}</span>
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

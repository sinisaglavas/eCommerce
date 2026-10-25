@extends('layouts.admin')
@section('title', 'Porudžbine')
@section('content')
    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4">
        <div>
            <p class="eyebrow mb-2">UPRAVLJANJE KUPOVINAMA</p>
            <h1 class="h2 fw-bold mb-2">Porudžbine</h1>
            <p class="text-body-secondary mb-0">Pregled svih porudžbina kupaca.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">← Dashboard</a>
    </div>

    <section class="panel overflow-hidden" aria-labelledby="orders-title">
        <div class="welcome-panel border-bottom p-4">
            <h2 id="orders-title" class="h5 mb-1">Sve porudžbine <span class="badge text-bg-secondary ms-2">{{ $orders->total() }}</span></h2>
            <p class="small text-body-secondary mb-0">Najnovije porudžbine prikazane su prve.</p>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-hover admin-table align-middle mb-0">
                <caption class="visually-hidden">Lista svih porudžbina</caption>
                <thead>
                    <tr>
                        <th scope="col" class="px-4 py-3">Porudžbina</th>
                        <th scope="col" class="py-3">Kupac</th>
                        <th scope="col" class="py-3">Datum</th>
                        <th scope="col" class="py-3">Status</th>
                        <th scope="col" class="py-3 text-nowrap">Izmeni status</th>
                        <th scope="col" class="py-3">Dostava i tracking</th>
                        <th scope="col" class="px-4 py-3 text-end">Iznos</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <th scope="row" class="px-4 py-3 text-nowrap">{{ $order->number }}</th>
                            <td class="py-3">{{ $order->first_name }} {{ $order->last_name }}</td>
                            <td class="py-3 text-body-secondary text-nowrap">{{ $order->created_at->format('d.m.Y. H:i') }}</td>
                            <td class="py-3 text-nowrap">
                                <strong>{{ $order->statusLabel() }}</strong>
                                <small class="d-block text-body-secondary">Plaćanje: {{ $order->paymentStatusLabel() }}</small>
                            </td>
                            <td class="py-3">
                                <form id="order-update-{{ $order->id }}" method="POST" action="{{ route('admin.orders.status', $order) }}" class="d-flex align-items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <label class="visually-hidden" for="order-status-{{ $order->id }}">Novi status porudžbine {{ $order->number }}</label>
                                    <select id="order-status-{{ $order->id }}" name="status" class="form-select form-select-sm bg-body" style="width: 150px" aria-label="Novi status porudžbine {{ $order->number }}">
                                        @foreach (\App\Models\Order::STATUS_LABELS as $status => $label)
                                            <option value="{{ $status }}" @selected($order->status === $status)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-outline-secondary btn-sm text-nowrap">Sačuvaj</button>
                                </form>
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-wrap gap-2" style="min-width: 330px">
                                    <label class="visually-hidden" for="tracking-number-{{ $order->id }}">Tracking broj {{ $order->number }}</label>
                                    <input id="tracking-number-{{ $order->id }}" form="order-update-{{ $order->id }}" name="tracking_number" value="{{ $order->tracking_number }}" class="form-control form-control-sm bg-body" style="width: 145px" placeholder="Tracking broj">
                                    <label class="visually-hidden" for="shipping-carrier-{{ $order->id }}">Dostavna služba {{ $order->number }}</label>
                                    <input id="shipping-carrier-{{ $order->id }}" form="order-update-{{ $order->id }}" name="shipping_carrier" value="{{ $order->shipping_carrier }}" class="form-control form-control-sm bg-body" style="width: 145px" placeholder="Dostavna služba">
                                    <label class="visually-hidden" for="delivery-date-{{ $order->id }}">Procenjena isporuka {{ $order->number }}</label>
                                    <input id="delivery-date-{{ $order->id }}" form="order-update-{{ $order->id }}" type="date" name="estimated_delivery_at" value="{{ $order->estimated_delivery_at?->format('Y-m-d') }}" class="form-control form-control-sm bg-body" style="width: 145px">
                                    <label class="visually-hidden" for="order-note-{{ $order->id }}">Napomena {{ $order->number }}</label>
                                    <input id="order-note-{{ $order->id }}" form="order-update-{{ $order->id }}" name="note" class="form-control form-control-sm bg-body" style="width: 145px" placeholder="Napomena">
                                </div>
                            </td>
                            <td class="px-4 py-3 text-end text-nowrap">€{{ number_format($order->total, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center px-4 py-5">
                                <span class="stat-icon mb-3" aria-hidden="true">▧</span>
                                <h3 class="h6">Nema porudžbina za prikaz.</h3>
                                <p class="small text-body-secondary mb-0">Porudžbine će se pojaviti nakon završetka checkout-a.</p>
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

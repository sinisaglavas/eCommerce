@extends('layouts.admin')
@section('title', 'Porudžbine')
@section('content')
    <p class="eyebrow mb-2">UPRAVLJANJE KUPOVINAMA</p><h1 class="h2 fw-bold">Porudžbine</h1>
    <p class="text-body-secondary mb-4">Pregled kupaca, iznosa i statusa porudžbina.</p>
    <div class="alert alert-info small" role="status">Demo podaci — prikazane porudžbine i kupci su izmišljeni.</div>
    <section class="panel overflow-hidden">
        <div class="welcome-panel p-4 border-bottom"><h2 class="h5 mb-0">Sve porudžbine <span class="badge text-bg-secondary ms-2">{{ $orders->count() }}</span></h2></div>
        <div class="table-responsive">
            <table class="table table-dark table-hover admin-table align-middle mb-0">
                <caption class="visually-hidden">Demo porudžbine</caption>
                <thead><tr><th scope="col" class="p-3">Porudžbina</th><th scope="col" class="p-3">Kupac</th><th scope="col" class="p-3">Datum</th><th scope="col" class="p-3">Iznos</th><th scope="col" class="p-3">Status</th><th scope="col" class="p-3 text-end">Akcije</th></tr></thead>
                <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <th scope="row" class="p-3">{{ $order->number }}</th>
                        <td class="p-3">{{ $order->customer }}<span class="d-block small text-body-secondary">{{ $order->email }}</span></td>
                        <td class="p-3 text-nowrap">{{ $order->date }}</td>
                        <td class="p-3 text-nowrap">€{{ number_format($order->total / 100, 2, ',', '.') }}</td>
                        <td class="p-3">@include('admin.orders._status', ['status' => $order->status])</td>
                        <td class="p-3 text-end"><a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.orders.show', $order->number) }}" aria-label="Detalji porudžbine {{ $order->number }}">Detalji</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center p-5 text-body-secondary">Nema porudžbina za prikaz.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

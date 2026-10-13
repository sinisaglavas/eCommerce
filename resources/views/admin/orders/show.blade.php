@extends('layouts.admin')
@section('title', 'Porudžbina '.$order->number)
@section('content')
    <a href="{{ route('admin.orders') }}" class="small text-decoration-none">← Sve porudžbine</a>
    <div class="d-flex gap-3 align-items-center flex-wrap mt-4 mb-2"><h1 class="h2 fw-bold mb-0">{{ $order->number }}</h1>@include('admin.orders._status', ['status' => $order->status])</div>
    <p class="text-body-secondary mb-4">Datum porudžbine: {{ $order->date }}</p>
    <div class="alert alert-info small" role="status">Demo porudžbina — izmišljeni podaci za pregled interfejsa.</div>
    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <section class="panel overflow-hidden">
                <div class="welcome-panel p-4 border-bottom"><h2 class="h5 mb-0">Stavke porudžbine</h2></div>
                <div class="table-responsive">
                    <table class="table table-dark admin-table align-middle mb-0">
                        <caption class="visually-hidden">Proizvodi u porudžbini {{ $order->number }}</caption>
                        <thead><tr><th scope="col" class="p-3">Proizvod</th><th scope="col" class="p-3">Cena</th><th scope="col" class="p-3">Količina</th><th scope="col" class="p-3 text-end">Iznos</th></tr></thead>
                        <tbody>@foreach ($order->items as $item)
                            <tr><th scope="row" class="p-3">{{ $item->name }}</th><td class="p-3 text-nowrap">€{{ number_format($item->unitPrice / 100, 2, ',', '.') }}</td><td class="p-3">{{ $item->quantity }}</td><td class="p-3 text-end text-nowrap">€{{ number_format($item->quantity * $item->unitPrice / 100, 2, ',', '.') }}</td></tr>
                        @endforeach</tbody>
                        <tfoot><tr><th scope="row" colspan="3" class="p-4">Ukupno</th><td class="p-4 text-end fw-bold text-nowrap">€{{ number_format($order->total / 100, 2, ',', '.') }}</td></tr></tfoot>
                    </table>
                </div>
            </section>
        </div>
        <aside class="col-12 col-xl-4">
            <section class="panel p-4 mb-4"><h2 class="h5 mb-3">Podaci kupca</h2><p class="fw-semibold mb-1">{{ $order->customer }}</p><p class="small text-body-secondary text-break">{{ $order->email }}</p><h3 class="h6 mt-4">Adresa za dostavu</h3><p class="small text-body-secondary mb-0">{{ $order->address }}</p></section>
            <section class="panel p-4">
                <h2 class="h5 mb-3">Status porudžbine</h2>
                <form method="POST" onsubmit="return false">
                    @csrf
                    @method('PATCH')
                    <label for="status" class="form-label">Izaberite status</label>
                    <select id="status" name="status" class="form-select bg-body mb-3">@foreach ($statuses as $value => $label)<option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>@endforeach</select>
                    <p class="small text-body-secondary">Promena statusa još nije dostupna.</p>
                    <button type="submit" class="btn btn-primary w-100" disabled>Sačuvaj status</button>
                </form>
            </section>
        </aside>
    </div>
@endsection

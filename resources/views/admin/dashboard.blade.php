@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4">
        <p class="eyebrow mb-2">PREGLED PRODAVNICE</p>
        <h1 class="h2 fw-bold mb-2">Dashboard</h1>
        <p class="text-body-secondary mb-0">Katalog i porudžbine na jednom mestu.</p>
    </div>

    <section class="panel welcome-panel p-4 mb-4" aria-labelledby="welcome-title">
        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div>
                <h2 id="welcome-title" class="h4 fw-semibold">Dobro došli{{ auth()->check() ? ', '.auth()->user()->name : '' }}.</h2>
                <p class="text-body-secondary mb-0">Organizujte ponudu i pratite kupovine svojih korisnika.</p>
            </div>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary">Otvori prodavnicu <span aria-hidden="true">↗</span></a>
        </div>
    </section>

    <div class="row g-3 mb-4">
        @foreach ([
            ['label' => 'Proizvodi', 'value' => $productsCount ?? null, 'icon' => '◇', 'description' => 'Proizvodi u katalogu'],
            ['label' => 'Kategorije', 'value' => $categoriesCount ?? null, 'icon' => '▤', 'description' => 'Organizacija vaše ponude'],
            ['label' => 'Porudžbine', 'value' => $ordersCount ?? null, 'icon' => '▧', 'description' => 'Ukupan broj porudžbina'],
        ] as $stat)
            <div class="col-12 col-md-4">
                <section class="panel p-4 h-100" aria-label="{{ $stat['label'] }}">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                        <h2 class="h6 text-body-secondary mb-0">{{ $stat['label'] }}</h2>
                        <span class="stat-icon" aria-hidden="true">{{ $stat['icon'] }}</span>
                    </div>
                    <div class="stat-value">{{ $stat['value'] === null ? '—' : number_format($stat['value'], 0, ',', '.') }}</div>
                    <p class="small text-body-secondary mb-0">{{ $stat['value'] === null ? 'Podaci još nisu dostupni' : $stat['description'] }}</p>
                </section>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <section class="panel h-100" aria-labelledby="orders-title">
                <div class="p-4 border-bottom d-flex align-items-center justify-content-between gap-3 flex-wrap">
                    <div>
                        <h2 id="orders-title" class="h5 mb-1">Poslednje porudžbine</h2>
                        <p class="small text-body-secondary mb-0">Prikazano je do 5 najnovijih porudžbina.</p>
                    </div>
                    @if (Route::has('admin.orders'))
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary btn-sm">Sve porudžbine</a>
                    @endif
                </div>
                @if ($recentOrders->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-dark table-hover admin-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Porudžbina</th>
                                    <th scope="col">Kupac</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-end">Iznos</th>
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
                        <h3 class="h6">Nema porudžbina za prikaz.</h3>
                        <p class="small text-body-secondary mb-0">Ovde će biti prikazano poslednjih pet porudžbina.</p>
                    </div>
                @endif
            </section>
        </div>
        <div class="col-12 col-xl-4">
            <section class="panel p-4 h-100" aria-labelledby="shortcuts-title">
                <h2 id="shortcuts-title" class="h5 mb-2">Brze akcije</h2>
                <p class="small text-body-secondary mb-4">Dopunite i organizujte ponudu prodavnice.</p>
                <div class="d-grid gap-3">
                    @foreach (['admin.products.create' => 'Dodaj proizvod', 'admin.categories.create' => 'Dodaj kategoriju'] as $route => $label)
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

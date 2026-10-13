@extends('layouts.admin')
@section('title', 'Proizvodi')
@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div><p class="eyebrow mb-2">UPRAVLJANJE KATALOGOM</p><h1 class="h2 fw-bold">Proizvodi</h1><p class="text-body-secondary mb-0">Pregled ponude, cena i kategorija.</p></div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary px-4 py-2">+ Novi proizvod</a>
    </div>
    <section class="panel overflow-hidden">
        <div class="welcome-panel p-4 border-bottom"><h2 class="h5">Katalog proizvoda <span class="badge text-bg-secondary ms-2">{{ $products->total() }}</span></h2><p class="small text-body-secondary mb-0">Prikaz privremenih podataka iz kataloga.</p></div>
        <div class="table-responsive">
            <table class="table table-dark table-hover admin-table align-middle mb-0">
                <caption class="visually-hidden">Proizvodi u katalogu</caption>
                <thead><tr><th scope="col" class="p-3">Proizvod</th><th scope="col" class="p-3">Kategorija</th><th scope="col" class="p-3">Cena</th><th scope="col" class="p-3 text-end">Akcije</th></tr></thead>
                <tbody>
                @forelse ($products as $product)
                    <tr>
                        <th scope="row" class="p-3"><div class="d-flex gap-3 align-items-center"><img src="{{ $product->image }}" alt="" class="product-thumbnail" loading="lazy"><div>{{ $product->name }}<span class="d-block small fw-normal text-body-secondary">{{ $product->brand }}</span></div></div></th>
                        <td class="p-3">{{ $categories->get($product->categorySlug)?->name ?? $product->categorySlug }}</td>
                        <td class="p-3 text-nowrap">€{{ number_format($product->price, 2, ',', '.') }} @if($product->oldPrice)<del class="d-block small text-body-secondary">€{{ number_format($product->oldPrice, 2, ',', '.') }}</del>@endif</td>
                        <td class="p-3 text-end"><a href="{{ route('admin.products.edit', $product->slug) }}" class="btn btn-sm btn-outline-secondary" aria-label="Izmeni {{ $product->name }}">Izmeni</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center p-5 text-body-secondary">Nema proizvoda za prikaz.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-top">{{ $products->links('pagination::bootstrap-5') }}</div>
    </section>
@endsection

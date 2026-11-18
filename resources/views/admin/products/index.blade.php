@extends('layouts.admin')
@section('title', 'Products')
@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div><p class="eyebrow mb-2">CATALOG MANAGEMENT</p><h1 class="h2 fw-bold">Products</h1><p class="text-body-secondary mb-0">Browse products, prices, and categories.</p></div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary px-4 py-2">+ New product</a>
    </div>
    <section class="panel overflow-hidden">
        <div class="welcome-panel p-4 border-bottom"><h2 class="h5">Product catalog <span class="badge text-bg-secondary ms-2">{{ $products->total() }}</span></h2><p class="small text-body-secondary mb-0">Current products in your catalog.</p></div>
        <div class="table-responsive">
            <table class="table table-dark table-hover admin-table align-middle mb-0">
                <caption class="visually-hidden">Products in the catalog</caption>
                <thead><tr><th scope="col" class="p-3">Product</th><th scope="col" class="p-3">Category</th><th scope="col" class="p-3">Price</th><th scope="col" class="p-3 text-end">Actions</th></tr></thead>
                <tbody>
                @forelse ($products as $product)
                    <tr>
                        <th scope="row" class="p-3"><div class="d-flex gap-3 align-items-center"><img src="{{ $product->image }}" alt="" class="product-thumbnail" loading="lazy"><div>{{ $product->name }}<span class="d-block small fw-normal text-body-secondary">{{ $product->brand?->name }}</span></div></div></th>
                        <td class="p-3">{{ $product->category?->name ?? '—' }}</td>
                        <td class="p-3 text-nowrap">€{{ number_format($product->price, 2, ',', '.') }} @if($product->compare_at_price)<del class="d-block small text-body-secondary">€{{ number_format($product->compare_at_price, 2, ',', '.') }}</del>@endif</td>
                        <td class="p-3 text-end"><a href="{{ route('admin.products.edit', $product->slug) }}" class="btn btn-sm btn-outline-secondary" aria-label="Edit {{ $product->name }}">Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center p-5 text-body-secondary">No products to display.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-top">{{ $products->links('pagination::bootstrap-5') }}</div>
    </section>
@endsection

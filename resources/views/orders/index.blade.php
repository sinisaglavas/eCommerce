@extends('layouts.storefront')

@section('content')
  <main class="py-5"><div class="container"><div class="d-flex align-items-center justify-content-between gap-3 mb-4"><div><h1 class="h2 fw-bold section-title mb-1">My orders</h1><p class="text-secondary mb-0">Track the current status of your saved orders.</p></div><a href="{{ route('home') }}" class="btn btn-outline-light">Continue shopping</a></div>
    @forelse ($orders as $order)
      <article class="card border p-4 mb-3"><div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3"><div><p class="small text-secondary mb-1">Ordered {{ $order->created_at->format('d M Y, H:i') }}</p><h2 class="h5 mb-1">Order {{ $order->number }}</h2><span class="badge text-bg-warning">{{ ucfirst($order->status) }}</span></div><div class="text-md-end"><p class="small text-secondary mb-1">Order total</p><strong class="fs-5">€{{ number_format($order->total, 2, ',', '.') }}</strong></div></div></article>
    @empty
      <section class="card border p-5 text-center"><h2 class="h4 fw-semibold">You have not placed any orders yet.</h2><p class="text-secondary mb-4">Your saved orders will appear here.</p><a href="{{ route('home') }}" class="btn btn-primary align-self-center">Browse products</a></section>
    @endforelse
    @if ($orders->hasPages())<div class="mt-4">{{ $orders->links('pagination::bootstrap-5') }}</div>@endif
  </div></main>
@endsection

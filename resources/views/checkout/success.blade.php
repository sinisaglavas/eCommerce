@extends('layouts.storefront')

@section('content')
  <main class="py-5"><div class="container"><section class="card border p-5 text-center mx-auto" style="max-width: 680px"><h1 class="h2 fw-bold section-title mb-3">Order saved successfully</h1><p class="text-secondary mb-2">Your order <strong>{{ $order->number }}</strong> has been saved.</p><p class="text-secondary mb-4">You will receive payment details by email shortly.</p><div class="d-flex justify-content-center flex-wrap gap-2"><a href="{{ route('orders.show', $order) }}" class="btn btn-primary">Track order</a><a href="{{ route('orders.index') }}" class="btn btn-outline-light">My orders</a></div></section></div></main>
@endsection

@extends('layouts.storefront')

@section('content')
  <main class="py-5"><div class="container"><section class="card border p-5 text-center mx-auto" style="max-width: 680px"><h1 class="h2 fw-bold section-title mb-3">Order saved successfully</h1><p class="text-secondary mb-2">Your order <strong>{{ $order->number }}</strong> has been saved.</p><p class="text-secondary mb-4">You will receive payment details by email shortly.</p><a href="{{ route('orders.index') }}" class="btn btn-primary">My orders</a></section></div></main>
@endsection

@extends('layouts.storefront')

@section('content')
  <main class="py-5"><div class="container">
    <div class="d-flex align-items-center justify-content-between gap-3 mb-4"><div><h1 class="h2 fw-bold section-title mb-1">Your cart</h1><p class="text-secondary mb-0">Review your items before checkout.</p></div><a href="{{ route('home') }}" class="btn btn-outline-light">Continue shopping</a></div>
    @if (session('success'))<div class="alert alert-success" role="alert">{{ session('success') }}</div>@endif
    @if ($cartItems->isEmpty())
      <div class="card border p-5 text-center"><h2 class="h4 fw-semibold">Your cart is empty.</h2><p class="text-secondary mb-4">Add a product to get started.</p><a href="{{ route('home') }}" class="btn btn-primary align-self-center">Browse products</a></div>
    @else
      <div class="row g-4 align-items-start"><div class="col-lg-8"><div class="card border"><div class="list-group list-group-flush">
        @foreach ($cartItems as $cartItem)
          <article class="list-group-item p-4"><div class="row g-3 align-items-center"><div class="col-4 col-sm-3"><img src="{{ $cartItem['product']->image }}" alt="{{ $cartItem['product']->name }}" class="img-fluid rounded" loading="lazy"></div><div class="col-8 col-sm-5"><p class="small text-secondary mb-1">{{ $cartItem['product']->brand->name }}</p><h2 class="h5 mb-2"><a href="{{ route('products.show', $cartItem['product']->slug) }}" class="text-body">{{ $cartItem['product']->name }}</a></h2><span class="fw-semibold">€{{ number_format($cartItem['product']->price, 2, ',', '.') }}</span></div><div class="col-7 col-sm-2"><form method="POST" action="{{ route('cart.update', $cartItem['product']) }}">@csrf @method('PATCH')<label for="quantity-{{ $cartItem['product']->id }}" class="visually-hidden">Quantity for {{ $cartItem['product']->name }}</label><input id="quantity-{{ $cartItem['product']->id }}" name="quantity" type="number" min="1" max="{{ $cartItem['product']->stock_quantity }}" value="{{ $cartItem['quantity'] }}" class="form-control" onchange="this.form.submit()"></form></div><div class="col-5 col-sm-2 text-end"><p class="fw-bold mb-2">€{{ number_format($cartItem['subtotal'], 2, ',', '.') }}</p><form method="POST" action="{{ route('cart.destroy', $cartItem['product']) }}">@csrf @method('DELETE')<button type="submit" class="btn btn-link text-danger p-0">Remove</button></form></div></div></article>
        @endforeach
      </div></div></div><aside class="col-lg-4"><div class="card border p-4"><h2 class="h4 fw-semibold mb-4">Order summary</h2><div class="d-flex justify-content-between gap-3 mb-4"><span class="text-secondary">Total</span><strong class="fs-5">€{{ number_format($total, 2, ',', '.') }}</strong></div><a href="{{ route('checkout.create') }}" class="btn btn-primary w-100">Checkout</a></div></aside></div>
    @endif
  </div></main>
@endsection

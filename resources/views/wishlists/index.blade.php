@extends('layouts.storefront')

@section('content')
  <main>
    <div id="page-content" aria-live="polite">
      <section class="py-5">
        <div class="container">
          <div class="d-flex align-items-center gap-3 mb-4">
            <span class="section-icon c-pink"><svg class="ico"><use href="#i-heart"/></svg></span>
            <div>
              <h1 class="h2 fw-bold section-title mb-0">Your wishlist</h1>
              <p class="text-secondary mb-0">Products you have saved for later</p>
            </div>
          </div>

          @if ($products->isEmpty())
            <div class="card border p-5 text-center">
              <svg class="ico text-secondary mx-auto mb-3" style="font-size:36px"><use href="#i-heart"/></svg>
              <h2 class="h4">Your wishlist is empty</h2>
              <p class="text-secondary mb-4">Save products to return to them later.</p>
              <a href="{{ route('home') }}" class="btn btn-primary align-self-center" data-ajax-link data-update-target="#page-content">Browse products</a>
            </div>
          @else
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4 p-3 rounded-3 border bg-surface-2">
              <div>
                <p class="small fw-semibold mb-1">Odaberite proizvode</p>
                <p class="small text-secondary mb-0">Dodajte jedan ili više sačuvanih proizvoda u korpu.</p>
              </div>
              <button type="button" class="btn btn-primary" data-wishlist-add-selected disabled>
                Add selected to cart
              </button>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-4 gy-5">
              @foreach ($products as $product)
                <div class="col">
                  @include('partials.product-card', ['product' => $product])
                  @php($isInCart = ($cartQuantities[$product->id] ?? 0) > 0)
                  <div class="d-flex align-items-center justify-content-between gap-2 mt-2 px-1">
                    <label class="form-check d-flex align-items-center gap-2 mb-0 small text-secondary">
                      <input class="form-check-input mt-0" type="checkbox" value="{{ $product->id }}" data-wishlist-select data-cart-url="{{ route('cart.store', $product) }}" aria-label="Select {{ $product->name }}" @disabled($isInCart)>
                      Select product
                    </label>
                    <form method="POST" action="{{ $isInCart ? route('cart.destroy', $product) : route('cart.store', $product) }}" class="d-inline" data-cart-form data-cart-in-cart="{{ $isInCart ? 'true' : 'false' }}" data-cart-add-url="{{ route('cart.store', $product) }}" data-cart-remove-url="{{ route('cart.destroy', $product) }}">
                      @csrf
                      @if ($isInCart)
                        @method('DELETE')
                      @endif
                      <button type="submit" class="btn btn-{{ $isInCart ? 'outline-light' : 'outline-primary' }} btn-sm text-nowrap" data-cart-button>{{ $isInCart ? 'Remove from cart' : 'Add to cart' }}</button>
                    </form>
                  </div>
                </div>
              @endforeach
            </div>
            <div class="mt-4">{{ $products->links() }}</div>
          @endif
        </div>
      </section>
    </div>
  </main>
@endsection

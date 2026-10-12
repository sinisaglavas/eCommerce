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
            <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-4">
              @foreach ($products as $product)
                <div class="col">@include('partials.product-card', ['product' => $product])</div>
              @endforeach
            </div>
            <div class="mt-4">{{ $products->links() }}</div>
          @endif
        </div>
      </section>
    </div>
  </main>
@endsection

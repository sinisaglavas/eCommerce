@extends('layouts.storefront')

@section('content')
  <main>
    <section class="py-5">
      <div class="container">
        <a href="{{ route('home') }}#categories" class="small text-secondary d-inline-flex align-items-center gap-2 mb-4">
          <svg class="ico"><use href="#i-arrow" style="transform:rotate(180deg)"/></svg>All categories
        </a>

        <div class="d-flex align-items-center gap-3 mb-4">
          <span class="section-icon {{ $category->color }}"><svg class="ico"><use href="#i-{{ $category->icon }}"/></svg></span>
          <div>
            <p class="text-secondary text-uppercase small fw-semibold mb-1">Category</p>
            <h1 class="fw-bold section-title mb-0">{{ $category->name }}</h1>
          </div>
        </div>

        <div class="row g-4 align-items-start">
          <aside class="col-lg-3" aria-label="Product filters">
            <form class="filter-panel card border p-4" method="GET" action="{{ route('categories.show', $category->slug) }}">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Filters</h2>
                <a href="{{ route('categories.show', $category->slug) }}" class="small text-primary">Clear</a>
              </div>

              <fieldset class="border-0 p-0 m-0">
                <legend class="fs-6 fw-semibold mb-3">Price (€)</legend>
                <div class="mb-3">
                  <label class="form-label small text-secondary" for="min-price">Minimum</label>
                  <input class="form-control" id="min-price" name="min_price" type="number" min="0" step="1" value="{{ $filters['min_price'] ?? '' }}" placeholder="0">
                </div>
                <div class="mb-4">
                  <label class="form-label small text-secondary" for="max-price">Maximum</label>
                  <input class="form-control" id="max-price" name="max_price" type="number" min="0" step="1" value="{{ $filters['max_price'] ?? '' }}" placeholder="Any price">
                </div>
              </fieldset>

              <button class="btn btn-primary w-100" type="submit">Apply filters</button>
            </form>
          </aside>

          <div class="col-lg-9">
            <p class="text-secondary mb-4">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} found</p>

            @if ($products->isEmpty())
              <div class="card border p-5 text-center">
                <h2 class="h4">No products found</h2>
                <p class="text-secondary mb-3">Try expanding your price range.</p>
                <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-outline-primary align-self-center">Clear filters</a>
              </div>
            @else
              <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4">
                @foreach ($products as $product)
                  <div class="col">
                    <article class="product-card card h-100 border overflow-hidden">
                      <div class="product-art {{ $product->accent }}">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy">
                      </div>
                      <div class="card-body d-flex flex-column p-4">
                        <p class="text-secondary small mb-2">{{ $product->brand }}</p>
                        <h2 class="h5 fw-semibold">{{ $product->name }}</h2>
                        <div class="mt-auto pt-3 d-flex justify-content-between align-items-center gap-3">
                          <div>
                            <span class="fw-bold fs-5">€{{ number_format($product->price, 0, ',', '.') }}</span>
                            @if ($product->oldPrice)
                              <del class="text-secondary small ms-1">€{{ number_format($product->oldPrice, 0, ',', '.') }}</del>
                            @endif
                          </div>
                          <button class="btn btn-primary btn-sm" type="button">Add to cart</button>
                        </div>
                      </div>
                    </article>
                  </div>
                @endforeach
              </div>

              @if ($products->hasPages())
                <nav class="mt-5" aria-label="Product pagination">
                  <ul class="pagination justify-content-center mb-0">
                    <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                      <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">&laquo;</a>
                    </li>
                    @for ($page = 1; $page <= $products->lastPage(); $page++)
                      <li class="page-item {{ $page === $products->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $products->url($page) }}">{{ $page }}</a>
                      </li>
                    @endfor
                    <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                      <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">&raquo;</a>
                    </li>
                  </ul>
                </nav>
              @endif
            @endif
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection

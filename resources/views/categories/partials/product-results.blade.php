@if ($products->isEmpty())
  <div class="card border p-5 text-center">
    <div class="category-icon {{ $category->color }} mx-auto mb-3"><svg class="ico"><use href="#i-{{ $category->icon }}"/></svg></div>
    <h2 class="h4">No {{ Str::lower($category->name) }} match these filters</h2>
    <p class="text-secondary mb-3">Try expanding your price range.</p>
    <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-outline-primary align-self-center" data-ajax-link data-update-target="#page-content">Clear filters</a>
  </div>
@else
  <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4">
    @foreach ($products as $product)
      <div class="col">
        <a href="{{ route('products.show', $product->slug) }}" class="product-card card h-100 border overflow-hidden text-body" data-ajax-link data-update-target="#page-content">
          <div class="product-art {{ $product->accent }}">
            @if ($product->oldPrice)
              <span class="product-discount-badge">-{{ round((1 - $product->price / $product->oldPrice) * 100) }}%</span>
            @endif
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
              <span class="btn btn-primary btn-sm">View product</span>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

  @if ($products->hasPages())
    <nav class="pagination-bar mt-5" aria-label="Product pagination">
      <a
        class="page-nav-btn {{ $products->onFirstPage() ? 'disabled' : '' }}"
        href="{{ $products->previousPageUrl() ?? '#' }}"
        aria-label="Previous"
        data-ajax-link
        data-update-target="#page-content"
      >
        <svg class="ico"><use href="#i-arrow" style="transform:rotate(180deg)"/></svg>
        <span class="d-none d-sm-inline">Previous</span>
      </a>

      <ul class="pagination mb-0">
        @for ($page = 1; $page <= $products->lastPage(); $page++)
          <li class="page-item {{ $page === $products->currentPage() ? 'active' : '' }}">
            <a class="page-link" href="{{ $products->url($page) }}" data-ajax-link data-update-target="#page-content">{{ $page }}</a>
          </li>
        @endfor
      </ul>

      <a
        class="page-nav-btn next {{ $products->hasMorePages() ? '' : 'disabled' }}"
        href="{{ $products->nextPageUrl() ?? '#' }}"
        aria-label="Next"
        data-ajax-link
        data-update-target="#page-content"
      >
        <span class="d-none d-sm-inline">Next</span>
        <svg class="ico"><use href="#i-arrow"/></svg>
      </a>
    </nav>
  @endif
@endif

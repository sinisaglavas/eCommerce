<p class="text-secondary mb-4">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} found</p>

@if ($products->isEmpty())
  <div class="card border p-5 text-center">
    <h2 class="h4">No products found</h2>
    <p class="text-secondary mb-3">Try expanding your price range.</p>
    <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-outline-primary align-self-center" data-ajax-link data-update-target="#page-content">Clear filters</a>
  </div>
@else
  <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4">
    @foreach ($products as $product)
      <div class="col">
        <a href="{{ route('products.show', $product->slug) }}" class="product-card card h-100 border overflow-hidden text-body" data-ajax-link data-update-target="#page-content">
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
              <span class="btn btn-primary btn-sm">View product</span>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

  @if ($products->hasPages())
    <nav class="mt-5" aria-label="Product pagination">
      <ul class="pagination justify-content-center mb-0">
        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
          <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous" data-ajax-link data-update-target="#page-content">&laquo;</a>
        </li>
        @for ($page = 1; $page <= $products->lastPage(); $page++)
          <li class="page-item {{ $page === $products->currentPage() ? 'active' : '' }}">
            <a class="page-link" href="{{ $products->url($page) }}" data-ajax-link data-update-target="#page-content">{{ $page }}</a>
          </li>
        @endfor
        <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
          <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next" data-ajax-link data-update-target="#page-content">&raquo;</a>
        </li>
      </ul>
    </nav>
  @endif
@endif

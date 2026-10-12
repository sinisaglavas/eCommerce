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
        @include('partials.product-card', ['product' => $product])
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

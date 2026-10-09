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
        <form class="filter-panel card border p-4" method="GET" action="{{ route('categories.show', $category->slug) }}" data-ajax-filter-form data-update-target="#page-content">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">Filters</h2>
            <a href="{{ route('categories.show', $category->slug) }}" class="small text-primary" data-ajax-link data-update-target="#page-content">Clear</a>
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
        @include('categories.partials.product-results')
      </div>
    </div>
  </div>
</section>

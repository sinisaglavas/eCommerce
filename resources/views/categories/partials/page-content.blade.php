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
        <form class="filter-panel card border" method="GET" action="{{ route('categories.show', $category->slug) }}" data-ajax-filter-form data-update-target="#page-content">
          <div class="filter-panel-header">
            <span class="filter-panel-icon"><svg class="ico"><use href="#i-tag"/></svg></span>
            <div>
              <h2 class="h5 mb-1">Refine products</h2>
              <p class="small text-secondary mb-0">Set your budget</p>
            </div>
            <a href="{{ route('categories.show', $category->slug) }}" class="filter-clear ms-auto" data-ajax-link data-update-target="#page-content">Clear</a>
          </div>

          <div class="filter-panel-body">
            <fieldset class="border-0 p-0 m-0">
              <legend class="filter-section-title">Price range</legend>
              <div class="filter-price-grid">
                <div>
                  <label class="form-label small text-secondary" for="min-price">Minimum</label>
                  <div class="filter-price-input">
                    <span>€</span>
                    <input id="min-price" name="min_price" type="number" min="0" step="1" value="{{ $filters['min_price'] ?? '' }}" placeholder="0">
                  </div>
                </div>
                <span class="filter-range-separator" aria-hidden="true"></span>
                <div>
                  <label class="form-label small text-secondary" for="max-price">Maximum</label>
                  <div class="filter-price-input">
                    <span>€</span>
                    <input id="max-price" name="max_price" type="number" min="0" step="1" value="{{ $filters['max_price'] ?? '' }}" placeholder="Any">
                  </div>
                </div>
              </div>
            </fieldset>

            <div class="filter-info">
              <svg class="ico"><use href="#i-sparkle"/></svg>
              Prices include VAT
            </div>
          </div>

          <div class="filter-panel-footer">
            <button class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2" type="submit">
              Apply filters <svg class="ico"><use href="#i-arrow"/></svg>
            </button>
          </div>
        </form>
      </aside>

      <div class="col-lg-9">
        @include('categories.partials.product-results')
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <a href="{{ route('categories.show', $category->slug) }}" class="small text-secondary d-inline-flex align-items-center gap-2 mb-4" data-ajax-link data-update-target="#page-content">
      <svg class="ico"><use href="#i-arrow" style="transform:rotate(180deg)"/></svg>Back to {{ $category->name }}
    </a>

    <article class="product-detail card border overflow-hidden">
      <div class="row g-0">
        <div class="col-lg-6">
          <div class="product-detail-image {{ $product->accent }}">
            <img src="{{ $product->image }}" alt="{{ $product->name }}">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="p-4 p-lg-5 h-100 d-flex flex-column">
            <p class="text-primary small text-uppercase fw-semibold mb-2">{{ $product->brand }}</p>
            <h1 class="display-6 fw-bold section-title">{{ $product->name }}</h1>
            <p class="text-secondary mt-3">{{ $product->description }}</p>

            <div class="d-flex align-items-baseline gap-2 my-3">
              <span class="display-6 fw-bold">€{{ number_format($product->price, 0, ',', '.') }}</span>
              @if ($product->oldPrice)
                <del class="text-secondary">€{{ number_format($product->oldPrice, 0, ',', '.') }}</del>
              @endif
            </div>

            <span class="d-inline-flex align-items-center gap-2 text-success small fw-semibold mb-4">
              <svg class="ico"><use href="#i-shield"/></svg>In stock · Ready to ship
            </span>

            <button class="btn btn-primary btn-lg fw-semibold align-self-start px-4" type="button">Add to cart</button>
          </div>
        </div>
      </div>
    </article>

    <section class="mt-5">
      <h2 class="h3 fw-bold section-title mb-4">Product details</h2>
      <div class="row g-4">
        <div class="col-lg-7">
          <div class="card border h-100 p-4">
            <h3 class="h5 fw-semibold mb-3">Description</h3>
            <p class="text-secondary mb-0">{{ $product->description }} Built for a reliable and enjoyable experience, with support from our standard two-year warranty.</p>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card border h-100 p-4">
            <h3 class="h5 fw-semibold mb-3">Specifications</h3>
            <dl class="row small mb-0">
              @foreach ($product->specifications as $label => $value)
                <dt class="col-6 text-secondary fw-normal mb-2">{{ $label }}</dt>
                <dd class="col-6 fw-semibold mb-2">{{ $value }}</dd>
              @endforeach
            </dl>
          </div>
        </div>
      </div>
    </section>
  </div>
</section>

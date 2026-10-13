<section class="py-5">
  <div class="container">
    <a href="{{ route('categories.show', $category->slug) }}" class="small text-secondary d-inline-flex align-items-center gap-2 mb-4" data-ajax-link data-update-target="#page-content">
      <svg class="ico"><use href="#i-arrow" style="transform:rotate(180deg)"/></svg>Back to {{ $category->name }}
    </a>

    <article class="product-detail">
      <div class="row g-4 g-xl-5 align-items-start">
        <div class="col-lg-5">
          <div class="product-gallery {{ $product->accent }}">
            <div class="product-gallery-main">
              @if ($product->image)
                <img src="{{ $product->image }}" alt="{{ $product->name }}" data-product-gallery-main>
                @if (count($product->gallery) > 1)
                  <button type="button" class="product-gallery-arrow product-gallery-arrow-prev" data-product-gallery-prev aria-label="Previous product image">
                    <svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg>
                  </button>
                  <button type="button" class="product-gallery-arrow product-gallery-arrow-next" data-product-gallery-next aria-label="Next product image">
                    <svg class="ico" aria-hidden="true"><use href="#i-arrow"/></svg>
                  </button>
                @endif
              @else
                <p class="text-secondary p-4">No images available.</p>
              @endif
              <span class="product-image-count"><svg class="ico"><use href="#i-camera"/></svg>{{ count($product->gallery) }} photo{{ count($product->gallery) === 1 ? '' : 's' }}</span>
            </div>

            @if (count($product->gallery) > 1)
              <div class="product-gallery-thumbnails" aria-label="Product images">
                @foreach ($product->gallery as $image)
                  <button class="product-gallery-thumbnail {{ $loop->first ? 'is-active' : '' }}" type="button" data-product-gallery-thumbnail aria-label="View {{ $product->name }} image {{ $loop->iteration }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                    <img src="{{ $image }}" alt="{{ $product->name }} image {{ $loop->iteration }}" loading="lazy">
                  </button>
                @endforeach
              </div>
            @endif
          </div>
        </div>
        <div class="col-lg-7">
          <div class="product-summary">
            <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
              <span class="product-brand">{{ $product->brand->name }}</span>
              <span class="product-stock"><svg class="ico"><use href="#i-shield"/></svg>{{ $product->stock_quantity > 0 ? 'In stock' : 'Out of stock' }}</span>
            </div>

            <h1 class="display-6 fw-bold section-title mb-3">{{ $product->name }}</h1>
            <p class="text-secondary mb-4">{{ $product->description }}</p>

            <div class="product-price-panel mb-4">
              <div>
                <span class="product-price">€{{ number_format($product->price, 0, ',', '.') }}</span>
                @if ($product->oldPrice)
                  <del class="text-secondary ms-2">€{{ number_format($product->oldPrice, 0, ',', '.') }}</del>
                @endif
              </div>
              <span class="small text-secondary">VAT included</span>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-4">
              <span class="product-benefit"><svg class="ico"><use href="#i-truck"/></svg>Free delivery</span>
              <span class="product-benefit"><svg class="ico"><use href="#i-refresh"/></svg>30-day returns</span>
              <span class="product-benefit"><svg class="ico"><use href="#i-shield"/></svg>{{ $product->warranty_months }}-month warranty</span>
            </div>

            <div class="d-flex flex-wrap gap-2">
              <button class="btn btn-primary btn-lg fw-semibold px-4" type="button">Add to cart</button>
              @auth
                <button class="btn btn-outline-light btn-lg fw-semibold px-4" type="button" data-wishlist-button data-product-id="{{ $product->id }}" data-wishlist-url="{{ route('wishlists.toggle', $product) }}" data-wishlisted="{{ $isWishlisted ? 'true' : 'false' }}">
                  <svg class="ico"><use href="#i-heart"/></svg>
                  <span data-wishlist-label>{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}</span>
                </button>
              @else
                <a class="btn btn-outline-light btn-lg fw-semibold px-4" href="{{ route('login') }}">
                  <svg class="ico"><use href="#i-heart"/></svg> Add to wishlist
                </a>
              @endauth
            </div>
          </div>
        </div>
      </div>
    </article>

    <section class="mt-5">
      <h2 class="h3 fw-bold section-title mb-4">Product details</h2>
      <div class="row g-4">
        <div class="col-lg-7">
          <div class="card border h-100 p-4 product-detail-card">
            <h3 class="h5 fw-semibold mb-3">Description</h3>
            <p class="text-secondary mb-0">{{ $product->description }} Built for a reliable and enjoyable experience, with support from our standard two-year warranty.</p>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card border h-100 p-4 product-detail-card">
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

    @if ($relatedProducts->isNotEmpty())
      <section class="mt-5">
        <div class="d-flex align-items-center gap-3 mb-4">
          <span class="section-icon {{ $category->color }}"><svg class="ico"><use href="#i-sparkle"/></svg></span>
          <div>
            <h2 class="h3 fw-bold section-title mb-0">You might also like</h2>
            <p class="text-secondary mb-0">More from {{ $category->name }}</p>
          </div>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-4">
          @foreach ($relatedProducts as $relatedProduct)
            <div class="col">
              @include('partials.product-card', ['product' => $relatedProduct])
            </div>
          @endforeach
        </div>
      </section>
    @endif
  </div>
</section>

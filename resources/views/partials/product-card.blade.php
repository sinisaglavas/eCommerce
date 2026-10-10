<a href="{{ route('products.show', $product->slug) }}" class="product-card card h-100 border overflow-hidden text-body" data-ajax-link data-update-target="#page-content">
  <div class="product-art {{ $product->accent }}">
    <div class="product-art-badges">
      @if ($product->oldPrice)
        <span class="product-discount-badge">-{{ round((1 - $product->price / $product->oldPrice) * 100) }}%</span>
      @endif
      @if (($product->tag ?? null) === 'bestseller')
        <span class="product-tag-badge tag-bestseller">Bestseller</span>
      @elseif (($product->tag ?? null) === 'new')
        <span class="product-tag-badge tag-new">New</span>
      @endif
    </div>

    <button type="button" class="product-wishlist-btn" data-wishlist-toggle aria-label="Add {{ $product->name }} to wishlist">
      <svg class="ico"><use href="#i-heart"/></svg>
    </button>

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

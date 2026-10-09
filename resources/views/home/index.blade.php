@extends('layouts.storefront')

@section('content')

  <main>
    <!-- Hero -->
    <section class="pt-4 pb-2">
      <div class="container">
        <div class="row g-3">
          <div class="col-lg-8">
            <div class="hero-main text-white p-4 p-md-5 d-flex flex-column justify-content-center h-100">
              <span class="hero-art"><svg class="ico"><use href="#i-laptop"/></svg></span>
              <span class="badge rounded-pill glass-badge align-self-start px-3 py-2 mb-3 d-inline-flex align-items-center gap-2">
                <svg class="ico" style="color:#fbbf24"><use href="#i-sparkle"/></svg> New collection 2026
              </span>
              <h1 class="fw-bold mb-3" style="max-width:520px">Technology that keeps up with you</h1>
              <p class="fs-5 mb-4" style="color:#cbd5e1; max-width:440px">The latest laptops, phones and gaming gear at the best prices. Up to 24 interest-free installments.</p>
              <a href="#categories" class="btn btn-light btn-lg fw-semibold align-self-start rounded-3 px-4 d-inline-flex align-items-center gap-2">
                Browse categories <svg class="ico"><use href="#i-arrow"/></svg>
              </a>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="row g-3 h-100">
              <div class="col-sm-6 col-lg-12">
                <a href="#" class="promo promo-orange text-white p-4 d-flex flex-column justify-content-end h-100">
                  <span class="promo-art"><svg class="ico"><use href="#i-percent"/></svg></span>
                  <small class="text-uppercase fw-semibold opacity-75">Black Week</small>
                  <strong class="fs-4 lh-sm">Up to 40% off TVs</strong>
                </a>
              </div>
              <div class="col-sm-6 col-lg-12">
                <a href="#" class="promo promo-purple text-white p-4 d-flex flex-column justify-content-end h-100">
                  <span class="promo-art"><svg class="ico"><use href="#i-gamepad"/></svg></span>
                  <small class="text-uppercase fw-semibold opacity-75">Gaming zone</small>
                  <strong class="fs-4 lh-sm">Consoles &amp; accessories</strong>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Perks -->
    <section class="py-3">
      <div class="container">
        <div class="bg-surface border rounded-4 p-4">
          <div class="row g-3">
            <div class="col-6 col-lg-3 d-flex align-items-center gap-3">
              <div class="perk-icon c-blue"><svg class="ico"><use href="#i-truck"/></svg></div>
              <div><div class="fw-semibold small">Fast delivery</div><div class="text-secondary small">Within 24–48h</div></div>
            </div>
            <div class="col-6 col-lg-3 d-flex align-items-center gap-3">
              <div class="perk-icon c-green"><svg class="ico"><use href="#i-shield"/></svg></div>
              <div><div class="fw-semibold small">Warranty</div><div class="text-secondary small">Up to 5 years</div></div>
            </div>
            <div class="col-6 col-lg-3 d-flex align-items-center gap-3">
              <div class="perk-icon c-violet"><svg class="ico"><use href="#i-card"/></svg></div>
              <div><div class="fw-semibold small">Pay in installments</div><div class="text-secondary small">Interest-free</div></div>
            </div>
            <div class="col-6 col-lg-3 d-flex align-items-center gap-3">
              <div class="perk-icon c-orange"><svg class="ico"><use href="#i-refresh"/></svg></div>
              <div><div class="fw-semibold small">Easy returns</div><div class="text-secondary small">14 days, no questions asked</div></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Categories -->
    <section class="py-5" id="categories">
      <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 gap-3">
          <div class="d-flex align-items-center gap-3">
            <span class="section-icon"><svg class="ico"><use href="#i-grid"/></svg></span>
            <div>
              <h2 class="fw-bold section-title mb-0">Shop by category</h2>
              <p class="text-secondary mb-0">Find exactly what you need</p>
            </div>
          </div>
          <a href="#" class="fw-semibold text-primary text-nowrap d-inline-flex align-items-center gap-1">All categories <svg class="ico"><use href="#i-arrow"/></svg></a>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3">
          @foreach ($categories as $category)
            <div class="col">
              <a href="{{ route('categories.show', $category->slug) }}" class="category-card card h-100 border text-center p-4 align-items-center text-body {{ $category->color }} {{ $category->badge ? 'position-relative' : '' }}" data-ajax-link data-update-target="#page-content">
                @if ($category->badge)
                  <span class="badge {{ $category->badgeClass }} position-absolute top-0 end-0 m-2">{{ $category->badge }}</span>
                @endif
                <div class="category-icon mb-3"><svg class="ico"><use href="#i-{{ $category->icon }}"/></svg></div>
                <h3 class="fs-6 fw-semibold mb-0">{{ $category->name }}</h3>
                <small class="text-secondary">{{ $category->productCount }} products</small>
                <span class="arrow mt-2"><svg class="ico"><use href="#i-arrow"/></svg></span>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    <!-- Featured categories -->
    <section class="pb-5">
      <div class="container">
        <div class="d-flex align-items-center gap-3 mb-4">
          <span class="section-icon"><svg class="ico"><use href="#i-star"/></svg></span>
          <div>
            <h2 class="fw-bold section-title mb-0">Featured</h2>
            <p class="text-secondary mb-0">Most popular categories this week</p>
          </div>
        </div>
        <div class="row g-3">
          <div class="col-md-4">
            <a href="#" class="feature-card f1 text-white p-4 d-flex flex-column justify-content-between h-100">
              <div>
                <span class="chip"><svg class="ico"><use href="#i-laptop"/></svg></span>
                <h3 class="fw-bold fs-4">Laptops for work</h3>
                <p class="opacity-75 small mb-0" style="max-width:200px">Light, fast and with all-day battery life</p>
              </div>
              <span class="go align-self-start rounded-3 px-3 py-2 small fw-semibold mt-3">Explore <svg class="ico"><use href="#i-arrow"/></svg></span>
              <span class="art"><svg class="ico"><use href="#i-laptop"/></svg></span>
            </a>
          </div>
          <div class="col-md-4">
            <a href="#" class="feature-card f2 text-white p-4 d-flex flex-column justify-content-between h-100">
              <div>
                <span class="chip"><svg class="ico"><use href="#i-home"/></svg></span>
                <h3 class="fw-bold fs-4">Smart home</h3>
                <p class="opacity-75 small mb-0" style="max-width:200px">Bulbs, cameras and sensors at your fingertips</p>
              </div>
              <span class="go align-self-start rounded-3 px-3 py-2 small fw-semibold mt-3">Explore <svg class="ico"><use href="#i-arrow"/></svg></span>
              <span class="art"><svg class="ico"><use href="#i-home"/></svg></span>
            </a>
          </div>
          <div class="col-md-4">
            <a href="#" class="feature-card f3 text-white p-4 d-flex flex-column justify-content-between h-100">
              <div>
                <span class="chip"><svg class="ico"><use href="#i-gamepad"/></svg></span>
                <h3 class="fw-bold fs-4">Gaming setup</h3>
                <p class="opacity-75 small mb-0" style="max-width:200px">Everything for the ultimate gaming experience</p>
              </div>
              <span class="go align-self-start rounded-3 px-3 py-2 small fw-semibold mt-3">Explore <svg class="ico"><use href="#i-arrow"/></svg></span>
              <span class="art"><svg class="ico"><use href="#i-gamepad"/></svg></span>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Brands -->
    <section class="pb-5">
      <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 gap-3">
          <div class="d-flex align-items-center gap-3">
            <span class="section-icon"><svg class="ico"><use href="#i-tag"/></svg></span>
            <h2 class="fw-bold section-title mb-0">Popular brands</h2>
          </div>
          <a href="#" class="fw-semibold text-primary text-nowrap d-inline-flex align-items-center gap-1">All brands <svg class="ico"><use href="#i-arrow"/></svg></a>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
          <div class="col"><a href="#" class="brand bg-surface border rounded-3 d-grid fw-800" style="place-items:center">BRAND 1</a></div>
          <div class="col"><a href="#" class="brand bg-surface border rounded-3 d-grid fw-800" style="place-items:center">BRAND 2</a></div>
          <div class="col"><a href="#" class="brand bg-surface border rounded-3 d-grid fw-800" style="place-items:center">BRAND 3</a></div>
          <div class="col"><a href="#" class="brand bg-surface border rounded-3 d-grid fw-800" style="place-items:center">BRAND 4</a></div>
          <div class="col"><a href="#" class="brand bg-surface border rounded-3 d-grid fw-800" style="place-items:center">BRAND 5</a></div>
          <div class="col"><a href="#" class="brand bg-surface border rounded-3 d-grid fw-800" style="place-items:center">BRAND 6</a></div>
        </div>
      </div>
    </section>

    <!-- Newsletter -->
    <section class="pb-5">
      <div class="container">
        <div class="newsletter text-white p-4 p-md-5">
          <div class="row g-4 align-items-center">
            <div class="col-lg-6 d-flex align-items-center gap-3">
              <span class="mail-icon"><svg class="ico"><use href="#i-mail"/></svg></span>
              <div>
                <h2 class="fw-bold fs-3 mb-1">Be the first to hear about deals</h2>
                <p class="text-secondary mb-0">Sign up and get 10% off your first order.</p>
              </div>
            </div>
            <div class="col-lg-6">
              <form class="d-flex gap-2" onsubmit="return false">
                <input type="email" class="form-control form-control-lg rounded-3" placeholder="Your email address">
                <button class="btn btn-primary btn-lg rounded-3 fw-semibold text-nowrap" type="submit">Subscribe</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection

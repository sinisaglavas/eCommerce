  <!-- Header -->
  <header class="bg-surface border-bottom sticky-top">
    <div class="container py-3">
      <div class="row g-3 align-items-center">
        <div class="col-auto">
          <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 fs-4 fw-800 text-body">
            <span class="logo-mark"><svg class="ico"><use href="#i-bolt"/></svg></span>
            {{ config('app.name') }}
          </a>
        </div>

        <div class="col-12 col-lg order-3 order-lg-2">
          <div class="search-box-wrapper">
            <form class="search-box d-flex align-items-center border rounded-3 ps-3 pe-1" role="search" action="{{ route('search') }}" data-search-form onsubmit="return false">
              <svg class="ico"><use href="#i-search"/></svg>
              <input class="form-control border-0 bg-transparent" type="search" name="q" placeholder="Search laptops, phones, TVs..." autocomplete="off" data-search-input>
              <button class="search-submit-btn" type="submit" aria-label="Search">
                <svg class="ico"><use href="#i-search"/></svg>
              </button>
            </form>
            <div class="search-results-dropdown" data-search-results hidden></div>
          </div>
        </div>

        <div class="col order-2 order-lg-3 d-flex justify-content-end align-items-center gap-2 gap-sm-3">
          @auth
            <div class="dropdown">
              <button class="hbtn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" title="Wishlist">
                <svg class="ico"><use href="#i-heart"/></svg>
                <span class="count pink" data-wishlist-count>{{ $wishlistCount }}</span>
              </button>
              <div class="dropdown-menu dropdown-menu-end notif-menu mt-2">
                <div class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
                  <strong>Wishlist</strong>
                  <span class="small text-secondary"><span data-wishlist-count>{{ $wishlistCount }}</span> saved</span>
                </div>
                <div data-wishlist-dropdown-list>
                @forelse ($wishlistItems as $wishlistItem)
                  <a href="{{ route('products.show', $wishlistItem->product->slug) }}" class="notif-item c-pink" data-wishlist-dropdown-item data-product-id="{{ $wishlistItem->product->id }}">
                    <span class="notif-icon overflow-hidden"><img src="{{ $wishlistItem->product->image }}" alt="" class="w-100 h-100 object-fit-cover"></span>
                    <span>
                      <span class="d-block small fw-semibold">{{ $wishlistItem->product->name }}</span>
                      <span class="d-block small text-secondary">€{{ number_format($wishlistItem->product->price, 0, ',', '.') }}</span>
                    </span>
                  </a>
                @empty
                  <p class="small text-secondary text-center px-3 py-4 mb-0" data-wishlist-empty>Your wishlist is empty.</p>
                @endforelse
                </div>
                <a href="{{ route('wishlists.index') }}" class="d-block text-center small text-primary fw-semibold py-3">View all wishlist</a>
              </div>
            </div>
          @else
            <a href="{{ route('login') }}" class="hbtn" title="Wishlist">
              <svg class="ico"><use href="#i-heart"/></svg>
              <span class="count pink" data-wishlist-count>0</span>
            </a>
          @endauth
          <a href="#" class="hbtn" title="Cart">
            <svg class="ico"><use href="#i-cart"/></svg>
          </a>
          @auth
            <div class="dropdown">
              <button class="user-chip dropdown-toggle d-flex align-items-center gap-2 bg-transparent text-start" type="button"
                      id="account-menu" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar" aria-hidden="true"><svg class="ico"><use href="#i-user"/></svg></span>
                <span class="lh-sm"><span class="d-block small text-secondary">Hello,</span>
                    <span class="d-block small fw-semibold text-break">{{ auth()->user()->name }}</span>
                </span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="account-menu">
                <li>
                  @if (Route::has('orders.index'))
                    <a class="dropdown-item" href="{{ route('orders.index') }}">My orders</a>
                  @else
                    <span class="dropdown-item disabled" aria-disabled="true">My orders</span>
                  @endif
                </li>
                @if (auth()->user()->is_admin)
                  <li>
                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin panel</a>
                  </li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Sign out</button>
                  </form>
                </li>
              </ul>
            </div>
          @else
            <a href="{{ route('login') }}" class="user-chip d-flex align-items-center gap-2">
              <span class="avatar" aria-hidden="true"><svg class="ico"><use href="#i-user"/></svg></span>
                <span class="lh-sm"><span class="d-block small text-secondary">Hello,</span>
                    <span class="small fw-semibold">SignIn</span>
                </span>
            </a>
          @endauth
        </div>
      </div>
    </div>

    <!-- Category navigation -->
    <div class="cat-nav border-top">
      <div class="container">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link py-3 {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" data-ajax-link data-update-target="#page-content" data-category-nav data-category-slug="">
              <svg class="ico"><use href="#i-home"/></svg>Home
            </a>
          </li>
          @php($activeCategorySlug = request()->route('category') ?? (($category ?? null)?->slug) ?? '')
          @foreach ($categories as $navigationCategory)
            <li class="nav-item">
              <a class="nav-link py-3 {{ $activeCategorySlug === $navigationCategory->slug ? 'active' : '' }}" href="{{ route('categories.show', $navigationCategory->slug) }}" data-ajax-link data-update-target="#page-content" data-category-nav data-category-slug="{{ $navigationCategory->slug }}">
                <svg class="ico"><use href="#i-{{ $navigationCategory->icon }}"/></svg>{{ $navigationCategory->name }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </header>

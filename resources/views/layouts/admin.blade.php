<!doctype html>
<html lang="sr-Latn" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin panel') — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        :root { --bs-body-bg: #0b0f18; --bs-body-color: #e5eaf3; --bs-secondary-color: #9aaac0; --bs-border-color: #233047; --bs-link-color: #79acff; --bs-link-hover-color: #a6c8ff; }
        body { min-height: 100vh; }
        .admin-shell { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 252px; flex-shrink: 0; background: #111827; border-right: 1px solid var(--bs-border-color); }
        .sidebar-inner { position: sticky; top: 0; min-height: 100vh; display: flex; flex-direction: column; padding: 28px 20px; }
        .brand-mark { display: inline-grid; place-items: center; width: 38px; height: 38px; flex-shrink: 0; border-radius: 11px; background: linear-gradient(135deg, #3988ff, #7545ef); color: white; box-shadow: 0 0 24px #4565ff30; }
        .eyebrow { font-size: .7rem; letter-spacing: .14em; font-weight: 700; color: #9aaac0; }
        .admin-nav .nav-link { color: #aab8ce; padding: 12px 14px; border-radius: 9px; display: flex; align-items: center; gap: 12px; }
        .admin-nav .nav-link:hover { background: #19243a; color: #fff; }
        .admin-nav .nav-link.active { background: #387ff51c; color: #79acff; box-shadow: inset 3px 0 #387ff5; }
        .admin-nav .nav-link.disabled { opacity: .5; }
        .nav-symbol { width: 20px; text-align: center; font-size: 1.1rem; }
        .admin-main { flex: 1; min-width: 0; }
        .admin-header { background: #111827; min-height: 83px; }
        .admin-content { max-width: 1400px; margin: 0 auto; }
        .panel { background: #111827; border: 1px solid var(--bs-border-color); border-radius: 14px; }
        .welcome-panel { background: linear-gradient(115deg, #172942, #171c32 70%, #111827); }
        .stat-icon { display: inline-grid; place-items: center; width: 42px; height: 42px; border-radius: 12px; color: #79acff; background: #387ff51a; border: 1px solid #387ff530; font-size: 1.2rem; }
        .stat-value { font-size: 2rem; font-weight: 700; }
        .btn-primary { --bs-btn-bg: #387ff5; --bs-btn-border-color: #387ff5; --bs-btn-hover-bg: #286ce0; --bs-btn-hover-border-color: #286ce0; }
        .btn-outline-secondary { --bs-btn-color: #aab8ce; --bs-btn-border-color: #34435b; --bs-btn-hover-bg: #233047; }
        .admin-table { --bs-table-bg: #111827; --bs-table-border-color: #233047; --bs-table-hover-bg: #19243a; }
        .product-thumbnail { width: 52px; height: 52px; object-fit: cover; border-radius: 8px; }
        .empty-symbol { color: #79acff; font-size: 2rem; }
        a { text-underline-offset: 4px; }
        a:focus-visible, button:focus-visible { outline: 2px solid #79acff; outline-offset: 4px; }
        @media (max-width: 991.98px) {
            .admin-shell { flex-direction: column; }
            .admin-sidebar { width: 100%; border-right: 0; border-bottom: 1px solid var(--bs-border-color); }
            .sidebar-inner { position: static; min-height: auto; padding: 16px; }
            .admin-nav { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
    </style>
</head>
<body>
<a href="#main-content" class="visually-hidden-focusable position-absolute bg-dark p-3">Pređi na sadržaj</a>
<div class="admin-shell">
    <aside class="admin-sidebar" aria-label="Admin navigacija">
        <div class="sidebar-inner">
            <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none text-body fw-bold fs-5 mb-4">
                <span class="brand-mark" aria-hidden="true">ϟ</span>
                <span class="text-break">{{ config('app.name') }}</span>
            </a>
            <p class="eyebrow px-3 mb-2">ADMIN PANEL</p>
            <nav class="admin-nav nav flex-column gap-1" aria-label="Upravljanje prodavnicom">
                @foreach ([
                    ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '▦'],
                    ['route' => 'admin.categories', 'pattern' => 'admin.categories', 'label' => 'Kategorije', 'icon' => '▤'],
                    ['route' => 'admin.products', 'pattern' => 'admin.products*', 'label' => 'Proizvodi', 'icon' => '◇'],
                    ['route' => 'admin.orders', 'pattern' => 'admin.orders*', 'label' => 'Porudžbine', 'icon' => '▧'],
                ] as $item)
                    @if (Route::has($item['route']))
                        <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['pattern']) ? 'active' : '' }}" @if(request()->routeIs($item['pattern'])) aria-current="page" @endif>
                            <span class="nav-symbol" aria-hidden="true">{{ $item['icon'] }}</span>{{ $item['label'] }}
                        </a>
                    @else
                        <span class="nav-link disabled" aria-disabled="true"><span class="nav-symbol" aria-hidden="true">{{ $item['icon'] }}</span>{{ $item['label'] }}</span>
                    @endif
                @endforeach
            </nav>
            <div class="mt-auto pt-4 d-none d-lg-block">
                <div class="border-top pt-3 px-3">
                    <p class="small text-body-secondary mb-1">Upravljanje prodavnicom</p>
                    <span class="eyebrow">VERZIJA 2.0</span>
                </div>
            </div>
        </div>
    </aside>
    <div class="admin-main d-flex flex-column">
        <header class="admin-header border-bottom px-3 px-md-4 py-3 d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <a href="{{ url('/') }}" class="small text-decoration-none">← Nazad u prodavnicu</a>
            <div class="d-flex align-items-center gap-3">
                @auth
                    <div class="text-end"><div class="small fw-semibold">{{ auth()->user()->name }}</div><div class="small text-body-secondary">Admin panel</div></div>
                    @if (Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Odjavi se</button>
                        </form>
                    @endif
                @endauth
            </div>
        </header>
        <main id="main-content" class="admin-content w-100 p-3 p-md-4 p-xl-5 flex-grow-1" tabindex="-1">
            @if (session('status'))
                <div class="alert alert-success" role="status">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </main>
        <footer class="px-4 py-3 border-top small text-body-secondary">&copy; {{ date('Y') }} {{ config('app.name') }} · Admin panel</footer>
    </div>
</div>
</body>
</html>

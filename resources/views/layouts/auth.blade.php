<!doctype html>
<html lang="sr-Latn" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        :root { --bs-body-bg: #0b0f18; --bs-body-color: #e5eaf3; --bs-secondary-color: #9aaac0; --bs-border-color: #233047; --bs-link-color: #79acff; --bs-link-hover-color: #a6c8ff; }
        body { min-height: 100vh; }
        .site-header { background: #111827; }
        .brand-mark { display: inline-grid; place-items: center; width: 38px; height: 38px; border-radius: 11px; background: linear-gradient(135deg, #3988ff, #7545ef); color: white; box-shadow: 0 0 24px #4565ff30; }
        .auth-card { background: #111827; border: 1px solid var(--bs-border-color); border-radius: 20px; box-shadow: 0 24px 80px #0003; }
        .auth-heading { background: linear-gradient(120deg, #19283f, #1a1c35); border-bottom: 1px solid var(--bs-border-color); border-radius: 20px 20px 0 0; }
        .eyebrow { font-size: .72rem; letter-spacing: .15em; color: #79acff; font-weight: 700; }
        .form-control { background-color: #0b111d; border-color: #29364c; min-height: 48px; }
        .form-control::placeholder { color: #77879e; }
        .form-control:focus { background-color: #0b111d; border-color: #5595ff; box-shadow: 0 0 0 .2rem #3988ff25; }
        .form-label { font-size: .875rem; font-weight: 600; }
        .btn-primary { --bs-btn-bg: #387ff5; --bs-btn-border-color: #387ff5; --bs-btn-hover-bg: #286ce0; --bs-btn-hover-border-color: #286ce0; min-height: 48px; font-weight: 600; }
        .form-check-input:checked { background-color: #387ff5; border-color: #387ff5; }
        .auth-container { max-width: 510px; }
        a { text-underline-offset: 4px; }
    </style>
</head>
<body class="d-flex flex-column">
    <header class="site-header border-bottom">
        <div class="container py-3 d-flex align-items-center justify-content-between gap-3">
            <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none text-body fw-bold fs-4">
                <span class="brand-mark" aria-hidden="true">ϟ</span>
                {{ config('app.name') }}
            </a>
            <a href="{{ url('/') }}" class="small text-decoration-none">← Nazad u prodavnicu</a>
        </div>
    </header>
    <main class="container auth-container py-4 py-md-5 flex-grow-1">
        <div class="auth-card my-md-3">
            <div class="auth-heading p-4 p-sm-5 pb-sm-4">
                <p class="eyebrow mb-3">VAŠ NALOG</p>
                <h1 class="h3 fw-bold mb-2">@yield('heading')</h1>
                <p class="text-body-secondary mb-0">@yield('description')</p>
            </div>
            <div class="p-4 p-sm-5 pt-sm-4">
                @if (session('status'))
                    <div class="alert alert-success small" role="status">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger small" role="alert">
                        <p class="fw-semibold mb-1">Proverite unete podatke.</p>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </main>
    <footer class="container text-center text-body-secondary small pb-4">
        &copy; {{ date('Y') }} {{ config('app.name') }}
    </footer>
</body>
</html>

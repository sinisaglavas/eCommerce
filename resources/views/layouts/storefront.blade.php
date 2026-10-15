<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/storefront.css', 'resources/js/app.js'])
    <style>
        .login-required-toast {
            position: fixed;
            z-index: 1080;
            top: 1.25rem;
            left: 50%;
            display: flex;
            align-items: center;
            gap: .75rem;
            width: min( calc(100% - 2rem), 420px );
            padding: .9rem 1rem;
            color: #e5eaf3;
            background: #111827;
            border: 1px solid #387ff5;
            border-radius: .75rem;
            box-shadow: 0 1rem 2.5rem #0008;
            transform: translateX(-50%);
            animation: login-required-toast-in .2s ease-out;
        }

        .login-required-toast-icon {
            display: grid;
            flex: 0 0 2rem;
            width: 2rem;
            height: 2rem;
            place-items: center;
            color: #fff;
            background: #387ff5;
            border-radius: 50%;
            font-weight: 700;
        }

        .login-required-toast-close {
            margin-left: auto;
            padding: .1rem .35rem;
            color: #9aaac0;
            font-size: 1.25rem;
            line-height: 1;
            background: transparent;
            border: 0;
        }

        .login-required-toast-close:hover { color: #fff; }

        @keyframes login-required-toast-in {
            from { opacity: 0; transform: translate(-50%, -.5rem); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }
    </style>
</head>
<body>
    @include('layouts.partials.icon-sprite')
    @include('layouts.partials.header')

    @yield('content')

    @include('layouts.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.showLoginRequiredNotice = (type) => {
            document.querySelector('[data-login-required-notice]')?.remove();

            const notice = document.createElement('div');
            notice.className = 'login-required-toast';
            notice.dataset.loginRequiredNotice = '';
            notice.setAttribute('role', 'alert');

            const icon = document.createElement('span');
            icon.className = 'login-required-toast-icon';
            icon.textContent = '!';

            const message = document.createElement('span');
            message.textContent = type === 'wishlist'
                ? 'Please sign in first to add products to your wishlist.'
                : 'Please sign in first to add products to your cart.';

            const close = document.createElement('button');
            close.className = 'login-required-toast-close';
            close.type = 'button';
            close.setAttribute('aria-label', 'Close notification');
            close.textContent = '×';
            close.addEventListener('click', () => notice.remove());

            notice.append(icon, message, close);
            document.body.append(notice);
            window.setTimeout(() => notice.remove(), 4500);
        };
    </script>
</body>
</html>

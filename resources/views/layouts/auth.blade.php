<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="skyline">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', 'Login') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skyline-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <style>
        .auth-page {
            min-height: 100vh;
            display: flex;
            background: var(--sky-bg);
        }
        .auth-visual {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 3rem;
        }
        .auth-visual::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(212,162,78,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        .auth-visual::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(212,162,78,0.06) 0%, transparent 70%);
            border-radius: 50%;
        }
        .auth-visual-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 380px;
        }
        .auth-visual-content .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--sky-text);
            margin-bottom: 1.5rem;
        }
        .auth-visual-content .brand-logo span { color: var(--sky-amber); }
        .auth-visual-content h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
            line-height: 1.4;
        }
        .auth-visual-content p {
            color: var(--sky-text-muted);
            font-size: 0.92rem;
            line-height: 1.7;
        }
        .auth-visual .auth-skyline-svg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            opacity: 0.15;
        }
        .auth-form-side {
            width: 480px;
            min-width: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: var(--sky-bg-alt);
            border-left: 1px solid rgba(212,162,78,0.08);
            position: relative;
        }
        .auth-form-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 2px;
            height: 100%;
            background: linear-gradient(to bottom, transparent, rgba(212,162,78,0.2), transparent);
        }
        .auth-form-wrap {
            width: 100%;
            max-width: 360px;
        }
        .auth-form-wrap h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            margin-bottom: 0.3rem;
        }
        .auth-form-wrap .auth-subtitle {
            color: var(--sky-text-muted);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .auth-form-wrap .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--sky-text-muted);
            margin-bottom: 0.4rem;
        }
        .auth-form-wrap .form-control {
            background: rgba(15,17,21,0.6);
            border: 1px solid rgba(212,162,78,0.12);
            color: var(--sky-text);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .auth-form-wrap .form-control::placeholder { color: rgba(154,150,145,0.5); }
        .auth-form-wrap .form-control:focus {
            border-color: var(--sky-amber);
            box-shadow: 0 0 0 3px rgba(212,162,78,0.15);
            background: rgba(15,17,21,0.8);
            color: var(--sky-text);
        }
        .auth-form-wrap .form-check-input {
            background-color: rgba(15,17,21,0.6);
            border-color: rgba(212,162,78,0.25);
        }
        .auth-form-wrap .form-check-input:checked {
            background-color: var(--sky-amber);
            border-color: var(--sky-amber);
        }
        .auth-form-wrap .form-check-label {
            color: var(--sky-text-muted);
            font-size: 0.85rem;
        }
        .auth-form-wrap .auth-link {
            color: var(--sky-amber);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color .2s;
        }
        .auth-form-wrap .auth-link:hover { color: var(--sky-amber-glow); }
        .auth-form-wrap .auth-divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            gap: 1rem;
        }
        .auth-form-wrap .auth-divider::before,
        .auth-form-wrap .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(212,162,78,0.12);
        }
        .auth-form-wrap .auth-divider span {
            font-size: 0.78rem;
            color: var(--sky-text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .auth-bottom-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.88rem;
            color: var(--sky-text-muted);
        }
        .urdu {
            font-size: 0.82em;
            opacity: 0.6;
        }
        /* Unified brand overrides for the creative login (indigo) */
        .auth-visual {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 55%, #0ea5e9 120%);
        }
        .auth-visual-content .brand-logo,
        .auth-visual-content .brand-logo span { color: #fff; }
        .auth-visual-content .brand-logo span { color: #c7d2fe; }
        .auth-visual-content h3 { color: #fff; }
        .auth-visual-content p { color: rgba(255,255,255,0.82); }
        .auth-form-wrap .auth-link { color: var(--primary); }
        .auth-form-wrap .auth-link:hover { color: var(--primary-dark); }
        .auth-form-wrap h2, .auth-form-wrap .auth-subtitle { color: var(--gray-800); }
        .auth-form-wrap .auth-subtitle { color: var(--text-muted); }
        .btn-amber {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .btn-amber:hover { background: var(--primary-dark); border-color: var(--primary-dark); color: #fff; }
        .auth-form-side { border-left: 1px solid var(--border); }
        .auth-page { background: var(--surface); }
        .auth-form-side { background: var(--surface); }
        .invalid-feedback {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }
        .auth-particles .sky-particle {
            position: absolute;
        }
        @media (max-width: 991.98px) {
            .auth-page { flex-direction: column; }
            .auth-visual { min-height: 240px; padding: 2rem; }
            .auth-visual-content h3 { font-size: 1.2rem; }
            .auth-form-side {
                width: 100%;
                min-width: 0;
                border-left: none;
                border-top: 1px solid rgba(212,162,78,0.08);
                padding: 2rem 1.5rem;
            }
            .auth-form-side::before {
                width: 100%;
                height: 2px;
                top: 0;
                left: 0;
                background: linear-gradient(to right, transparent, rgba(212,162,78,0.2), transparent);
            }
        }
        @media (max-width: 480px) {
            .auth-form-side { padding: 1.5rem 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-page">
        <div class="auth-visual d-none d-lg-flex">
            <div class="auth-particles">
                @for($i = 0; $i < 8; $i++)
                    <div class="sky-particle" style="left:{{ rand(10,90) }}%;top:{{ rand(10,90) }}%;animation-delay:{{ $i * 0.7 }}s;animation-duration:{{ rand(5,8) }}s;width:{{ rand(3,5) }}px;height:{{ rand(3,5) }}px;"></div>
                @endfor
            </div>
            <div class="auth-visual-content">
                <div class="brand-logo"><i class="ti ti-building-skyscraper"></i> Sky<span>line</span></div>
                <h3>Welcome to Pakistan's Most Trusted Real Estate Platform</h3>
                <p>Manage properties, track deals, and close transactions — all in one place.</p>
            </div>
            <svg class="auth-skyline-svg" viewBox="0 0 600 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <rect x="0" y="40" width="600" height="40" fill="#1A1D24"/>
                <rect x="30" y="15" width="40" height="65" fill="#D4A24E" opacity="0.06"/>
                <rect x="35" y="20" width="5" height="5" class="skyline-window" style="animation-delay:0.2s"/><rect x="45" y="20" width="5" height="5" class="skyline-window" style="animation-delay:0.5s"/>
                <rect x="90" y="8" width="50" height="72" fill="#D4A24E" opacity="0.06"/>
                <rect x="96" y="14" width="6" height="6" class="skyline-window" style="animation-delay:0.3s"/><rect x="110" y="14" width="6" height="6" class="skyline-window" style="animation-delay:0.7s"/>
                <rect x="170" y="20" width="35" height="60" fill="#D4A24E" opacity="0.06"/>
                <rect x="230" y="5" width="55" height="75" fill="#D4A24E" opacity="0.06"/>
                <rect x="236" y="11" width="7" height="7" class="skyline-window" style="animation-delay:0.4s"/><rect x="250" y="11" width="7" height="7" class="skyline-window" style="animation-delay:0.9s"/>
                <rect x="310" y="18" width="40" height="62" fill="#D4A24E" opacity="0.06"/>
                <rect x="380" y="10" width="45" height="70" fill="#D4A24E" opacity="0.06"/>
                <rect x="386" y="16" width="6" height="6" class="skyline-window" style="animation-delay:0.2s"/><rect x="400" y="16" width="6" height="6" class="skyline-window" style="animation-delay:0.6s"/>
                <rect x="460" y="22" width="35" height="58" fill="#D4A24E" opacity="0.06"/>
                <rect x="520" y="12" width="50" height="68" fill="#D4A24E" opacity="0.06"/>
                <rect x="526" y="18" width="6" height="6" class="skyline-window" style="animation-delay:0.3s"/><rect x="540" y="18" width="6" height="6" class="skyline-window" style="animation-delay:0.8s"/>
            </svg>
        </div>

        <div class="auth-form-side">
            <div class="auth-form-wrap">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap.bundle.min.js') }}"></script>
    @include('partials.toastr')
    <script>
        @if (session()->has('toastr'))
            @php $t = session('toastr'); @endphp
            toastr.{{ $t['type'] }}(@json($t['message']));
        @endif
        @if($errors->any())
            @foreach($errors->all() as $message)
                toastr.error(@json($message));
            @endforeach
        @endif
    </script>
    @stack('scripts')
</body>
</html>

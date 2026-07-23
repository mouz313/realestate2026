<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/tabler-icons.min.css') }}" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1a1a2e;
            --primary-light: #16213e;
            --accent: #e94560;
            --accent-hover: #d63851;
            --gold: #f5a623;
            --text-light: #f8f9fa;
            --text-muted: #adb5bd;
            --section-bg: #f8f9fa;
            --card-shadow: 0 4px 24px rgba(0,0,0,0.08);
            --card-hover-shadow: 0 12px 40px rgba(0,0,0,0.12);
        }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; color: #333; }
        .navbar { background: var(--primary) !important; padding: 0.75rem 0; }
        .navbar-brand { font-weight: 800; font-size: 1.35rem; letter-spacing: -0.5px; }
        .navbar .nav-link { color: rgba(255,255,255,0.8) !important; font-weight: 500; padding: 0.5rem 1rem !important; border-radius: 6px; transition: all 0.2s; }
        .navbar .nav-link:hover, .navbar .nav-link.active { color: #fff !important; background: rgba(255,255,255,0.1); }
        .hero-section { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); padding: 6rem 0 5rem; position: relative; overflow: hidden; }
        .hero-section::before { content: ''; position: absolute; inset: 0; background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); opacity: 0.5; }
        .hero-section h1 { font-size: 3rem; font-weight: 800; line-height: 1.15; }
        .hero-section .lead { font-size: 1.15rem; color: rgba(255,255,255,0.75); max-width: 560px; }
        .hero-search { max-width: 600px; }
        .hero-search .form-control, .hero-search .form-select { border-radius: 50px; padding: 0.75rem 1.25rem; border: none; }
        .hero-search .btn { border-radius: 50px; padding: 0.75rem 1.75rem; font-weight: 600; }
        .section-title { font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; }
        .section-subtitle { color: var(--text-muted); font-size: 1.05rem; max-width: 600px; margin: 0 auto 2.5rem; }
        .property-card { border: none; border-radius: 12px; overflow: hidden; box-shadow: var(--card-shadow); transition: all 0.3s ease; }
        .property-card:hover { transform: translateY(-4px); box-shadow: var(--card-hover-shadow); }
        .property-card .card-img-top { height: 200px; object-fit: cover; }
        .property-card .card-body { padding: 1.25rem; }
        .property-card .price-tag { font-size: 1.2rem; font-weight: 700; color: var(--accent); }
        .property-card .badge-location { background: var(--primary); color: #fff; font-size: 0.7rem; padding: 0.25rem 0.6rem; border-radius: 50px; }
        .stat-card { text-align: center; padding: 2rem; background: #fff; border-radius: 16px; box-shadow: var(--card-shadow); transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-4px); }
        .stat-card .stat-number { font-size: 2.5rem; font-weight: 800; color: var(--primary); }
        .stat-card .stat-label { color: var(--text-muted); font-weight: 500; margin-top: 0.25rem; }
        .stat-card .stat-icon { font-size: 2rem; margin-bottom: 0.75rem; display: block; }
        .about-section { background: #fff; }
        .team-card { border: none; border-radius: 12px; overflow: hidden; box-shadow: var(--card-shadow); transition: transform 0.3s; }
        .team-card:hover { transform: translateY(-4px); }
        .team-card .team-img { width: 100%; height: 260px; object-fit: cover; }
        .contact-section { background: var(--section-bg); }
        .contact-info-card { border: none; border-radius: 12px; padding: 1.5rem; background: #fff; box-shadow: var(--card-shadow); }
        .contact-info-card .icon-circle { width: 48px; height: 48px; border-radius: 50%; background: rgba(233,69,96,0.1); display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1.25rem; flex-shrink: 0; }
        .footer { background: var(--primary); color: rgba(255,255,255,0.7); }
        .footer h6 { color: #fff; font-weight: 700; margin-bottom: 1rem; }
        .footer a { color: rgba(255,255,255,0.6); text-decoration: none; transition: color 0.2s; }
        .footer a:hover { color: #fff; }
        .footer .social-link { width: 38px; height: 38px; border-radius: 50%; background: rgba(255,255,255,0.1); display: inline-flex; align-items: center; justify-content: center; transition: background 0.2s; }
        .footer .social-link:hover { background: var(--accent); }
        .page-banner { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); padding: 3.5rem 0; text-align: center; }
        .page-banner h1 { font-size: 2.25rem; font-weight: 800; }
        .page-banner p { color: rgba(255,255,255,0.7); max-width: 500px; margin: 0 auto; }
        .filter-section { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow); margin-bottom: 2rem; }
        .btn-accent { background: var(--accent); color: #fff; border: none; font-weight: 600; }
        .btn-accent:hover { background: var(--accent-hover); color: #fff; }
        .btn-outline-accent { border: 2px solid var(--accent); color: var(--accent); font-weight: 600; background: transparent; }
        .btn-outline-accent:hover { background: var(--accent); color: #fff; }
        .text-accent { color: var(--accent); }
        .bg-accent { background: var(--accent); }
        .feature-icon { width: 56px; height: 56px; border-radius: 12px; background: rgba(233,69,96,0.1); display: flex; align-items: center; justify-content: center; color: var(--accent); font-size: 1.5rem; }
        .page-link { border-radius: 8px !important; margin: 0 3px; color: var(--primary); }
        .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }
        @media (max-width: 768px) {
            .hero-section h1 { font-size: 2rem; }
            .hero-section { padding: 4rem 0 3rem; }
            .section-title { font-size: 1.5rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="ti ti-building-skyscraper me-1"></i>{{ config('app.name') }}
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('website.properties*') ? 'active' : '' }}" href="{{ route('website.properties') }}">Properties</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('website.about') ? 'active' : '' }}" href="{{ route('website.about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('website.contact') ? 'active' : '' }}" href="{{ route('website.contact') }}">Contact</a></li>
                    <li class="nav-item ms-lg-2"><a class="nav-link btn btn-accent px-3 py-1" href="{{ route('login') }}"><i class="ti ti-user me-1"></i>Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    {{-- Footer --}}
    <footer class="footer pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h6 class="d-flex align-items-center"><i class="ti ti-building-skyscraper me-2"></i>{{ config('app.name') }}</h6>
                    <p class="small">Your trusted real estate partner in Pakistan. We help you find the perfect property for your needs.</p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="#" class="social-link"><i class="ti ti-brand-facebook"></i></a>
                        <a href="#" class="social-link"><i class="ti ti-brand-instagram"></i></a>
                        <a href="#" class="social-link"><i class="ti ti-brand-whatsapp"></i></a>
                        <a href="#" class="social-link"><i class="ti ti-brand-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('home') }}">Home</a></li>
                        <li class="mb-2"><a href="{{ route('website.properties') }}">Properties</a></li>
                        <li class="mb-2"><a href="{{ route('website.about') }}">About Us</a></li>
                        <li class="mb-2"><a href="{{ route('website.contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6>Property Types</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('website.properties', ['type' => 'house']) }}">Houses</a></li>
                        <li class="mb-2"><a href="{{ route('website.properties', ['type' => 'flat']) }}">Flats</a></li>
                        <li class="mb-2"><a href="{{ route('website.properties', ['type' => 'plot']) }}">Plots</a></li>
                        <li class="mb-2"><a href="{{ route('website.properties', ['type' => 'commercial']) }}">Commercial</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6>Contact Info</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="ti ti-map-pin me-1"></i> Islamabad, Pakistan</li>
                        <li class="mb-2"><i class="ti ti-phone me-1"></i> +92 300 1234567</li>
                        <li class="mb-2"><i class="ti ti-mail me-1"></i> info@example.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color:rgba(255,255,255,0.1);">
            <div class="text-center small">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({ duration: 800, once: true });</script>
    <script>
        @if (session()->has('toastr'))
            toastr.{{ session('toastr')['type'] }}('{{ session('toastr')['message'] }}');
        @endif
        @if($errors->any())
            @foreach($errors->all() as $message)
                toastr.error('{{ $message }}');
            @endforeach
        @endif
    </script>
    @stack('scripts')
</body>
</html>

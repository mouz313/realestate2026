<!DOCTYPE html>
<html lang="en" data-theme="skyline">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Skyline Real Estate'))</title>
    <meta name="description" content="@yield('meta_description', 'Premium real estate agency in Pakistan — find houses, flats, plots, and commercial properties for sale and rent.')">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Open Graph -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ config('app.name', 'Skyline Real Estate') }}">
    <meta property="og:title" content="@yield('title', config('app.name', 'Skyline Real Estate'))">
    <meta property="og:description" content="@yield('meta_description', 'Premium real estate agency in Pakistan.')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('assets/img/og-default.jpg'))">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('app.name', 'Skyline Real Estate'))">
    <meta name="twitter:description" content="@yield('meta_description', 'Premium real estate agency in Pakistan.')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/img/og-default.jpg'))">

    <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skyline-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/toastr.min.css') }}">
    <noscript><style>.reveal,.reveal-left,.reveal-right,.reveal-scale{opacity:1!important;transform:none!important}</style></noscript>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg skyline-nav fixed-top" id="skyNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                @php $navLogo = \App\Models\Setting::where('key', 'brand_logo')->value('value'); @endphp
                @if($navLogo)
                    <img src="{{ Storage::url($navLogo) }}" alt="{{ config('app.name') }}" style="height:34px;width:auto;">
                @else
                    <i class="ti ti-building-skyscraper"></i> Sky<span>line</span>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#skyNavCollapse" aria-controls="skyNavCollapse" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="skyNavCollapse">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('website.properties*') ? 'active' : '' }}" href="{{ route('website.properties') }}">Listings</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('website.about') ? 'active' : '' }}" href="{{ route('website.about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('website.blog*') ? 'active' : '' }}" href="{{ route('website.blog') }}">Blog</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('website.contact') ? 'active' : '' }}" href="{{ route('website.contact') }}">Contact</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-amber btn-sm" href="{{ route('login') }}">
                            <i class="ti ti-login"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    @include('partials.website-footer')

    @php
        $waSetting = \App\Models\Setting::where('key', 'social_whatsapp')->value('value');
        $waNumber = $waSetting && $waSetting !== '#' ? preg_replace('/[^0-9]/', '', $waSetting) : '';
    @endphp
    @if($waNumber)
        <a href="https://wa.me/{{ $waNumber }}" class="floating-whatsapp" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
            <i class="ti ti-brand-whatsapp"></i>
        </a>
    @endif

    <script src="{{ asset('assets/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nav = document.getElementById('skyNav');
            window.addEventListener('scroll', function() {
                nav.classList.toggle('scrolled', window.scrollY > 50);
            });
        });
    </script>
    @stack('scripts')
    <script src="{{ asset('assets/toastr.min.js') }}"></script>
    <script>
        toastr.options = { positionClass: 'toast-top-right', timeOut: 4000, progressBar: true };
        @if (session()->has('toastr'))
            @php $t = session('toastr'); @endphp
            toastr.{{ $t['type'] }}(@json($t['message']));
        @endif
        @foreach (session('toastr_errors', []) as $message)
            toastr.error(@json($message));
        @endforeach
        @if ($errors->any())
            @foreach ($errors->all() as $message)
                toastr.error(@json($message));
            @endforeach
        @endif
    </script>
</body>
</html>

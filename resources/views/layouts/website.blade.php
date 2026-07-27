<!DOCTYPE html>
<html lang="en" data-theme="skyline">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'RealEstate2026'))</title>
    <meta name="description" content="@yield('meta_description', 'Find your dream property with RealEstate2026 — premium real estate agency in Pakistan.')">

    <link rel="stylesheet" href="{{ asset('assets/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skyline-theme.css') }}">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg skyline-nav fixed-top" id="skyNav">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="ti ti-building-skyscraper"></i> Sky<span>line</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#skyNavCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="skyNavCollapse">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('website.properties*') ? 'active' : '' }}" href="{{ route('website.properties') }}">Listings</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('website.about') ? 'active' : '' }}" href="{{ route('website.about') }}">About</a></li>
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
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Client Portal') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/tabler-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/custom.css') }}" rel="stylesheet">
    <style>
        body { background: var(--body-bg); }
        .navbar-brand { font-weight: 700; font-size: 1.25rem; }
        .navbar { background: var(--sidebar-bg) !important; }
        .portal-card { border-radius: 0.75rem; box-shadow: var(--card-shadow); transition: box-shadow 0.25s ease; }
        .portal-card:hover { box-shadow: var(--card-hover-shadow); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('portal.quotations') }}">{{ config('app.name') }} — Client Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#portalNav"><span class="navbar-toggler-icon"></span></button>
            @if(session('client_id'))
            <div class="collapse navbar-collapse" id="portalNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.properties*') ? 'active' : '' }}" href="{{ route('portal.properties') }}"><i class="ti ti-building"></i> Properties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.visits*') ? 'active' : '' }}" href="{{ route('portal.visits') }}"><i class="ti ti-calendar-clock"></i> My Visits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.deals*') ? 'active' : '' }}" href="{{ route('portal.deals') }}"><i class="ti ti-handshake"></i> My Deals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.quotations*') ? 'active' : '' }}" href="{{ route('portal.quotations') }}"><i class="ti ti-file-description"></i> Quotations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.invoices*') ? 'active' : '' }}" href="{{ route('portal.invoices') }}"><i class="ti ti-receipt"></i> Invoices</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-white-50 me-2 small">{{ session('client_name') }}</span>
                    <a href="{{ route('portal.logout') }}" class="btn btn-sm btn-outline-light">Logout</a>
                </div>
            </div>
            @endif
        </div>
    </nav>
    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

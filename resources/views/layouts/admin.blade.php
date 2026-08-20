<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php $brandFavicon = \App\Models\Setting::where('key', 'brand_favicon')->value('value'); @endphp
    <link rel="icon" type="image/x-icon" href="{{ $brandFavicon ? Storage::url($brandFavicon) : asset('favicon.ico') }}">
    @php $brandName = \App\Models\Setting::where('key', 'business_name')->value('value'); @endphp
    <title>@yield('title', config('app.name')) - {{ $brandName ?? config('app.name') }}</title>
    <link href="{{ asset('assets/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/tabler-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <style>.urdu{font-size:0.75em;opacity:0.75;unicode-bidi:embed}.min-w-0{min-width:0}</style>
    @stack('styles')
</head>
<body>
@include('partials.preloader')
<div class="d-flex" style="min-height: 100vh;">
    {{-- Sidebar Toggle Button (mobile only) --}}
    <button class="btn btn-dark sidebar-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
        <i class="ti ti-menu-2"></i>
    </button>

    {{-- Sidebar (Desktop) --}}
    <div class="d-none d-lg-flex flex-column flex-shrink-0 p-3 sidebar" style="width: 260px; position: fixed; top: 0; left: 0; height: 100vh; overflow-y: auto; z-index: 100;">
        <a href="{{ route(dashboard_route()) }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none gap-2 sidebar-brand">
            @php $brand = \App\Models\Setting::whereIn('key', ['brand_logo', 'business_name'])->pluck('value', 'key'); @endphp
            @if(($brand['brand_logo'] ?? null))
                <img src="{{ Storage::url($brand['brand_logo']) }}" alt="{{ $brand['business_name'] ?? config('app.name') }}" class="sidebar-logo">
            @else
                <span class="fs-5 fw-bold">{{ $brand['business_name'] ?? config('app.name') }}</span>
            @endif
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route(dashboard_route()) }}" class="nav-link {{ request()->routeIs(dashboard_route()) ? 'active' : '' }}">
                    <i class="ti ti-home"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item mt-2">
                <span class="text-white-50 small fw-medium px-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.1em;">Sales &amp; CRM</span>
            </li>
            <li>
                <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <i class="ti ti-users"></i>
                    Clients
                </a>
            </li>
            <li>
                <a href="{{ route('call-logs.index') }}" class="nav-link {{ request()->routeIs('call-logs.*') ? 'active' : '' }}">
                    <i class="ti ti-phone-call"></i>
                    Call Logs
                </a>
            </li>
            <li>
                <a href="{{ route('quotations.index') }}" class="nav-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}">
                    <i class="ti ti-file-description"></i>
                    Quotations
                </a>
            </li>
            <li>
                <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <i class="ti ti-file-invoice"></i>
                    Invoices
                </a>
            </li>
            <li>
                <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                    <i class="ti ti-currency-dollar"></i>
                    Payments
                </a>
            </li>
            <li class="nav-item mt-2">
                <span class="text-white-50 small fw-medium px-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.1em;">Real Estate</span>
            </li>
            <li>
                <a href="{{ route('properties.index') }}" class="nav-link {{ request()->routeIs('properties.*') && !request()->routeIs('properties.available') ? 'active' : '' }}">
                    <i class="ti ti-building"></i>
                    Properties
                </a>
            </li>
            <li>
                <a href="{{ route('properties.available') }}" class="nav-link {{ request()->routeIs('properties.available') ? 'active' : '' }}">
                    <i class="ti ti-building-community"></i>
                    Available Properties
                </a>
            </li>
            <li>
                <a href="{{ route('deals.index') }}" class="nav-link {{ request()->routeIs('deals.*') ? 'active' : '' }}">
                    <i class="ti ti-handshake"></i>
                    Deals
                </a>
            </li>
            <li>
                <a href="{{ route('tokens.index') }}" class="nav-link {{ request()->routeIs('tokens.*') ? 'active' : '' }}">
                    <i class="ti ti-coin"></i>
                    Tokens
                </a>
            </li>
            <li>
                <a href="{{ route('property-visits.index') }}" class="nav-link {{ request()->routeIs('property-visits.*') ? 'active' : '' }}">
                    <i class="ti ti-calendar-event"></i>
                    Visits
                </a>
            </li>
            <li class="nav-item mt-2">
                <span class="text-white-50 small fw-medium px-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.1em;">Rentals <span class="urdu">(کرائے)</span></span>
            </li>
            <li>
                <a href="{{ route('rental-records.index') }}" class="nav-link {{ request()->routeIs('rental-records.*') ? 'active' : '' }}">
                    <i class="ti ti-home-check"></i>
                    Rented Records
                </a>
            </li>
            <li class="nav-item mt-2">
                <span class="text-white-50 small fw-medium px-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.1em;">Team &amp; Payouts</span>
            </li>
            @can('view_team')
            <li>
                <a href="{{ route('team.index') }}" class="nav-link {{ request()->routeIs('team.*') || request()->routeIs('agents.*') ? 'active' : '' }}">
                    <i class="ti ti-users-group"></i>
                    Team
                </a>
            </li>
            @endcan
            <li>
                <a href="{{ route('commissions.index') }}" class="nav-link {{ request()->routeIs('commissions.*') ? 'active' : '' }}">
                    <i class="ti ti-percentage"></i>
                    Commissions
                </a>
            </li>
            <li>
                <a href="{{ route('agent-payouts.index') }}" class="nav-link {{ request()->routeIs('agent-payouts.*') ? 'active' : '' }}">
                    <i class="ti ti-cash"></i>
                    Agent Payouts
                </a>
            </li>
        </ul>
            <hr>
            <ul class="nav nav-pills flex-column">
                <li>
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="ti ti-report"></i>
                        Reports
                    </a>
                </li>
                <li>
                    <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <i class="ti ti-bell"></i>
                        Notifications
                        @php
                            $unread = Auth::user()?->unreadNotifications()->count() ?? 0;
                        @endphp
                        @if($unread > 0)
                            <span class="badge rounded-pill bg-danger ms-1">{{ $unread }}</span>
                        @endif
                    </a>
                </li>
                @can('admin')
                <li class="nav-item mt-2">
                    <span class="text-white-50 small fw-medium px-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.1em;">Administration</span>
                </li>
                <li>
                    <a href="{{ route('cities.index') }}" class="nav-link {{ request()->routeIs('cities.*') ? 'active' : '' }}">
                        <i class="ti ti-building-community"></i>
                        Cities
                    </a>
                </li>
                <li>
                    <a href="{{ route('contacts.index') }}" class="nav-link {{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                        <i class="ti ti-message-report me-2"></i> Enquiries <span class="urdu">(انکوائریاں)</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('referrals.index') }}" class="nav-link {{ request()->routeIs('referrals.*') ? 'active' : '' }}">
                        <i class="ti ti-users-group me-2"></i> Referrals <span class="urdu">(ریفرلز)</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                        <i class="ti ti-receipt"></i>
                        Expenses
                    </a>
                </li>
                <li>
                    <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <i class="ti ti-shield"></i>
                        Roles
                    </a>
                </li>
                <li>
                    <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                        <i class="ti ti-key"></i>
                        Permissions
                    </a>
                </li>
                <li>
                    <a href="{{ route('activity-log') }}" class="nav-link {{ request()->routeIs('activity-log') ? 'active' : '' }}">
                        <i class="ti ti-history"></i>
                        Activity Log
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.items') }}" class="nav-link {{ request()->routeIs('item-templates.*') || request()->routeIs('settings.items') ? 'active' : '' }}">
                        <i class="ti ti-template"></i>
                        Item Templates
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') && !request()->routeIs('settings.items') ? 'active' : '' }}">
                        <i class="ti ti-settings"></i>
                        Settings
                    </a>
                </li>
                @endcan
            </ul>
        </div>

    {{-- Sidebar (Mobile Offcanvas) --}}
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header" style="background:var(--sidebar-bg);">
            <a href="{{ route(dashboard_route()) }}" class="text-decoration-none sidebar-brand d-flex align-items-center gap-2">
                @php $brand = \App\Models\Setting::whereIn('key', ['brand_logo', 'business_name'])->pluck('value', 'key'); @endphp
                @if(($brand['brand_logo'] ?? null))
                    <img src="{{ Storage::url($brand['brand_logo']) }}" alt="{{ $brand['business_name'] ?? config('app.name') }}" class="sidebar-logo">
                @else
                    <span class="fs-5 fw-bold">{{ $brand['business_name'] ?? config('app.name') }}</span>
                @endif
            </a>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0 sidebar" style="background:var(--sidebar-bg);">
            <ul class="nav nav-pills flex-column p-3 mb-auto">
                <li class="nav-item">
                    <a href="{{ route(dashboard_route()) }}" class="nav-link {{ request()->routeIs(dashboard_route()) ? 'active' : '' }}">
                        <i class="ti ti-home"></i> Dashboard
                    </a>
                </li>
                 <li class="nav-item mt-2"><span class="text-white-50 small fw-medium px-2 text-uppercase" style="font-size:0.65rem;letter-spacing:0.1em;">Sales &amp; CRM</span></li>
                 <li><a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"><i class="ti ti-users"></i> Clients</a></li>
                 <li><a href="{{ route('call-logs.index') }}" class="nav-link {{ request()->routeIs('call-logs.*') ? 'active' : '' }}"><i class="ti ti-phone-call"></i> Call Logs</a></li>
                 <li><a href="{{ route('quotations.index') }}" class="nav-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}"><i class="ti ti-file-description"></i> Quotations</a></li>
                <li><a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}"><i class="ti ti-file-invoice"></i> Invoices</a></li>
                <li><a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}"><i class="ti ti-currency-dollar"></i> Payments</a></li>
                 <li class="nav-item mt-2"><span class="text-white-50 small fw-medium px-2 text-uppercase" style="font-size:0.65rem;letter-spacing:0.1em;">Real Estate</span></li>
                 <li><a href="{{ route('properties.index') }}" class="nav-link {{ request()->routeIs('properties.*') && !request()->routeIs('properties.available') ? 'active' : '' }}"><i class="ti ti-building"></i> Properties</a></li>
                 <li><a href="{{ route('properties.available') }}" class="nav-link {{ request()->routeIs('properties.available') ? 'active' : '' }}"><i class="ti ti-building-community"></i> Available Properties</a></li>
                 <li><a href="{{ route('deals.index') }}" class="nav-link {{ request()->routeIs('deals.*') ? 'active' : '' }}"><i class="ti ti-handshake"></i> Deals</a></li>
                <li><a href="{{ route('tokens.index') }}" class="nav-link {{ request()->routeIs('tokens.*') ? 'active' : '' }}"><i class="ti ti-coin"></i> Tokens</a></li>
                 <li><a href="{{ route('property-visits.index') }}" class="nav-link {{ request()->routeIs('property-visits.*') ? 'active' : '' }}"><i class="ti ti-calendar-event"></i> Visits</a></li>
                  <li class="nav-item mt-2"><span class="text-white-50 small fw-medium px-2 text-uppercase" style="font-size:0.65rem;letter-spacing:0.1em;">Rentals <span class="urdu">(کرائے)</span></span></li>
                  <li><a href="{{ route('rental-records.index') }}" class="nav-link {{ request()->routeIs('rental-records.*') ? 'active' : '' }}"><i class="ti ti-home-check"></i> Rented Records</a></li>
                 <li class="nav-item mt-2"><span class="text-white-50 small fw-medium px-2 text-uppercase" style="font-size:0.65rem;letter-spacing:0.1em;">Team &amp; Payouts</span></li>
                @can('view_team')
                <li><a href="{{ route('team.index') }}" class="nav-link {{ request()->routeIs('team.*') || request()->routeIs('agents.*') ? 'active' : '' }}"><i class="ti ti-users-group"></i> Team</a></li>
                @endcan
                <li><a href="{{ route('commissions.index') }}" class="nav-link {{ request()->routeIs('commissions.*') ? 'active' : '' }}"><i class="ti ti-percentage"></i> Commissions</a></li>
                <li><a href="{{ route('agent-payouts.index') }}" class="nav-link {{ request()->routeIs('agent-payouts.*') ? 'active' : '' }}"><i class="ti ti-cash"></i> Agent Payouts</a></li>
            </ul>
            <hr>
            <ul class="nav nav-pills flex-column p-3">
                <li><a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"><i class="ti ti-report"></i> Reports</a></li>
                <li><a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"><i class="ti ti-bell"></i> Notifications @php $unread = Auth::user()?->unreadNotifications()->count() ?? 0; @endphp @if($unread > 0)<span class="badge rounded-pill bg-danger ms-1">{{ $unread }}</span>@endif</a></li>
                @can('admin')
                <li class="nav-item mt-2"><span class="text-white-50 small fw-medium px-2 text-uppercase" style="font-size:0.65rem;letter-spacing:0.1em;">Administration</span></li>
                <li><a href="{{ route('cities.index') }}" class="nav-link {{ request()->routeIs('cities.*') ? 'active' : '' }}"><i class="ti ti-building-community"></i> Cities</a></li>
                <li><a href="{{ route('contacts.index') }}" class="nav-link {{ request()->routeIs('contacts.*') ? 'active' : '' }}"><i class="ti ti-message-report"></i> Enquiries</a></li>
                <li><a href="{{ route('referrals.index') }}" class="nav-link {{ request()->routeIs('referrals.*') ? 'active' : '' }}"><i class="ti ti-users-group"></i> Referrals</a></li>
                <li><a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}"><i class="ti ti-receipt"></i> Expenses</a></li>
                <li><a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"><i class="ti ti-shield"></i> Roles</a></li>
                <li><a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}"><i class="ti ti-key"></i> Permissions</a></li>
                <li><a href="{{ route('activity-log') }}" class="nav-link {{ request()->routeIs('activity-log') ? 'active' : '' }}"><i class="ti ti-history"></i> Activity Log</a></li>
                <li><a href="{{ route('settings.items') }}" class="nav-link {{ request()->routeIs('item-templates.*') || request()->routeIs('settings.items') ? 'active' : '' }}"><i class="ti ti-template"></i> Item Templates</a></li>
                <li><a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') && !request()->routeIs('settings.items') ? 'active' : '' }}"><i class="ti ti-settings"></i> Settings</a></li>
                @endcan
            </ul>
        </div>
    </div>

        {{-- Main Content --}}
        <div class="d-flex flex-column flex-grow-1 main-content-area">
            {{-- Top Navbar --}}
            <nav class="navbar navbar-expand topbar shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-link d-lg-none text-decoration-none text-secondary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                        <i class="ti ti-menu-2" style="font-size:1.4rem;"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        {{-- Global Search --}}
                        @include('partials.global-search')
        <ul class="navbar-nav ms-auto align-items-center gap-2">
            <li class="nav-item">
                <a class="nav-link position-relative" href="{{ route('notifications.index') }}" title="Notifications">
                    <i class="ti ti-bell"></i>
                    @php $unread = Auth::user()?->unreadNotifications()->count() ?? 0; @endphp
                    @if($unread > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unread }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <button class="theme-toggle" id="themeToggle" title="Toggle theme">
                    <i class="ti ti-sun" id="themeIcon"></i>
                </button>
            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:var(--table-stripe);color:var(--text-muted);font-weight:700;font-size:0.85rem;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                                    @endif
                                    <div class="text-end me-2 lh-1">
                                        <div class="fw-semibold" style="font-size:0.8rem;">{{ Auth::user()->name }}</div>
                                        <div class="text-secondary" style="font-size:0.7rem;">{{ Auth::user()->email }}</div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="ti ti-user"></i> Profile</a></li>
                                    @can('admin')<li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="ti ti-settings"></i> Settings</a></li>@endcan
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="ti ti-logout"></i> Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            {{-- Page Content --}}
            <div class="flex-grow-1 p-4 page-content section-fade">
                @hasSection('breadcrumbs')
                    <div class="mb-3">
                        @yield('breadcrumbs')
                    </div>
                @endif
                @yield('content')
            </div>

            {{-- Footer --}}
            <footer class="text-center py-3 small border-top footer">
                {{ config('app.name') }} &copy; {{ date('Y') }}
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/jquery.min.js') }}"></script>
    <script>
        // Disable buttons on submit to prevent double-clicks
        document.addEventListener('submit', function(e) {
            const form = e.target;
            form.querySelectorAll('button[type="submit"]').forEach(btn => {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Processing...';
            });
        });
    </script>
    <script>
        // Theme toggle
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
            const icon = document.getElementById('themeIcon');
            if (icon) icon.className = theme === 'dark' ? 'ti ti-moon' : 'ti ti-sun';

            document.getElementById('themeToggle')?.addEventListener('click', function() {
                const current = document.documentElement.getAttribute('data-theme');
                const next = current === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', next);
                localStorage.setItem('theme', next);
                const icon = document.getElementById('themeIcon');
                if (icon) icon.className = next === 'dark' ? 'ti ti-moon' : 'ti ti-sun';
            });
        })();
    </script>
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

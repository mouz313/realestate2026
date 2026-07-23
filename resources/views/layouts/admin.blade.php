<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/tabler-icons.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="{{ asset('assets/custom.css') }}" rel="stylesheet">
    <style>.urdu{font-size:0.75em;opacity:0.75;unicode-bidi:embed}.min-w-0{min-width:0}</style>
    @stack('styles')
</head>
<body>
<div class="d-flex" style="min-height: 100vh;">
    {{-- Sidebar --}}
    <div class="d-flex flex-column flex-shrink-0 p-3 sidebar" style="width: 260px; position: fixed; top: 0; left: 0; height: 100vh; overflow-y: auto; z-index: 100;">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none gap-2 sidebar-brand">
            @php $brandLogo = \App\Models\Setting::where('key', 'brand_logo')->value('value'); @endphp
            @if($brandLogo)
                <img src="{{ Storage::url($brandLogo) }}" alt="{{ config('app.name') }}" class="sidebar-logo">
            @else
                <span class="fs-5 fw-bold">{{ config('app.name') }}</span>
            @endif
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="ti ti-home"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <i class="ti ti-users"></i>
                    Clients
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
                    <a href="{{ route('properties.index') }}" class="nav-link {{ request()->routeIs('properties.*') ? 'active' : '' }}">
                        <i class="ti ti-building"></i>
                        Properties
                    </a>
                </li>
                @can('admin')
                <li>
                    <a href="{{ route('agents.index') }}" class="nav-link {{ request()->routeIs('agents.*') ? 'active' : '' }}">
                        <i class="ti ti-users-group"></i>
                        Team
                    </a>
                </li>
                <li>
                    <a href="{{ route('cities.index') }}" class="nav-link {{ request()->routeIs('cities.*') ? 'active' : '' }}">
                        <i class="ti ti-building-community"></i>
                        Cities
                    </a>
                </li>
                @endcan
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
                    <a href="{{ route('installments.index') }}" class="nav-link {{ request()->routeIs('installments.*') ? 'active' : '' }}">
                        <i class="ti ti-calendar-stats"></i>
                        Installments
                    </a>
                </li>
                <li>
                    <a href="{{ route('rent-agreements.index') }}" class="nav-link {{ request()->routeIs('rent-agreements.*') ? 'active' : '' }}">
                        <i class="ti ti-home-2"></i>
                        Rent Agreements
                    </a>
                </li>
                <li>
                    <a href="{{ route('property-visits.index') }}" class="nav-link {{ request()->routeIs('property-visits.*') ? 'active' : '' }}">
                        <i class="ti ti-calendar-event"></i>
                        Visits
                    </a>
                </li>
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
                    <a href="{{ route('home') }}" target="_blank" class="nav-link">
                        <i class="ti ti-external-link"></i>
                        Visit Website
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="ti ti-report"></i>
                        Reports
                    </a>
                </li>
                @can('admin')
                <li>
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <i class="ti ti-settings"></i>
                        Settings
                    </a>
                </li>
                @endcan
            </ul>
        </div>

        {{-- Main Content --}}
        <div class="d-flex flex-column flex-grow-1 main-content-area" style="margin-left: 260px;">
            {{-- Top Navbar --}}
            <nav class="navbar navbar-expand-lg topbar shadow-sm">
                <div class="container-fluid">
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        {{-- Global Search --}}
                        <div class="nav-search position-relative me-auto">
                            <i class="ti ti-search nav-search-icon"></i>
                            <input type="text" id="globalSearch" class="form-control nav-search-input" placeholder="Search anything..." autocomplete="off">
                            <div id="searchResults" class="search-dropdown"></div>
                        </div>
                        <ul class="navbar-nav ms-auto align-items-center gap-2">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
        // Global search
        (function() {
            const input = document.getElementById('globalSearch');
            const results = document.getElementById('searchResults');
            let timer;

            if (!input) return;

            input.addEventListener('input', function() {
                clearTimeout(timer);
                const q = this.value.trim();
                if (q.length < 2) {
                    results.classList.remove('show');
                    return;
                }
                timer = setTimeout(() => {
                    fetch('{{ route('search.index') }}?q=' + encodeURIComponent(q), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.length) {
                            results.innerHTML = '<div class="search-dropdown-empty">No results found</div>';
                            results.classList.add('show');
                            return;
                        }
                        results.innerHTML = '';
                        data.forEach(item => {
                            const el = document.createElement('a');
                            el.href = item.url;
                            el.className = 'search-dropdown-item';
                            el.innerHTML = '<i class="' + item.icon + '"></i>' +
                                '<div class="search-dropdown-text">' +
                                    '<div class="search-dropdown-label"></div>' +
                                    '<div class="search-dropdown-sub"></div>' +
                                '</div>';
                            el.querySelector('.search-dropdown-label').textContent = item.label;
                            el.querySelector('.search-dropdown-sub').textContent = item.type + (item.sub ? ' · ' + item.sub : '');
                            results.appendChild(el);
                        });
                        results.classList.add('show');
                    })
                    .catch((err) => console.error('Search error:', err));
                }, 300);
            });

            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !results.contains(e.target)) {
                    results.classList.remove('show');
                }
            });
        })();
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

@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
  .urdu { font-size:0.75em; opacity:0.75; unicode-bidi:embed; }
  .min-w-0 { min-width:0; }
  @media(max-width:575.98px){
    .stat-value.fs-5 { font-size:1rem !important; }
    .stat-card .stat-label { font-size:0.7rem !important; }
  }
  /* Attention panel */
  .attention-card { border:0; border-radius:12px; transition:transform .15s ease, box-shadow .15s ease; overflow:hidden; }
  .attention-card:hover { transform:translateY(-3px); box-shadow:0 10px 25px rgba(0,0,0,.12); }
  .attention-card .att-icon { width:46px; height:46px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
  .attention-card .att-count { font-size:1.7rem; font-weight:700; line-height:1; }
  .attention-card .att-label { font-size:0.82rem; color:var(--text-muted); }
  .attention-danger { background:rgba(239,68,68,.08); }
  .attention-danger .att-icon { background:rgba(239,68,68,.15); color:#ef4444; }
  .attention-danger .att-count { color:#ef4444; }
  .attention-warning { background:rgba(245,158,11,.10); }
  .attention-warning .att-icon { background:rgba(245,158,11,.18); color:#f59e0b; }
  .attention-warning .att-count { color:#f59e0b; }
  .attention-info { background:rgba(14,165,233,.08); }
  .attention-info .att-icon { background:rgba(14,165,233,.15); color:#0ea5e9; }
  .attention-info .att-count { color:#0ea5e9; }
  .attention-purple { background:rgba(139,92,246,.08); }
  .attention-purple .att-icon { background:rgba(139,92,246,.15); color:#8b5cf6; }
  .attention-purple .att-count { color:#8b5cf6; }
  /* Compact list rows */
  .mini-list { margin:0; padding:0; list-style:none; }
  .mini-list li { padding:.6rem .25rem; border-bottom:1px solid var(--table-border, #eee); }
  .mini-list li:last-child { border-bottom:0; }
  .mini-row { display:flex; align-items:center; gap:.6rem; }
  .mini-thumb { width:38px; height:38px; border-radius:9px; background:rgba(212,162,78,.12); color:var(--sky-amber,#D4A24E); display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
</style>
@endpush

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
</nav>
@endsection

@section('content')
{{-- Attention Required — action items admin must not miss --}}
@php
    $attentionItems = collect([
        ['count' => $stats['new_enquiries'], 'label' => 'New Enquiries', 'urdu' => 'نئی انکوائریاں', 'icon' => 'ti-message-report', 'cls' => 'attention-danger', 'route' => 'contacts.index', 'show' => $stats['new_enquiries'] > 0],
        ['count' => $recentReferrals->count(), 'label' => 'Referrals', 'urdu' => 'ریفرلز', 'icon' => 'ti-users-group', 'cls' => 'attention-purple', 'route' => 'referrals.index', 'show' => $recentReferrals->count() > 0],
    ])->filter(fn ($i) => $i['show']);
@endphp
@if($attentionItems->isNotEmpty())
<div class="alert-attention mb-3">
    <div class="d-flex align-items-center gap-2 mb-2">
        <i class="ti ti-bell-ringing text-danger"></i>
        <h6 class="mb-0 fw-bold">Attention Required <span class="urdu">(توجہ درکار)</span></h6>
        <span class="badge bg-danger ms-1">{{ $attentionItems->sum('count') }}</span>
    </div>
    <div class="row g-3">
        @foreach($attentionItems as $item)
        <div class="col-6 col-lg-3">
            <a href="{{ route($item['route']) }}" class="text-decoration-none">
                <div class="card attention-card {{ $item['cls'] }}">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="att-icon"><i class="ti {{ $item['icon'] }}"></i></div>
                        <div class="min-w-0">
                            <div class="att-count">{{ $item['count'] }}</div>
                            <div class="att-label">{{ $item['label'] }} <span class="urdu">({{ $item['urdu'] }})</span></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- KPI Cards — Records overview --}}
@php
    $totalProperties = \App\Models\Property::count();
    $availableProps  = \App\Models\Property::where('status', 'available')->count();
    $rentedProps     = \App\Models\Property::where('status', 'rented')->count();
    $sellersCount    = \App\Models\Client::where('client_type', 'seller')->count();
    $buyersCount     = \App\Models\Client::where('client_type', 'buyer')->count();
    $callLogCount    = \App\Models\CallLog::count();
    $followupsDue    = \App\Models\CallLog::whereNotNull('follow_up_date')
                            ->whereDate('follow_up_date', '<=', \Carbon\Carbon::today())
                            ->whereNotIn('status', ['converted', 'lost'])
                            ->count();
    $callStatusBadge = [
        'new' => 'bg-info', 'contacted' => 'bg-primary', 'callback' => 'bg-warning',
        'matched' => 'bg-success', 'converted' => 'bg-success', 'lost' => 'bg-danger',
    ];
    $recentCallLogs = \App\Models\CallLog::with('client')->latest()->take(5)->get();
    $recentRentals  = \App\Models\RentalRecord::with(['property', 'tenant'])->latest()->take(5)->get();
@endphp
<div class="kpi-row mb-1">
    <div class="col">
        <div class="kpi-card h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon"><i class="ti ti-building"></i></div>
                <div class="min-w-0">
                    <div class="kpi-value">{{ $totalProperties }}</div>
                    <div class="kpi-label">Total Properties</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="kpi-card kpi-success h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon"><i class="ti ti-building-community"></i></div>
                <div class="min-w-0">
                    <div class="kpi-value">{{ $availableProps }}</div>
                    <div class="kpi-label">Available</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="kpi-card kpi-info h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon"><i class="ti ti-home-check"></i></div>
                <div class="min-w-0">
                    <div class="kpi-value">{{ $rentedProps }}</div>
                    <div class="kpi-label">Rented</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="kpi-card kpi-accent h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon"><i class="ti ti-user-check"></i></div>
                <div class="min-w-0">
                    <div class="kpi-value">{{ $sellersCount }}</div>
                    <div class="kpi-label">Sellers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="kpi-card kpi-accent h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon"><i class="ti ti-user-plus"></i></div>
                <div class="min-w-0">
                    <div class="kpi-value">{{ $buyersCount }}</div>
                    <div class="kpi-label">Buyers</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="kpi-card h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon"><i class="ti ti-phone-call"></i></div>
                <div class="min-w-0">
                    <div class="kpi-value">{{ $callLogCount }}</div>
                    <div class="kpi-label">Call Logs</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="kpi-card kpi-warning h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon"><i class="ti ti-bell-ringing"></i></div>
                <div class="min-w-0">
                    <div class="kpi-value">{{ $followupsDue }}</div>
                    <div class="kpi-label">Follow-ups Due</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Core Business Stats --}}
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">
    <div class="col">
        <div class="card stat-card stat-card-clients">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Total Clients <span class="urdu">(کل گاہک)</span></div>
                        <div class="stat-value">{{ $stats['total_clients'] }}</div>
                        <a href="{{ route('clients.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0"><i class="ti ti-users"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card stat-card-quotations">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Quotations <span class="urdu">(کوٹیشنز)</span></div>
                        <div class="stat-value">{{ $stats['total_quotations'] }}</div>
                        <a href="{{ route('quotations.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0"><i class="ti ti-file-description"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card stat-card-pending">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Pending Quotes <span class="urdu">(زیر التواء)</span></div>
                        <div class="stat-value">{{ $stats['pending_quotations'] }}</div>
                        <a href="{{ route('quotations.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0"><i class="ti ti-clock"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card stat-card-invoices">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Invoices <span class="urdu">(انوائسز)</span></div>
                        <div class="stat-value">{{ $stats['total_invoices'] }}</div>
                        <a href="{{ route('invoices.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0"><i class="ti ti-file-invoice"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card stat-card-unpaid">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Unpaid / Overdue <span class="urdu">(ادھار / زائد)</span></div>
                        <div class="stat-value">{{ $stats['unpaid_invoices'] }} / {{ $stats['overdue_invoices'] }}</div>
                        <a href="{{ route('invoices.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0"><i class="ti ti-alert-circle"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card" style="--accent-clients: #10b981;">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Revenue / Outstanding <span class="urdu">(آمدنی / بقایا)</span></div>
                        <div class="stat-value fs-5">{{ number_format($stats['total_revenue'], 0) }} / {{ number_format($stats['outstanding'], 0) }}</div>
                        <a href="{{ route('payments.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0" style="background: rgba(16,185,129,0.1);color:#10b981;"><i class="ti ti-currency-dollar"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card" style="--accent-clients: #ef4444;">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Expenses <span class="urdu">(اخراجات)</span></div>
                        <div class="stat-value fs-5">{{ number_format($stats['total_expenses'], 0) }}</div>
                        <a href="{{ route('expenses.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0" style="background: rgba(239,68,68,0.1);color:#ef4444;"><i class="ti ti-receipt"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card" style="--accent-clients: #8b5cf6;">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Conversion Rate <span class="urdu">(تبادلی شرح)</span></div>
                        <div class="stat-value">{{ $stats['conversion_rate'] }}%</div>
                        <span class="stat-link">Quotes → Invoices</span>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0" style="background: rgba(139,92,246,0.1);color:#8b5cf6;"><i class="ti ti-trending-up"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card" style="--accent-clients: #f59e0b;">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Avg Deal Size <span class="urdu">(اوسط ڈیل)</span></div>
                        <div class="stat-value fs-5">{{ number_format($stats['avg_deal_size'], 0) }}</div>
                        <span class="stat-link">Per Invoice <span class="urdu">(فی انوائس)</span></span>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0" style="background: rgba(245,158,11,0.1);color:#f59e0b;"><i class="ti ti-chart-bar"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Monthly Trends --}}
<div class="mt-3 mt-md-4">
    <div class="row g-3">
        <div class="col-lg-7">
            <h5 class="mb-3 fw-semibold section-heading"><i class="ti ti-trending-up me-1"></i> Monthly Trends (6 Months) <span class="urdu">(ماہانہ رجحانات)</span></h5>
            <div class="card">
                <div class="card-body">
                    <div style="position:relative;height:250px;">
                        <canvas id="trendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <h5 class="mb-3 fw-semibold section-heading"><i class="ti ti-cash me-1"></i> Revenue vs Expenses <span class="urdu">(آمدنی بمقابلہ اخراجات)</span></h5>
            <div class="card">
                <div class="card-body">
                    <div style="position:relative;height:250px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Real Estate Stats --}}
<div class="mt-3 mt-md-4">
    <h5 class="mb-3 fw-semibold section-heading"><i class="ti ti-building me-1"></i> Real Estate Overview <span class="urdu">(جائزہ)</span></h5>
    <div class="row g-3">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card" style="--accent-clients: #6366f1;">
                <div class="card-body">
                    <div class="stat-label">Available <span class="urdu">(دستیاب)</span></div>
                    <div class="stat-value" style="font-size:1.35rem;">{{ $stats['active_properties'] }}</div>
                    <a href="{{ route('properties.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                </div>
            </div>
        </div>
        @can('admin')
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card" style="--accent-clients: #ec4899;">
                <div class="card-body">
                    <div class="stat-label">Agents <span class="urdu">(ایجنٹس)</span></div>
                    <div class="stat-value" style="font-size:1.35rem;">{{ $stats['active_agents'] }}</div>
                    <a href="{{ route('team.index', ['type' => 'agents']) }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                </div>
            </div>
        </div>
        @endcan
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card" style="--accent-clients: #3b82f6;">
                <div class="card-body">
                    <div class="stat-label">Active Deals <span class="urdu">(فعال ڈیلز)</span></div>
                    <div class="stat-value" style="font-size:1.35rem;">{{ $stats['active_deals'] }}</div>
                    <a href="{{ route('deals.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card" style="--accent-clients: #f59e0b;">
                <div class="card-body">
                    <div class="stat-label">Visits <span class="urdu">(دورے)</span></div>
                    <div class="stat-value" style="font-size:1.35rem;">{{ $stats['upcoming_visits'] }}</div>
                    <a href="{{ route('property-visits.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card" style="--accent-clients: #8b5cf6;">
                <div class="card-body">
                    <div class="stat-label">Pending Comm. <span class="urdu">(زیر کمیشن)</span></div>
                    <div class="stat-value" style="font-size:1.35rem;">{{ number_format($stats['pending_commissions'], 0) }}</div>
                    <a href="{{ route('commissions.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Records — Call Logs & Rental Records --}}
<div class="mt-3 mt-md-4">
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-phone-call me-1"></i> Recent Call Logs <span class="urdu">(حالیہ کالیں)</span></h5>
                    <a href="{{ route('call-logs.index') }}" class="small text-decoration-none fw-medium">View all</a>
                </div>
                <div class="card-body p-0">
                    <ul class="mini-list">
                        @forelse($recentCallLogs as $cl)
                        <li>
                            <div class="mini-row">
                                <div class="mini-thumb"><i class="ti ti-phone"></i></div>
                                <div class="min-w-0 flex-grow-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-medium text-truncate">{{ $cl->name ?: ($cl->client->name ?? '—') }}</span>
                                        <span class="badge {{ $callStatusBadge[$cl->status] ?? 'bg-secondary' }}">{{ ucfirst(str_replace('_', ' ', $cl->status)) }}</span>
                                    </div>
                                    <div class="small text-secondary text-truncate">
                                        {{ $cl->category ? ucfirst(str_replace('_', ' ', $cl->category)) : 'Requirement' }}
                                        @if($cl->transaction_type) &middot; {{ ucfirst($cl->transaction_type) }} @endif
                                        @if($cl->follow_up_date) &middot; Follow-up: {{ $cl->follow_up_date->format('d M Y') }} @endif
                                    </div>
                                </div>
                                <a href="{{ route('call-logs.show', $cl) }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-eye"></i></a>
                            </div>
                        </li>
                        @empty
                        <li class="text-center text-secondary py-4 small">No call logs yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-home-check me-1"></i> Recent Rental Records <span class="urdu">(حالیہ کرائے)</span></h5>
                    <a href="{{ route('rental-records.index') }}" class="small text-decoration-none fw-medium">View all</a>
                </div>
                <div class="card-body p-0">
                    <ul class="mini-list">
                        @forelse($recentRentals as $rr)
                        <li>
                            <div class="mini-row">
                                <div class="mini-thumb"><i class="ti ti-home"></i></div>
                                <div class="min-w-0 flex-grow-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-medium text-truncate">{{ $rr->property->title ?? 'Property' }}</span>
                                        <span class="badge {{ $callStatusBadge[$rr->status] ?? 'bg-secondary' }}">{{ ucfirst(str_replace('_', ' ', $rr->status)) }}</span>
                                    </div>
                                    <div class="small text-secondary text-truncate">
                                        Tenant: {{ $rr->tenant->name ?? '—' }}
                                        @if($rr->start_date) &middot; From {{ $rr->start_date->format('d M Y') }} @endif
                                    </div>
                                </div>
                                <a href="{{ route('rental-records.show', $rr) }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-eye"></i></a>
                            </div>
                        </li>
                        @empty
                        <li class="text-center text-secondary py-4 small">No rental records yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lead Source Analytics --}}
<div class="mt-3 mt-md-4">
    <h5 class="mb-3 fw-semibold section-heading"><i class="ti ti-target me-1"></i> Lead Sources <span class="urdu">(لیڈ کے ذرائع)</span></h5>
    <div class="card">
        <div class="card-body">
            @php
                $lsTotal = $stats['lead_sources']->sum();
                $lsMax = $stats['lead_sources']->max() ?: 1;
            @endphp
            @if($lsTotal > 0)
                <div class="row g-3">
                    <div class="col-lg-7">
                        <div class="d-flex flex-column gap-2">
                            @foreach($stats['lead_sources'] as $key => $count)
                                <div>
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>{{ \App\Helpers\Status::leadSourceLabel($key) }}</span>
                                        <span class="text-secondary">{{ $count }} ({{ round($count / $lsTotal * 100) }}%)</span>
                                    </div>
                                    <div class="progress" style="height:8px;">
                                        <div class="progress-bar" style="width: {{ round($count / $lsMax * 100) }}%;background:linear-gradient(90deg,#6366f1,#8b5cf6);"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-5 d-flex flex-column justify-content-center align-items-center text-center border-start-lg">
                        <div class="stat-value fs-3">{{ $lsTotal }}</div>
                        <div class="stat-label">Total Leads <span class="urdu">(کل لیڈز)</span></div>
                        <a href="{{ route('contacts.index') }}" class="stat-link mt-2">View Enquiries <i class="ti ti-arrow-right"></i></a>
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <i class="ti ti-target"></i>
                    <p>No leads captured yet. <span class="urdu">(ابھی تک کوئی لیڈ نہیں)</span></p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- New-enquiry / review / referral widgets --}}
<div class="mt-3 mt-md-4">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-message-report me-1"></i> Recent Enquiries <span class="urdu">(حالیہ انکوائریاں)</span></h5>
                    <a href="{{ route('contacts.index') }}" class="small text-decoration-none fw-medium">View all</a>
                </div>
                <div class="card-body p-0">
                    <ul class="mini-list">
                        @forelse($newEnquiries as $c)
                        <li>
                            <div class="mini-row">
                                <div class="mini-thumb"><i class="ti ti-user"></i></div>
                                <div class="min-w-0 flex-grow-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-medium text-truncate">{{ $c->name }}</span>
                                        @if(! $c->read_at)<span class="badge bg-warning text-dark">New</span>@endif
                                    </div>
                                    <div class="small text-secondary text-truncate">
                                        {{ $c->property_type ? \App\Helpers\Status::propertyTypeLabel($c->property_type) : '—' }}
                                        @if($c->purpose) &middot; {{ \App\Helpers\Status::purposeLabel($c->purpose) }} @endif
                                        @if($c->city) &middot; {{ $c->city }} @endif
                                    </div>
                                </div>
                                <a href="{{ route('contacts.show', $c) }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-eye"></i></a>
                            </div>
                        </li>
                        @empty
                        <li class="text-center text-secondary py-4 small">No enquiries yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-users-group me-1"></i> Recent Referrals <span class="urdu">(حالیہ ریفرلز)</span></h5>
                    <a href="{{ route('referrals.index') }}" class="small text-decoration-none fw-medium">View all</a>
                </div>
                <div class="card-body p-0">
                    <ul class="mini-list">
                        @forelse($recentReferrals as $rf)
                        <li>
                            <div class="mini-row">
                                <div class="mini-thumb"><i class="ti ti-user-plus"></i></div>
                                <div class="min-w-0 flex-grow-1">
                                    <div class="fw-medium text-truncate">{{ $rf->referred_name ?? 'Referral' }}</div>
                                    <div class="small text-secondary text-truncate">
                                        {{ ucfirst($rf->status) }}
                                        @if($rf->referrer_name) &middot; by {{ $rf->referrer_name }} @endif
                                    </div>
                                </div>
                                <a href="{{ route('referrals.index') }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-eye"></i></a>
                            </div>
                        </li>
                        @empty
                        <li class="text-center text-secondary py-4 small">No referrals yet. <span class="urdu">(کوئی ریفرل نہیں)</span></li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Activity --}}
<div class="row g-3 mt-3 mt-md-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="ti ti-currency-dollar me-1"></i> Recent Payments <span class="urdu">(حالیہ ادائیگیاں)</span></h5>
                <a href="{{ route('payments.index') }}" class="small text-decoration-none fw-medium flex-shrink-0">View all <span class="urdu">(تمام)</span></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Invoice <span class="urdu">(انوائس)</span></th>
                                <th>Amount <span class="urdu">(رقم)</span></th>
                                <th class="d-none d-sm-table-cell">Method <span class="urdu">(ذریعہ)</span></th>
                                <th>Date <span class="urdu">(تاریخ)</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $p)
                            <tr>
                                <td>
                                    @if($p->invoice && $p->invoice->invoice_number)
                                        <a href="{{ route('invoices.show', $p->invoice) }}" class="text-decoration-none fw-medium">{{ $p->invoice->invoice_number }}</a>
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif
                                </td>
                                <td class="{{ $p->payment_type === 'security_deposit_return' ? 'text-danger' : 'text-success' }} fw-semibold">{{ $p->payment_type === 'security_deposit_return' ? '- ' : '' }}{{ number_format($p->amount, 2) }}</td>
                                <td class="d-none d-sm-table-cell">{{ $p->method ?: '-' }}</td>
                                <td class="text-secondary">{{ $p->paid_date->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-secondary py-4">No payments yet. <span class="urdu">(کوئی ادائیگی نہیں)</span></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="ti ti-file-description me-1"></i> Recent Quotations <span class="urdu">(حالیہ کوٹیشنز)</span></h5>
                <a href="{{ route('quotations.index') }}" class="small text-decoration-none fw-medium flex-shrink-0">View all <span class="urdu">(تمام)</span></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client <span class="urdu">(گاہک)</span></th>
                                <th>Total <span class="urdu">(کل)</span></th>
                                <th>Status <span class="urdu">(کیفیت)</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentQuotations as $q)
                            <tr>
                                <td><a href="{{ route('quotations.show', $q) }}" class="text-decoration-none fw-medium">{{ $q->quote_number }}</a></td>
                                <td>{{ $q->client->name }}</td>
                                <td class="fw-medium">{{ number_format($q->total, 2) }}</td>
                                <td>
                                    @php $sc = \App\Helpers\Status::classes('invoice'); @endphp
                                    <span class="badge {{ $sc[$q->status] ?? 'status-draft' }}">{{ ucfirst($q->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-secondary py-4">No quotations yet. <span class="urdu">(کوئی کوٹیشن نہیں)</span></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="ti ti-handshake me-1"></i> Recent Deals <span class="urdu">(حالیہ ڈیلز)</span></h5>
                <a href="{{ route('deals.index') }}" class="small text-decoration-none fw-medium flex-shrink-0">View all <span class="urdu">(تمام)</span></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr><th>#</th><th>Property <span class="urdu">(جائیداد)</span></th><th class="d-none d-sm-table-cell">Buyer <span class="urdu">(خریدار)</span></th><th class="d-none d-md-table-cell">Agent <span class="urdu">(ایجنٹ)</span></th><th>Amount <span class="urdu">(رقم)</span></th><th>Status <span class="urdu">(کیفیت)</span></th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentDeals ?? [] as $d)
                            <tr>
                                <td><a href="{{ route('deals.show', $d) }}" class="text-decoration-none fw-medium">{{ $d->deal_number }}</a></td>
                                <td>{{ $d->property?->title ?? '-' }}</td>
                                <td class="d-none d-sm-table-cell">{{ $d->buyer?->name ?? '-' }}</td>
                                <td class="d-none d-md-table-cell">{{ $d->agent?->name ?? '-' }}</td>
                                <td class="fw-medium">{{ number_format($d->sale_price ?? 0, 0) }}</td>
                                <td><span class="badge status-{{ $d->status ?? 'pending' }}">{{ str_replace('_', ' ', ucfirst($d->status ?? 'pending')) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-secondary py-4">No deals yet. <span class="urdu">(کوئی ڈیل نہیں)</span></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="ti ti-calendar-event me-1"></i> Upcoming Visits <span class="urdu">(آنے والے دورے)</span></h5>
                <a href="{{ route('property-visits.index') }}" class="small text-decoration-none fw-medium flex-shrink-0">View all <span class="urdu">(تمام)</span></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr><th>Property <span class="urdu">(جائیداد)</span></th><th class="d-none d-sm-table-cell">Client <span class="urdu">(گاہک)</span></th><th class="d-none d-md-table-cell">Agent <span class="urdu">(ایجنٹ)</span></th><th>Scheduled <span class="urdu">(مقررہ)</span></th><th>Status <span class="urdu">(کیفیت)</span></th></tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingVisits ?? [] as $v)
                            <tr>
                                <td>{{ $v->property?->title ?? '-' }}</td>
                                <td class="d-none d-sm-table-cell">{{ $v->client?->name ?? '-' }}</td>
                                <td class="d-none d-md-table-cell">{{ $v->agent?->name ?? '-' }}</td>
                                <td class="text-secondary">{{ $v->scheduled_date->format('d M h:i A') }}</td>
                                <td><span class="badge status-pending">Scheduled</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-secondary py-4">No upcoming visits. <span class="urdu">(کوئی دورہ نہیں)</span></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-report-money me-1"></i> Financial Summary <span class="urdu">(مالی خلاصہ)</span></h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small">Total Revenue</span>
                    <span class="fw-bold text-success">Rs. {{ number_format($stats['total_revenue'], 0) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small">Total Expenses</span>
                    <span class="fw-bold text-danger">Rs. {{ number_format($stats['total_expenses'], 0) }}</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Net Profit</span>
                    <span class="fw-bold {{ $stats['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">Rs. {{ number_format($stats['net_profit'], 0) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary small">Outstanding</span>
                    <span class="fw-bold text-warning">Rs. {{ number_format($stats['outstanding'], 0) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-history me-1"></i> Recent Activity <span class="urdu">(حالیہ سرگرمی)</span></h5>
            </div>
            <div class="card-body p-0">
                @forelse($recentActivities as $act)
                <div class="d-flex align-items-start gap-2 px-3 py-2 border-bottom">
                    <div class="mt-1">
                        @if($act->event === 'created')
                            <span class="badge bg-success" style="font-size:0.6rem;">+</span>
                        @elseif($act->event === 'updated')
                            <span class="badge bg-primary" style="font-size:0.6rem;">~</span>
                        @elseif($act->event === 'deleted')
                            <span class="badge bg-danger" style="font-size:0.6rem;">x</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-grow-1">
                        <div class="small fw-medium text-truncate">{{ $act->description ?? 'Activity' }}</div>
                        <div class="text-muted" style="font-size:0.65rem;">{{ $act->causer?->name ?? 'System' }} &middot; {{ $act->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-secondary py-4 small">No recent activity.</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-building me-1"></i> Properties by Status <span class="urdu">(حالت کے مطابق)</span></h5>
            </div>
            <div class="card-body">
                @foreach($stats['properties_by_status'] as $status => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge status-{{ $status }} text-capitalize">{{ str_replace('_', ' ', $status) }}</span>
                    <span class="fw-bold">{{ $count }}</span>
                </div>
                @endforeach
                <div class="mt-2 pt-2 border-top">
                    <a href="{{ route('properties.index') }}" class="stat-link">View all Properties <i class="ti ti-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ti ti-handshake me-1"></i> Deals by Status</h5>
                <span class="badge bg-dark">Total: Rs. {{ number_format($stats['total_deal_value'] ?? 0, 0) }}</span>
            </div>
            <div class="card-body">
                @php $dealStatusColors = ['inquiry' => 'warning', 'visit_scheduled' => 'info', 'offer_made' => 'warning', 'token_received' => 'purple', 'agreement_signed' => 'info', 'in_progress' => 'primary', 'completed' => 'success', 'cancelled' => 'danger']; @endphp
                @foreach($stats['deals_by_status'] as $status => $count)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-{{ $dealStatusColors[$status] ?? 'secondary' }} text-capitalize">{{ str_replace('_', ' ', $status) }}</span>
                    <span class="fw-bold">{{ $count }}</span>
                </div>
                @endforeach
                @if($stats['deals_by_status']->isEmpty())
                <div class="text-center text-secondary py-3">No deals yet.</div>
                @endif
                <div class="mt-2 pt-2 border-top">
                    <a href="{{ route('deals.index') }}" class="stat-link">View all Deals <i class="ti ti-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Links --}}
<div class="row g-3 mt-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="ti ti-info-circle me-1"></i> Quick Links <span class="urdu">(فوری روابط)</span></h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('contacts.create') }}" class="btn btn-outline-secondary w-100 justify-content-center text-start text-sm-center"><i class="ti ti-plus"></i> <span class="d-none d-sm-inline">Add </span>Enquiry <span class="urdu d-none d-md-inline">(نئی انکوائری)</span></a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('properties.create') }}" class="btn btn-outline-secondary w-100 justify-content-center text-start text-sm-center"><i class="ti ti-plus"></i> <span class="d-none d-sm-inline">Add </span>Property <span class="urdu d-none d-md-inline">(شامل)</span></a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('deals.create') }}" class="btn btn-outline-secondary w-100 justify-content-center text-start text-sm-center"><i class="ti ti-plus"></i> <span class="d-none d-sm-inline">New </span>Deal <span class="urdu d-none d-md-inline">(نئی)</span></a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('clients.create') }}" class="btn btn-outline-secondary w-100 justify-content-center text-start text-sm-center"><i class="ti ti-plus"></i> <span class="d-none d-sm-inline">Add </span>Client <span class="urdu d-none d-md-inline">(شامل)</span></a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('quotations.create') }}" class="btn btn-outline-secondary w-100 justify-content-center text-start text-sm-center"><i class="ti ti-plus"></i> <span class="d-none d-sm-inline">New </span>Quote <span class="urdu d-none d-md-inline">(نئی)</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(isset($stats['monthly_quotations']) || isset($stats['monthly_invoices']))
<script src="{{ asset('assets/chart.umd.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qByMonth = @json($stats['monthly_quotations']->toArray());
    const iByMonth = @json($stats['monthly_invoices']->toArray());
    const months = [...new Set([...Object.keys(qByMonth), ...Object.keys(iByMonth)])].sort();
    const qData = months.map(m => qByMonth[m] ?? 0);
    const iData = months.map(m => iByMonth[m] ?? 0);

    new Chart(document.getElementById('trendsChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                { label: 'Quotations', data: qData, backgroundColor: '#3b82f6', borderRadius: 4 },
                { label: 'Invoices', data: iData, backgroundColor: '#10b981', borderRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    const revByMonth = @json($stats['monthly_revenue']->toArray());
    const expByMonth = @json(($stats['monthly_expenses'] ?? collect())->toArray());
    const revMonths = [...new Set([...Object.keys(revByMonth), ...Object.keys(expByMonth)])].sort();
    const revData = revMonths.map(m => Number(revByMonth[m] ?? 0));
    const expData = revMonths.map(m => Number(expByMonth[m] ?? 0));

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revMonths,
            datasets: [
                { label: 'Revenue', data: revData, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.3 },
                { label: 'Expenses', data: expData, borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.1)', fill: true, tension: 0.3 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>
@endif
@endpush

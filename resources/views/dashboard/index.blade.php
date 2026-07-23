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
{{-- Core Business Stats --}}
<div class="row g-3">
    <div class="col-6 col-md-4 col-xl-3">
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
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card stat-card stat-card-items">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Items <span class="urdu">(آئٹمز)</span></div>
                        <div class="stat-value">{{ $stats['total_items'] }}</div>
                        <a href="{{ route('items.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0"><i class="ti ti-package"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3">
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
    <div class="col-6 col-md-4 col-xl-3">
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
    <div class="col-6 col-md-4 col-xl-3">
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
    <div class="col-6 col-md-4 col-xl-3">
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
    <div class="col-6 col-md-4 col-xl-3">
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
    <div class="col-6 col-md-4 col-xl-3">
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
                    <a href="{{ route('agents.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
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
            <div class="card stat-card" style="--accent-clients: #f97316;">
                <div class="card-body">
                    <div class="stat-label">Rentals <span class="urdu">(کرایہ)</span></div>
                    <div class="stat-value" style="font-size:1.35rem;">{{ $stats['active_rentals'] }}</div>
                    <a href="{{ route('rent-agreements.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
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
                                <td><a href="{{ route('invoices.show', $p->invoice) }}" class="text-decoration-none fw-medium">{{ $p->invoice->invoice_number }}</a></td>
                                <td class="text-success fw-semibold">{{ number_format($p->amount, 2) }}</td>
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
                                    @php $sc = ['draft'=>'status-draft','sent'=>'status-sent','approved'=>'status-approved','rejected'=>'status-rejected','invoiced'=>'status-invoiced']; @endphp
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
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-report-money me-1"></i> Financial Summary <span class="urdu">(مالی خلاصہ)</span></h5>
            </div>
            <div class="card-body">
                <div class="row g-0 text-center">
                    <div class="col-6 border-end">
                        <div class="fs-3 fw-bold" style="color: var(--accent-invoices);">{{ number_format($stats['total_revenue'], 2) }}</div>
                        <div class="text-secondary small">Total Collected <span class="urdu">(کل وصول)</span></div>
                    </div>
                    <div class="col-6">
                        <div class="fs-3 fw-bold" style="color: #ef4444;">{{ number_format($stats['outstanding'], 2) }}</div>
                        <div class="text-secondary small">Outstanding <span class="urdu">(بقایا)</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-info-circle me-1"></i> Quick Links <span class="urdu">(فوری روابط)</span></h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
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
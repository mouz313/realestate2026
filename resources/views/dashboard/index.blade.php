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
                                <td>
                                    @if($p->invoice && $p->invoice->invoice_number)
                                        <a href="{{ route('invoices.show', $p->invoice) }}" class="text-decoration-none fw-medium">{{ $p->invoice->invoice_number }}</a>
                                    @elseif($p->rentAgreement)
                                        <a href="{{ route('rent-agreements.show', $p->rentAgreement) }}" class="text-decoration-none fw-medium">Agreement #{{ $p->rentAgreement->id }}</a>
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
                    <div class="col-6">
                        <a href="{{ route('rent-payments.index') }}" class="btn btn-outline-secondary w-100 justify-content-center text-start text-sm-center"><i class="ti ti-cash"></i> <span class="d-none d-sm-inline">Rent </span>Overview <span class="urdu d-none d-md-inline">(کرایہ)</span></a>
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

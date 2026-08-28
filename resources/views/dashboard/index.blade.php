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
{{-- KPI Cards — at a glance --}}
<div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
    <div class="col">
        <div class="card stat-card stat-card-clients h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Clients <span class="urdu">(کل گاہک)</span></div>
                        <div class="stat-value">{{ $stats['total_clients'] }}</div>
                        <a href="{{ route('clients.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0"><i class="ti ti-users"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card stat-card-quotations h-100">
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
        <div class="card stat-card stat-card-pending h-100">
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
        <div class="card stat-card stat-card-invoices h-100">
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
        <div class="card stat-card stat-card-unpaid h-100">
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
        <div class="card stat-card" style="--accent-clients: #6366f1;">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Active Deals <span class="urdu">(فعال ڈیلز)</span></div>
                        <div class="stat-value">{{ $stats['active_deals'] }}</div>
                        <a href="{{ route('deals.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0" style="background: rgba(99,102,241,0.1);color:#6366f1;"><i class="ti ti-businessplan"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card" style="--accent-clients: #ec4899;">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="min-w-0">
                        <div class="stat-label">Properties <span class="urdu">(دستیاب)</span></div>
                        <div class="stat-value">{{ $stats['active_properties'] }}</div>
                        <a href="{{ route('properties.index') }}" class="stat-link">View <i class="ti ti-arrow-right"></i></a>
                    </div>
                    <div class="stat-icon-wrap flex-shrink-0" style="background: rgba(236,72,153,0.1);color:#ec4899;"><i class="ti ti-building"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Monthly Trends --}}
<div class="mt-4">
    <h5 class="mb-3 fw-semibold section-heading"><i class="ti ti-trending-up me-1"></i> Monthly Trends (6 Months) <span class="urdu">(ماہانہ رجحانات)</span></h5>
    <div class="card">
        <div class="card-body">
            <div style="position:relative;height:260px;">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Recent Records --}}
<div class="mt-4">
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-currency-dollar me-1"></i> Recent Payments <span class="urdu">(حالیہ ادائیگیاں)</span></h5>
                    <a href="{{ route('payments.index') }}" class="small text-decoration-none fw-medium">View all</a>
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
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0"><i class="ti ti-file-description me-1"></i> Recent Quotations <span class="urdu">(حالیہ کوٹیشنز)</span></h5>
                    <a href="{{ route('quotations.index') }}" class="small text-decoration-none fw-medium">View all</a>
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
</div>

{{-- Quick Links --}}
<div class="mt-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="ti ti-info-circle me-1"></i> Quick Links <span class="urdu">(فوری روابط)</span></h5>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <a href="{{ route('call-logs.create') }}" class="btn btn-outline-secondary w-100 justify-content-center text-start text-sm-center"><i class="ti ti-plus"></i> <span class="d-none d-sm-inline">Add </span>Lead <span class="urdu d-none d-md-inline">(نئی لیڈ)</span></a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('properties.create') }}" class="btn btn-outline-secondary w-100 justify-content-center text-start text-sm-center"><i class="ti ti-plus"></i> <span class="d-none d-sm-inline">Add </span>Property <span class="urdu d-none d-md-inline">(شامل)</span></a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('deals.create') }}" class="btn btn-outline-secondary w-100 justify-content-center text-start text-sm-center"><i class="ti ti-plus"></i> <span class="d-none d-sm-inline">New </span>Deal <span class="urdu d-none d-md-inline">(نئی)</span></a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('clients.create') }}" class="btn btn-outline-secondary w-100 justify-content-center text-start text-sm-center"><i class="ti ti-plus"></i> <span class="d-none d-sm-inline">Add </span>Client <span class="urdu d-none d-md-inline">(شامل)</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@endpush

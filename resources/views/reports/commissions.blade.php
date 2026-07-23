@extends('layouts.admin')
@section('title', 'Commission Report <span class="urdu">(کمیشن رپورٹ)</span>')
@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports <span class="urdu">(رپورٹس)</span></a></li>
        <li class="breadcrumb-item active">Commission Report <span class="urdu">(کمیشن رپورٹ)</span></li>
    </ol>
</nav>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h3 class="mb-0"><i class="ti ti-percentage me-1"></i> Commission Report <span class="urdu">(کمیشن رپورٹ)</span></h3>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label small">From <span class="urdu">(سے)</span></label>
                <input type="date" name="start" class="form-control" value="{{ $start }}">
            </div>
            <div class="col-auto">
                <label class="form-label small">To <span class="urdu">(تک)</span></label>
                <input type="date" name="end" class="form-control" value="{{ $end }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-dark">Filter <span class="urdu">(فلٹر)</span></button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card"><div class="card-body text-center"><div class="fs-3 fw-bold text-warning">{{ number_format($totalPending, 0) }}</div><div class="text-secondary small">Pending <span class="urdu">(زیر التواء)</span></div></div></div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-body text-center"><div class="fs-3 fw-bold text-info">{{ number_format($totalApproved, 0) }}</div><div class="text-secondary small">Approved <span class="urdu">(منظور شدہ)</span></div></div></div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-body text-center"><div class="fs-3 fw-bold text-success">{{ number_format($totalPaid, 0) }}</div><div class="text-secondary small">Paid <span class="urdu">(ادا شدہ)</span></div></div></div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-wrap gap-2"><h5 class="mb-0">Commissions <span class="urdu">(کمیشنز)</span></h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Agent <span class="urdu">(ایجنٹ)</span></th>
                        <th>Deal <span class="urdu">(ڈیل)</span></th>
                        <th class="d-none d-sm-table-cell">Property <span class="urdu">(جائیداد)</span></th>
                        <th class="text-end">Amount <span class="urdu">(رقم)</span></th>
                        <th class="d-none d-sm-table-cell">Status <span class="urdu">(کیفیت)</span></th>
                        <th class="d-none d-md-table-cell">Date <span class="urdu">(تاریخ)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commissions as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->agent?->name ?? '-' }}</td>
                        <td>
                            @if($c->deal)
                                <a href="{{ route('deals.show', $c->deal) }}">{{ $c->deal->deal_number }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell">{{ $c->deal?->property?->title ?? '-' }}</td>
                        <td class="text-end">{{ number_format($c->amount, 0) }}</td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge bg-{{ $c->status === 'paid' ? 'success' : ($c->status === 'approved' ? 'info' : 'warning') }}">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $c->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-3">No commissions found. <span class="urdu">(کوئی کمیشن نہیں ملا)</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3 d-flex justify-content-center">
    {{ $commissions->withQueryString()->links() }}
</div>
@endsection
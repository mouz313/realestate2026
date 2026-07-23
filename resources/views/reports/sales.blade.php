@extends('layouts.admin')
@section('title', 'Sales Report <span class="urdu">(فروخت رپورٹ)</span>')
@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports <span class="urdu">(رپورٹس)</span></a></li>
        <li class="breadcrumb-item active">Sales Report <span class="urdu">(فروخت رپورٹ)</span></li>
    </ol>
</nav>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h3 class="mb-0"><i class="ti ti-trending-up me-1"></i> Sales Report <span class="urdu">(فروخت رپورٹ)</span></h3>
    <a href="{{ route('reports.sales.pdf', ['start' => $start, 'end' => $end]) }}" class="btn btn-dark"><i class="ti ti-file-download"></i> Export PDF <span class="urdu">(پی ڈی ایف برآمد)</span></a>
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
        <div class="card"><div class="card-body text-center"><div class="fs-3 fw-bold">{{ $dealCount }}</div><div class="text-secondary small">Deals Closed <span class="urdu">(ڈیلیں بند)</span></div></div></div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-body text-center"><div class="fs-3 fw-bold text-success">{{ number_format($totalVolume, 0) }}</div><div class="text-secondary small">Total Volume (PKR) <span class="urdu">(کل حجم)</span></div></div></div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-body text-center"><div class="fs-3 fw-bold text-primary">{{ number_format($totalCommission, 0) }}</div><div class="text-secondary small">Total Commission <span class="urdu">(کل کمیشن)</span></div></div></div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-wrap gap-2"><h5 class="mb-0">Monthly Trends <span class="urdu">(ماہانہ رجحانات)</span></h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Month <span class="urdu">(مہینہ)</span></th><th class="text-end">Deals <span class="urdu">(ڈیلیں)</span></th><th class="text-end d-none d-sm-table-cell">Volume <span class="urdu">(حجم)</span></th><th class="text-end d-none d-sm-table-cell">Commission <span class="urdu">(کمیشن)</span></th></tr>
                </thead>
                <tbody>
                    @forelse($monthlyData as $row)
                    <tr>
                        <td>{{ $row->month }}</td>
                        <td class="text-end">{{ $row->count }}</td>
                        <td class="text-end d-none d-sm-table-cell">{{ number_format($row->volume, 0) }}</td>
                        <td class="text-end d-none d-sm-table-cell">{{ number_format($row->commission, 0) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-secondary py-3">No data yet. <span class="urdu">(ابھی تک کوئی ڈیٹا نہیں)</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex flex-wrap gap-2"><h5 class="mb-0">Deals <span class="urdu">(ڈیلیں)</span></h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Deal # <span class="urdu">(ڈیل نمبر)</span></th><th>Property <span class="urdu">(جائیداد)</span></th><th class="d-none d-sm-table-cell">Buyer <span class="urdu">(خریدار)</span></th><th class="d-none d-sm-table-cell">Agent <span class="urdu">(ایجنٹ)</span></th><th class="text-end">Price <span class="urdu">(قیمت)</span></th><th class="text-end d-none d-md-table-cell">Commission <span class="urdu">(کمیشن)</span></th></tr>
                </thead>
                <tbody>
                    @forelse($deals as $d)
                    <tr>
                        <td><a href="{{ route('deals.show', $d) }}">{{ $d->deal_number }}</a></td>
                        <td>{{ $d->property?->title ?? '-' }}</td>
                        <td class="d-none d-sm-table-cell">{{ $d->buyer?->name ?? '-' }}</td>
                        <td class="d-none d-sm-table-cell">{{ $d->agent?->name ?? '-' }}</td>
                        <td class="text-end">{{ number_format($d->sale_price ?? 0, 0) }}</td>
                        <td class="text-end d-none d-md-table-cell">{{ number_format($d->commission_amount ?? 0, 0) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-3">No completed deals in this period. <span class="urdu">(اس مدت میں کوئی مکمل ڈیل نہیں)</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
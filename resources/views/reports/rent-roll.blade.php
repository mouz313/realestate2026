@extends('layouts.admin')
@section('title', 'Rent Roll <span class="urdu">(کرایہ رول)</span>')
@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports <span class="urdu">(رپورٹس)</span></a></li>
        <li class="breadcrumb-item active">Rent Roll <span class="urdu">(کرایہ رول)</span></li>
    </ol>
</nav>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h3 class="mb-0"><i class="ti ti-home-2 me-1"></i> Rent Roll <span class="urdu">(کرایہ رول)</span></h3>
</div>

<div class="card mb-4">
    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ $status === 'active' ? 'active' : '' }}" href="{{ route('reports.rent-roll', ['status' => 'active']) }}">Active <span class="urdu">(فعال)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'expired' ? 'active' : '' }}" href="{{ route('reports.rent-roll', ['status' => 'expired']) }}">Expired <span class="urdu">(میعاد ختم)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'terminated' ? 'active' : '' }}" href="{{ route('reports.rent-roll', ['status' => 'terminated']) }}">Terminated <span class="urdu">(ختم شدہ)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" href="{{ route('reports.rent-roll', ['status' => 'all']) }}">All <span class="urdu">(تمام)</span></a>
            </li>
        </ul>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card"><div class="card-body text-center"><div class="fs-3 fw-bold">{{ $activeCount }}</div><div class="text-secondary small">Active Agreements <span class="urdu">(فعال معاہدے)</span></div></div></div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-body text-center"><div class="fs-3 fw-bold text-success">{{ number_format($totalMonthlyRent, 0) }}</div><div class="text-secondary small">Monthly Rent (PKR) <span class="urdu">(ماہانہ کرایہ)</span></div></div></div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-body text-center"><div class="fs-3 fw-bold text-primary">{{ number_format($totalDeposits, 0) }}</div><div class="text-secondary small">Total Security Deposits <span class="urdu">(کل سیکیورٹی ڈپازٹ)</span></div></div></div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex flex-wrap gap-2"><h5 class="mb-0">Rent Agreements <span class="urdu">(کرایہ کے معاہدے)</span></h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Property <span class="urdu">(جائیداد)</span></th>
                        <th>Tenant <span class="urdu">(کرایہ دار)</span></th>
                        <th class="d-none d-sm-table-cell">Owner <span class="urdu">(مالک)</span></th>
                        <th class="text-end">Rent <span class="urdu">(کرایہ)</span></th>
                        <th class="text-end d-none d-sm-table-cell">Deposit <span class="urdu">(ڈپازٹ)</span></th>
                        <th class="d-none d-md-table-cell">Period <span class="urdu">(مدت)</span></th>
                        <th class="d-none d-sm-table-cell">Status <span class="urdu">(کیفیت)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agreements as $a)
                    <tr>
                        <td>{{ $a->property?->title ?? '-' }}</td>
                        <td>{{ $a->tenant?->name ?? '-' }}</td>
                        <td class="d-none d-sm-table-cell">{{ $a->owner?->name ?? '-' }}</td>
                        <td class="text-end">{{ number_format($a->rent_amount, 0) }}</td>
                        <td class="text-end d-none d-sm-table-cell">{{ number_format($a->security_deposit, 0) }}</td>
                        <td class="d-none d-md-table-cell">{{ $a->start_date?->format('d M Y') }} - {{ $a->end_date?->format('d M Y') }}</td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge bg-{{ $a->status === 'active' ? 'success' : ($a->status === 'expired' ? 'secondary' : 'danger') }}">
                                {{ ucfirst($a->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-3">No rent agreements found. <span class="urdu">(کوئی کرایہ معاہدہ نہیں ملا)</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3 d-flex justify-content-center">
    {{ $agreements->withQueryString()->links() }}
</div>
@endsection
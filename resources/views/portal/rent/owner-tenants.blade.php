@extends('portal.layouts.app')

@section('title', 'My Tenants')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="ti ti-users me-1"></i> My Tenants</h4>
</div>

@if($agreements->count())
<div class="row g-3">
    @foreach($agreements as $agreement)
    <div class="col-md-6">
        <div class="card portal-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">{{ $agreement->tenant?->name ?? '-' }}</h6>
                    <span class="badge bg-success">Active</span>
                </div>
                <div class="small text-secondary mb-2"><i class="ti ti-building"></i> {{ $agreement->property?->title ?? 'N/A' }}</div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary small">Phone</span><span>{{ $agreement->tenant?->phone ?? '-' }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary small">Email</span><span>{{ $agreement->tenant?->email ?? '-' }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary small">CNIC</span><span>{{ $agreement->tenant?->cnic ?? '-' }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-secondary small">Monthly Rent</span><span class="fw-bold">Rs. {{ number_format($agreement->rent_amount, 0) }}</span></div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card portal-card">
    <div class="card-body text-center py-5 text-secondary">
        <i class="ti ti-users" style="font-size:3rem;display:block;margin-bottom:12px;"></i>
        No active tenants found.
    </div>
</div>
@endif
@endsection

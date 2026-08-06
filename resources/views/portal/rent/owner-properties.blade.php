@extends('portal.layouts.app')

@section('title', 'My Rented Properties')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="ti ti-building me-1"></i> My Rented Properties</h4>
</div>

@if($agreements->count())
<div class="row g-3">
    @foreach($agreements as $agreement)
    @php
        $totalPaid = (float) $agreement->rentPayments->where('status', 'paid')->sum('amount');
        $totalPending = (float) $agreement->rentPayments->whereIn('status', ['pending', 'overdue'])->sum('total_due');
    @endphp
    <div class="col-md-6">
        <div class="card portal-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">{{ $agreement->property?->title ?? 'Property #' . $agreement->property_id }}</h6>
                    <span class="badge {{ $agreement->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($agreement->status) }}</span>
                </div>
                <div class="small text-secondary mb-2"><i class="ti ti-user"></i> Tenant: {{ $agreement->tenant?->name ?? '-' }}</div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary small">Monthly Rent</span><span class="fw-bold">Rs. {{ number_format($agreement->rent_amount, 0) }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary small">Collected</span><span class="text-success fw-semibold">Rs. {{ number_format($totalPaid, 0) }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary small">Pending</span><span class="text-warning fw-semibold">Rs. {{ number_format($totalPending, 0) }}</span></div>
                <div class="d-flex justify-content-between mb-3"><span class="text-secondary small">Period</span><span class="small">{{ $agreement->start_date?->format('d M Y') ?? '-' }} — {{ $agreement->end_date?->format('d M Y') ?? 'Open' }}</span></div>
                <a href="{{ route('portal.owner.property', $agreement) }}" class="btn btn-sm btn-outline-dark w-100"><i class="ti ti-eye me-1"></i> View Details</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card portal-card">
    <div class="card-body text-center py-5 text-secondary">
        <i class="ti ti-building" style="font-size:3rem;display:block;margin-bottom:12px;"></i>
        No rented properties found.
    </div>
</div>
@endif
@endsection

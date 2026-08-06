@extends('portal.layouts.app')

@section('title', 'My Agreements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="ti ti-file-text me-1"></i> My Agreements</h4>
</div>

@if($agreements->count())
<div class="row g-3">
    @foreach($agreements as $agreement)
    <div class="col-md-6">
        <div class="card portal-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">{{ $agreement->property?->title ?? 'Property #' . $agreement->property_id }}</h6>
                    <span class="badge {{ $agreement->status === 'active' ? 'bg-success' : ($agreement->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">{{ ucfirst($agreement->status) }}</span>
                </div>
                <div class="small text-secondary mb-2">
                    <i class="ti ti-map-pin"></i> {{ $agreement->property?->city ?? '-' }}
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-secondary small">Monthly Rent</span>
                    <span class="fw-bold">Rs. {{ number_format($agreement->rent_amount, 0) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-secondary small">Period</span>
                    <span class="small">{{ $agreement->start_date?->format('d M Y') ?? '-' }} — {{ $agreement->end_date?->format('d M Y') ?? 'Open' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-secondary small">Owner</span>
                    <span class="small">{{ $agreement->owner?->name ?? '-' }}</span>
                </div>
                <a href="{{ route('portal.rent.agreement', $agreement) }}" class="btn btn-sm btn-outline-dark w-100"><i class="ti ti-eye me-1"></i> View Details</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card portal-card">
    <div class="card-body text-center py-5 text-secondary">
        <i class="ti ti-file-text" style="font-size:3rem;display:block;margin-bottom:12px;"></i>
        No rent agreements found.
    </div>
</div>
@endif
@endsection

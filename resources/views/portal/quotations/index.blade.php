@extends('portal.layouts.app')

@section('title', 'My Quotations')

@section('content')
<h4 class="mb-4">My Quotations</h4>

@if($quotations->isEmpty())
<div class="card shadow">
    <div class="card-body text-center text-secondary py-5">
        <i class="ti ti-file-description" style="font-size: 3rem;"></i>
        <p class="mt-2 mb-0">No quotations available yet.</p>
    </div>
</div>
@else
<div class="row g-3">
    @foreach($quotations as $q)
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="mb-1">{{ $q->quote_number }}</h5>
                        <small class="text-secondary">{{ $q->created_at->format('d M Y') }}</small>
                    </div>
                    @php
                        $sc = ['draft' => 'secondary', 'sent' => 'primary', 'approved' => 'success', 'rejected' => 'danger', 'invoiced' => 'info'];
                    @endphp
                    <span class="badge bg-{{ $sc[$q->status] ?? 'secondary' }}">{{ ucfirst($q->status) }}</span>
                </div>
                <p class="fs-5 fw-bold mb-2">{{ number_format($q->total, 2) }}</p>
                <a href="{{ route('portal.quotations.show', $q) }}" class="btn btn-sm btn-outline-dark">View Details</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@if($quotations->hasPages())
<div class="mt-3">{{ $quotations->withQueryString()->links() }}</div>
@endif
@endif
@endsection

@extends('portal.layouts.app')

@section('title', 'My Deals')

@section('content')
<h4 class="mb-4">My Deals</h4>

@if($deals->isEmpty())
<div class="card shadow">
    <div class="card-body text-center text-secondary py-5">
        <i class="ti ti-handshake" style="font-size: 3rem;"></i>
        <p class="mt-2 mb-0">No deals available yet.</p>
    </div>
</div>
@else
<div class="row g-3">
    @foreach($deals as $deal)
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="mb-1">{{ $deal->deal_number }}</h5>
                        <small class="text-secondary">{{ $deal->created_at->format('d M Y') }}</small>
                    </div>
                    @php
                        $ds = ['pending' => 'warning', 'active' => 'info', 'completed' => 'success', 'cancelled' => 'danger', 'on_hold' => 'secondary'];
                    @endphp
                    <span class="badge bg-{{ $ds[$deal->status] ?? 'secondary' }}">{{ ucfirst($deal->status) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0"><span class="text-secondary">Type:</span> {{ ucfirst($deal->type) }}</p>
                        <p class="mb-0"><span class="text-secondary">Property:</span> {{ $deal->property->title ?? 'N/A' }}</p>
                        <p class="fs-5 fw-bold mb-0 mt-1">{{ number_format($deal->sale_price, 2) }}</p>
                    </div>
                    <a href="{{ route('portal.deals.show', $deal) }}" class="btn btn-sm btn-outline-dark">View</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@if($deals->hasPages())
<div class="mt-3">{{ $deals->links() }}</div>
@endif
@endif
@endsection

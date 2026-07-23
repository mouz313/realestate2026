@extends('portal.layouts.app')

@section('title', 'My Invoices')

@section('content')
<h4 class="mb-4">My Invoices</h4>

@if($invoices->isEmpty())
<div class="card shadow">
    <div class="card-body text-center text-secondary py-5">
        <i class="ti ti-receipt" style="font-size: 3rem;"></i>
        <p class="mt-2 mb-0">No invoices available yet.</p>
    </div>
</div>
@else
<div class="row g-3">
    @foreach($invoices as $inv)
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h5 class="mb-1">{{ $inv->invoice_number }}</h5>
                        <small class="text-secondary">{{ $inv->created_at->format('d M Y') }}</small>
                    </div>
                    @php
                        $ps = ['pending' => 'warning', 'partial' => 'info', 'paid' => 'success'];
                    @endphp
                    <span class="badge bg-{{ $ps[$inv->payment_status] ?? 'warning' }}">{{ ucfirst($inv->payment_status) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="fs-5 fw-bold mb-0">{{ number_format($inv->total, 2) }}</p>
                        <small class="text-success">Paid: {{ number_format($inv->paid_amount, 2) }}</small>
                    </div>
                    <a href="{{ route('portal.invoices.show', $inv) }}" class="btn btn-sm btn-outline-dark">View</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@if($invoices->hasPages())
<div class="mt-3">{{ $invoices->links() }}</div>
@endif
@endif
@endsection
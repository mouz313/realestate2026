@extends('portal.layouts.app')

@section('title', 'Property Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0"><i class="ti ti-building me-1"></i> {{ $rentAgreement->property?->title ?? 'Property Details' }}</h4>
    <a href="{{ route('portal.owner.properties') }}" class="btn btn-outline-dark btn-sm"><i class="ti ti-arrow-left"></i> Back</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card portal-card text-center p-3">
            <div class="small text-secondary">Monthly Rent</div>
            <div class="fs-5 fw-bold">Rs. {{ number_format($rentAgreement->rent_amount, 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card portal-card text-center p-3">
            <div class="small text-secondary">Collected</div>
            <div class="fs-5 fw-bold text-success">Rs. {{ number_format($totalPaid, 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card portal-card text-center p-3">
            <div class="small text-secondary">Pending</div>
            <div class="fs-5 fw-bold text-warning">Rs. {{ number_format($totalPending, 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card portal-card text-center p-3">
            <div class="small text-secondary">Status</div>
            <div class="mt-1"><span class="badge {{ $rentAgreement->status === 'active' ? 'bg-success' : 'bg-secondary' }} fs-6">{{ ucfirst($rentAgreement->status) }}</span></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card portal-card">
            <div class="card-header"><h6 class="mb-0"><i class="ti ti-building me-1"></i> Property</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Property</span><span class="fw-semibold">{{ $rentAgreement->property?->title ?? 'N/A' }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">City</span><span>{{ $rentAgreement->property?->city ?? '-' }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Type</span><span>{{ ucfirst($rentAgreement->property?->type ?? '-') }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-secondary">Period</span><span>{{ $rentAgreement->start_date?->format('d M Y') ?? '-' }} — {{ $rentAgreement->end_date?->format('d M Y') ?? 'Open' }}</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card portal-card">
            <div class="card-header"><h6 class="mb-0"><i class="ti ti-user me-1"></i> Tenant</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Name</span><span class="fw-semibold">{{ $rentAgreement->tenant?->name ?? '-' }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Phone</span><span>{{ $rentAgreement->tenant?->phone ?? '-' }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-secondary">Email</span><span>{{ $rentAgreement->tenant?->email ?? '-' }}</span></div>
            </div>
        </div>
    </div>
</div>

<div class="card portal-card mt-3">
    <div class="card-header"><h6 class="mb-0"><i class="ti ti-calendar-stats me-1"></i> Payment History</h6></div>
    <div class="card-body p-0">
        @if($payments->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Month</th><th class="text-end">Rent</th><th class="text-end">Late Fee</th><th class="text-end">Total</th><th>Status</th><th class="text-end">Paid Date</th></tr></thead>
                <tbody>
                    @foreach($payments as $rp)
                    @php $isOverdue = $rp->status === 'pending' && $rp->due_date->isPast(); @endphp
                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                        <td class="fw-semibold">{{ date('M Y', mktime(0, 0, 0, $rp->month, 1, $rp->year)) }}</td>
                        <td class="text-end">{{ number_format($rp->amount, 0) }}</td>
                        <td class="text-end {{ $rp->late_fee > 0 ? 'text-danger fw-bold' : '' }}">{{ number_format($rp->late_fee, 0) }}</td>
                        <td class="text-end fw-bold">{{ number_format($rp->total_due, 0) }}</td>
                        <td>
                            @if($rp->status === 'paid')<span class="badge bg-success">Paid</span>
                            @elseif($isOverdue)<span class="badge bg-danger">Overdue</span>
                            @elseif($rp->status === 'waived')<span class="badge bg-secondary">Waived</span>
                            @else<span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td class="text-end text-secondary">{{ $rp->paid_date ? $rp->paid_date->format('d M Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center text-secondary py-4">No payment records yet.</div>
        @endif
    </div>
</div>
@endsection

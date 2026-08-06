@extends('portal.layouts.app')

@section('title', 'Tenant Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="ti ti-dashboard me-1"></i> Tenant Dashboard</h4>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card portal-card text-center p-3">
            <div class="small text-secondary">Active Agreements</div>
            <div class="fs-4 fw-bold">{{ $activeCount }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card portal-card text-center p-3">
            <div class="small text-secondary">Total Paid</div>
            <div class="fs-4 fw-bold text-success">Rs. {{ number_format($totalPaid, 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card portal-card text-center p-3">
            <div class="small text-secondary">Total Pending</div>
            <div class="fs-4 fw-bold text-warning">Rs. {{ number_format($totalPending, 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card portal-card text-center p-3">
            <div class="small text-secondary">Total Agreements</div>
            <div class="fs-4 fw-bold">{{ $agreements->count() }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card portal-card">
            <div class="card-header"><h6 class="mb-0"><i class="ti ti-file-text me-1"></i> My Agreements</h6></div>
            <div class="card-body p-0">
                @if($agreements->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Property</th><th>Rent</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                        <tbody>
                            @foreach($agreements->take(5) as $agreement)
                            <tr>
                                <td>{{ $agreement->property?->title ?? 'N/A' }}</td>
                                <td>Rs. {{ number_format($agreement->rent_amount, 0) }}</td>
                                <td><span class="badge {{ $agreement->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($agreement->status) }}</span></td>
                                <td class="text-end"><a href="{{ route('portal.rent.agreement', $agreement) }}" class="btn btn-sm btn-outline-dark"><i class="ti ti-eye"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-secondary py-4">No agreements found.</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card portal-card">
            <div class="card-header"><h6 class="mb-0"><i class="ti ti-clock me-1"></i> Recent Payments</h6></div>
            <div class="card-body p-0">
                @if($recentPayments->count())
                <ul class="list-group list-group-flush">
                    @foreach($recentPayments as $rp)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">{{ $rp->month_name }}</div>
                            <div class="text-secondary small">{{ $rp->rentAgreement?->property?->title ?? 'N/A' }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">Rs. {{ number_format($rp->amount, 0) }}</div>
                            <div class="small text-secondary">{{ $rp->paid_date?->format('d M Y') ?? '-' }}</div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center text-secondary py-4">No payments yet.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

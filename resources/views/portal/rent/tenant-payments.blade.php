@extends('portal.layouts.app')

@section('title', 'My Payments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="ti ti-cash me-1"></i> My Payments</h4>
</div>

<div class="card portal-card">
    <div class="card-body p-0">
        @if($payments->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Property</th><th>Month</th><th class="text-end">Amount</th><th class="text-end">Late Fee</th><th class="text-end">Total</th><th>Status</th><th class="text-end">Paid Date</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @foreach($payments as $rp)
                    @php $isOverdue = $rp->status === 'pending' && $rp->due_date->isPast(); @endphp
                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                        <td>{{ $rp->rentAgreement?->property?->title ?? 'N/A' }}</td>
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
                        <td class="text-end">
                            @if($rp->status === 'paid')
                            <a href="{{ route('portal.rent.receipt', $rp) }}" class="btn btn-sm btn-outline-dark" target="_blank"><i class="ti ti-receipt"></i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center text-secondary py-5">No payment records found.</div>
        @endif
    </div>
</div>
@endsection

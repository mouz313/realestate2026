@extends('layouts.admin')

@section('title', 'Rent Payments')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="mb-0"><i class="ti ti-cash me-1"></i> Rent Overview <span class="urdu">(کرایہ کا جائزہ)</span></h4>
    <a href="{{ route('rent-payments.export-excel') }}" class="btn btn-outline-success">
        <i class="ti ti-file-spreadsheet"></i> Export Excel <span class="urdu">(اکسل برآمد)</span>
    </a>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3 h-100">
            <div class="small text-secondary">Pending Collection</div>
            <div class="fs-4 fw-bold text-warning">Rs. {{ number_format($totalPending, 0) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3 h-100">
            <div class="small text-secondary">Overdue</div>
            <div class="fs-4 fw-bold text-danger">Rs. {{ number_format($totalOverdue, 0) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3 h-100">
            <div class="small text-secondary">Collected This Month</div>
            <div class="fs-4 fw-bold text-success">Rs. {{ number_format($collectedThisMonth, 0) }}</div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-10">
                <label class="form-label small">Search Property or Tenant</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Property title or tenant name...">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-dark w-100"><i class="ti ti-search"></i></button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('rent-payments.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Tenant</th>
                        <th class="d-none d-md-table-cell">Owner</th>
                        <th class="text-center">Current Month</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Pending</th>
                        <th class="text-end d-none d-sm-table-cell">Late Fees</th>
                        <th class="text-center">Months</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agreements as $agreement)
                    @php
                        $currentPayment = $agreement->current_payment;
                        $currentStatus = $currentPayment ? $currentPayment->status : 'no_record';
                        $isOverdue = $currentPayment && $currentPayment->status === 'pending' && $currentPayment->due_date->isPast();
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('rent-payments.show', $agreement) }}" class="text-decoration-none fw-semibold">
                                {{ $agreement->property?->title ?? 'N/A' }}
                            </a>
                        </td>
                        <td>{{ $agreement->tenant?->name ?? '-' }}</td>
                        <td class="d-none d-md-table-cell">{{ $agreement->owner?->name ?? '-' }}</td>
                        <td class="text-center">
                            @if($isOverdue)
                                <span class="badge bg-danger">Overdue</span>
                                <div class="small text-muted mt-1">Rs. {{ number_format($currentPayment->total_due, 0) }}</div>
                            @elseif($currentStatus === 'paid')
                                <span class="badge bg-success">Paid</span>
                                <div class="small text-muted mt-1">Rs. {{ number_format($currentPayment->amount, 0) }}</div>
                            @elseif($currentStatus === 'waived')
                                <span class="badge bg-secondary">Waived</span>
                            @elseif($currentStatus === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                                <div class="small text-muted mt-1">Rs. {{ number_format($currentPayment->total_due, 0) }}</div>
                            @else
                                <span class="badge bg-info text-dark">No Record</span>
                            @endif
                        </td>
                        <td class="text-end text-success fw-semibold">{{ number_format($agreement->total_paid, 0) }}</td>
                        <td class="text-end text-warning fw-semibold">{{ number_format($agreement->total_pending, 0) }}</td>
                        <td class="text-end text-danger d-none d-sm-table-cell">{{ number_format($agreement->total_late_fees, 0) }}</td>
                        <td class="text-center">{{ $agreement->months_paid }}/{{ $agreement->total_months }}</td>
                        <td class="text-end">
                            <a href="{{ route('rent-payments.show', $agreement) }}" class="btn btn-sm btn-outline-dark" title="View Payments">
                                <i class="ti ti-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-secondary py-4">
                            <i class="ti ti-cash" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            No active rent agreements found.
                            <div class="mt-2">
                                <a href="{{ route('rent-agreements.create') }}" class="btn btn-dark btn-sm">
                                    <i class="ti ti-plus"></i> Create Agreement
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

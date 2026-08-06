@extends('portal.layouts.app')

@section('title', 'Agreement Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0"><i class="ti ti-file-text me-1"></i> Agreement #{{ $rentAgreement->id }}</h4>
    <a href="{{ route('portal.rent.agreements') }}" class="btn btn-outline-dark btn-sm"><i class="ti ti-arrow-left"></i> Back</a>
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
            <div class="small text-secondary">Total Paid</div>
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
            <div class="card-header"><h6 class="mb-0"><i class="ti ti-building me-1"></i> Property Details</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Property</span><span class="fw-semibold">{{ $rentAgreement->property?->title ?? 'N/A' }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">City</span><span>{{ $rentAgreement->property?->city ?? '-' }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Type</span><span>{{ ucfirst($rentAgreement->property?->type ?? '-') }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Period</span><span>{{ $rentAgreement->start_date?->format('d M Y') ?? '-' }} — {{ $rentAgreement->end_date?->format('d M Y') ?? 'Open' }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Late Fee/Day</span><span>Rs. {{ number_format($rentAgreement->late_fee_per_day ?? 0, 0) }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-secondary">Deposit</span><span>Rs. {{ number_format($rentAgreement->security_deposit ?? 0, 0) }}</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card portal-card">
            <div class="card-header"><h6 class="mb-0"><i class="ti ti-user me-1"></i> Owner</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Name</span><span class="fw-semibold">{{ $rentAgreement->owner?->name ?? '-' }}</span></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-secondary">Phone</span><span>{{ $rentAgreement->owner?->phone ?? '-' }}</span></div>
                <div class="d-flex justify-content-between"><span class="text-secondary">Email</span><span>{{ $rentAgreement->owner?->email ?? '-' }}</span></div>
            </div>
        </div>
    </div>
</div>

<div class="card portal-card mt-3">
    <div class="card-header"><h6 class="mb-0"><i class="ti ti-calendar-stats me-1"></i> Payment Schedule</h6></div>
    <div class="card-body p-0">
        @if($payments->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Month</th><th>Due Date</th><th class="text-end">Rent</th><th class="text-end">Late Fee</th><th class="text-end">Total</th><th>Status</th><th class="text-end">Paid Date</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @foreach($payments as $rp)
                    @php $isOverdue = $rp->status === 'pending' && $rp->due_date->isPast(); @endphp
                    <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                        <td class="fw-semibold">{{ date('M Y', mktime(0, 0, 0, $rp->month, 1, $rp->year)) }}</td>
                        <td>{{ $rp->due_date->format('d M Y') }}</td>
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
        <div class="text-center text-secondary py-4">No payment records yet.</div>
        @endif
    </div>
</div>

@if($rentAgreement->terms)
<div class="card portal-card mt-3">
    <div class="card-header"><h6 class="mb-0"><i class="ti ti-file-text me-1"></i> Terms & Conditions</h6></div>
    <div class="card-body">{!! nl2br(e($rentAgreement->terms)) !!}</div>
</div>
@endif

@if($rentAgreement->status === 'active')
@php
    $noticePeriod = $rentAgreement->notice_period_days ?? 30;
    $minMoveOut = \Carbon\Carbon::today()->addDays($noticePeriod)->toDateString();
    $hasPendingNotice = $rentAgreement->rentNotices()->where('status', 'pending')->exists();
@endphp
<div class="card portal-card mt-3">
    <div class="card-header"><h6 class="mb-0"><i class="ti ti-alert-triangle me-1"></i> Give Notice</h6></div>
    <div class="card-body">
        @if($hasPendingNotice)
            <div class="alert alert-info mb-0">
                <i class="ti ti-info-circle me-1"></i>
                You already have a pending notice for this agreement. Please wait for it to be reviewed.
            </div>
        @else
            <p class="text-secondary mb-3">
                Submit your notice to vacate. You must give at least <strong>{{ $noticePeriod }} days</strong> notice.
                The earliest move-out date is <strong>{{ \Carbon\Carbon::parse($minMoveOut)->format('d M Y') }}</strong>.
            </p>
            <form action="{{ route('portal.rent.notice', $rentAgreement) }}" method="POST" id="noticeForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Move-Out Date <span class="text-danger">*</span></label>
                        <input type="date" name="move_out_date" id="move_out_date" class="form-control"
                               min="{{ $minMoveOut }}" required>
                        @error('move_out_date')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Reason (Optional)</label>
                        <textarea name="reason" class="form-control" rows="3" maxlength="1000"
                                  placeholder="Let us know why you're moving out..."></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-warning fw-semibold"
                                onclick="return confirm('Are you sure you want to submit your notice to vacate?')">
                            <i class="ti ti-send me-1"></i> Submit Notice
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>
@endif

@if($rentAgreement->rentNotices()->count())
<div class="card portal-card mt-3">
    <div class="card-header"><h6 class="mb-0"><i class="ti ti-history me-1"></i> Notice History</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Submitted</th><th>Move-Out Date</th><th>Reason</th><th>Status</th><th>Admin Notes</th></tr></thead>
                <tbody>
                    @foreach($rentAgreement->rentNotices()->latest()->get() as $notice)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($notice->notice_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($notice->move_out_date)->format('d M Y') }}</td>
                        <td>{{ $notice->reason ?: '-' }}</td>
                        <td>
                            @if($notice->status === 'pending')<span class="badge bg-warning text-dark">Pending</span>
                            @elseif($notice->status === 'accepted')<span class="badge bg-success">Accepted</span>
                            @else<span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>{{ $notice->admin_notes ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

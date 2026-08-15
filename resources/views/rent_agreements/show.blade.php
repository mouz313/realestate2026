@extends('layouts.admin')

@section('title', 'Rent Agreement Details <span class="urdu">(کرایہ نامہ کی تفصیلات)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('rent-agreements.index') }}" class="text-decoration-none">Rent Agreements <span class="urdu">(کرایہ نامہ)</span></a></li>
        <li class="breadcrumb-item active">Agreement #{{ $rentAgreement->id }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <h3>Rent Agreement <span class="urdu">(کرایہ نامہ)</span> #{{ $rentAgreement->id }}</h3>
    <div class="page-header-sub">
        <span class="badge status-{{ $rentAgreement->status ?? 'pending' }} fs-6">{{ ucfirst($rentAgreement->status ?? 'pending') }}</span>
    </div>
    <div class="action-btns">
        <a href="{{ route('pdf.rent-agreement', $rentAgreement) }}" class="btn btn-dark me-2">
            <i class="ti ti-file-download"></i> PDF <span class="urdu">(پی ڈی ایف)</span>
        </a>
        @if($rentAgreement->status === 'active' || $rentAgreement->status === 'expired')
        <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#renewModal">
            <i class="ti ti-refresh"></i> Renew Agreement
        </button>
        @endif
        @if(in_array($rentAgreement->status, ['active', 'expired']))
        <button class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#moveOutModal">
            <i class="ti ti-door-exit"></i> End Tenancy <span class="urdu">(کرایہ ختم)</span>
        </button>
        @endif
        <a href="{{ route('rent-agreements.edit', $rentAgreement) }}" class="btn btn-dark">
            <i class="ti ti-edit"></i> Edit Agreement <span class="urdu">(کرایہ نامہ میں ترمیم)</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header flex-wrap gap-2">
                <h5><i class="ti ti-file-description me-1"></i> Agreement Details <span class="urdu">(کرایہ نامہ کی تفصیلات)</span></h5>
            </div>
            <div class="card-body">
                <table class="detail-table">
                    <tr>
                        <th>Agreement ID <span class="urdu">(کرایہ نامہ کی شناخت)</span></th>
                        <td>{{ $rentAgreement->id }}</td>
                    </tr>
                    <tr>
                        <th>Status <span class="urdu">(کیفیت)</span></th>
                        <td>
                            <span class="badge status-{{ $rentAgreement->status ?? 'pending' }}">{{ ucfirst($rentAgreement->status ?? 'pending') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Start Date <span class="urdu">(شروع کی تاریخ)</span></th>
                        <td>{{ $rentAgreement->start_date ? $rentAgreement->start_date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>End Date <span class="urdu">(ختم کی تاریخ)</span></th>
                        <td>{{ $rentAgreement->end_date ? $rentAgreement->end_date->format('d M Y') : 'Open' }}</td>
                    </tr>
                    <tr>
                        <th>Rent Amount <span class="urdu">(کرایہ کی رقم)</span></th>
                        <td class="fw-semibold">{{ number_format($rentAgreement->rent_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Security Deposit <span class="urdu">(سیکیورٹی ڈپازٹ)</span></th>
                        <td>{{ $rentAgreement->security_deposit ? number_format($rentAgreement->security_deposit, 2) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Notice Period <span class="urdu">(نوٹس کی مدت)</span></th>
                        <td>{{ $rentAgreement->notice_period_days ? $rentAgreement->notice_period_days . ' days' : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Late Fee / Day <span class="urdu">(تاخیری فیس / یوم)</span></th>
                        <td>{{ $rentAgreement->late_fee_per_day ? number_format($rentAgreement->late_fee_per_day, 2) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Rent Increase <span class="urdu">(کرایہ میں اضافہ)</span></th>
                        <td>{{ $rentAgreement->rent_increase_percent ? $rentAgreement->rent_increase_percent . '%' : '-' }}
                            {{ $rentAgreement->rent_increase_frequency ? '(' . ucfirst($rentAgreement->rent_increase_frequency) . ')' : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Notes <span class="urdu">(نوٹس)</span></th>
                        <td>{{ $rentAgreement->notes ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Payment Frequency <span class="urdu">(ادائیگی کی تعدد)</span></th>
                        <td>{{ ucfirst($rentAgreement->payment_frequency ?? 'monthly') }}</td>
                    </tr>
                    <tr>
                        <th>Terms & Conditions <span class="urdu">(شرائط و ضوابط)</span></th>
                        <td>{!! nl2br(e($rentAgreement->terms ?? 'Default: Rent payable by 10th each month. Security deposit refundable at end of tenancy (less damages). Tenant shall not sublet without written consent. Utility bills payable by Tenant. 30 days notice required from either party.')) !!}</td>
                    </tr>
                    <tr>
                        <th>Agreement Doc <span class="urdu">(دستاویز)</span></th>
                        <td>
                            @if($rentAgreement->agreement_doc)
                                <a href="{{ Storage::url($rentAgreement->agreement_doc) }}" target="_blank" class="text-decoration-none"><i class="ti ti-file-download"></i> View Document</a>
                            @else
                                <span class="text-secondary">No document uploaded</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header flex-wrap gap-2">
                <h5><i class="ti ti-building me-1"></i> Property <span class="urdu">(جائیداد)</span></h5>
            </div>
            <div class="card-body">
                @if($rentAgreement->property)
                <table class="detail-table">
                    <tr>
                        <th>Title <span class="urdu">(عنوان)</span></th>
                        <td><a href="{{ route('properties.show', $rentAgreement->property) }}" class="text-decoration-none">{{ $rentAgreement->property->title }}</a></td>
                    </tr>
                    <tr>
                        <th>Code <span class="urdu">(کوڈ)</span></th>
                        <td>{{ $rentAgreement->property->property_code ?? $rentAgreement->property->id }}</td>
                    </tr>
                    <tr>
                        <th>Type <span class="urdu">(قسم)</span></th>
                        <td>{{ ucfirst($rentAgreement->property->type ?? '-') }}</td>
                    </tr>
                    <tr>
                        <th>City <span class="urdu">(شہر)</span></th>
                        <td>{{ $rentAgreement->property->city ?? '-' }}</td>
                    </tr>
                </table>
                @else
                <div class="empty-state">
                    <i class="ti ti-building"></i>
                    <span>No property linked. <span class="urdu">(کوئی جائیداد منسلک نہیں)</span></span>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header flex-wrap gap-2">
                <h5><i class="ti ti-users me-1"></i> Parties <span class="urdu">(فریقین)</span></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <strong>Tenant <span class="urdu">(کرایہ دار)</span></strong>
                        <p class="mb-0">
                            @if($rentAgreement->tenant)
                                <a href="{{ route('clients.show', $rentAgreement->tenant) }}" class="text-decoration-none">{{ $rentAgreement->tenant->name }}</a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-6">
                        <strong>Owner <span class="urdu">(مالک)</span></strong>
                        <p class="mb-0">
                            @if($rentAgreement->owner)
                                <a href="{{ route('clients.show', $rentAgreement->owner) }}" class="text-decoration-none">{{ $rentAgreement->owner->name }}</a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Payment Schedule --}}
<div class="row g-4 mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="ti ti-calendar-stats me-1"></i> Payment Schedule <span class="urdu">(ادائیگی کا شیڈول)</span></h5>
                <form action="{{ route('rent-agreements.regenerate-schedule', $rentAgreement) }}" method="POST" onsubmit="return confirm('Regenerate schedule? Existing paid records will be kept.')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-dark"><i class="ti ti-refresh"></i> Regenerate</button>
                </form>
            </div>
            <div class="card-body p-0">
                @php
                    $rentAgreement->load('rentPayments');
                    $totalPaid = $rentAgreement->rentPayments->where('status', 'paid')->sum('amount');
                    $totalPending = $rentAgreement->rentPayments->whereIn('status', ['pending', 'overdue'])->sum('total_due');
                    $totalLateFee = $rentAgreement->rentPayments->sum('late_fee');
                @endphp

                @if($rentAgreement->rentPayments->count())
                <div class="row g-0 text-center mb-3 px-3 pt-3">
                    <div class="col-md-3">
                        <div class="fs-5 fw-bold text-success">Rs. {{ number_format($totalPaid, 0) }}</div>
                        <div class="text-secondary small">Total Paid</div>
                    </div>
                    <div class="col-md-3">
                        <div class="fs-5 fw-bold text-warning">Rs. {{ number_format($totalPending, 0) }}</div>
                        <div class="text-secondary small">Total Pending</div>
                    </div>
                    <div class="col-md-3">
                        <div class="fs-5 fw-bold text-danger">Rs. {{ number_format($totalLateFee, 0) }}</div>
                        <div class="text-secondary small">Total Late Fees</div>
                    </div>
                    <div class="col-md-3">
                        <div class="fs-5 fw-bold">{{ $rentAgreement->rentPayments->where('status', 'paid')->count() }} / {{ $rentAgreement->rentPayments->count() }}</div>
                        <div class="text-secondary small">Months Paid</div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Due Date</th>
                                <th class="text-end">Rent</th>
                                <th class="text-end">Late Fee</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                                <th class="text-end">Paid Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentAgreement->rentPayments->sortBy('year')->sortBy('month') as $rp)
                            @php
                                $isOverdue = $rp->status === 'pending' && $rp->due_date->isPast();
                            @endphp
                            <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                                <td class="fw-semibold">{{ date('M Y', mktime(0, 0, 0, $rp->month, 1, $rp->year)) }}</td>
                                <td>{{ $rp->due_date->format('d M Y') }}</td>
                                <td class="text-end">{{ number_format($rp->amount, 0) }}</td>
                                <td class="text-end {{ $rp->late_fee > 0 ? 'text-danger fw-bold' : '' }}">{{ number_format($rp->late_fee, 0) }}</td>
                                <td class="text-end fw-bold">{{ number_format($rp->total_due, 0) }}</td>
                                <td>
                                    @if($rp->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($isOverdue)
                                        <span class="badge bg-danger">Overdue</span>
                                    @elseif($rp->status === 'waived')
                                        <span class="badge bg-secondary">Waived</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end text-secondary">{{ $rp->paid_date ? $rp->paid_date->format('d M Y') : '-' }}</td>
                                <td class="text-end">
                                    @if($rp->status === 'paid')
                                    <a href="{{ route('rent-payments.receipt', $rp) }}" class="btn btn-sm btn-outline-dark" title="Receipt" target="_blank">
                                        <i class="ti ti-receipt"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-secondary py-4">
                    <i class="ti ti-calendar-stats" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                    No payment schedule. Click "Regenerate" to create one.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($rentAgreement->status === 'renewed' && $rentAgreement->renewals->count())
<div class="row g-4 mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-refresh me-1"></i> Renewal History</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Agreement #</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th class="text-end">Rent</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentAgreement->renewals as $renewal)
                            <tr>
                                <td class="fw-semibold">#{{ $renewal->id }}</td>
                                <td>{{ $renewal->start_date?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $renewal->end_date?->format('d M Y') ?? 'Open' }}</td>
                                <td class="text-end">{{ number_format($renewal->rent_amount, 0) }}</td>
                                <td><span class="badge status-{{ $renewal->status }}">{{ ucfirst($renewal->status) }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('rent-agreements.show', $renewal) }}" class="btn btn-sm btn-outline-dark"><i class="ti ti-eye"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($rentAgreement->renewedFrom)
<div class="alert alert-info mt-3">
    <i class="ti ti-refresh me-1"></i>
    This is a renewal of <a href="{{ route('rent-agreements.show', $rentAgreement->renewedFrom) }}">Agreement #{{ $rentAgreement->renewedFrom->id }}</a>.
</div>
@endif

<div class="row g-4 mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="ti ti-wallet me-1"></i> Security Deposit Settlement <span class="urdu">(سیکیورٹی ڈپازٹ کا تصفیہ)</span></h5>
                <div class="action-btns flex-nowrap">
                    @if(($rentAgreement->security_deposit ?? 0) > 0 && $rentAgreement->deposit_remaining > 0 && !$rentAgreement->deposit_returned)
                    <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#receiveDepositModal">
                        <i class="ti ti-arrow-down"></i> Receive Deposit
                    </button>
                    @endif
                    @if($rentAgreement->status === 'terminated' && $rentAgreement->net_deposit_return > 0 && !$rentAgreement->deposit_returned)
                    <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#returnDepositModal">
                        <i class="ti ti-arrow-up"></i> Return Deposit to Tenant
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table class="detail-table">
                    <tr><th>Security Deposit</th><td class="fw-bold">Rs. {{ number_format($rentAgreement->security_deposit ?? 0, 2) }}</td></tr>
                    <tr><th>Received</th><td class="fw-bold text-success">Rs. {{ number_format($rentAgreement->deposit_received_amount ?? 0, 2) }}</td></tr>
                    @if($rentAgreement->deposit_received_date)
                    <tr><th>Received Date</th><td>{{ $rentAgreement->deposit_received_date->format('d M Y') }}</td></tr>
                    @endif
                    @if(($rentAgreement->security_deposit ?? 0) > $rentAgreement->deposit_received_amount && !$rentAgreement->deposit_returned)
                    <tr><th>Remaining</th><td class="text-warning fw-semibold">Rs. {{ number_format($rentAgreement->deposit_remaining, 2) }}</td></tr>
                    @endif
                    <tr><th>Deposit Received</th><td>
                        @if($rentAgreement->deposit_received)<span class="badge bg-success">Yes</span>
                        @elseif(($rentAgreement->deposit_received_amount ?? 0) > 0)<span class="badge bg-warning text-dark">Partial</span>
                        @else<span class="badge bg-secondary">Not Received</span>@endif
                    </td></tr>
                    @if($rentAgreement->deposit_deductions > 0)
                    <tr><th>Deductions</th><td class="text-danger fw-bold">- Rs. {{ number_format($rentAgreement->deposit_deductions, 2) }}</td></tr>
                    @endif
                    <tr><th>Net Return</th><td class="fw-bold {{ $rentAgreement->net_deposit_return > 0 ? 'text-success' : 'text-muted' }}">Rs. {{ number_format($rentAgreement->net_deposit_return, 2) }}</td></tr>
                    <tr><th>Deposit Returned</th><td>@if($rentAgreement->deposit_returned)<span class="badge bg-success">Yes &mdash; {{ $rentAgreement->deposit_returned_date ? $rentAgreement->deposit_returned_date->format('d M Y') : '-' }}</span>@else<span class="badge bg-secondary">Not Yet</span>@endif</td></tr>
                </table>

                @if($rentAgreement->status === 'terminated')
                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0"><i class="ti ti-list-details me-1"></i> Deductions <span class="urdu">(خرچ/نقصان)</span></h6>
                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addDeductionModal">
                        <i class="ti ti-plus"></i> Add Deduction
                    </button>
                </div>
                @if($rentAgreement->depositDeductions->count())
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Category <span class="urdu">(قسم)</span></th>
                                <th>Title <span class="urdu">(عنوان)</span></th>
                                <th class="text-end">Amount <span class="urdu">(رقم)</span></th>
                                <th>Notes <span class="urdu">(نوٹس)</span></th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentAgreement->depositDeductions as $deduction)
                            <tr>
                                <td><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $deduction->category)) }}</span></td>
                                <td>{{ $deduction->title }}</td>
                                <td class="text-end text-danger fw-semibold">- Rs. {{ number_format($deduction->amount, 2) }}</td>
                                <td class="text-secondary small">{{ $deduction->notes ?? '-' }}</td>
                                <td class="text-end">
                                    <form action="{{ route('rent-agreements.deductions.destroy', [$rentAgreement, $deduction]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this deduction?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove"><i class="ti ti-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-secondary small mb-0">No deductions recorded. <span class="urdu">(کوئی خرچ درج نہیں)</span></p>
                @endif
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="ti ti-bell me-1"></i> Notice History</h5>
                @if($rentAgreement->status === 'active' && !$rentAgreement->rentNotices->contains('status', 'pending'))
                <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addNoticeModal">
                    <i class="ti ti-plus"></i> Add Notice
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                @if($rentAgreement->rentNotices->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>Date</th><th>Move Out</th><th>Type</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                        <tbody>
                            @foreach($rentAgreement->rentNotices as $notice)
                            <tr>
                                <td>{{ $notice->notice_date ? $notice->notice_date->format('d M Y') : '-' }}</td>
                                <td class="fw-semibold">{{ $notice->move_out_date ? $notice->move_out_date->format('d M Y') : '-' }}</td>
                                <td><span class="badge bg-light text-dark">{{ ucfirst($notice->notice_type) }}</span></td>
                                <td>
                                    @if($notice->status === 'pending')<span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($notice->status === 'accepted')<span class="badge bg-success">Accepted</span>
                                    @elseif($notice->status === 'rejected')<span class="badge bg-danger">Rejected</span>
                                    @else<span class="badge bg-secondary">{{ ucfirst($notice->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($notice->status === 'pending')
                                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#respondNotice{{ $notice->id }}"><i class="ti ti-check"></i></button>
                                    @endif
                                </td>
                            </tr>
                            @if($notice->status === 'pending')
                            <div class="modal fade" id="respondNotice{{ $notice->id }}" tabindex="-1">
                                <div class="modal-dialog"><div class="modal-content">
                                    <form action="{{ route('rent-agreements.notices.respond', [$rentAgreement, $notice]) }}" method="POST">
                                        @csrf
                                        <div class="modal-header"><h5 class="modal-title">Respond to Notice</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">
                                            <p>Move-out date: <strong>{{ $notice->move_out_date ? $notice->move_out_date->format('d M Y') : '-' }}</strong></p>
                                            @if($notice->reason)<p>Reason: {{ $notice->reason }}</p>@endif
                                            <div class="mb-3"><label class="form-label">Admin Notes</label><textarea name="admin_notes" class="form-control" rows="2" placeholder="Optional"></textarea></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="status" value="rejected" class="btn btn-danger"><i class="ti ti-x"></i> Reject</button>
                                            <button type="submit" name="status" value="accepted" class="btn btn-success"><i class="ti ti-check"></i> Accept</button>
                                        </div>
                                    </form>
                                </div></div>
                            </div>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-secondary py-4"><i class="ti ti-bell-off" style="font-size:2rem;display:block;margin-bottom:8px;"></i>No notices recorded.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="renewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('rent-agreements.renew', $rentAgreement) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Renew Agreement #{{ $rentAgreement->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>The current agreement will be marked as <strong>Renewed</strong> and a new agreement will be created.</p>
                    <div class="mb-3">
                        <label class="form-label">New Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="new_start_date" class="form-control" required min="{{ date('Y-m-d', strtotime('tomorrow')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New End Date <span class="text-danger">*</span></label>
                        <input type="date" name="new_end_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Rent Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="new_rent_amount" class="form-control" value="{{ $rentAgreement->rent_amount }}" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="ti ti-refresh"></i> Renew</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(in_array($rentAgreement->status, ['active', 'expired']))
<div class="modal fade" id="moveOutModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('rent-agreements.move-out', $rentAgreement) }}" method="POST">
            @csrf
            <div class="modal-header"><h5 class="modal-title">End Tenancy <span class="urdu">(کرایہ ختم)</span></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p>The agreement will be marked <strong>Terminated</strong>, property possession recorded, and pending installments after the possession date will be waived.</p>
                <div class="mb-3">
                    <label class="form-label">Possession Returned Date <span class="text-danger">*</span></label>
                    <input type="date" name="possession_returned_date" class="form-control" value="{{ now()->toDateString() }}" max="{{ now()->toDateString() }}" min="{{ $rentAgreement->start_date ? $rentAgreement->start_date->toDateString() : '' }}" required>
                    <div class="form-text">Property wapis hamesha aaj ya past date par hi ho sakti hai.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger"><i class="ti ti-door-exit"></i> End Tenancy</button>
            </div>
        </form>
    </div></div>
</div>
@endif

@if($rentAgreement->status === 'terminated')
<div class="modal fade" id="addDeductionModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('rent-agreements.deductions.store', $rentAgreement) }}" method="POST">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Add Deduction <span class="urdu">(خرچ درج کریں)</span></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="damage">Damage <span class="urdu">(نقصان)</span></option>
                        <option value="unpaid_rent">Unpaid Rent <span class="urdu">(زیر التوا کرایہ)</span></option>
                        <option value="utilities">Utilities <span class="urdu">(یوٹیلیٹیز)</span></option>
                        <option value="other">Other <span class="urdu">(دیگر)</span></option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" maxlength="255" placeholder="e.g. Broken window, wall paint" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control" min="0.01" placeholder="0.00" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2" maxlength="1000"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-dark"><i class="ti ti-plus"></i> Add Deduction</button>
            </div>
        </form>
    </div></div>
</div>
@endif

@if($rentAgreement->status === 'terminated' && $rentAgreement->net_deposit_return > 0 && !$rentAgreement->deposit_returned)
<div class="modal fade" id="returnDepositModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('rent-agreements.return-deposit', $rentAgreement) }}" method="POST">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Return Security Deposit <span class="urdu">(ڈپازٹ واپسی)</span></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Received</span><span>Rs. {{ number_format($rentAgreement->deposit_received_amount ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Deductions</span><span class="text-danger">- Rs. {{ number_format($rentAgreement->deposit_deductions, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary fw-semibold">Net Return to Tenant</span><span class="fs-5 fw-bold text-success">Rs. {{ number_format($rentAgreement->net_deposit_return, 2) }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                    <select name="method" class="form-select" required>
                        <option value="">Select Method</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="JazzCash">JazzCash</option>
                        <option value="Easypaisa">Easypaisa</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Reference / Txn ID</label>
                    <input type="text" name="reference" class="form-control" maxlength="255" placeholder="Optional">
                </div>
                <div class="mb-3">
                    <label class="form-label">Return Date <span class="text-danger">*</span></label>
                    <input type="date" name="paid_date" class="form-control" value="{{ now()->toDateString() }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-dark"><i class="ti ti-arrow-up"></i> Return Rs. {{ number_format($rentAgreement->net_deposit_return, 0) }}</button>
            </div>
        </form>
    </div></div>
</div>
@endif

@if(($rentAgreement->security_deposit ?? 0) > 0 && $rentAgreement->deposit_remaining > 0 && !$rentAgreement->deposit_returned)
<div class="modal fade" id="receiveDepositModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('rent-agreements.deposit-receive', $rentAgreement) }}" method="POST">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Receive Security Deposit</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Total Deposit</span><span class="fw-semibold">Rs. {{ number_format($rentAgreement->security_deposit, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Already Received</span><span>Rs. {{ number_format($rentAgreement->deposit_received_amount ?? 0, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Remaining</span><span class="fw-semibold text-warning">Rs. {{ number_format($rentAgreement->deposit_remaining, 2) }}</span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" id="depositReceiveAmount" class="form-control" value="{{ $rentAgreement->deposit_remaining }}" min="0.01" max="{{ $rentAgreement->deposit_remaining }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                    <select name="method" class="form-select" required>
                        <option value="">Select Method</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="JazzCash">JazzCash</option>
                        <option value="Easypaisa">Easypaisa</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Reference / Txn ID</label>
                    <input type="text" name="reference" class="form-control" maxlength="255" placeholder="Optional">
                </div>
                <div class="mb-3">
                    <label class="form-label">Receipt Date <span class="text-danger">*</span></label>
                    <input type="date" name="paid_date" class="form-control" value="{{ now()->toDateString() }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-dark"><i class="ti ti-arrow-down"></i> Receive Deposit</button>
            </div>
        </form>
    </div></div>
</div>
@endif

@if($rentAgreement->status === 'active' && !$rentAgreement->rentNotices->contains('status', 'pending'))
<div class="modal fade" id="addNoticeModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form action="{{ route('portal.rent.notice', $rentAgreement) }}" method="POST">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Submit Notice</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p>Notice period: <strong>{{ $rentAgreement->notice_period_days ?? 30 }} days</strong>. Minimum move-out date: <strong>{{ \Carbon\Carbon::today()->addDays($rentAgreement->notice_period_days ?? 30)->format('d M Y') }}</strong></p>
                <div class="mb-3"><label class="form-label">Move Out Date <span class="text-danger">*</span></label><input type="date" name="move_out_date" class="form-control" required min="{{ \Carbon\Carbon::today()->addDays($rentAgreement->notice_period_days ?? 30)->format('Y-m-d') }}"></div>
                <div class="mb-3"><label class="form-label">Reason</label><textarea name="reason" class="form-control" rows="2" placeholder="Optional"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-dark"><i class="ti ti-send"></i> Submit Notice</button>
            </div>
        </form>
    </div></div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    var receiveAmount = document.getElementById('depositReceiveAmount');
    if (receiveAmount) {
        var maxReceive = parseFloat(receiveAmount.max) || 0;
        receiveAmount.addEventListener('input', function() {
            var val = parseFloat(this.value) || 0;
            if (val > maxReceive) {
                this.value = maxReceive;
            }
        });
    }
});
</script>
@endsection

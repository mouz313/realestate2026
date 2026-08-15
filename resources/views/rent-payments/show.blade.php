@extends('layouts.admin')

@section('title', 'Rent Payment History')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('rent-payments.index') }}" class="text-decoration-none">Rent Overview</a></li>
        <li class="breadcrumb-item active">{{ $rentAgreement->property?->title ?? 'Agreement #' . $rentAgreement->id }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0">
        <i class="ti ti-cash me-1"></i>
        {{ $rentAgreement->property?->title ?? 'Rent Agreement' }}
        <span class="urdu">(کرایہ کی ادائیگیاں)</span>
    </h4>
    <a href="{{ route('rent-payments.index') }}" class="btn btn-outline-dark btn-sm">
        <i class="ti ti-arrow-left"></i> Back to Overview
    </a>
</div>

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="small text-secondary">Total Paid</div>
            <div class="fs-5 fw-bold text-success">Rs. {{ number_format($totalPaid, 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="small text-secondary">Total Pending</div>
            <div class="fs-5 fw-bold text-warning">Rs. {{ number_format($totalPending, 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="small text-secondary">Late Fees</div>
            <div class="fs-5 fw-bold text-danger">Rs. {{ number_format($totalLateFee, 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="small text-secondary">Months Paid</div>
            <div class="fs-5 fw-bold">{{ $monthsPaid }} / {{ $totalMonths }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body small">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-secondary">Tenant</span>
                    <span class="fw-semibold">{{ $rentAgreement->tenant?->name ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-secondary">Owner</span>
                    <span>{{ $rentAgreement->owner?->name ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-secondary">Rent</span>
                    <span class="fw-bold">Rs. {{ number_format($rentAgreement->rent_amount, 0) }}/mo</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body small">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-secondary">Period</span>
                    <span>{{ $rentAgreement->start_date?->format('d M Y') ?? '-' }} — {{ $rentAgreement->end_date?->format('d M Y') ?? 'Open' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-secondary">Status</span>
                    <span class="badge status-{{ $rentAgreement->status }}">{{ ucfirst($rentAgreement->status) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-secondary">Late Fee/Day</span>
                    <span>Rs. {{ number_format($rentAgreement->late_fee_per_day ?? 0, 0) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body small">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-secondary">Security Deposit</span>
                    <span>Rs. {{ number_format($rentAgreement->security_deposit ?? 0, 0) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-secondary">Deposit Received</span>
                    <span>{{ $rentAgreement->deposit_received ? 'Yes' : 'No' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-secondary">Increase</span>
                    <span>{{ $rentAgreement->rent_increase_percent ? $rentAgreement->rent_increase_percent . '% (' . ucfirst($rentAgreement->rent_increase_frequency ?? 'none') . ')' : 'None' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0"><i class="ti ti-calendar-stats me-1"></i> Payment History <span class="urdu">(ادائیگیوں کی تاریخ)</span></h5>
        <form action="{{ route('rent-agreements.generate-next-month', $rentAgreement) }}" method="POST" onsubmit="return confirm('Generate the next month payment if not already created?')">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-dark"><i class="ti ti-plus"></i> Generate Next Month</button>
        </form>
    </div>
    <div class="card-body p-0">
        @if($payments->count())
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
                    @foreach($payments as $rp)
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
                            @if($rp->status !== 'paid' && $rp->status !== 'waived')
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#payModal{{ $rp->id }}" title="Mark Paid">
                                <i class="ti ti-check"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#waiveModal{{ $rp->id }}" title="Waive">
                                <i class="ti ti-player-skip-forward"></i>
                            </button>
                            @endif
                            @if($rp->status === 'paid')
                            <a href="{{ route('rent-payments.receipt', $rp) }}" class="btn btn-sm btn-outline-dark" title="Receipt" target="_blank">
                                <i class="ti ti-receipt"></i>
                            </a>
                            @endif
                            <a href="{{ route('rent-payments.edit', $rp) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('rent-payments.destroy', $rp) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this payment record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>

        @foreach($payments as $rp)
            @if($rp->status !== 'paid' && $rp->status !== 'waived')
            <div class="modal fade" id="payModal{{ $rp->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('rent-payments.pay', $rp) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="modal-header">
                                <h5 class="modal-title">Mark Paid — {{ date('F Y', mktime(0, 0, 0, $rp->month, 1, $rp->year)) }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Amount Due</label>
                                    <div class="fs-5 fw-bold">Rs. {{ number_format($rp->total_due, 2) }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-select" required>
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="raast">Raast</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="online">Online</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Paid Date <span class="text-danger">*</span></label>
                                    <input type="date" name="paid_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reference No</label>
                                    <input type="text" name="reference_no" class="form-control" placeholder="Optional">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Optional"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success"><i class="ti ti-check"></i> Confirm Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="waiveModal{{ $rp->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('rent-payments.waive', $rp) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="modal-header">
                                <h5 class="modal-title">Waive Payment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to waive <strong>Rs. {{ number_format($rp->total_due, 2) }}</strong> for {{ date('F Y', mktime(0, 0, 0, $rp->month, 1, $rp->year)) }}?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning"><i class="ti ti-player-skip-forward"></i> Waive</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @endforeach

        @else
        <div class="text-center text-secondary py-5">
            <i class="ti ti-calendar-stats" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
            No payment records yet. Click "Generate Next Month" to create the first payment.
        </div>
        @endif
    </div>
</div>
@endsection

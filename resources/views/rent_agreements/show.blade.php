@extends('layouts.admin')

@section('title', 'Rent Agreement Details <span class="urdu">(کرایہ نامہ کی تفصیلات)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
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

        <div class="card mt-4">
            <div class="card-header flex-wrap gap-2">
                <h5><i class="ti ti-file-text me-1"></i> Document <span class="urdu">(دستاویز)</span></h5>
            </div>
            <div class="card-body text-center">
                <div class="empty-state">
                    <i class="ti ti-file-download" style="font-size: 2rem;"></i>
                    <span>Document download placeholder <span class="urdu">(دستاویز ڈاؤن لوڈ کی جگہ)</span></span>
                    <small>Upload and link agreement documents here. <span class="urdu">(یہاں کرایہ نامے کی دستاویزات اپ لوڈ کریں)</span></small>
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
@endsection

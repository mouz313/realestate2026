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
@endsection

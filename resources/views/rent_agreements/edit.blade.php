@extends('layouts.admin')

@section('title', 'Edit Rent Agreement <span class="urdu">(کرایہ نامہ میں ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('rent-agreements.index') }}" class="text-decoration-none">Rent Agreements <span class="urdu">(کرایہ نامہ)</span></a></li>
        <li class="breadcrumb-item active">Edit Rent Agreement <span class="urdu">(کرایہ نامہ میں ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header flex-wrap gap-2">
        <h4><i class="ti ti-file-text"></i> Edit Rent Agreement <span class="urdu">(کرایہ نامہ میں ترمیم)</span></h4>
    </div>
    <form action="{{ route('rent-agreements.update', $rentAgreement) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Property <span class="urdu">(جائیداد)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('property_id') is-invalid @enderror" name="property_id" required>
                            <option value="">Select Property <span class="urdu">(جائیداد منتخب)</span></option>
                            @foreach($properties ?? [] as $property)
                                <option value="{{ $property->id }}" {{ old('property_id', $rentAgreement->property_id) == $property->id ? 'selected' : '' }}>{{ $property->title }} ({{ $property->property_code ?? $property->id }})</option>
                            @endforeach
                        </select>
                        @error('property_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tenant <span class="urdu">(کرایہ دار)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('tenant_id') is-invalid @enderror" name="tenant_id" required>
                            <option value="">Select Tenant <span class="urdu">(کرایہ دار منتخب)</span></option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" {{ old('tenant_id', $rentAgreement->tenant_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('tenant_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Owner <span class="urdu">(مالک)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('owner_id') is-invalid @enderror" name="owner_id" required>
                            <option value="">Select Owner <span class="urdu">(مالک منتخب)</span></option>
                            @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" {{ old('owner_id', $rentAgreement->owner_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('owner_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date <span class="urdu">(شروع کی تاریخ)</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date', $rentAgreement->start_date ? $rentAgreement->start_date->format('Y-m-d') : '') }}">
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">End Date <span class="urdu">(ختم کی تاریخ)</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ old('end_date', $rentAgreement->end_date ? $rentAgreement->end_date->format('Y-m-d') : '') }}">
                                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rent Amount <span class="urdu">(کرایہ کی رقم)</span> <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('rent_amount') is-invalid @enderror" name="rent_amount" value="{{ old('rent_amount', $rentAgreement->rent_amount) }}" required>
                        @error('rent_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Security Deposit <span class="urdu">(سیکیورٹی ڈپازٹ)</span></label>
                        <input type="number" step="0.01" class="form-control @error('security_deposit') is-invalid @enderror" name="security_deposit" value="{{ old('security_deposit', $rentAgreement->security_deposit) }}">
                        @error('security_deposit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Notice Period (Days) <span class="urdu">(نوٹس کی مدت - دن)</span></label>
                        <input type="number" min="0" class="form-control @error('notice_period_days') is-invalid @enderror" name="notice_period_days" value="{{ old('notice_period_days', $rentAgreement->notice_period_days ?? 30) }}">
                        @error('notice_period_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Late Fee (Per Day) <span class="urdu">(تاخیری فیس - یومیہ)</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('late_fee_per_day') is-invalid @enderror" name="late_fee_per_day" value="{{ old('late_fee_per_day', $rentAgreement->late_fee_per_day) }}">
                        @error('late_fee_per_day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rent Increase (%) <span class="urdu">(کرایہ میں اضافہ - فیصد)</span></label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control @error('rent_increase_percent') is-invalid @enderror" name="rent_increase_percent" value="{{ old('rent_increase_percent', $rentAgreement->rent_increase_percent) }}">
                        @error('rent_increase_percent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rent Increase Frequency <span class="urdu">(کرایہ میں اضافے کی تعدد)</span></label>
                        <select class="form-select @error('rent_increase_frequency') is-invalid @enderror" name="rent_increase_frequency">
                            <option value="">Select <span class="urdu">(منتخب)</span></option>
                            <option value="monthly" {{ old('rent_increase_frequency', $rentAgreement->rent_increase_frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('rent_increase_frequency', $rentAgreement->rent_increase_frequency) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="custom" {{ old('rent_increase_frequency', $rentAgreement->rent_increase_frequency) == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('rent_increase_frequency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="urdu">(کیفیت)</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="active" {{ old('status', $rentAgreement->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ old('status', $rentAgreement->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="expired" {{ old('status', $rentAgreement->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="terminated" {{ old('status', $rentAgreement->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes', $rentAgreement->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Agreement <span class="urdu">(کرایہ نامہ اپ ڈیٹ کریں)</span></button>
            <a href="{{ route('rent-agreements.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </div>
    </form>
</div>
@endsection

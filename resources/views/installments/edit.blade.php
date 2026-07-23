@extends('layouts.admin')

@section('title', 'Edit Installment <span class="urdu">(قسط میں ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('installments.index') }}" class="text-decoration-none">Installments <span class="urdu">(اقساط)</span></a></li>
        <li class="breadcrumb-item active">Edit Installment <span class="urdu">(قسط میں ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap gap-2">
        <h4><i class="ti ti-credit-card"></i> <span class="urdu">(قسط میں ترمیم کریں)</span></h4>
    </div>
    <form action="{{ route('installments.update', $installment) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Installment # <span class="urdu">(قسط نمبر)</span></label>
                        <input type="text" class="form-control" value="{{ $installment->installment_no ?? $installment->id }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="urdu">(رقم)</span> <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $installment->amount) }}" required>
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date <span class="urdu">(واجب الادا تاریخ)</span></label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date" value="{{ old('due_date', $installment->due_date ? $installment->due_date->format('Y-m-d') : '') }}">
                        @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="urdu">(کیفیت)</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="pending" {{ old('status', $installment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('status', $installment->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="overdue" {{ old('status', $installment->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="partial" {{ old('status', $installment->status) == 'partial' ? 'selected' : '' }}>Partial</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Paid Amount <span class="urdu">(ادا شدہ رقم)</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('paid_amount') is-invalid @enderror" name="paid_amount" value="{{ old('paid_amount', $installment->paid_amount) }}">
                        @error('paid_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Paid Date <span class="urdu">(ادائیگی کی تاریخ)</span></label>
                        <input type="date" class="form-control @error('paid_date') is-invalid @enderror" name="paid_date" value="{{ old('paid_date', $installment->paid_date ? $installment->paid_date->format('Y-m-d') : '') }}">
                        @error('paid_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Late Fee <span class="urdu">(تاخیری فیس)</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('late_fee') is-invalid @enderror" name="late_fee" value="{{ old('late_fee', $installment->late_fee) }}">
                        @error('late_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> <span class="urdu">(قسط اپ ڈیٹ کریں)</span></button>
            <a href="{{ route('installments.index') }}" class="btn btn-link text-secondary text-decoration-none"><span class="urdu">(منسوخ کریں)</span></a>
        </div>
    </form>
</div>
@endsection

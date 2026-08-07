@extends('layouts.admin')

@section('title', 'Edit Rent Payment')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('rent-payments.index') }}" class="text-decoration-none">Rent Overview</a></li>
        <li class="breadcrumb-item active">Edit Payment — {{ date('F Y', mktime(0, 0, 0, $rentPayment->month, 1, $rentPayment->year)) }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0">
        <i class="ti ti-edit me-1"></i>
        Edit Payment — {{ date('F Y', mktime(0, 0, 0, $rentPayment->month, 1, $rentPayment->year)) }}
    </h4>
    <a href="{{ route('rent-payments.show', $rentPayment->rent_agreement_id) }}" class="btn btn-outline-dark btn-sm">
        <i class="ti ti-arrow-left"></i> Back
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('rent-payments.update', $rentPayment) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Month <span class="text-danger">*</span></label>
                    <select name="month" class="form-select @error('month') is-invalid @enderror" required>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ old('month', $rentPayment->month) == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endforeach
                    </select>
                    @error('month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Year <span class="text-danger">*</span></label>
                    <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', $rentPayment->year) }}" min="2020" max="2050" required>
                    @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Due Date <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $rentPayment->due_date?->format('Y-m-d')) }}" required>
                    @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $rentPayment->amount) }}" min="0" required>
                    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes', $rentPayment->notes) }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Payment</button>
                <a href="{{ route('rent-payments.show', $rentPayment->rent_agreement_id) }}" class="btn btn-link text-secondary text-decoration-none">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

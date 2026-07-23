@extends('layouts.admin')

@section('title', 'Add Installment Plan <span class="urdu">(قسط کا منصوبہ شامل کریں)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('installments.index') }}" class="text-decoration-none">Installments <span class="urdu">(اقساط)</span></a></li>
        <li class="breadcrumb-item active">Add Installment Plan <span class="urdu">(قسط کا منصوبہ شامل کریں)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap gap-2">
        <h4><i class="ti ti-credit-card"></i> <span class="urdu">(قسط کا منصوبہ شامل کریں)</span></h4>
    </div>
    <form action="{{ route('installments.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Deal <span class="urdu">(ڈیل)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('deal_id') is-invalid @enderror" name="deal_id" required>
                            <option value=""><span class="urdu">(ڈیل منتخب کریں)</span></option>
                            @foreach($deals ?? [] as $deal)
                                <option value="{{ $deal->id }}" {{ old('deal_id') == $deal->id ? 'selected' : '' }}>{{ $deal->deal_number }} - {{ $deal->property->title ?? '' }}</option>
                            @endforeach
                        </select>
                        @error('deal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Installments <span class="urdu">(کل اقساط)</span> <span class="text-danger">*</span></label>
                        <input type="number" min="1" class="form-control @error('total_installments') is-invalid @enderror" name="total_installments" value="{{ old('total_installments', 1) }}" required>
                        @error('total_installments') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Installment Amount <span class="urdu">(قسط کی رقم)</span> <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('installment_amount') is-invalid @enderror" name="installment_amount" value="{{ old('installment_amount') }}" required>
                        @error('installment_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Frequency <span class="urdu">(تعدد)</span></label>
                        <select class="form-select @error('frequency') is-invalid @enderror" name="frequency">
                            <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="quarterly" {{ old('frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            <option value="biannually" {{ old('frequency') == 'biannually' ? 'selected' : '' }}>Bi-annually</option>
                            <option value="annually" {{ old('frequency') == 'annually' ? 'selected' : '' }}>Annually</option>
                            <option value="custom" {{ old('frequency') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('frequency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date <span class="urdu">(شروع کی تاریخ)</span></label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}">
                        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> <span class="urdu">(قسط کا منصوبہ محفوظ کریں)</span></button>
            <a href="{{ route('installments.index') }}" class="btn btn-link text-secondary text-decoration-none"><span class="urdu">(منسوخ کریں)</span></a>
        </div>
    </form>
</div>
@endsection

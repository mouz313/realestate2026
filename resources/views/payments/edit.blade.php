@extends('layouts.admin')

@section('title', 'Edit Payment <span class="urdu">(ادائیگی میں ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}" class="text-decoration-none">Invoices <span class="urdu">(انوائسز)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.show', $payment->invoice) }}" class="text-decoration-none">{{ $payment->invoice->invoice_number }}</a></li>
        <li class="breadcrumb-item active">Edit Payment <span class="urdu">(ادائیگی میں ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>Edit Payment <span class="urdu">(ادائیگی میں ترمیم)</span></h3>
        <div class="page-header-sub">Invoice: <span class="urdu">(انوائس)</span> {{ $payment->invoice->invoice_number }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5><i class="ti ti-currency-dollar me-1"></i> Payment Details <span class="urdu">(ادائیگی کی تفصیلات)</span></h5>
    </div>
    <div class="card-body">
        <form action="{{ route('payments.update', $payment) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="urdu">(رقم)</span></label>
                        <input type="number" name="amount" step="0.01" min="0.01" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $payment->amount) }}" required>
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Method <span class="urdu">(ذریعہ)</span></label>
                        <select name="method" class="form-select @error('method') is-invalid @enderror">
                            <option value="">Select</option>
                            @foreach(['cash', 'bank_transfer', 'cheque', 'credit_card', 'jazzcash', 'easypaisa'] as $m)
                            <option value="{{ $m }}" {{ old('method', $payment->method) == $m ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $m)) }}</option>
                            @endforeach
                        </select>
                        @error('method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Reference <span class="urdu">(حوالہ)</span></label>
                        <input type="text" name="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference', $payment->reference) }}">
                        @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Date <span class="urdu">(تاریخ)</span></label>
                        <input type="date" name="paid_date" class="form-control @error('paid_date') is-invalid @enderror" value="{{ old('paid_date', $payment->paid_date->format('Y-m-d')) }}" required>
                        @error('paid_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <input type="text" name="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $payment->notes) }}">
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Payment <span class="urdu">(ادائیگی اپ ڈیٹ)</span></button>
                <a href="{{ route('invoices.show', $payment->invoice) }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
            </div>
        </form>
    </div>
</div>
@endsection

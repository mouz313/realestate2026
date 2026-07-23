@extends('layouts.admin')

@section('title', 'Add Token <span class="urdu">(ٹوکن شامل کریں)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('tokens.index') }}" class="text-decoration-none">Tokens <span class="urdu">(ٹوکنز)</span></a></li>
        <li class="breadcrumb-item active">Add Token <span class="urdu">(ٹوکن شامل کریں)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap gap-2">
        <h4><i class="ti ti-coin"></i> <span class="urdu">(نیا ٹوکن شامل کریں)</span></h4>
    </div>
    <form action="{{ route('tokens.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Deal <span class="urdu">(ڈیل)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('deal_id') is-invalid @enderror" name="deal_id" required>
                            <option value=""><span class="urdu">(ڈیل منتخب کریں)</span></option>
                            @foreach($deals ?? [] as $deal)
                                <option value="{{ $deal->id }}" {{ old('deal_id', request('deal_id')) == $deal->id ? 'selected' : '' }}>{{ $deal->deal_number }} - {{ $deal->property->title ?? '' }}</option>
                            @endforeach
                        </select>
                        @error('deal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="urdu">(رقم)</span> <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required>
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="urdu">(ادائیگی کا طریقہ)</span></label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" name="payment_method">
                            <option value=""><span class="urdu">(طریقہ منتخب کریں)</span></option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online Payment</option>
                        </select>
                        @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Reference No <span class="urdu">(حوالہ نمبر)</span></label>
                        <input type="text" class="form-control @error('reference_no') is-invalid @enderror" name="reference_no" value="{{ old('reference_no') }}">
                        @error('reference_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Received Date <span class="urdu">(وصولی کی تاریخ)</span></label>
                        <input type="date" class="form-control @error('received_date') is-invalid @enderror" name="received_date" value="{{ old('received_date') }}">
                        @error('received_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="urdu">(کیفیت)</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="received" {{ old('status') == 'received' ? 'selected' : '' }}>Received</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="2">{{ old('notes') }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> <span class="urdu">(ٹوکن محفوظ کریں)</span></button>
            <a href="{{ route('tokens.index') }}" class="btn btn-link text-secondary text-decoration-none"><span class="urdu">(منسوخ کریں)</span></a>
        </div>
    </form>
</div>
@endsection

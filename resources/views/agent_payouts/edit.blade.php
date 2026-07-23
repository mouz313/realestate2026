@extends('layouts.admin')

@section('title', 'Edit Agent Payout <span class="urdu">(ایجنٹ ادائیگی میں ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('agent-payouts.index') }}" class="text-decoration-none">Agent Payouts <span class="urdu">(ایجنٹ ادائیگیاں)</span></a></li>
        <li class="breadcrumb-item active">Edit Payout <span class="urdu">(ادائیگی میں ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header flex-wrap gap-2">
        <h4><i class="ti ti-cash"></i> Edit Agent Payout <span class="urdu">(ایجنٹ ادائیگی میں ترمیم)</span></h4>
    </div>
    <form action="{{ route('agent-payouts.update', $agentPayout) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Agent <span class="urdu">(ایجنٹ)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('agent_id') is-invalid @enderror" name="agent_id" required>
                            <option value="">Select Agent <span class="urdu">(ایجنٹ منتخب)</span></option>
                            @foreach($agents ?? [] as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id', $agentPayout->agent_id) == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        @error('agent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="urdu">(رقم)</span> <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $agentPayout->amount) }}" required>
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payout Date <span class="urdu">(ادائیگی کی تاریخ)</span></label>
                        <input type="date" class="form-control @error('payout_date') is-invalid @enderror" name="payout_date" value="{{ old('payout_date', $agentPayout->payout_date ? $agentPayout->payout_date->format('Y-m-d') : '') }}">
                        @error('payout_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Method <span class="urdu">(ذریعہ)</span></label>
                        <select class="form-select @error('method') is-invalid @enderror" name="method">
                            <option value="">Select Method <span class="urdu">(ذریعہ منتخب)</span></option>
                            <option value="cash" {{ old('method', $agentPayout->method) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('method', $agentPayout->method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cheque" {{ old('method', $agentPayout->method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="online" {{ old('method', $agentPayout->method) == 'online' ? 'selected' : '' }}>Online Payment</option>
                        </select>
                        @error('method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference <span class="urdu">(حوالہ)</span></label>
                        <input type="text" class="form-control @error('reference') is-invalid @enderror" name="reference" value="{{ old('reference', $agentPayout->reference) }}">
                        @error('reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="2">{{ old('notes', $agentPayout->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Payout <span class="urdu">(ادائیگی اپ ڈیٹ کریں)</span></button>
            <a href="{{ route('agent-payouts.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </div>
    </form>
</div>
@endsection

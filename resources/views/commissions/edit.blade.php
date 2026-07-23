@extends('layouts.admin')

@section('title', 'Edit Commission <span class="urdu">(کمیشن میں ترمیم)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('commissions.index') }}" class="text-decoration-none">Commissions <span class="urdu">(کمیشنز)</span></a></li>
        <li class="breadcrumb-item active">Edit Commission <span class="urdu">(کمیشن میں ترمیم)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header flex-wrap gap-2">
        <h4><i class="ti ti-percentage"></i> Edit Commission <span class="urdu">(کمیشن میں ترمیم)</span></h4>
    </div>
    <form action="{{ route('commissions.update', $commission) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Deal <span class="urdu">(ڈیل)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('deal_id') is-invalid @enderror" name="deal_id" required>
                            <option value="">Select Deal <span class="urdu">(ڈیل منتخب)</span></option>
                            @foreach($deals ?? [] as $deal)
                                <option value="{{ $deal->id }}" {{ old('deal_id', $commission->deal_id) == $deal->id ? 'selected' : '' }}>{{ $deal->deal_number }} - {{ $deal->property->title ?? '' }}</option>
                            @endforeach
                        </select>
                        @error('deal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Agent <span class="urdu">(ایجنٹ)</span> <span class="text-danger">*</span></label>
                        <select class="form-select @error('agent_id') is-invalid @enderror" name="agent_id" required>
                            <option value="">Select Agent <span class="urdu">(ایجنٹ منتخب)</span></option>
                            @foreach($agents ?? [] as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id', $commission->agent_id) == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        @error('agent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="urdu">(قسم)</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" name="type">
                            <option value="percentage" {{ old('type', $commission->type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed" {{ old('type', $commission->type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Percentage (%) <span class="urdu">(فیصد)</span></label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control @error('percentage') is-invalid @enderror" name="percentage" value="{{ old('percentage', $commission->percentage) }}">
                        @error('percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="urdu">(رقم)</span> <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $commission->amount) }}" required>
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="urdu">(کیفیت)</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status">
                            <option value="pending" {{ old('status', $commission->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('status', $commission->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="paid" {{ old('status', $commission->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ old('status', $commission->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes <span class="urdu">(نوٹس)</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="2">{{ old('notes', $commission->notes) }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-dark"><i class="ti ti-device-floppy"></i> Update Commission <span class="urdu">(کمیشن اپ ڈیٹ کریں)</span></button>
            <a href="{{ route('commissions.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel <span class="urdu">(منسوخ)</span></a>
        </div>
    </form>
</div>
@endsection

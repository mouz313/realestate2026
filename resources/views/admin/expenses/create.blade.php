@extends('layouts.admin')

@section('title', 'Add Expense')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="ti ti-receipt me-1"></i> Add Expense <span class="urdu">(اخراجات شامل کریں)</span></h4>
    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Select category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $cat)) }}</option>
                            @endforeach
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Amount (Rs.) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        @error('expense_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="">Select method</option>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="raast" {{ old('payment_method') === 'raast' ? 'selected' : '' }}>Raast</option>
                            <option value="online" {{ old('payment_method') === 'online' ? 'selected' : '' }}>Online</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Agent</label>
                        <select name="agent_id" class="form-select">
                            <option value="">None</option>
                            @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Deal</label>
                        <select name="deal_id" class="form-select">
                            <option value="">None</option>
                            @foreach($deals as $deal)
                            <option value="{{ $deal->id }}" {{ old('deal_id') == $deal->id ? 'selected' : '' }}>{{ $deal->deal_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Reference No</label>
                        <input type="text" name="reference_no" class="form-control" value="{{ old('reference_no') }}">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-dark"><i class="ti ti-check"></i> Save Expense</button>
            <a href="{{ route('expenses.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel</a>
        </form>
    </div>
</div>
@endsection

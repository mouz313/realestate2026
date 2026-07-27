@extends('layouts.admin')

@section('title', 'Expenses')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="ti ti-receipt me-1"></i> Expenses <span class="urdu">(اخراجات)</span></h4>
    <a href="{{ route('expenses.create') }}" class="btn btn-dark btn-sm"><i class="ti ti-plus"></i> Add Expense</a>
</div>

<div class="row g-2 mb-3">
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="small text-secondary">Total Expenses</div>
            <div class="fs-4 fw-bold text-danger">Rs. {{ number_format($totalAmount, 0) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="small text-secondary">This Month</div>
            <div class="fs-4 fw-bold">Rs. {{ number_format(\App\Models\Expense::whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year)->sum('amount'), 0) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="small text-secondary">This Year</div>
            <div class="fs-4 fw-bold">Rs. {{ number_format(\App\Models\Expense::whereYear('expense_date', now()->year)->sum('amount'), 0) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3">
            <div class="small text-secondary">Total Records</div>
            <div class="fs-4 fw-bold">{{ number_format(\App\Models\Expense::count()) }}</div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small">Category</label>
                <select name="category" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $cat)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">From</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">To</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Title, notes...">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-dark btn-sm w-100"><i class="ti ti-filter"></i></button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Title</th>
                        <th>Agent</th>
                        <th>Deal</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $exp)
                    <tr>
                        <td>{{ $exp->expense_date->format('d M Y') }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $exp->category)) }}</span></td>
                        <td class="fw-semibold">{{ $exp->title }}</td>
                        <td class="text-secondary">{{ $exp->agent?->name ?? '-' }}</td>
                        <td class="text-secondary">{{ $exp->deal?->deal_number ?? '-' }}</td>
                        <td class="text-end fw-bold text-danger">Rs. {{ number_format($exp->amount, 0) }}</td>
                        <td class="text-end">
                            <div class="action-btns justify-content-end">
                                <a href="{{ route('expenses.edit', $exp) }}" class="btn btn-sm btn-outline-dark" title="Edit"><i class="ti ti-pencil"></i></a>
                                <form action="{{ route('expenses.destroy', $exp) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this expense?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="ti ti-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">No expenses found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">{{ $expenses->links() }}</div>
</div>
@endsection

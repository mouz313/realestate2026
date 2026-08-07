@extends('layouts.admin')

@section('title', 'Checkout — {{ $package->name }}')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('billing.index') }}" class="text-decoration-none">Billing</a></li>
        <li class="breadcrumb-item active">Checkout — {{ $package->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="mb-0"><i class="ti ti-ticket me-1"></i> Checkout: {{ $package->name }}</h4>
                @if($isDowngrade)
                <span class="badge bg-danger">Downgrade blocked</span>
                @elseif($isUpgrade)
                <span class="badge bg-success">Upgrade</span>
                @endif
            </div>

            @if($isDowngrade)
            <div class="alert alert-danger mb-0 rounded-0">
                <i class="ti ti-alert-triangle"></i> Downgrading from <strong>{{ $current->package->name }}</strong> ({{ number_format($current->package->price, 2) }}) to a cheaper package is not allowed.
            </div>
            @else
            <form action="{{ route('billing.checkout.store', $package) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h5 class="mb-2">Your Company</h5>
                            <p class="mb-0">{{ $company->name }}</p>

                            <h5 class="mb-2 mt-3">Plan</h5>
                            <ul class="list-group list-group-borderless small">
                                <li class="list-group-item py-1"><span class="text-secondary">Price:</span> {{ $package->isFree() ? 'Free' : number_format($package->price, 2).' '.$package->currency.' / '.$package->intervalLabel() }}</li>
                                <li class="list-group-item py-1"><span class="text-secondary">Employees:</span> {{ $package->limitLabel('max_employees') }}</li>
                                <li class="list-group-item py-1"><span class="text-secondary">Clients:</span> {{ $package->limitLabel('max_clients') }}</li>
                                <li class="list-group-item py-1"><span class="text-secondary">Properties:</span> {{ $package->limitLabel('max_properties') }}</li>
                                @if($package->trial_days)
                                <li class="list-group-item py-1 text-success">Trial: {{ $package->trial_days }} days</li>
                                @endif
                            </ul>

                            @if($isUpgrade && $current)
                            <div class="alert alert-info small mt-3 mb-0">
                                Upgrading from {{ $current->package->name }}. Pay the difference ({{ number_format(max(0, $package->price - $current->package->price), 2) }} {{ $package->currency }}) and upload proof.
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-2">Payment Proof</h5>
                            <div class="mb-3">
                                <label class="form-label">Amount Paid <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('amount_paid') is-invalid @enderror" name="amount_paid" value="{{ old('amount_paid', $package->isFree() ? 0 : $package->price) }}" required>
                                @error('amount_paid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Proof of Payment <span class="text-danger">{{ $package->isFree() ? '' : '*' }}</span></label>
                                <input type="file" class="form-control @error('proof') is-invalid @enderror" name="proof" {{ $package->isFree() ? '' : 'required' }} accept=".pdf,.jpg,.jpeg,.png">
                                @error('proof') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text">Upload a screenshot of your bank receipt / transfer. The super-admin will verify the amount and approve.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Note (optional)</label>
                                <textarea class="form-control" name="note" rows="2" placeholder="Transaction reference, remarks...">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-dark"><i class="ti ti-shopping-cart"></i> Submit for Approval</button>
                    <a href="{{ route('billing.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel</a>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

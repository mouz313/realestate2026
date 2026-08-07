@extends('layouts.admin')

@section('title', 'Billing')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Billing</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3><i class="ti ti-currency-dollar me-1"></i> Billing &amp; Subscriptions</h3>
        <div class="page-header-sub">Manage your plan, view usage limits and payment history.</div>
    </div>
</div>

{{-- Current plan + usage --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        @if($active)
        <div class="card h-100">
            <div class="card-header d-flex flex-wrap gap-2 justify-content-between">
                <h5 class="mb-0"><i class="ti ti-package"></i> {{ $active->package->name }} <span class="badge {{ $active->badgeClass() }}">{{ $active->statusLabel() }}</span></h5>
                <div class="text-end">
                    @if($active->ends_at)
                    <span class="small text-secondary">Renews {{ $active->ends_at->format('M d, Y') }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <p class="mb-1"><span class="text-secondary small">Interval:</span> {{ $active->package->intervalLabel() }} · <span class="text-secondary small">Price:</span> {{ $active->package->isFree() ? 'Free' : number_format($active->package->price, 2).' '.$active->package->currency }}</p>
                <div class="mt-3 d-flex flex-wrap gap-2">
                    <a href="#packages" class="btn btn-outline-dark btn-sm"><i class="ti ti-arrow-up-right"></i> Change / Upgrade Plan</a>
                    <span class="form-text small">Downgrading to a cheaper package is not allowed.</span>
                </div>
            </div>
        </div>
        @else
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="ti ti-alert-triangle"></i>
                <h5 class="mb-0">No active subscription</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">Your subscription is missing or expired. Please purchase a package to keep your data accessible.</p>
            </div>
            <div class="card-footer">
                <a href="#packages" class="btn btn-dark"><i class="ti ti-shopping-cart"></i> Buy a package</a>
            </div>
        </div>
        @endif
    </div>

    {{-- Limits / usage --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0"><i class="ti ti-gauge"></i> Current Usage</h5></div>
            <div class="card-body">
                @php $pkg = $active?->package; @endphp
                <ul class="list-group list-group-borderless">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Employees</span>
                        <span>{{ $usage['employees'] }} @if($pkg) / {{ $pkg->limitLabel('max_employees') }} @endif</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Clients</span>
                        <span>{{ $usage['clients'] }} @if($pkg) / {{ $pkg->limitLabel('max_clients') }} @endif</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Properties</span>
                        <span>{{ $usage['properties'] }} @if($pkg) / {{ $pkg->limitLabel('max_properties') }} @endif</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Purchase history --}}
<div class="card table-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Subscription History</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Package</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Period</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription->package->name }}</td>
                    <td>{{ $subscription->package->isFree() && (float) $subscription->amount_paid <= 0 ? 'Free' : number_format($subscription->amount_paid, 2).' '.$subscription->currency }}</td>
                    <td><span class="badge {{ $subscription->badgeClass() }}">{{ $subscription->statusLabel() }}</span></td>
                    <td class="text-secondary small">{{ $subscription->started_at?->format('M d Y') ?? '—' }} → {{ $subscription->ends_at?->format('M d Y') ?? '—' }}</td>
                    <td class="text-end">
                        @if($subscription->status === \App\Models\Subscription::STATUS_PENDING && $subscription->company_id === $company->id)
                        <form action="{{ route('billing.cancel', $subscription) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Cancel this pending request?')" class="text-danger small btn btn-sm btn-link p-0">Cancel request</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><p>No subscriptions yet.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Available packages --}}
<div id="packages" class="mb-4">
    <h4 class="mb-2 mb-md-3">Available Packages</h4>
    <div class="row g-3">
        @forelse($packages as $package)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">{{ $package->name }}</h5></div>
                <div class="card-body">
                    <p class="text-secondary small">{{ $package->description ? Str::limit($package->description, 90) : '—' }}</p>
                    <h3 class="my-2">{{ $package->isFree() ? 'Free' : number_format($package->price, 2) }}<small class="fs-6 text-secondary">{{ $package->isFree() ? '' : ' / '.$package->currency.' per '.Str::lower($package->intervalLabel()) }}</small></h3>
                    <ul class="list-group list-group-borderless small">
                        <li class="list-group-item py-1">Employees: {{ $package->limitLabel('max_employees') }}</li>
                        <li class="list-group-item py-1">Clients: {{ $package->limitLabel('max_clients') }}</li>
                        <li class="list-group-item py-1">Properties: {{ $package->limitLabel('max_properties') }}</li>
                        @if($package->trial_days)
                        <li class="list-group-item py-1 text-success">Includes {{ $package->trial_days }}-day trial</li>
                        @endif
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="{{ $package->is_active ? route('billing.checkout', $package) : '#' }}" class="btn {{ $package->is_active ? 'btn-dark' : 'btn-outline-secondary' }} w-100" {{ $package->is_active ? '' : 'disabled' }}>{{ $package->is_active ? 'Buy Now' : 'Unavailable' }}</a>
                </div>
            </div>
        </div>
        @empty
        <p class="text-secondary">No packages available.</p>
        @endforelse
    </div>
</div>
@endsection

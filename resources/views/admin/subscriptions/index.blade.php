@extends('layouts.admin')

@section('title', 'Subscriptions')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Subscriptions</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3><i class="ti ti-package"></i> Subscriptions</h3>
        <div class="page-header-sub">All company subscriptions across every plan.</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.subscriptions.pending') }}" class="btn btn-outline-dark btn-sm">
            <i class="ti ti-clock-dollar"></i> Pending ({{ $pendingCount }})
        </a>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Company</th>
                    <th class="d-none d-md-table-cell">Package</th>
                    <th class="d-none d-md-table-cell">Amount</th>
                    <th>Status</th>
                    <th class="d-none d-md-table-cell">Period</th>
                    <th class="d-none d-md-table-cell">Submitted By / Verified</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $subscription)
                <tr>
                    <td class="fw-semibold">{{ $subscription->company->name }}</td>
                    <td class="d-none d-md-table-cell">{{ $subscription->package->name }}</td>
                    <td class="d-none d-md-table-cell">{{ $subscription->package->isFree() ? 'Free' : number_format($subscription->amount_paid, 2).' '.$subscription->currency }}</td>
                    <td><span class="badge {{ $subscription->badgeClass() }}">{{ $subscription->statusLabel() }}</span></td>
                    <td class="d-none d-md-table-cell text-secondary small">{{ $subscription->started_at?->format('M d Y') ?? '—' }} → {{ $subscription->ends_at?->format('M d Y') ?? '—' }}</td>
                    <td class="d-none d-md-table-cell text-secondary small">
                        {{ $subscription->created_at?->format('M d, Y') }}
                        @if($subscription->verifiedBy) / {{ $subscription->verifiedBy->name }}@endif
                    </td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            @if($subscription->status === \App\Models\Subscription::STATUS_SUSPENDED && ($subscription->company->current_subscription_id === $subscription->id || is_null($subscription->company->current_subscription_id)))
                            <form action="{{ route('admin.subscriptions.unblock', $subscription) }}" method="POST" class="d-inline" onsubmit="return confirm('Unblock this company?')">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Unblock"><i class="ti ti-lock-open"></i></button>
                            </form>
                            @endif
                            @if($subscription->status === \App\Models\Subscription::STATUS_PENDING)
                            <a href="{{ route('admin.subscriptions.pending') }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-checklist"></i></a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><p>No subscriptions yet.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Pending Subscriptions')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}" class="text-decoration-none">Subscriptions</a></li>
        <li class="breadcrumb-item active">Pending Approval</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3><i class="ti ti-clock-dollar me-1"></i> Pending Subscription Approvals</h3>
        <div class="page-header-sub">{{ $subscriptions->count() }} requests awaiting your review.</div>
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
                    <th>Proof</th>
                    <th class="d-none d-md-table-cell">Submitted</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $subscription)
                <tr>
                    <td class="fw-semibold">{{ $subscription->company->name }}</td>
                    <td class="d-none d-md-table-cell">{{ $subscription->package->name }} ({{ $subscription->package->intervalLabel() }})</td>
                    <td class="d-none d-md-table-cell">{{ number_format($subscription->amount_paid, 2) }} {{ $subscription->currency }}</td>
                    <td>
                        @if($subscription->proof_path)
                        <a href="{{ Storage::url($subscription->proof_path) }}" target="_blank" class="d-block">
                            @php $ext = strtolower(pathinfo($subscription->proof_path, PATHINFO_EXTENSION)); @endphp
                            @if(in_array($ext, ['jpg','jpeg','png']))
                            <img src="{{ Storage::url($subscription->proof_path) }}" alt="proof" class="img-fluid rounded" style="max-width:120px;max-height:80px;object-fit:cover;">
                            @else
                            <span class="badge bg-secondary">PDF</span>
                            @endif
                        </a>
                        @else
                        <span class="text-secondary small">No proof</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell text-secondary small">{{ $subscription->created_at?->format('M d, Y') }}</td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            {{-- Approve --}}
                            <form action="{{ route('subscriptions.approve', $subscription) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <button type="submit" onclick="return confirm('Approve and activate this subscription for {{ $subscription->company->name }}?')" class="btn btn-sm btn-outline-success" title="Approve">
                                    <i class="ti ti-check"></i>
                                </button>
                            </form>
                            {{-- Block --}}
                            <form action="{{ route('subscriptions.block', $subscription) }}" method="POST" class="d-inline" onsubmit="return fillBlock(this)">
                                @csrf @method('PUT')
                                <input type="hidden" name="block_reason" class="block-reason">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Block">
                                    <i class="ti ti-alert-triangle"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><p>No pending requests.</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
function fillBlock(form) {
    const reason = prompt('Reason for blocking this subscription? (required)');
    if (!reason) return false;
    form.querySelector('.block-reason').value = reason;
    return confirm('Block this subscription? The company will be suspended.');
}
</script>
@endpush

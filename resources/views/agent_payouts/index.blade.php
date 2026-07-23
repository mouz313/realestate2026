@extends('layouts.admin')

@section('title', 'Agent Payouts <span class="urdu">(ایجنٹ ادائیگیاں)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Agent Payouts <span class="urdu">(ایجنٹ ادائیگیاں)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Agent Payouts <span class="urdu">(ایجنٹ ادائیگیاں)</span></h3>
        <div class="page-header-sub">{{ $agentPayouts->total() }} <span class="urdu">(کل)</span> payouts <span class="urdu">(ادائیگیاں)</span></div>
    </div>
    <a href="{{ route('agent-payouts.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add Payout <span class="urdu">(نیا ادائیگی)</span>
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Agent <span class="urdu">(ایجنٹ)</span></th>
                    <th>Amount <span class="urdu">(رقم)</span></th>
                    <th class="d-none d-sm-table-cell">Payout Date <span class="urdu">(ادائیگی کی تاریخ)</span></th>
                    <th class="d-none d-sm-table-cell">Method <span class="urdu">(ذریعہ)</span></th>
                    <th class="d-none d-md-table-cell">Reference <span class="urdu">(حوالہ)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($agentPayouts as $payout)
                <tr>
                    <td class="fw-semibold">{{ $payout->agent->name ?? '-' }}</td>
                    <td class="fw-medium">{{ number_format($payout->amount, 0) }}</td>
                    <td class="text-secondary d-none d-sm-table-cell">{{ $payout->payout_date ? $payout->payout_date->format('d M Y') : '-' }}</td>
                    <td class="d-none d-sm-table-cell">{{ ucfirst($payout->method ?? '-') }}</td>
                    <td class="text-secondary d-none d-md-table-cell">{{ $payout->reference ?? '-' }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('agent-payouts.edit', $payout) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('agent-payouts.destroy', $payout) }}" method="POST" onsubmit="return confirm('Delete this payout?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="ti ti-cash"></i>
                            <p>No payouts yet. <span class="urdu">(کوئی ادائیگی نہیں)</span></p>
                            <a href="{{ route('agent-payouts.create') }}" class="text-decoration-none fw-medium">Add your first payout <span class="urdu">(اپنی پہلی ادائیگی شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($agentPayouts->hasPages())
    <div class="p-3 border-top">
        {{ $agentPayouts->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

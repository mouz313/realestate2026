@extends('layouts.admin')

@section('title', 'Agent Payout Details')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('agent-payouts.index') }}" class="text-decoration-none">Agent Payouts <span class="urdu">(ایجنٹ ادائیگیاں)</span></a></li>
        <li class="breadcrumb-item active">#{{ $agentPayout->id }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <h3>Agent Payout <span class="urdu">(ایجنٹ ادائیگی)</span> #{{ $agentPayout->id }}</h3>
    <div class="page-header-sub">
        <span class="badge status-paid fs-6">Rs. {{ number_format($agentPayout->amount, 2) }}</span>
    </div>
    <div class="action-btns">
        <a href="{{ route('agent-payouts.edit', $agentPayout) }}" class="btn btn-dark">
            <i class="ti ti-edit"></i> <span class="urdu">(ترمیم کریں)</span>
        </a>
        <form action="{{ route('agent-payouts.destroy', $agentPayout) }}" method="POST" onsubmit="return confirm('Delete this payout?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="ti ti-trash"></i> <span class="urdu">(حذف کریں)</span>
            </button>
        </form>
        <a href="{{ route('agent-payouts.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> <span class="urdu">(واپس)</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-cash me-1"></i> Payout Information <span class="urdu">(ادائیگی کی معلومات)</span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="detail-table table">
                        <tr>
                            <th>Agent <span class="urdu">(ایجنٹ)</span></th>
                            <td>
                                @if($agentPayout->agent)
                                    <a href="{{ route('agents.show', $agentPayout->agent) }}" class="text-decoration-none fw-medium">{{ $agentPayout->agent->name }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Amount <span class="urdu">(رقم)</span></th>
                            <td class="fw-semibold text-success">Rs. {{ number_format($agentPayout->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Payout Date <span class="urdu">(ادائیگی کی تاریخ)</span></th>
                            <td>{{ $agentPayout->payout_date ? $agentPayout->payout_date->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Method <span class="urdu">(ذریعہ)</span></th>
                            <td>{{ ucfirst($agentPayout->method ?? '-') }}</td>
                        </tr>
                        <tr>
                            <th>Reference <span class="urdu">(حوالہ)</span></th>
                            <td>{{ $agentPayout->reference ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Commissions <span class="urdu">(کمیشن)</span></th>
                            <td>{{ $agentPayout->commission_ids ? count($agentPayout->commission_ids) . ' commission(s)' : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Created <span class="urdu">(تخلیق)</span></th>
                            <td>{{ $agentPayout->created_at ? $agentPayout->created_at->format('d M Y h:i A') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-notes me-1"></i> Notes <span class="urdu">(نوٹس)</span></h5>
            </div>
            <div class="card-body">
                <p class="text-secondary mb-0">{{ $agentPayout->notes ?? '-' }}</p>
            </div>
            </div>
        </div>
    </div>

@php
    $linkedCommissions = $agentPayout->commission_ids
        ? App\Models\Commission::with(['deal', 'agent'])->whereIn('id', $agentPayout->commission_ids)->get()
        : collect();
@endphp

@if($linkedCommissions->isNotEmpty())
<div class="card mt-4">
    <div class="card-header">
        <h5><i class="ti ti-percentage me-1"></i> Linked Commissions <span class="urdu">(منسلک کمیشنز)</span></h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Deal # <span class="urdu">(ڈیل نمبر)</span></th>
                        <th>Agent <span class="urdu">(ایجنٹ)</span></th>
                        <th>Source <span class="urdu">(ذریعہ)</span></th>
                        <th>Agency (90%) <span class="urdu">(ایجنسی)</span></th>
                        <th>Agent (10%) <span class="urdu">(ایجنٹ)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($linkedCommissions as $c)
                    <tr>
                        <td class="fw-semibold">
                            @if($c->deal)
                                <a href="{{ route('deals.show', $c->deal) }}" class="text-decoration-none">{{ $c->deal->deal_number }}</a>
                            @else - @endif
                        </td>
                        <td>{{ $c->agent->name ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark">{{ ucfirst($c->source ?? ($c->type ?? '-')) }}</span></td>
                        <td class="fw-semibold">{{ number_format($c->agency_amount ?? 0, 2) }}</td>
                        <td class="fw-semibold">{{ number_format($c->agent_amount ?? $c->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection

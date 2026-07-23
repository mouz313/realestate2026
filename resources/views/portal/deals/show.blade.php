@extends('portal.layouts.app')

@section('title', $deal->deal_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $deal->deal_number }}</h4>
    <a href="{{ route('portal.deals') }}" class="btn btn-outline-secondary btn-sm">&larr; Back</a>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5>Deal Information</h5>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-secondary">Type</td>
                        <td class="fw-semibold">{{ ucfirst($deal->type) }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Status</td>
                        <td>
                            @php
                                $ds = ['pending' => 'warning', 'active' => 'info', 'completed' => 'success', 'cancelled' => 'danger', 'on_hold' => 'secondary'];
                            @endphp
                            <span class="badge bg-{{ $ds[$deal->status] ?? 'secondary' }}">{{ ucfirst($deal->status) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Sale Price</td>
                        <td class="fw-bold fs-5">{{ number_format($deal->sale_price, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Agreement Date</td>
                        <td>{{ $deal->agreement_date ? $deal->agreement_date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-secondary">Possession Date</td>
                        <td>{{ $deal->possession_date ? $deal->possession_date->format('d M Y') : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($deal->tokens->count() > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="ti ti-coin me-1"></i> Tokens</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Received Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deal->tokens as $token)
                        <tr>
                            <td class="fw-semibold">{{ number_format($token->amount, 2) }}</td>
                            <td>{{ $token->payment_method ?? '-' }}</td>
                            <td>{{ $token->received_date ? $token->received_date->format('d M Y') : '-' }}</td>
                            <td>
                                @php $ts = ['received' => 'success', 'refunded' => 'danger']; @endphp
                                <span class="badge bg-{{ $ts[$token->status] ?? 'secondary' }}">{{ ucfirst($token->status) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($deal->installmentPlan)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="ti ti-calendar-stats me-1"></i> Installment Plan</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Due Date</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">Paid</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deal->installmentPlan->installments as $inst)
                        <tr>
                            <td>{{ $inst->installment_no }}</td>
                            <td>{{ $inst->due_date->format('d M Y') }}</td>
                            <td class="text-end">{{ number_format($inst->amount, 2) }}</td>
                            <td class="text-end">{{ number_format($inst->paid_amount ?? 0, 2) }}</td>
                            <td>
                                @php
                                    $ist = ['pending' => 'warning', 'partial' => 'info', 'paid' => 'success', 'overdue' => 'danger'];
                                @endphp
                                <span class="badge bg-{{ $ist[$inst->status] ?? 'secondary' }}">{{ ucfirst($inst->status) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($deal->invoices->count() > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="ti ti-receipt me-1"></i> Invoices</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Number</th>
                            <th>Date</th>
                            <th class="text-end">Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deal->invoices as $inv)
                        <tr>
                            <td>{{ $inv->invoice_number }}</td>
                            <td>{{ $inv->created_at->format('d M Y') }}</td>
                            <td class="text-end">{{ number_format($inv->total, 2) }}</td>
                            <td>
                                @php $is = ['pending' => 'warning', 'partial' => 'info', 'paid' => 'success']; @endphp
                                <span class="badge bg-{{ $is[$inv->payment_status] ?? 'warning' }}">{{ ucfirst($inv->payment_status) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($deal->commissions->count() > 0)
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="ti ti-percentage me-1"></i> Commissions</h5>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Agent</th>
                            <th>Type</th>
                            <th class="text-end">Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deal->commissions as $comm)
                        <tr>
                            <td>{{ $comm->agent->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($comm->type) }}</td>
                            <td class="text-end">{{ number_format($comm->amount, 2) }}</td>
                            <td>
                                @php $cs = ['pending' => 'warning', 'paid' => 'success']; @endphp
                                <span class="badge bg-{{ $cs[$comm->status] ?? 'secondary' }}">{{ ucfirst($comm->status) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-5">
        @if($deal->property)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5>Property</h5>
                <p class="mb-1 fw-semibold">{{ $deal->property->title }}</p>
                <p class="mb-0 small text-secondary">{{ $deal->property->city ?? '' }}{{ $deal->property->sector_town ? ', ' . $deal->property->sector_town : '' }}</p>
                <a href="{{ route('portal.properties.show', $deal->property) }}" class="btn btn-sm btn-outline-dark mt-2">View Property</a>
            </div>
        </div>
        @endif

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5>Parties</h5>
                <div class="mb-2">
                    <span class="text-secondary small">Buyer</span>
                    <p class="mb-0 fw-semibold">{{ $deal->buyer->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-secondary small">Seller</span>
                    <p class="mb-0 fw-semibold">{{ $deal->seller->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5>Agents</h5>
                <div class="mb-2">
                    <span class="text-secondary small">Main Agent</span>
                    <p class="mb-0 fw-semibold">{{ $deal->agent->name ?? 'N/A' }}</p>
                    @if($deal->agent)
                    <small class="text-secondary">{{ $deal->agent->phone ?? '' }}</small>
                    @endif
                </div>
                @if($deal->coAgent)
                <div>
                    <span class="text-secondary small">Co-Agent</span>
                    <p class="mb-0 fw-semibold">{{ $deal->coAgent->name }}</p>
                    <small class="text-secondary">{{ $deal->coAgent->phone ?? '' }}</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

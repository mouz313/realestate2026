@extends('layouts.admin')

@section('title', 'Deal Details <span class="urdu">(ڈیل کی تفصیلات)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('deals.index') }}" class="text-decoration-none">Deals <span class="urdu">(ڈیلز)</span></a></li>
        <li class="breadcrumb-item active">{{ $deal->deal_number }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <h3>Deal <span class="urdu">(ڈیل)</span> {{ $deal->deal_number }}</h3>
    <div class="page-header-sub">
        <span class="badge status-{{ $deal->status ?? 'pending' }} fs-6 me-2">{{ ucfirst($deal->status ?? 'pending') }}</span>
    </div>
    <div class="action-btns">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown">
                <i class="ti ti-file-download"></i> PDF <span class="urdu">(پی ڈی ایف)</span>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('pdf.sale-agreement', $deal) }}">Sale Agreement <span class="urdu">(سیل ایگریمنٹ)</span></a></li>
                <li><a class="dropdown-item" href="{{ route('pdf.token-receipt', $deal) }}">Token Receipt <span class="urdu">(ٹوکن رسید)</span></a></li>
                <li><a class="dropdown-item" href="{{ route('pdf.possession-letter', $deal) }}">Possession Letter <span class="urdu">(قبضے کا خط)</span></a></li>
            </ul>
        </div>
        <a href="{{ \App\Helpers\WhatsApp::shareLink($settings['business_phone'] ?? '', \App\Helpers\WhatsApp::dealUpdateMessage($deal)) }}" target="_blank" class="btn btn-success me-2">
            <i class="ti ti-brand-whatsapp"></i> WhatsApp <span class="urdu">(واٹس ایپ)</span>
        </a>
        <a href="{{ route('deals.edit', $deal) }}" class="btn btn-dark">
            <i class="ti ti-edit"></i> <span class="urdu">(ڈیل میں ترمیم کریں)</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-file-description me-1"></i> Deal Information <span class="urdu">(ڈیل کی معلومات)</span></h5>
            </div>
            <div class="card-body">
                <table class="detail-table">
                    <tr>
                        <th>Deal Number <span class="urdu">(ڈیل نمبر)</span></th>
                        <td>{{ $deal->deal_number }}</td>
                    </tr>
                    <tr>
                        <th>Type <span class="urdu">(قسم)</span></th>
                        <td>{{ ucfirst($deal->type ?? '-') }}</td>
                    </tr>
                    <tr>
                        <th>Status <span class="urdu">(کیفیت)</span></th>
                        <td>
                            <span class="badge status-{{ $deal->status ?? 'pending' }}">{{ ucfirst($deal->status ?? 'pending') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Sale Price <span class="urdu">(فروخت قیمت)</span></th>
                        <td class="fw-semibold">{{ number_format($deal->sale_price, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Commission Percentage <span class="urdu">(کمیشن فیصد)</span></th>
                        <td>{{ $deal->commission_percentage ? $deal->commission_percentage . '%' : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Commission Amount <span class="urdu">(کمیشن رقم)</span></th>
                        <td class="text-success fw-semibold">
                            {{ $deal->commission_amount ? number_format($deal->commission_amount, 2) : ($deal->commission_percentage ? number_format($deal->sale_price * $deal->commission_percentage / 100, 2) : '-') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Token Amount <span class="urdu">(ٹوکن رقم)</span></th>
                        <td>{{ $deal->token_amount ? number_format($deal->token_amount, 2) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Token Date <span class="urdu">(ٹوکن تاریخ)</span></th>
                        <td>{{ $deal->token_date ? $deal->token_date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Agreement Date <span class="urdu">(معاہدے کی تاریخ)</span></th>
                        <td>{{ $deal->agreement_date ? $deal->agreement_date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Possession Date <span class="urdu">(قبضے کی تاریخ)</span></th>
                        <td>{{ $deal->possession_date ? $deal->possession_date->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Payment Plan <span class="urdu">(ادائیگی کا منصوبہ)</span></th>
                        <td><pre class="mb-0 small">{{ $deal->payment_plan ?? '-' }}</pre></td>
                    </tr>
                    <tr>
                        <th>Notes <span class="urdu">(نوٹس)</span></th>
                        <td>{{ $deal->notes ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-building me-1"></i> Property <span class="urdu">(جائیداد)</span></h5>
            </div>
            <div class="card-body">
                @if($deal->property)
                <table class="detail-table">
                    <tr>
                        <th>Title <span class="urdu">(عنوان)</span></th>
                        <td><a href="{{ route('properties.show', $deal->property) }}" class="text-decoration-none">{{ $deal->property->title }}</a></td>
                    </tr>
                    <tr>
                        <th>Code <span class="urdu">(کوڈ)</span></th>
                        <td>{{ $deal->property->property_code ?? $deal->property->id }}</td>
                    </tr>
                    <tr>
                        <th>Type <span class="urdu">(قسم)</span></th>
                        <td>{{ ucfirst($deal->property->type ?? '-') }}</td>
                    </tr>
                    <tr>
                        <th>Price <span class="urdu">(قیمت)</span></th>
                        <td>{{ number_format($deal->property->price, 2) }}</td>
                    </tr>
                    <tr>
                        <th>City <span class="urdu">(شہر)</span></th>
                        <td>{{ $deal->property->city ?? '-' }}</td>
                    </tr>
                </table>
                @else
                <div class="empty-state">
                    <i class="ti ti-building"></i>
                    <span><span class="urdu">(کوئی جائیداد منسلک نہیں)</span></span>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="ti ti-users me-1"></i> Clients <span class="urdu">(کلائنٹس)</span></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <strong>Buyer <span class="urdu">(خریدار)</span></strong>
                        <p class="mb-0">
                            @if($deal->buyer)
                                <a href="{{ route('clients.show', $deal->buyer) }}" class="text-decoration-none">{{ $deal->buyer->name }}</a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-6">
                        <strong>Seller <span class="urdu">(فروخت کنندہ)</span></strong>
                        <p class="mb-0">
                            @if($deal->seller)
                                <a href="{{ route('clients.show', $deal->seller) }}" class="text-decoration-none">{{ $deal->seller->name }}</a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="ti ti-user-check me-1"></i> Agents <span class="urdu">(ایجنٹس)</span></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <strong>Agent <span class="urdu">(ایجنٹ)</span></strong>
                        <p class="mb-0">
                            @if($deal->agent)
                                <a href="{{ route('agents.show', $deal->agent) }}" class="text-decoration-none">{{ $deal->agent->name }}</a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-6">
                        <strong>Co-Agent <span class="urdu">(کو-ایجنٹ)</span></strong>
                        <p class="mb-0">
                            @if($deal->coAgent)
                                <a href="{{ route('agents.show', $deal->coAgent) }}" class="text-decoration-none">{{ $deal->coAgent->name }}</a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tokens --}}
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="ti ti-coin me-1"></i> Tokens <span class="urdu">(ٹوکنز)</span></h5>
        <a href="{{ route('tokens.create', ['deal_id' => $deal->id]) }}" class="btn btn-sm btn-dark"><i class="ti ti-plus"></i> <span class="urdu">(ٹوکن شامل کریں)</span></a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount <span class="urdu">(رقم)</span></th>
                        <th>Method <span class="urdu">(طریقہ)</span></th>
                        <th>Reference <span class="urdu">(حوالہ)</span></th>
                        <th>Date <span class="urdu">(تاریخ)</span></th>
                        <th>Status <span class="urdu">(کیفیت)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deal->tokens ?? [] as $token)
                    <tr>
                        <td>{{ $token->id }}</td>
                        <td>{{ number_format($token->amount, 2) }}</td>
                        <td>{{ $token->payment_method ?? '-' }}</td>
                        <td>{{ $token->reference_no ?? '-' }}</td>
                        <td>{{ $token->received_date ? $token->received_date->format('d M Y') : '-' }}</td>
                        <td>
                            <span class="badge status-{{ $token->status ?? 'pending' }}">{{ ucfirst($token->status ?? 'pending') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="ti ti-coin"></i><span><span class="urdu">(اس ڈیل کے لیے کوئی ٹوکن نہیں)</span></span></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Invoices --}}
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="ti ti-file-invoice me-1"></i> Invoices <span class="urdu">(انوائسز)</span></h5>
        <a href="{{ route('invoices.create', ['deal_id' => $deal->id]) }}" class="btn btn-sm btn-dark"><i class="ti ti-plus"></i> <span class="urdu">(انوائس شامل کریں)</span></a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Invoice # <span class="urdu">(انوائس نمبر)</span></th>
                        <th>Amount <span class="urdu">(رقم)</span></th>
                        <th>Status <span class="urdu">(کیفیت)</span></th>
                        <th>Date <span class="urdu">(تاریخ)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deal->invoices ?? [] as $invoice)
                    <tr>
                        <td><a href="{{ route('invoices.show', $invoice) }}" class="text-decoration-none">{{ $invoice->invoice_number }}</a></td>
                        <td>{{ number_format($invoice->total, 2) }}</td>
                        <td>
                            <span class="badge status-{{ $invoice->payment_status ?? 'draft' }}">{{ ucfirst($invoice->payment_status ?? 'draft') }}</span>
                        </td>
                        <td>{{ $invoice->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4"><div class="empty-state"><i class="ti ti-file-invoice"></i><span><span class="urdu">(اس ڈیل کے لیے کوئی انوائس نہیں)</span></span></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Commissions --}}
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="ti ti-currency-dollar me-1"></i> Commissions <span class="urdu">(کمیشنز)</span></h5>
        <a href="{{ route('commissions.create', ['deal_id' => $deal->id]) }}" class="btn btn-sm btn-dark"><i class="ti ti-plus"></i> <span class="urdu">(کمیشن شامل کریں)</span></a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Agent <span class="urdu">(ایجنٹ)</span></th>
                        <th>Type <span class="urdu">(قسم)</span></th>
                        <th>Percentage <span class="urdu">(فیصد)</span></th>
                        <th>Amount <span class="urdu">(رقم)</span></th>
                        <th>Status <span class="urdu">(کیفیت)</span></th>
                        <th>Paid Date <span class="urdu">(ادائیگی کی تاریخ)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deal->commissions ?? [] as $commission)
                    <tr>
                        <td>{{ $commission->agent->name ?? '-' }}</td>
                        <td>{{ ucfirst($commission->type ?? '-') }}</td>
                        <td>{{ $commission->percentage ? $commission->percentage . '%' : '-' }}</td>
                        <td class="fw-semibold">{{ number_format($commission->amount, 2) }}</td>
                        <td>
                            <span class="badge status-{{ $commission->status ?? 'pending' }}">{{ ucfirst($commission->status ?? 'pending') }}</span>
                        </td>
                        <td>{{ $commission->paid_date ? $commission->paid_date->format('d M Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="ti ti-currency-dollar"></i><span><span class="urdu">(اس ڈیل کے لیے کوئی کمیشن نہیں)</span></span></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Installment Plan --}}
@if(($deal->installmentPlan ?? null) || ($deal->installments ?? null)?->isNotEmpty())
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="ti ti-calendar-stats me-1"></i> Installment Plan <span class="urdu">(قسط کا منصوبہ)</span></h5>
        <a href="{{ route('installments.create', ['deal_id' => $deal->id]) }}" class="btn btn-sm btn-dark"><i class="ti ti-plus"></i> <span class="urdu">(قسط شامل کریں)</span></a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount <span class="urdu">(رقم)</span></th>
                        <th>Paid Amount <span class="urdu">(ادا شدہ رقم)</span></th>
                        <th>Due Date <span class="urdu">(واجب الادا تاریخ)</span></th>
                        <th>Status <span class="urdu">(کیفیت)</span></th>
                        <th>Late Fee <span class="urdu">(تاخیری فیس)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($deal->installments ?? $deal->installmentPlan?->installments ?? []) as $installment)
                    <tr>
                        <td>{{ $installment->installment_no ?? $loop->iteration }}</td>
                        <td>{{ number_format($installment->amount, 2) }}</td>
                        <td>{{ number_format($installment->paid_amount ?? 0, 2) }}</td>
                        <td>{{ $installment->due_date ? $installment->due_date->format('d M Y') : '-' }}</td>
                        <td>
                            <span class="badge status-{{ $installment->status ?? 'pending' }}">{{ ucfirst($installment->status ?? 'pending') }}</span>
                        </td>
                        <td>{{ $installment->late_fee ? number_format($installment->late_fee, 2) : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="ti ti-calendar-stats"></i><span><span class="urdu">(اس ڈیل کے لیے کوئی قسط نہیں)</span></span></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection

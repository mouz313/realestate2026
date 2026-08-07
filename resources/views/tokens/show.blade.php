@extends('layouts.admin')

@section('title', 'Token Details <span class="urdu">(ٹوکن کی تفصیلات)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('tokens.index') }}" class="text-decoration-none">Tokens <span class="urdu">(ٹوکنز)</span></a></li>
        <li class="breadcrumb-item active">#{{ $token->id }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <h3>Token <span class="urdu">(ٹوکن)</span> #{{ $token->id }}</h3>
    <div class="page-header-sub">
        @php $sc = ['received' => 'status-paid', 'pending' => 'status-pending', 'cancelled' => 'status-cancelled']; @endphp
        <span class="badge {{ $sc[$token->status] ?? 'status-pending' }} fs-6">{{ ucfirst($token->status ?? 'pending') }}</span>
    </div>
    <div class="action-btns">
        <a href="{{ route('tokens.edit', $token) }}" class="btn btn-dark">
            <i class="ti ti-edit"></i> <span class="urdu">(ترمیم کریں)</span>
        </a>
        <form action="{{ route('tokens.destroy', $token) }}" method="POST" onsubmit="return confirm('Delete this token?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="ti ti-trash"></i> <span class="urdu">(حذف کریں)</span>
            </button>
        </form>
        <a href="{{ route('tokens.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> <span class="urdu">(واپس)</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-coin me-1"></i> Token Information <span class="urdu">(ٹوکن کی معلومات)</span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="detail-table table">
                        <tr>
                            <th>ID <span class="urdu">(شناخت)</span></th>
                            <td class="fw-semibold">#{{ $token->id }}</td>
                        </tr>
                        <tr>
                            <th>Deal <span class="urdu">(ڈیل)</span></th>
                            <td>
                                @if($token->deal)
                                    <a href="{{ route('deals.show', $token->deal) }}" class="text-decoration-none fw-medium">{{ $token->deal->deal_number }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Amount <span class="urdu">(رقم)</span></th>
                            <td class="fw-semibold">Rs. {{ number_format($token->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method <span class="urdu">(ادائیگی کا طریقہ)</span></th>
                            <td>{{ $token->payment_method ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Reference <span class="urdu">(حوالہ)</span></th>
                            <td>{{ $token->reference_no ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Received Date <span class="urdu">(وصولی کی تاریخ)</span></th>
                            <td>{{ $token->received_date ? $token->received_date->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status <span class="urdu">(کیفیت)</span></th>
                            <td>
                                <span class="badge {{ $sc[$token->status] ?? 'status-pending' }}">{{ ucfirst($token->status ?? 'pending') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created <span class="urdu">(تخلیق)</span></th>
                            <td>{{ $token->created_at ? $token->created_at->format('d M Y h:i A') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-refund-2 me-1"></i> Refund / Notes <span class="urdu">(رقم کی واپسی / نوٹس)</span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="detail-table table">
                        <tr>
                            <th>Refund Date <span class="urdu">(واپسی کی تاریخ)</span></th>
                            <td>{{ $token->refund_date ? $token->refund_date->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Refund Reason <span class="urdu">(واپسی کی وجہ)</span></th>
                            <td>{{ $token->refund_reason ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="mt-3">
                    <h6 class="fw-semibold mb-2">Notes <span class="urdu">(نوٹس)</span></h6>
                    <p class="text-secondary mb-0">{{ $token->notes ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

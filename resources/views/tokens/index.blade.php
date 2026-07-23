@extends('layouts.admin')

@section('title', 'Tokens <span class="urdu">(ٹوکنز)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Tokens <span class="urdu">(ٹوکنز)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>Tokens <span class="urdu">(ٹوکنز)</span></h3>
        <div class="page-header-sub">{{ $tokens->total() }} <span class="urdu">(کل ٹوکنز)</span></div>
    </div>
    <a href="{{ route('tokens.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> <span class="urdu">(ٹوکن شامل کریں)</span>
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID <span class="urdu">(شناخت)</span></th>
                    <th>Deal # <span class="urdu">(ڈیل نمبر)</span></th>
                    <th>Amount <span class="urdu">(رقم)</span></th>
                    <th class="d-none d-sm-table-cell">Payment Method <span class="urdu">(ادائیگی کا طریقہ)</span></th>
                    <th class="d-none d-md-table-cell">Reference <span class="urdu">(حوالہ)</span></th>
                    <th>Date <span class="urdu">(تاریخ)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($tokens as $token)
                <tr>
                    <td class="fw-semibold">{{ $token->id }}</td>
                    <td><a href="{{ route('deals.show', $token->deal) }}" class="text-decoration-none fw-medium">{{ $token->deal->deal_number ?? '#' }}</a></td>
                    <td class="fw-medium">{{ number_format($token->amount, 0) }}</td>
                    <td class="d-none d-sm-table-cell">{{ $token->payment_method ?? '-' }}</td>
                    <td class="d-none d-md-table-cell text-secondary">{{ $token->reference_no ?? '-' }}</td>
                    <td class="text-secondary">{{ $token->received_date ? $token->received_date->format('d M Y') : '-' }}</td>
                    <td>
                        @php $sc = ['received' => 'status-paid', 'pending' => 'status-pending', 'cancelled' => 'status-cancelled']; @endphp
                        <span class="badge {{ $sc[$token->status] ?? 'status-pending' }}">{{ ucfirst($token->status ?? 'pending') }}</span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('tokens.edit', $token) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('tokens.destroy', $token) }}" method="POST" onsubmit="return confirm('Delete this token?')">
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
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="ti ti-coin"></i>
                            <p>No tokens yet. <span class="urdu">(ابھی تک کوئی ٹوکن نہیں)</span></p>
                            <a href="{{ route('tokens.create') }}" class="text-decoration-none fw-medium"><span class="urdu">(اپنا پہلا ٹوکن شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tokens->hasPages())
    <div class="p-3 border-top">
        {{ $tokens->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
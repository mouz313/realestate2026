@extends('layouts.admin')

@section('title', 'Deals <span class="urdu">(ڈیلز)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Deals <span class="urdu">(ڈیلز)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>Deals <span class="urdu">(ڈیلز)</span></h3>
        <div class="page-header-sub">{{ $deals->total() }} <span class="urdu">(کل ڈیلز)</span></div>
    </div>
    <a href="{{ route('deals.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> <span class="urdu">(ڈیل شامل کریں)</span>
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Deal # <span class="urdu">(ڈیل نمبر)</span></th>
                    <th>Type <span class="urdu">(قسم)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th>Property <span class="urdu">(جائیداد)</span></th>
                    <th>Buyer <span class="urdu">(خریدار)</span></th>
                    <th class="d-none d-md-table-cell">Seller <span class="urdu">(فروخت کنندہ)</span></th>
                    <th class="d-none d-md-table-cell">Agent <span class="urdu">(ایجنٹ)</span></th>
                    <th>Sale Price <span class="urdu">(فروخت قیمت)</span></th>
                    <th class="d-none d-md-table-cell">Commission <span class="urdu">(کمیشن)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($deals as $deal)
                <tr>
                    <td class="fw-semibold">{{ $deal->deal_number }}</td>
                    <td>{{ ucfirst($deal->type ?? '-') }}</td>
                    <td>
                        @php $sc = ['pending' => 'status-pending', 'active' => 'status-active', 'completed' => 'status-completed', 'cancelled' => 'status-cancelled']; @endphp
                        <span class="badge {{ $sc[$deal->status] ?? 'status-pending' }}">{{ ucfirst($deal->status ?? 'pending') }}</span>
                    </td>
                    <td>{{ $deal->property->title ?? '-' }}</td>
                    <td>{{ $deal->buyer->name ?? '-' }}</td>
                    <td class="d-none d-md-table-cell">{{ $deal->seller->name ?? '-' }}</td>
                    <td class="d-none d-md-table-cell">{{ $deal->agent->name ?? '-' }}</td>
                    <td class="fw-medium">{{ number_format($deal->sale_price, 0) }}</td>
                    <td class="d-none d-md-table-cell">{{ $deal->commission_amount ? number_format($deal->commission_amount, 0) : ($deal->commission_percentage ? $deal->commission_percentage . '%' : '-') }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('deals.show', $deal) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('deals.edit', $deal) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('Delete this deal?')">
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
                    <td colspan="10">
                        <div class="empty-state">
                            <i class="ti ti-handshake"></i>
                            <p>No deals yet. <span class="urdu">(ابھی تک کوئی ڈیل نہیں)</span></p>
                            <a href="{{ route('deals.create') }}" class="text-decoration-none fw-medium"><span class="urdu">(اپنی پہلی ڈیل شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($deals->hasPages())
    <div class="p-3 border-top">
        {{ $deals->links() }}
    </div>
    @endif
</div>
@endsection
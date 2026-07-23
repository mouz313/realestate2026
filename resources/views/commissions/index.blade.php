@extends('layouts.admin')

@section('title', 'Commissions <span class="urdu">(کمیشنز)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Commissions <span class="urdu">(کمیشنز)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Commissions <span class="urdu">(کمیشنز)</span></h3>
        <div class="page-header-sub">{{ $commissions->total() }} <span class="urdu">(کل)</span> commissions <span class="urdu">(کمیشنز)</span></div>
    </div>
    <a href="{{ route('commissions.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add Commission <span class="urdu">(نیا کمیشن)</span>
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Deal # <span class="urdu">(ڈیل نمبر)</span></th>
                    <th>Agent <span class="urdu">(ایجنٹ)</span></th>
                    <th class="d-none d-sm-table-cell">Type <span class="urdu">(قسم)</span></th>
                    <th class="d-none d-sm-table-cell">Percentage <span class="urdu">(فیصد)</span></th>
                    <th>Amount <span class="urdu">(رقم)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="d-none d-md-table-cell">Paid Date <span class="urdu">(ادائیگی کی تاریخ)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($commissions as $commission)
                <tr>
                    <td class="fw-semibold">
                        @if($commission->deal)
                            <a href="{{ route('deals.show', $commission->deal) }}" class="text-decoration-none">{{ $commission->deal->deal_number }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $commission->agent->name ?? '-' }}</td>
                    <td class="d-none d-sm-table-cell">{{ ucfirst($commission->type ?? '-') }}</td>
                    <td class="d-none d-sm-table-cell">{{ $commission->percentage ? $commission->percentage . '%' : '-' }}</td>
                    <td class="fw-semibold">{{ number_format($commission->amount, 0) }}</td>
                    <td>
                        @php $sc = ['pending' => 'status-pending', 'approved' => 'status-active', 'paid' => 'status-paid', 'cancelled' => 'status-cancelled']; @endphp
                        <span class="badge {{ $sc[$commission->status] ?? 'status-pending' }}">{{ ucfirst($commission->status ?? 'pending') }}</span>
                    </td>
                    <td class="text-secondary d-none d-md-table-cell">{{ $commission->paid_date ? $commission->paid_date->format('d M Y') : '-' }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('pdf.commission-invoice', $commission) }}" class="btn btn-sm btn-outline-secondary" title="Download Invoice">
                                <i class="ti ti-file-download"></i>
                            </a>
                            @if(in_array($commission->status, ['pending', 'approved']))
                            <form action="{{ route('commissions.markPaid', $commission) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Mark Paid" onclick="return confirm('Mark this commission as paid?')">
                                    <i class="ti ti-check"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('commissions.edit', $commission) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('commissions.destroy', $commission) }}" method="POST" onsubmit="return confirm('Delete this commission?')">
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
                            <i class="ti ti-percentage"></i>
                            <p>No commissions yet. <span class="urdu">(کوئی کمیشن نہیں)</span></p>
                            <a href="{{ route('commissions.create') }}" class="text-decoration-none fw-medium">Add your first commission <span class="urdu">(اپنا پہلا کمیشن شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($commissions->hasPages())
    <div class="p-3 border-top">
        {{ $commissions->links() }}
    </div>
    @endif
</div>
@endsection

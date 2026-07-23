@extends('layouts.admin')

@section('title', 'Rent Agreements <span class="urdu">(کرایہ نامہ)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Rent Agreements <span class="urdu">(کرایہ نامہ)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Rent Agreements <span class="urdu">(کرایہ نامہ)</span></h3>
        <div class="page-header-sub">{{ $rentAgreements->total() }} <span class="urdu">(کل)</span> <span class="urdu">(کرایہ نامہ)</span></div>
    </div>
    <a href="{{ route('rent-agreements.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add Rent Agreement <span class="urdu">(نیا کرایہ نامہ)</span>
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="d-none d-sm-table-cell">ID <span class="urdu">(شناخت)</span></th>
                    <th>Property <span class="urdu">(جائیداد)</span></th>
                    <th>Tenant <span class="urdu">(کرایہ دار)</span></th>
                    <th>Rent Amount <span class="urdu">(کرایہ کی رقم)</span></th>
                    <th class="d-none d-md-table-cell">Start / End <span class="urdu">(شروع / ختم)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="d-none d-md-table-cell">Deposit <span class="urdu">(ڈپازٹ)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($rentAgreements as $agreement)
                <tr>
                    <td class="fw-semibold d-none d-sm-table-cell">{{ $agreement->id }}</td>
                    <td>{{ $agreement->property->title ?? '-' }}</td>
                    <td>{{ $agreement->tenant->name ?? '-' }}</td>
                    <td class="fw-medium">{{ number_format($agreement->rent_amount, 0) }}</td>
                    <td class="text-secondary d-none d-md-table-cell">{{ $agreement->start_date ? $agreement->start_date->format('d M Y') : '-' }} / {{ $agreement->end_date ? $agreement->end_date->format('d M Y') : 'Open' }}</td>
                    <td>
                        @php $sc = ['active' => 'status-active', 'expired' => 'status-draft', 'terminated' => 'status-cancelled', 'pending' => 'status-pending']; @endphp
                        <span class="badge {{ $sc[$agreement->status] ?? 'status-pending' }}">{{ ucfirst($agreement->status ?? 'pending') }}</span>
                    </td>
                    <td class="d-none d-md-table-cell">{{ $agreement->security_deposit ? number_format($agreement->security_deposit, 0) : '-' }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('rent-agreements.show', $agreement) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('rent-agreements.edit', $agreement) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('rent-agreements.destroy', $agreement) }}" method="POST" onsubmit="return confirm('Delete this agreement?')">
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
                            <i class="ti ti-home-2"></i>
                            <p>No rent agreements yet. <span class="urdu">(کوئی کرایہ نامہ نہیں)</span></p>
                            <a href="{{ route('rent-agreements.create') }}" class="text-decoration-none fw-medium">Add your first agreement <span class="urdu">(اپنا پہلا کرایہ نامہ شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($rentAgreements->hasPages())
    <div class="p-3 border-top">
        {{ $rentAgreements->links() }}
    </div>
    @endif
</div>
@endsection

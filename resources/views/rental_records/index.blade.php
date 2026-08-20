@extends('layouts.admin')

@section('title', 'Rental Records <span class="urdu">(کرایہ ریکارڈ)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Rental Records <span class="urdu">(کرایہ ریکارڈ)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Rental Records <span class="urdu">(کرایہ ریکارڈ)</span></h3>
        <div class="page-header-sub">{{ $records->total() }} <span class="urdu">(کل ریکارڈ)</span></div>
    </div>
    <a href="{{ route('rental-records.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add Rental Record <span class="urdu">(نیا ریکارڈ)</span>
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('rental-records.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Search <span class="urdu">(تلاش)</span></label>
                <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Property title or tenant name">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status <span class="urdu">(کیفیت)</span></label>
                <select name="status" class="form-select">
                    <option value="">All <span class="urdu">(تمام)</span></option>
                    <option value="active" @selected($status === 'active')>Active</option>
                    <option value="ended" @selected($status === 'ended')>Ended</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark w-100"><i class="ti ti-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Property <span class="urdu">(جائیداد)</span></th>
                    <th>Tenant <span class="urdu">(کرایہ دار)</span></th>
                    <th>Start <span class="urdu">(شروع)</span></th>
                    <th>End <span class="urdu">(اختتام)</span></th>
                    <th>Duration <span class="urdu">(دورانیہ)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr>
                    <td class="fw-medium">{{ $record->property->title ?? '-' }}</td>
                    <td>{{ $record->tenant->name ?? '-' }}</td>
                    <td>{{ $record->start_date ? $record->start_date->format('d M Y') : '-' }}</td>
                    <td>{{ $record->end_date ? $record->end_date->format('d M Y') : '-' }}</td>
                    <td>{{ $record->duration_months ? $record->duration_months.' mo' : '-' }}</td>
                    <td>
                        <span class="badge {{ $record->status === 'active' ? 'status-active' : 'status-cancelled' }}">{{ ucfirst($record->status) }}</span>
                    </td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            <a href="{{ route('rental-records.show', $record) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('rental-records.edit', $record) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('rental-records.destroy', $record) }}" method="POST" onsubmit="return confirm('Delete this rental record?')">
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
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="ti ti-home"></i>
                            <p>No rental records yet. <span class="urdu">(ابھی تک کوئی ریکارڈ نہیں)</span></p>
                            <a href="{{ route('rental-records.create') }}" class="text-decoration-none fw-medium">Add Rental Record <span class="urdu">(نیا ریکارڈ شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
    <div class="p-3 border-top">
        {{ $records->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

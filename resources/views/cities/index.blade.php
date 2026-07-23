@extends('layouts.admin')

@section('title', 'Cities <span class="urdu">(شہر)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Cities <span class="urdu">(شہر)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>Cities <span class="urdu">(شہر)</span></h3>
        <div class="page-header-sub">{{ $cities->total() }} <span class="urdu">(کل شہر)</span></div>
    </div>
    <a href="{{ route('cities.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add City <span class="urdu">(نیا شہر)</span>
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name <span class="urdu">(نام)</span></th>
                    <th class="d-none d-md-table-cell">Province <span class="urdu">(صوبہ)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($cities as $city)
                <tr>
                    <td class="fw-semibold">{{ $city->name }}</td>
                    <td class="d-none d-md-table-cell">{{ $city->province ?? '-' }}</td>
                    <td>
                        @php $sc = [1 => 'status-active', 0 => 'status-draft']; @endphp
                        <span class="badge {{ $sc[(int)$city->is_active] ?? 'status-draft' }}">{{ $city->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('cities.show', $city) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('cities.edit', $city) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('cities.destroy', $city) }}" method="POST" onsubmit="return confirm('Delete this city?')">
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
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="ti ti-building"></i>
                            <p>No cities yet. <span class="urdu">(کوئی شہر نہیں)</span></p>
                            <a href="{{ route('cities.create') }}" class="text-decoration-none fw-medium">Add your first city <span class="urdu">(اپنا پہلا شہر شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($cities->hasPages())
    <div class="p-3 border-top">
        {{ $cities->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

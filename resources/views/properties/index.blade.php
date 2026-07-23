@extends('layouts.admin')

@section('title', 'Properties <span class="urdu">(جائیدادیں)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Properties <span class="urdu">(جائیدادیں)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>Properties <span class="urdu">(جائیدادیں)</span></h3>
        <div class="page-header-sub">{{ $properties->total() }} <span class="urdu">(کل جائیدادیں)</span></div>
    </div>
    <a href="{{ route('properties.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> <span class="urdu">(جائیداد شامل کریں)</span>
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Code <span class="urdu">(کوڈ)</span></th>
                    <th>Title <span class="urdu">(عنوان)</span></th>
                    <th>Type <span class="urdu">(قسم)</span></th>
                    <th>Price <span class="urdu">(قیمت)</span></th>
                    <th>City <span class="urdu">(شہر)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="d-none d-md-table-cell">Owner <span class="urdu">(مالک)</span></th>
                    <th class="d-none d-md-table-cell">Agent <span class="urdu">(ایجنٹ)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($properties as $property)
                <tr>
                    <td class="fw-semibold text-secondary">{{ $property->property_code ?? $property->id }}</td>
                    <td class="fw-medium">{{ $property->title }}</td>
                    <td>{{ ucfirst($property->type ?? '-') }}</td>
                    <td class="fw-medium">{{ number_format($property->price, 0) }}</td>
                    <td>{{ $property->city ?? '-' }}</td>
                    <td>
                        @php $sc = ['available' => 'status-available', 'sold' => 'status-sold', 'rented' => 'status-rented', 'under_offer' => 'status-under_offer', 'pending' => 'status-pending', 'reserved' => 'status-reserved']; @endphp
                        <span class="badge {{ $sc[$property->status] ?? 'status-pending' }}">{{ ucfirst(str_replace('_', ' ', $property->status ?? 'available')) }}</span>
                    </td>
                    <td class="d-none d-md-table-cell">{{ $property->owner->name ?? '-' }}</td>
                    <td class="d-none d-md-table-cell">{{ $property->agent->name ?? '-' }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('properties.show', $property) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('properties.edit', $property) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Delete this property?')">
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
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="ti ti-building"></i>
                            <p>No properties yet. <span class="urdu">(ابھی تک کوئی جائیداد نہیں)</span></p>
                            <a href="{{ route('properties.create') }}" class="text-decoration-none fw-medium"><span class="urdu">(اپنی پہلی جائیداد شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($properties->hasPages())
    <div class="p-3 border-top">
        {{ $properties->links() }}
    </div>
    @endif
</div>
@endsection
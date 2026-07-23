@extends('layouts.admin')

@section('title', 'Items <span class="urdu">(آئٹمز)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Item Catalog <span class="urdu">(آئٹم کیٹلاگ)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>Item Catalog <span class="urdu">(آئٹم کیٹلاگ)</span></h3>
        <div class="page-header-sub">{{ $items->total() }} <span class="urdu">(کل آئٹمز)</span></div>
    </div>
    <a href="{{ route('items.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add Item <span class="urdu">(نیا آئٹم)</span>
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name <span class="urdu">(نام)</span></th>
                    <th class="d-none d-md-table-cell">Description <span class="urdu">(وضاحت)</span></th>
                    <th>Default Price <span class="urdu">(قیمت)</span></th>
                    <th class="d-none d-md-table-cell">Unit <span class="urdu">(یونٹ)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td class="fw-semibold">{{ $item->name }}</td>
                    <td class="text-secondary d-none d-md-table-cell">{{ Str::limit($item->description, 50) ?? '-' }}</td>
                    <td class="fw-medium">{{ number_format($item->default_price, 0) }}</td>
                    <td class="d-none d-md-table-cell">{{ $item->unit ?? '-' }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item?')">
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
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="ti ti-package"></i>
                            <p>No items yet. <span class="urdu">(کوئی آئٹم نہیں)</span></p>
                            <a href="{{ route('items.create') }}" class="text-decoration-none fw-medium">Add your first item <span class="urdu">(اپنا پہلا آئٹم شامل کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="p-3 border-top">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection

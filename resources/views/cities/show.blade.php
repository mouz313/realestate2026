@extends('layouts.admin')

@section('title', 'City Details <span class="urdu">(شہر کی تفصیلات)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('cities.index') }}" class="text-decoration-none">Cities <span class="urdu">(شہر)</span></a></li>
        <li class="breadcrumb-item active">{{ $city->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <h3>{{ $city->name }}</h3>
    <div class="page-header-sub">City Details <span class="urdu">(شہر کی تفصیلات)</span></div>
    <div class="action-btns">
        <a href="{{ route('cities.edit', $city) }}" class="btn btn-dark">
            <i class="ti ti-edit"></i> Edit City <span class="urdu">(شہر کی ترمیم)</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-building me-1"></i> City Information <span class="urdu">(شہر کی معلومات)</span></h5>
            </div>
            <div class="card-body">
                <table class="detail-table">
                    <tr><th>Name <span class="urdu">(نام)</span></th><td>{{ $city->name }}</td></tr>
                    <tr><th>Province <span class="urdu">(صوبہ)</span></th><td>{{ $city->province ?? '-' }}</td></tr>
                    <tr>
                        <th>Status <span class="urdu">(کیفیت)</span></th>
                        <td>
                            <span class="badge {{ $city->is_active ? 'status-active' : 'status-draft' }}">{{ $city->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                    </tr>
                    <tr><th>Created <span class="urdu">(تخلیق)</span></th><td>{{ $city->created_at->format('d M Y') }}</td></tr>
                    <tr><th>Last Updated <span class="urdu">(آخری اپ ڈیٹ)</span></th><td>{{ $city->updated_at->format('d M Y') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

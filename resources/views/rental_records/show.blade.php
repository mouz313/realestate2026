@extends('layouts.admin')

@section('title', 'Rental Record Details <span class="urdu">(ریکارڈ کی تفصیلات)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('rental-records.index') }}" class="text-decoration-none">Rental Records <span class="urdu">(کرایہ ریکارڈ)</span></a></li>
        <li class="breadcrumb-item active">{{ $rentalRecord->property->title ?? 'Record' }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h3>{{ $rentalRecord->property->title ?? 'Rental Record' }}</h3>
        <div class="page-header-sub">Rental Record #{{ $rentalRecord->id }}</div>
    </div>
    <div>
        <span class="badge {{ $rentalRecord->status === 'active' ? 'status-active' : 'status-cancelled' }}">{{ ucfirst($rentalRecord->status) }}</span>
        <a href="{{ route('rental-records.edit', $rentalRecord) }}" class="btn btn-dark ms-2">
            <i class="ti ti-edit"></i> <span class="urdu">(ترمیم)</span>
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-home me-1"></i> Rental Information <span class="urdu">(کرایہ کی معلومات)</span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="detail-table table">
                        <tr><th>Property <span class="urdu">(جائیداد)</span></th><td>@if($rentalRecord->property) <a href="{{ route('properties.show', $rentalRecord->property) }}" class="text-decoration-none">{{ $rentalRecord->property->title }}</a> @else - @endif</td></tr>
                        <tr><th>Tenant <span class="urdu">(کرایہ دار)</span></th><td>@if($rentalRecord->tenant) <a href="{{ route('clients.show', $rentalRecord->tenant) }}" class="text-decoration-none">{{ $rentalRecord->tenant->name }}</a> @else - @endif</td></tr>
                        <tr><th>Landlord <span class="urdu">(مالک)</span></th><td>@if($rentalRecord->landlord) <a href="{{ route('clients.show', $rentalRecord->landlord) }}" class="text-decoration-none">{{ $rentalRecord->landlord->name }}</a> @else - @endif</td></tr>
                        <tr><th>Created By <span class="urdu">(تخلیق کنندہ)</span></th><td>{{ $rentalRecord->creator->name ?? '-' }}</td></tr>
                        <tr><th>Start Date <span class="urdu">(شروع)</span></th><td>{{ $rentalRecord->start_date ? $rentalRecord->start_date->format('d M Y') : '-' }}</td></tr>
                        <tr><th>End Date <span class="urdu">(اختتام)</span></th><td>{{ $rentalRecord->end_date ? $rentalRecord->end_date->format('d M Y') : '-' }}</td></tr>
                        <tr><th>Duration <span class="urdu">(دورانیہ)</span></th><td>{{ $rentalRecord->duration_months ? $rentalRecord->duration_months.' months' : '-' }}</td></tr>
                        <tr><th>Status <span class="urdu">(کیفیت)</span></th><td><span class="badge {{ $rentalRecord->status === 'active' ? 'status-active' : 'status-cancelled' }}">{{ ucfirst($rentalRecord->status) }}</span></td></tr>
                        <tr><th>Last Verification Sent <span class="urdu">(آخری تصدیق)</span></th><td>{{ $rentalRecord->last_verification_sent_at ? $rentalRecord->last_verification_sent_at->format('d M Y H:i') : '-' }}</td></tr>
                        <tr><th>Reminders Sent <span class="urdu">(بھیجے گئے ریمائنڈر)</span></th><td>@if(!empty($rentalRecord->reminders_sent)) @foreach($rentalRecord->reminders_sent as $m => $d) <span class="badge bg-light text-dark me-1">{{ $m }} mo: {{ $d }}</span> @endforeach @else - @endif</td></tr>
                        <tr><th>Notes <span class="urdu">(نوٹس)</span></th><td>{{ $rentalRecord->notes ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('rental-records.index') }}" class="btn btn-link text-secondary text-decoration-none"><i class="ti ti-arrow-left"></i> Back to Rental Records <span class="urdu">(واپس)</span></a>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Property Visit Details <span class="urdu">(جائیداد کے دورے کی تفصیلات)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('property-visits.index') }}" class="text-decoration-none">Property Visits <span class="urdu">(جائیداد کے دورے)</span></a></li>
        <li class="breadcrumb-item active">#{{ $propertyVisit->id }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header">
    <h3>Property Visit <span class="urdu">(جائیداد کا دورہ)</span> #{{ $propertyVisit->id }}</h3>
    <div class="page-header-sub">
        @php $sc = \App\Helpers\Status::classes('property_visit'); @endphp
        <span class="badge {{ $sc[$propertyVisit->status] ?? 'status-active' }} fs-6">{{ ucfirst(str_replace('_', ' ', $propertyVisit->status ?? 'scheduled')) }}</span>
    </div>
    <div class="action-btns">
        <a href="{{ route('property-visits.edit', $propertyVisit) }}" class="btn btn-dark">
            <i class="ti ti-edit"></i> <span class="urdu">(ترمیم کریں)</span>
        </a>
        <form action="{{ route('property-visits.destroy', $propertyVisit) }}" method="POST" onsubmit="return confirm('Delete this visit?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="ti ti-trash"></i> <span class="urdu">(حذف کریں)</span>
            </button>
        </form>
        <a href="{{ route('property-visits.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> <span class="urdu">(واپس)</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-calendar-event me-1"></i> Visit Information <span class="urdu">(دورے کی معلومات)</span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="detail-table table">
                        <tr>
                            <th>Property <span class="urdu">(جائیداد)</span></th>
                            <td>
                                @if($propertyVisit->property)
                                    <a href="{{ route('properties.show', $propertyVisit->property) }}" class="text-decoration-none fw-medium">{{ $propertyVisit->property->title }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Client <span class="urdu">(گاہک)</span></th>
                            <td>
                                @if($propertyVisit->client)
                                    <a href="{{ route('clients.show', $propertyVisit->client) }}" class="text-decoration-none fw-medium">{{ $propertyVisit->client->name }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Agent <span class="urdu">(ایجنٹ)</span></th>
                            <td>
                                @if($propertyVisit->agent)
                                    <a href="{{ route('agents.show', $propertyVisit->agent) }}" class="text-decoration-none fw-medium">{{ $propertyVisit->agent->name }}</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Scheduled Date <span class="urdu">(طے شدہ تاریخ)</span></th>
                            <td class="fw-semibold">{{ $propertyVisit->scheduled_date ? $propertyVisit->scheduled_date->format('d M Y h:i A') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status <span class="urdu">(کیفیت)</span></th>
                            <td>
                                <span class="badge {{ $sc[$propertyVisit->status] ?? 'status-active' }}">{{ ucfirst(str_replace('_', ' ', $propertyVisit->status ?? 'scheduled')) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Rating <span class="urdu">(درجہ بندی)</span></th>
                            <td>{{ $propertyVisit->rating ? $propertyVisit->rating . ' / 5' : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-message-report me-1"></i> Feedback / Notes <span class="urdu">(رائے / نوٹس)</span></h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-semibold mb-2">Feedback <span class="urdu">(رائے)</span></h6>
                    <p class="text-secondary mb-0">{{ $propertyVisit->feedback ?? '-' }}</p>
                </div>
                <div>
                    <h6 class="fw-semibold mb-2">Notes <span class="urdu">(نوٹس)</span></h6>
                    <p class="text-secondary mb-0">{{ $propertyVisit->notes ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

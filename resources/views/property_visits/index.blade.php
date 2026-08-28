@extends('layouts.admin')

@section('title', 'Property Visits <span class="urdu">(جائیداد کے دورے)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Property Visits <span class="urdu">(جائیداد کے دورے)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Property Visits <span class="urdu">(جائیداد کے دورے)</span></h3>
        <div class="page-header-sub">{{ $propertyVisits->total() }} <span class="urdu">(کل)</span> visits <span class="urdu">(دورے)</span></div>
    </div>
    <a href="{{ route('property-visits.create') }}" class="btn btn-dark">
        <i class="ti ti-plus"></i> Add Visit <span class="urdu">(نیا دورہ)</span>
    </a>
</div>

@if(!empty($leads) && $leads->isNotEmpty())
<form method="GET" action="{{ route('property-visits.index') }}" class="mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-auto">
            <label class="form-label small mb-1">Lead <span class="urdu">(لیڈ)</span></label>
            <select name="call_log_id" class="form-select" onchange="this.form.submit()">
                <option value="">All <span class="urdu">(تمام)</span></option>
                @foreach($leads as $lead)
                    <option value="{{ $lead->id }}" {{ request('call_log_id') == $lead->id ? 'selected' : '' }}>{{ $lead->name }}</option>
                @endforeach
            </select>
        </div>
        @if(request('call_log_id'))
        <div class="col-auto">
            <a href="{{ route('property-visits.index') }}" class="btn btn-outline-secondary">Clear <span class="urdu">(کلیئر)</span></a>
        </div>
        @endif
    </div>
</form>
@endif

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Lead <span class="urdu">(لیڈ)</span></th>
                    <th>Property <span class="urdu">(جائیداد)</span></th>
                    <th>Client <span class="urdu">(گاہک)</span></th>
                    <th class="d-none d-sm-table-cell">Agent <span class="urdu">(ایجنٹ)</span></th>
                    <th>Scheduled <span class="urdu">(طے شدہ)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="d-none d-md-table-cell">Rating <span class="urdu">(درجہ بندی)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($propertyVisits as $visit)
                <tr>
                    <td>
                        @if($visit->callLog)
                            <a href="{{ route('call-logs.show', $visit->callLog) }}">{{ $visit->callLog->name }}</a>
                        @else
                            <span class="text-secondary">—</span>
                        @endif
                    </td>
                    <td class="fw-medium">{{ $visit->property->title ?? '-' }}</td>
                    <td>{{ $visit->client->name ?? '-' }}</td>
                    <td class="d-none d-sm-table-cell">{{ $visit->agent->name ?? '-' }}</td>
                    <td class="text-secondary">{{ $visit->scheduled_date ? $visit->scheduled_date->format('d M Y h:i A') : '-' }}</td>
                    <td>
                        @php $sc = \App\Helpers\Status::classes('property_visit'); @endphp
                        <span class="badge {{ $sc[$visit->status] ?? 'status-active' }}">{{ ucfirst(str_replace('_', ' ', $visit->status ?? 'scheduled')) }}</span>
                    </td>
                    <td class="d-none d-md-table-cell">{{ $visit->rating ? $visit->rating . ' / 5' : '-' }}</td>
                    <td class="text-end">
                        <div class="action-btns flex-nowrap">
                            <a href="{{ route('property-visits.edit', $visit) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="ti ti-edit"></i>
                            </a>
                            <form action="{{ route('property-visits.destroy', $visit) }}" method="POST" onsubmit="return confirm('Delete this visit?')">
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
                            <i class="ti ti-calendar-event"></i>
                            <p>No property visits yet. <span class="urdu">(کوئی جائیداد کا دورہ نہیں)</span></p>
                            <a href="{{ route('property-visits.create') }}" class="text-decoration-none fw-medium">Schedule your first visit <span class="urdu">(اپنا پہلا دورہ طے کریں)</span></a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($propertyVisits->hasPages())
    <div class="p-3 border-top">
        {{ $propertyVisits->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

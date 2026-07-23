@extends('portal.layouts.app')

@section('title', 'My Visits')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">My Visits</h4>
    <a href="{{ route('portal.visits.create') }}" class="btn btn-dark btn-sm"><i class="ti ti-plus"></i> Request Visit</a>
</div>

@if($visits->isEmpty())
<div class="card shadow">
    <div class="card-body text-center text-secondary py-5">
        <i class="ti ti-calendar-clock" style="font-size: 3rem;"></i>
        <p class="mt-2 mb-0">No visits scheduled yet.</p>
    </div>
</div>
@else
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Property</th>
                    <th>Agent</th>
                    <th>Scheduled Date/Time</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visits as $v)
                <tr>
                    <td>
                        <a href="{{ route('portal.properties.show', $v->property_id) }}" class="text-decoration-none fw-semibold">
                            {{ $v->property->title ?? 'N/A' }}
                        </a>
                    </td>
                    <td>{{ $v->agent->name ?? 'N/A' }}</td>
                    <td>{{ $v->scheduled_date->format('d M Y, h:i A') }}</td>
                    <td>
                        @php
                            $vs = ['scheduled' => 'primary', 'confirmed' => 'info', 'completed' => 'success', 'cancelled' => 'danger', 'rescheduled' => 'warning'];
                        @endphp
                        <span class="badge bg-{{ $vs[$v->status] ?? 'secondary' }}">{{ ucfirst($v->status) }}</span>
                    </td>
                    <td>{{ $v->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@if($visits->hasPages())
<div class="mt-3">{{ $visits->withQueryString()->links() }}</div>
@endif
@endif
@endsection

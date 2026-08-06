@extends('layouts.admin')

@section('title', 'Activity Log')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0"><i class="ti ti-history me-1"></i> Activity Log <span class="urdu">(سرگرمی کا ریکارڈ)</span></h4>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-sm-6 col-md-5">
                <label class="form-label small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Search activity...">
            </div>
            <div class="col-6 col-sm-3 col-md-3">
                <label class="form-label small">Event Type</label>
                <select name="event" class="form-select form-select-sm">
                    <option value="">All Events</option>
                    <option value="created" {{ request('event') === 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('event') === 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('event') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-dark btn-sm w-100"><i class="ti ti-filter"></i> Filter</button>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('admin.activity-log') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Event</th>
                        <th>Description</th>
                        <th>By</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $act)
                    <tr>
                        <td class="text-secondary small">{{ $act->created_at->format('d M Y H:i') }}</td>
                        <td>
                            @if($act->event === 'created')
                                <span class="badge bg-success">Created</span>
                            @elseif($act->event === 'updated')
                                <span class="badge bg-primary">Updated</span>
                            @elseif($act->event === 'deleted')
                                <span class="badge bg-danger">Deleted</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($act->event) }}</span>
                            @endif
                        </td>
                        <td>{{ $act->description ?? '-' }}</td>
                        <td class="text-secondary">{{ $act->causer?->name ?? 'System' }}</td>
                        <td>
                            @if($act->properties)
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#details{{ $act->id }}">
                                    <i class="ti ti-eye"></i>
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @if($act->properties)
                    <tr>
                        <td colspan="5" class="p-0">
                            <div class="collapse" id="details{{ $act->id }}">
                                <div class="px-3 py-2 bg-light small">
                                    @if(isset($act->properties['old']))
                                        <strong>Changed fields:</strong>
                                        <ul class="mb-0">
                                            @foreach($act->properties['new'] as $key => $val)
                                                <li><code>{{ $key }}</code>: {{ $act->properties['old'][$key] ?? '-' }} → {{ $val }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <pre class="mb-0" style="font-size:11px">{{ json_encode($act->properties, JSON_PRETTY_PRINT) }}</pre>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">
                            <i class="ti ti-history" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            No activities recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $activities->links() }}
    </div>
</div>
@endsection

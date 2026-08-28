@extends('layouts.admin')

@section('title', 'Call Logs <span class="urdu">(کال لاگ)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item active">Call Logs <span class="urdu">(کال لاگ)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<x-page-header
    title="Call Logs"
    :urdu="'کال لاگ'"
    :subtitle="$callLogs->total() . ' total'"
    icon="ti-phone-call">
    <a href="{{ route('call-logs.kanban') }}" class="btn btn-outline-dark">
        <i class="ti ti-layout-kanban"></i> Kanban <span class="urdu">(کنبان)</span>
    </a>
    <a href="{{ route('call-logs.create') }}" class="btn btn-dark">
        <i class="ti ti-phone-plus"></i> Log Call <span class="urdu">(کال درج کریں)</span>
    </a>
</x-page-header>

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('call-logs.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small mb-1">Search <span class="urdu">(تلاش)</span></label>
                <input type="text" name="search" class="form-control" placeholder="Name or phone" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Status <span class="urdu">(کیفیت)</span></label>
                <select name="status" class="form-select">
                    <option value="">All <span class="urdu">(تمام)</span></option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Lead Source <span class="urdu">(ذریعہ)</span></label>
                <select name="lead_source" class="form-select">
                    <option value="">All <span class="urdu">(تمام)</span></option>
                    @foreach($leadSources as $key => $label)
                        <option value="{{ $key }}" @selected(request('lead_source') == $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Category <span class="urdu">(زمرہ)</span></label>
                <select name="category" class="form-select">
                    <option value="">All <span class="urdu">(تمام)</span></option>
                    @foreach($categories as $c)
                        <option value="{{ $c }}" @selected(request('category') == $c)>{{ ucfirst(str_replace('_', ' ', $c)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Type <span class="urdu">(قسم)</span></label>
                <select name="transaction_type" class="form-select">
                    <option value="">All <span class="urdu">(تمام)</span></option>
                    @foreach($transactionTypes as $t)
                        <option value="{{ $t }}" @selected(request('transaction_type') == $t)>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="due" value="1" id="dueCheck" @checked(request('due') == 1)>
                    <label class="form-check-label small" for="dueCheck">Due <span class="urdu">(بقایا)</span></label>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-dark"><i class="ti ti-filter"></i> Filter <span class="urdu">(فلٹر)</span></button>
                <a href="{{ route('call-logs.index') }}" class="btn btn-link text-secondary text-decoration-none">Reset <span class="urdu">(ری سیٹ)</span></a>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name <span class="urdu">(نام)</span></th>
                    <th>Phone <span class="urdu">(فون)</span></th>
                    <th>Category <span class="urdu">(زمرہ)</span></th>
                    <th>Type <span class="urdu">(قسم)</span></th>
                    <th>City <span class="urdu">(شہر)</span></th>
                    <th>Budget <span class="urdu">(بجٹ)</span></th>
                    <th>Follow-up <span class="urdu">(فالو اپ)</span></th>
                    <th>Status <span class="urdu">(کیفیت)</span></th>
                    <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($callLogs as $call)
                <tr>
                    <td class="fw-medium">{{ $call->name }}</td>
                    <td>{{ $call->phone }}</td>
                    <td>{{ $call->category ? ucfirst(str_replace('_', ' ', $call->category)) : '-' }}</td>
                    <td>{{ $call->transaction_type ? ucfirst($call->transaction_type) : '-' }}</td>
                    <td>{{ $call->city ?? ($call->city_id ? $call->city_id : '-') }}</td>
                    <td class="fw-medium">
                        @if($call->budget_min || $call->budget_max)
                            {{ $call->budget_min ? number_format($call->budget_min, 0) : '0' }} - {{ $call->budget_max ? number_format($call->budget_max, 0) : '∞' }}
                        @else - @endif
                    </td>
                    <td>{{ $call->follow_up_date ? $call->follow_up_date->format('d M Y') : '-' }}</td>
                    <td>
                        <x-status-badge :status="$call->status" />
                    </td>
                    <td class="text-end">
                    <x-action-buttons
                        :show="['view','edit','delete','whatsapp']"
                        :whatsapp-phone="$call->phone"
                            :whatsapp-msg="'Assalam o Alaikum '.$call->name.', regarding your enquiry...'"
                            :view-url="route('call-logs.show', $call)"
                            :edit-url="route('call-logs.edit', $call)"
                            :delete-url="route('call-logs.destroy', $call)" />
                    </td>
                </tr>
                @empty
                <tr>
                    <x-empty-state
                        icon="ti-phone-call"
                        title="No call logs yet."
                        urdu="ابھی تک کوئی کال لاگ نہیں"
                        :action-url="route('call-logs.create')"
                        action-label="Log first call (پہلی کال درج کریں)"
                        colspan="9" />
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($callLogs->hasPages())
    <div class="p-3 border-top">
        {{ $callLogs->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

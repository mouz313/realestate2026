@extends('layouts.admin')

@section('title', 'Call Log Details <span class="urdu">(کال لاگ کی تفصیلات)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('call-logs.index') }}" class="text-decoration-none">Call Logs <span class="urdu">(کال لاگ)</span></a></li>
        <li class="breadcrumb-item active">{{ $callLog->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>{{ $callLog->name }}</h3>
        <div class="page-header-sub">{{ $callLog->phone }}</div>
    </div>
    <div class="d-flex gap-2">
        <x-status-badge :status="$callLog->status" size="md" />
        <a href="{{ \App\Helpers\WhatsApp::shareLink($callLog->phone, 'Assalam o Alaikum '.$callLog->name.', this is regarding your enquiry. How can we help you?') }}"
           target="_blank"
           class="btn btn-success"
           title="Chat on WhatsApp">
            <i class="ti ti-brand-whatsapp"></i> WhatsApp
        </a>
        @if($callLog->alternate_phone)
        <a href="{{ \App\Helpers\WhatsApp::shareLink($callLog->alternate_phone, 'Assalam o Alaikum '.$callLog->name.', this is regarding your enquiry.') }}"
           target="_blank"
           class="btn btn-outline-success"
           title="Chat on alternate WhatsApp">
            <i class="ti ti-brand-whatsapp"></i> Alt
        </a>
        @endif
        @if($callLog->deal)
        <a href="{{ route('deals.show', $callLog->deal) }}" class="btn btn-success">
            <i class="ti ti-building-store"></i> View Deal <span class="urdu">(ڈیل دیکھیں)</span>
        </a>
        @else
        <a href="{{ route('call-logs.convert', $callLog) }}" class="btn btn-success">
            <i class="ti ti-building-store"></i> Convert to Deal <span class="urdu">(ڈیل بنائیں)</span>
        </a>
        @endif
        @if(in_array($callLog->caller_role, [null, 'seller']))
        <a href="{{ route('call-logs.add-property', $callLog) }}" class="btn btn-outline-success">
            <i class="ti ti-home-plus"></i> Register Property <span class="urdu">(جائیداد درج کریں)</span>
        </a>
        @endif
        <a href="{{ route('call-logs.edit', $callLog) }}" class="btn btn-dark">
            <i class="ti ti-edit"></i> <span class="urdu">(ترمیم)</span>
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-phone me-1"></i> Call Information <span class="urdu">(کال کی معلومات)</span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="detail-table table">
                        <tr><th>Name <span class="urdu">(نام)</span></th><td>{{ $callLog->name }}</td></tr>
                        <tr><th>Phone <span class="urdu">(فون)</span></th><td>{{ $callLog->phone }}</td></tr>
                        <tr><th>Alternate Phone <span class="urdu">(متبادل فون)</span></th><td>{{ $callLog->alternate_phone ?? '-' }}</td></tr>
                        <tr><th>Lead Source <span class="urdu">(ذریعہ)</span></th><td>{{ $callLog->lead_source ? ucfirst(str_replace('_', ' ', $callLog->lead_source)) : '-' }}</td></tr>
                        <tr><th>Caller is <span class="urdu">(کال کرنے والا)</span></th><td>{{ $callLog->caller_role ? ucfirst($callLog->caller_role) : 'Unknown' }}</td></tr>
                        <tr><th>Category <span class="urdu">(زمرہ)</span></th><td>{{ $callLog->category ? ucfirst(str_replace('_', ' ', $callLog->category)) : '-' }}</td></tr>
                        <tr><th>Transaction Type <span class="urdu">(قسم)</span></th><td>{{ $callLog->transaction_type ? ucfirst($callLog->transaction_type) : '-' }}</td></tr>
                        <tr><th>City <span class="urdu">(شہر)</span></th><td>{{ $callLog->city ?? ($callLog->city_id ?: '-') }}</td></tr>
                        <tr><th>Location <span class="urdu">(مقام)</span></th><td>{{ $callLog->location ?? '-' }}</td></tr>
                        <tr><th>Bedrooms <span class="urdu">(بیڈروم)</span></th><td>{{ $callLog->bedrooms ?? '-' }}</td></tr>
                        <tr><th>Budget <span class="urdu">(بجٹ)</span></th><td>@if($callLog->budget_min || $callLog->budget_max) {{ $callLog->budget_min ? number_format($callLog->budget_min, 0) : '0' }} - {{ $callLog->budget_max ? number_format($callLog->budget_max, 0) : '∞' }} @else - @endif</td></tr>
                        <tr><th>Call Datetime <span class="urdu">(کال کا وقت)</span></th><td>{{ $callLog->call_datetime ? $callLog->call_datetime->format('d M Y h:i A') : '-' }}</td></tr>
                        <tr><th>Follow-up Date <span class="urdu">(فالو اپ کی تاریخ)</span></th><td>{{ $callLog->follow_up_date ? $callLog->follow_up_date->format('d M Y') : '-' }}</td></tr>
                        <tr><th>Status <span class="urdu">(کیفیت)</span></th><td>{{ ucfirst(str_replace('_', ' ', $callLog->status)) }}</td></tr>
                        <tr><th>Assigned Agent <span class="urdu">(ایجنٹ)</span></th><td>@if($callLog->assignedAgent) <a href="{{ route('agents.show', $callLog->assignedAgent) }}" class="text-decoration-none">{{ $callLog->assignedAgent->name }}</a> @else - @endif</td></tr>
                        <tr><th>Property <span class="urdu">(جائیداد)</span></th><td>@if($callLog->property) <a href="{{ route('properties.show', $callLog->property) }}" class="text-decoration-none">{{ $callLog->property->title }}</a> @else - @endif</td></tr>
                        <tr><th>Client <span class="urdu">(گاہک)</span></th><td>@if($callLog->client) <a href="{{ route('clients.show', $callLog->client) }}" class="text-decoration-none">{{ $callLog->client->name }}</a> @else - @endif</td></tr>
                        <tr><th>Deal <span class="urdu">(ڈیل)</span></th><td>@if($callLog->deal) <a href="{{ route('deals.show', $callLog->deal) }}" class="text-decoration-none">{{ $callLog->deal->deal_number }}</a> @else - @endif</td></tr>
                        <tr><th>Notes / Requirement <span class="urdu">(نوٹس / ضرورت)</span></th><td>{{ $callLog->notes ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        @if($matchType === 'buyer')
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-phone me-1"></i> Matching Buyer Leads <span class="urdu">(موازنہ خریدار کالز)</span> ({{ $buyerLeads->count() }})</h5>
            </div>
            <div class="card-body p-0">
                @if($buyerLeads->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name <span class="urdu">(نام)</span></th>
                                <th>Phone <span class="urdu">(فون)</span></th>
                                <th>Requirement <span class="urdu">(ضرورت)</span></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($buyerLeads as $b)
                            <tr>
                                <td class="fw-medium">{{ $b->name }}</td>
                                <td>{{ $b->phone }}</td>
                                <td>
                                    @if($b->category) {{ ucfirst(str_replace('_', ' ', $b->category)) }} @endif
                                    @if($b->transaction_type) / {{ ucfirst($b->transaction_type) }} @endif
                                    @if($b->budget_max) / up to {{ number_format($b->budget_max, 0) }} @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('call-logs.show', $b) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-secondary">
                    <i class="ti ti-search" style="font-size:2rem;opacity:0.4;"></i>
                    <p class="mt-2 mb-0">No matching buyer leads. <span class="urdu">(کوئی موازنہ خریدار کال نہیں)</span></p>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="ti ti-users me-1"></i> Matching Buyer Clients <span class="urdu">(موازنہ خریدار کلائنٹس)</span> ({{ $buyerClients->count() }})</h5>
            </div>
            <div class="card-body p-0">
                @if($buyerClients->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name <span class="urdu">(نام)</span></th>
                                <th>Phone <span class="urdu">(فون)</span></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($buyerClients as $b)
                            <tr>
                                <td class="fw-medium">{{ $b->name }}</td>
                                <td>{{ $b->phone ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('clients.show', $b) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-secondary">
                    <i class="ti ti-search" style="font-size:2rem;opacity:0.4;"></i>
                    <p class="mt-2 mb-0">No buyer clients yet. <span class="urdu">(ابھی کوئی خریدار کلائنٹ نہیں)</span></p>
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-header">
                <h5><i class="ti ti-building me-1"></i> Matching Available Properties <span class="urdu">(موازنہ جائیدادیں)</span> ({{ $matches->count() }})</h5>
            </div>
            <div class="card-body p-0">
                @if($matches->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Title <span class="urdu">(عنوان)</span></th>
                                <th>Category <span class="urdu">(زمرہ)</span></th>
                                <th>Type <span class="urdu">(قسم)</span></th>
                                <th>City <span class="urdu">(شہر)</span></th>
                                <th>Price <span class="urdu">(قیمت)</span></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matches as $p)
                            <tr>
                                <td class="fw-medium">{{ $p->title ?? ($p->property_code ?? $p->id) }}</td>
                                <td>{{ $p->category ? ucfirst(str_replace('_', ' ', $p->category)) : '-' }}</td>
                                <td>{{ $p->transaction_type ? ucfirst($p->transaction_type) : '-' }}</td>
                                <td>{{ $p->city ?? '-' }}</td>
                                <td class="fw-medium">{{ number_format($p->price, 0) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('properties.show', $p) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-secondary">
                    <i class="ti ti-search" style="font-size:2rem;opacity:0.4;"></i>
                    <p class="mt-2 mb-0">No matching available properties. <span class="urdu">(کوئی موازنہ جائیداد نہیں)</span></p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="card mt-3">
            <div class="card-footer">
                <a href="{{ route('call-logs.index') }}" class="btn btn-link text-secondary text-decoration-none"><i class="ti ti-arrow-left"></i> Back to Call Logs <span class="urdu">(واپس)</span></a>
            </div>
        </div>
    </div>
</div>
@endsection

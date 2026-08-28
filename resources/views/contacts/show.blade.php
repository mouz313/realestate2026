@extends('layouts.admin')

@section('title', 'Enquiry Details <span class="urdu">(انکوائری کی تفصیلات)</span>')

@push('styles')
<style>
  .urdu { font-size:0.75em; opacity:0.75; unicode-bidi:embed; }
  .min-w-0 { min-width:0; }

  /* ---- Hero profile card ---- */
  .enq-hero { border-radius:var(--radius-lg); overflow:hidden; background:var(--surface); border:1px solid var(--border); box-shadow:var(--shadow-sm); }
  .enq-head { display:flex; flex-wrap:wrap; align-items:center; gap:1rem; padding:1.25rem; background:var(--gray-50); border-bottom:1px solid var(--border); }
  .enq-avatar {
    width:72px; height:72px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg, var(--primary), var(--accent)); color:#fff;
    display:flex; align-items:center; justify-content:center; font-size:1.7rem; font-weight:700;
    box-shadow:var(--shadow-sm);
  }
  .enq-id { min-width:0; }
  .enq-name { font-size:1.3rem; font-weight:var(--fw-bold); color:var(--gray-900); margin:0; display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }
  .enq-meta { display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.4rem; }
  .enq-meta .chip {
    display:inline-flex; align-items:center; gap:.35rem; font-size:.8rem; color:var(--gray-700);
    background:var(--surface); border:1px solid var(--border); padding:.25rem .6rem; border-radius:var(--radius-pill);
  }
  .enq-meta .chip a { color:inherit; text-decoration:none; }
  .enq-facts { display:flex; flex-wrap:wrap; padding:.35rem 1.25rem; }
  .enq-fact { padding:.5rem 1.15rem; border-right:1px solid var(--border); }
  .enq-fact:first-child { padding-left:0; }
  .enq-fact:last-child { border-right:0; }
  .enq-fact .k { font-size:.68rem; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); }
  .enq-fact .v { font-weight:var(--fw-semibold); color:var(--gray-800); margin-top:.1rem; }

  /* ---- Info list ---- */
  .info-list { margin:0; padding:0; list-style:none; }
  .info-list li { display:flex; align-items:center; gap:.75rem; padding:.6rem 0; border-bottom:1px solid var(--border); }
  .info-list li:last-child { border-bottom:0; }
  .info-ico {
    width:36px; height:36px; border-radius:10px; flex-shrink:0; display:flex; align-items:center; justify-content:center;
    background:var(--primary-light); color:var(--primary); font-size:1rem;
  }
  .info-k { font-size:.72rem; text-transform:uppercase; letter-spacing:.04em; color:var(--text-muted); }
  .info-v { font-weight:var(--fw-medium); color:var(--gray-800); }

  /* ---- Kanban ---- */
  .kanban { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px,1fr)); gap:1rem; }
  .kanban-col { background:var(--gray-50); border:1px solid var(--border); border-radius:var(--radius); padding:.75rem; transition:background .15s ease, box-shadow .15s ease; }
  .kanban-col.drag-over { background:var(--primary-light); box-shadow:inset 0 0 0 2px var(--primary); }
  .kanban-col-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:.6rem; }
  .kanban-col-head .ttl { display:inline-flex; align-items:center; gap:.45rem; font-size:.82rem; font-weight:var(--fw-semibold); color:var(--gray-700); }
  .kanban-dot { width:10px; height:10px; border-radius:50%; }
  .kanban-count { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-pill); font-size:.72rem; padding:.1rem .55rem; color:var(--gray-600); }
  .kanban-card { background:#fff; border:1px solid var(--border); border-radius:var(--radius); padding:.75rem; cursor:grab; box-shadow:var(--shadow-xs); }
  .kanban-card:active { cursor:grabbing; }
  .kanban-empty { font-size:.76rem; color:var(--gray-400); text-align:center; padding:.6rem 0; }

  .st-open { background:var(--success); }
  .st-pending { background:var(--warning); }
  .st-closed { background:var(--gray-400); }

  /* ---- Property cards ---- */
  .prop-card { background:#fff; border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; height:100%; transition:transform .15s ease, box-shadow .15s ease; display:flex; flex-direction:column; }
  .prop-card:hover { transform:translateY(-3px); box-shadow:var(--shadow); }
  .prop-thumb { height:118px; background:linear-gradient(135deg, var(--primary-light), var(--accent-light)); display:flex; align-items:center; justify-content:center; font-size:2rem; color:#fff; overflow:hidden; }
  .prop-thumb img { width:100%; height:100%; object-fit:cover; }
  .prop-body { padding:.8rem; display:flex; flex-direction:column; gap:.35rem; flex:1; }
  .prop-title { font-weight:var(--fw-semibold); color:var(--gray-900); }
  .prop-price { color:var(--primary); font-weight:var(--fw-bold); }
  .prop-sub { font-size:.8rem; color:var(--text-muted); }

  /* ---- Timeline (visits) ---- */
  .timeline { position:relative; margin:0; padding:0 0 0 1.1rem; list-style:none; }
  .timeline::before { content:""; position:absolute; left:5px; top:6px; bottom:6px; width:2px; background:var(--border); }
  .timeline li { position:relative; padding:.6rem 0; }
  .timeline li::before {
    content:""; position:absolute; left:-1.05rem; top:.95rem; width:11px; height:11px; border-radius:50%;
    background:#fff; border:3px solid var(--primary);
  }
  .timeline .t-title { font-weight:var(--fw-semibold); color:var(--gray-800); }
  .timeline .t-sub { font-size:.8rem; color:var(--text-muted); }
</style>
@endpush

@section('content')
{{-- Shared form used by Kanban moves and property linking (reuses contacts.update) --}}
<form id="enquiryForm" method="POST" action="{{ route('contacts.update', $contact) }}" class="d-none">
    @csrf @method('PUT')
    <input type="hidden" name="name" value="{{ old('name', $contact->name) }}">
    <input type="hidden" name="phone" value="{{ old('phone', $contact->phone) }}">
    <input type="hidden" name="email" value="{{ old('email', $contact->email) }}">
    <input type="hidden" name="property_type" value="{{ old('property_type', $contact->property_type) }}">
    <input type="hidden" name="purpose" value="{{ old('purpose', $contact->purpose) }}">
    <input type="hidden" name="city" value="{{ old('city', $contact->city) }}">
    <input type="hidden" name="location" value="{{ old('location', $contact->location) }}">
    <input type="hidden" name="budget_min" value="{{ old('budget_min', $contact->budget_min) }}">
    <input type="hidden" name="budget_max" value="{{ old('budget_max', $contact->budget_max) }}">
    <input type="hidden" name="lead_source" value="{{ old('lead_source', $contact->lead_source) }}">
    <input type="hidden" name="message" value="{{ old('message', $contact->message) }}">
    <input type="hidden" name="status" id="enquiryStatus" value="{{ old('status', $contact->status) }}">
    <input type="hidden" name="property_id" id="enquiryProperty" value="{{ old('property_id', $contact->property_id) }}">
</form>

{{-- Hero --}}
<div class="enq-hero mb-4">
    <div class="enq-head">
        <div class="enq-avatar">{{ strtoupper(substr($contact->name, 0, 1)) }}</div>
        <div class="enq-id flex-grow-1 min-w-0">
            <h1 class="enq-name">
                {{ $contact->name }}
                <span class="badge {{ $contact->read_at ? 'status-completed' : 'status-pending' }}">{{ $contact->read_at ? 'Read' : 'New' }}</span>
                <span class="badge {{ \App\Helpers\Status::classes('contact')[$contact->status] ?? 'status-pending' }}">{{ ucfirst($contact->status) }}</span>
            </h1>
            <div class="enq-meta">
                @if($contact->email)<span class="chip"><i class="ti ti-mail"></i><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></span>@endif
                @if($contact->phone)<span class="chip"><i class="ti ti-phone"></i><a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a></span>@endif
            </div>
        </div>
        <div class="action-btns">
            @if($contact->property)
                <a href="{{ route('properties.show', $contact->property) }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-building"></i> Property</a>
            @endif
            @can('view_deals')
            <a href="{{ route('deals.create', ['contact_id' => $contact->id]) }}" class="btn btn-sm btn-success"><i class="ti ti-circle-check"></i> Create Deal</a>
            @endcan
            @if($contact->email)
                <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject ?? 'Your enquiry' }}" class="btn btn-sm btn-dark"><i class="ti ti-mail"></i> Reply</a>
            @endif
            <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-edit"></i> Edit</a>
            <a href="{{ route('contacts.index') }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-arrow-left"></i> Back</a>
            <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete this enquiry?')" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i> Delete</button>
            </form>
        </div>
    </div>
    <div class="enq-facts">
        <div class="enq-fact">
            <div class="k">Type</div>
            <div class="v">@if($contact->property_type){{ \App\Helpers\Status::propertyTypeLabel($contact->property_type) }}@else — @endif</div>
        </div>
        <div class="enq-fact">
            <div class="k">Purpose</div>
            <div class="v">@if($contact->purpose){{ \App\Helpers\Status::purposeLabel($contact->purpose) }}@else — @endif</div>
        </div>
        <div class="enq-fact">
            <div class="k">Budget</div>
            <div class="v">
                @if($contact->budget_min || $contact->budget_max)
                    {{ $contact->budget_min ? number_format($contact->budget_min) : '—' }}@if($contact->budget_max) – {{ number_format($contact->budget_max) }} @endif
                @else — @endif
            </div>
        </div>
        <div class="enq-fact">
            <div class="k">City</div>
            <div class="v">{{ $contact->city ?? '—' }}</div>
        </div>
        <div class="enq-fact">
            <div class="k">Source</div>
            <div class="v">{{ \App\Helpers\Status::leadSourceLabel($contact->lead_source) }}</div>
        </div>
    </div>
</div>

{{-- Kanban — Enquiry Status --}}
<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="ti ti-layout-kanban text-primary"></i>
        <span>Enquiry Status <span class="urdu">(کمنبورڈ)</span></span>
    </div>
    <div class="card-body">
        <div class="kanban">
            @foreach($statusOptions as $key => $label)
                <div class="kanban-col" data-status="{{ $key }}">
                    <div class="kanban-col-head">
                        <span class="ttl"><span class="kanban-dot st-{{ $key }}"></span> {{ $label }}</span>
                        <span class="kanban-count">{{ $contact->status === $key ? 1 : 0 }}</span>
                    </div>
                    @if($contact->status === $key)
                        <div class="kanban-card" draggable="true" id="kanbanCard">
                            <div class="fw-semibold">{{ $contact->name }}</div>
                            <div class="small text-secondary text-truncate">{{ $contact->property_type ? \App\Helpers\Status::propertyTypeLabel($contact->property_type) : '' }} &middot; {{ ucfirst($contact->purpose ?? '') }}</div>
                            <div class="small text-secondary">{{ $contact->city ?? '—' }}</div>
                        </div>
                    @else
                        <div class="kanban-empty">Drop here</div>
                        <button type="button" class="btn btn-sm btn-outline-secondary w-100 mt-2" onclick="moveEnquiry('{{ $key }}')">
                            Move to {{ $label }}
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Left: Message + Relevant Properties --}}
    <div class="col-lg-7">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="ti ti-message-report text-primary"></i>
                <span>Message <span class="urdu">(پیغام)</span></span>
            </div>
            <div class="card-body">
                <p class="text-secondary mb-0" style="line-height:1.8;white-space:pre-wrap;">{{ $contact->message }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="ti ti-building text-primary"></i>
                <span>Relevant Properties <span class="urdu">(متعلقہ جائیدادیں)</span></span>
            </div>
            <div class="card-body">
                @if($relevantProperties->isNotEmpty())
                    <div class="row g-3">
                        @foreach($relevantProperties as $prop)
                            <div class="col-md-6">
                                <div class="prop-card">
                                    <div class="prop-thumb">
                                        @if($prop->primaryMedia)
                                            <img src="{{ asset('storage/'.$prop->primaryMedia->file_path) }}" alt="{{ $prop->title }}">
                                        @else
                                            <i class="ti ti-building"></i>
                                        @endif
                                    </div>
                                    <div class="prop-body">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div class="prop-title text-truncate">{{ $prop->title }}</div>
                                            <span class="badge status-info flex-shrink-0">{{ \App\Helpers\Status::propertyTypeLabel($prop->category) }}</span>
                                        </div>
                                        <div class="prop-price">{{ $prop->price ? 'Rs. '.number_format($prop->price, 0) : '—' }}</div>
                                        <div class="prop-sub">{{ $prop->city ?? '—' }}@if($prop->location_address) &middot; {{ $prop->location_address }} @endif</div>
                                        <div class="d-flex gap-2 mt-1">
                                            <a href="{{ route('properties.show', $prop) }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-eye"></i> View</a>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="linkProperty({{ $prop->id }})"><i class="ti ti-link"></i> Link</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="ti ti-building"></i>
                        <p>No matching properties found. <span class="urdu">(کوئی ملتی جلتی جائیداد نہیں)</span></p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Visits --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2 flex-wrap">
                <i class="ti ti-calendar-event text-primary"></i>
                <span>Scheduled Visits <span class="urdu">(مقررہ دورے)</span></span>
                @can('view_visits')
                <a href="{{ route('property-visits.create', [
                        'contact_id'  => $contact->id,
                        'property_id' => $contact->property_id,
                        'client_id'   => optional($client)->id,
                    ]) }}" class="btn btn-sm btn-primary ms-auto">
                    <i class="ti ti-calendar-plus"></i> Schedule Visit
                </a>
                @endcan
            </div>
            <div class="card-body">
                @if($visits->isNotEmpty())
                    <ul class="timeline">
                        @foreach($visits as $v)
                            <li>
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div class="min-w-0">
                                        <div class="t-title text-truncate">{{ $v->property?->title ?? '—' }}</div>
                                        <div class="t-sub">
                                            @if($v->agent?->name) Agent: {{ $v->agent->name }} &middot; @endif
                                            {{ $v->scheduled_date ? $v->scheduled_date->format('d M Y h:i A') : '—' }}
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">{!! \App\Helpers\Status::badge('property_visit', $v->status) !!}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <i class="ti ti-calendar-event"></i>
                        <p>No visits scheduled yet. <span class="urdu">(کوئی دورہ شیڈول نہیں)</span></p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Related Deal --}}
    @if($contact->deals->isNotEmpty())
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="ti ti-circle-check text-primary"></i>
            <span>Related Deal <span class="urdu">(متعلقہ ڈیل)</span></span>
        </div>
        <div class="card-body">
            @foreach($contact->deals as $d)
                <div class="d-flex justify-content-between align-items-center gap-2 py-2 border-bottom">
                    <div class="min-w-0">
                        <div class="fw-semibold text-truncate">
                            <a href="{{ route('deals.show', $d) }}">{{ $d->deal_number }}</a>
                        </div>
                        <div class="small text-secondary">{{ $d->property?->title ?? '—' }} &middot; {{ ucfirst($d->type ?? 'sale') }}</div>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        <div>{!! \App\Helpers\Status::badge('deal', $d->status) !!}</div>
                        @if($d->commissions->isNotEmpty())
                            <div class="small text-secondary mt-1">
                                Commission: {{ number_format($d->commissions->sum('amount'), 0) }} ({{ ucfirst($d->commissions->first()->status) }})
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    function moveEnquiry(status) {
        document.getElementById('enquiryStatus').value = status;
        document.getElementById('enquiryForm').submit();
    }

    function linkProperty(id) {
        document.getElementById('enquiryProperty').value = id;
        document.getElementById('enquiryForm').submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const card = document.getElementById('kanbanCard');
        if (! card) return;

        card.addEventListener('dragstart', function (e) {
            e.dataTransfer.setData('text/plain', '{{ $contact->status }}');
        });

        document.querySelectorAll('.kanban-col').forEach(function (col) {
            col.addEventListener('dragover', function (e) {
                e.preventDefault();
                col.classList.add('drag-over');
            });
            col.addEventListener('dragleave', function () {
                col.classList.remove('drag-over');
            });
            col.addEventListener('drop', function (e) {
                e.preventDefault();
                col.classList.remove('drag-over');
                const status = col.dataset.status;
                if (status) moveEnquiry(status);
            });
        });
    });
</script>
@endpush

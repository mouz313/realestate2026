@extends('layouts.admin')

@section('title', 'Lead Kanban <span class="urdu">(لیڈ کنبان)</span>')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route(dashboard_route()) }}" class="text-decoration-none">Dashboard <span class="urdu">(ڈیش بورڈ)</span></a></li>
        <li class="breadcrumb-item"><a href="{{ route('call-logs.index') }}" class="text-decoration-none">Call Logs <span class="urdu">(کال لاگ)</span></a></li>
        <li class="breadcrumb-item active">Kanban <span class="urdu">(کنبان)</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="page-header flex-wrap gap-2">
    <div>
        <h3>Lead Kanban <span class="urdu">(لیڈ کنبان)</span></h3>
        <div class="page-header-sub">Drag card to change status. <span class="urdu">(کارڈ گھسیٹ کر کیفیت بدلیں)</span></div>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <form method="GET" action="{{ route('call-logs.kanban') }}" class="d-flex gap-2">
            <select name="agent_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Agents (تمام ایجنٹ)</option>
                @foreach($agents as $a)
                    <option value="{{ $a->id }}" @selected(request('agent_id') == $a->id)>{{ $a->name }}</option>
                @endforeach
            </select>
            <label class="form-check form-switch mb-0 d-flex align-items-center gap-1">
                <input type="checkbox" name="due" value="1" class="form-check-input" @checked(request('due')==1) onchange="this.form.submit()">
                <span class="form-check-label small">Due only <span class="urdu">(صرف سررسید)</span></span>
            </label>
        </form>
        <a href="{{ route('call-logs.index') }}" class="btn btn-sm btn-outline-dark">
            <i class="ti ti-list"></i> List <span class="urdu">(فہرست)</span>
        </a>
        <a href="{{ route('call-logs.create') }}" class="btn btn-sm btn-dark">
            <i class="ti ti-phone-plus"></i> Log Call <span class="urdu">(کال درج)</span>
        </a>
    </div>
</div>

<style>
.kanban-board { display:flex; gap:1rem; overflow-x:auto; padding-bottom:.5rem; }
.kanban-col { flex: 0 0 280px; background:#f4f5f7; border-radius:.5rem; padding:.5rem; max-height:78vh; display:flex; flex-direction:column; }
.kanban-col-header { font-weight:600; padding:.25rem .5rem; margin-bottom:.5rem; display:flex; justify-content:space-between; align-items:center; }
.kanban-col-body { overflow-y:auto; flex:1; min-height:80px; }
.kanban-card { background:#fff; border:1px solid #e6e8eb; border-radius:.4rem; padding:.6rem; margin-bottom:.5rem; cursor:grab; box-shadow:0 1px 2px rgba(0,0,0,.04); }
.kanban-card.dragging { opacity:.4; }
.kanban-col.drag-over { background:#e9ecef; outline:2px dashed #adb5bd; }
.kanban-card-title { font-weight:600; font-size:.9rem; }
.kanban-card-meta { font-size:.75rem; color:#6c757d; margin-top:.25rem; }
.kanban-card-tags { margin-top:.4rem; display:flex; flex-wrap:wrap; gap:.25rem; }
</style>

<div class="kanban-board">
@foreach($board as $key => $col)
    <div class="kanban-col" data-status="{{ $key }}">
        <div class="kanban-col-header">
            <span class="badge bg-{{ $col['meta']['color'] }}">{{ $col['meta']['label'] }} <span class="urdu"></span></span>
            <span class="text-muted small">{{ $col['cards']->count() }}</span>
        </div>
        <div class="kanban-col-body" ondrop="dropCard(event)" ondragover="allowDrop(event)" ondragleave="leaveDrop(event)">
            @forelse($col['cards'] as $c)
                <div class="kanban-card" draggable="true" id="card-{{ $c->id }}"
                     ondragstart="dragCard(event)" data-id="{{ $c->id }}">
                    <div class="kanban-card-title">
                        <a href="{{ route('call-logs.show', $c) }}" class="text-decoration-none text-dark">
                            {{ $c->name }}
                        </a>
                    </div>
                    <div class="kanban-card-meta">
                        <i class="ti ti-phone"></i> {{ $c->phone }}
                    </div>
                    <div class="kanban-card-tags">
                        @if($c->category)
                            <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_',' ',$c->category)) }}</span>
                        @endif
                        @if($c->transaction_type)
                            <span class="badge bg-light text-dark">{{ ucfirst($c->transaction_type) }}</span>
                        @endif
                        @if($c->city)
                            <span class="badge bg-light text-dark">{{ $c->city }}</span>
                        @endif
                        @if($c->follow_up_date && $c->follow_up_date->lte(today()))
                            <span class="badge bg-danger">Due {{ $c->follow_up_date->format('d M') }}</span>
                        @endif
                    </div>
                    <div class="kanban-card-meta d-flex justify-content-between align-items-center mt-1">
                        <span><i class="ti ti-user"></i> {{ $c->assignedAgent?->name ?? 'Unassigned' }}</span>
                        <a href="{{ WhatsApp::shareLink($c->phone, 'Assalam o Alaikum '.$c->name.', regarding your enquiry...') }}"
                           target="_blank" class="text-success" title="WhatsApp">
                            <i class="ti ti-brand-whatsapp"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted small py-3">No cards <span class="urdu">(خالی)</span></div>
            @endforelse
        </div>
    </div>
@endforeach
</div>

<script>
function allowDrop(ev) {
    ev.preventDefault();
    ev.currentTarget.closest('.kanban-col').classList.add('drag-over');
}
function leaveDrop(ev) {
    ev.currentTarget.closest('.kanban-col').classList.remove('drag-over');
}
function dragCard(ev) {
    ev.dataTransfer.setData('text/plain', ev.currentTarget.dataset.id);
    ev.currentTarget.classList.add('dragging');
}
function dropCard(ev) {
    ev.preventDefault();
    const col = ev.currentTarget.closest('.kanban-col');
    col.classList.remove('drag-over');
    const cardId = ev.dataTransfer.getData('text/plain');
    const newStatus = col.dataset.status;
    const card = document.getElementById('card-' + cardId);
    if (!card) return;
    card.classList.remove('dragging');
    ev.currentTarget.prepend(card);
    // Update count
    col.querySelector('.kanban-col-header .text-muted').textContent = col.querySelectorAll('.kanban-card').length;

    fetch("{{ url('/call-logs') }}/" + cardId + "/status", {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(r => r.json())
    .then(d => {
        if (d.ok) {
            // toast
            const ev2 = new CustomEvent('toastr:success', { detail: 'Status → ' + newStatus });
            window.dispatchEvent(ev2);
        }
    })
    .catch(() => alert('Failed to update status'));
}
</script>
@endsection

@if(!empty($duplicates) && count($duplicates) > 0)
<div class="alert alert-warning d-flex align-items-start gap-2" role="alert">
    <i class="ti ti-alert-triangle" style="font-size:1.4rem;"></i>
    <div class="flex-grow-1">
        <div class="fw-semibold mb-1">
            Possible duplicate — same phone called {{ count($duplicates) }} time(s) today
            <span class="urdu">(ممکنہ نقل — آج اسی نمبر سے {{ count($duplicates) }} کال)</span>
        </div>
        <ul class="mb-2 small">
            @foreach($duplicates as $d)
            <li>
                <a href="{{ route('call-logs.show', $d) }}" target="_blank" class="text-decoration-none">
                    #{{ $d->id }} — {{ $d->name }} ({{ $d->phone }})
                </a>
                • {{ ucfirst(str_replace('_', ' ', $d->status)) }}
                • Agent: {{ $d->assignedAgent?->name ?? 'unassigned' }}
                • {{ $d->created_at->format('h:i A') }}
            </li>
            @endforeach
        </ul>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="force_save" id="force_save" value="1">
            <label class="form-check-label" for="force_save">
                This is a different enquiry — save anyway
                <span class="urdu">(یہ مختلف انکوئری ہے — بچائیں)</span>
            </label>
        </div>
    </div>
</div>
@endif

{{-- Matching properties panel for call-log create/edit.
     Watches city + category + transaction_type and suggests Properties
     the agent can attach to the lead (sets hidden property_id on submit). --}}
<input type="hidden" name="property_id" id="matched_property_id"
       value="{{ old('property_id', isset($callLog) ? $callLog->property_id : '') }}">

<div class="card border-info-subtle mb-3" id="property-matcher" style="display:none">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="mb-0"><i class="ti ti-building me-1"></i> Matching Properties <span class="urdu">(موازن پراپرٹیاں)</span></h6>
            <span class="badge bg-info" id="matcher-count"></span>
        </div>
        <div id="matcher-results" class="d-flex flex-column gap-2"></div>
        <div id="matcher-empty" class="text-muted small" style="display:none">
            No matching property found — please schedule a <strong>callback</strong> below.
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const cityEl  = document.getElementById('city_id');
    const catEl   = document.getElementById('category');
    const typeEl  = document.getElementById('transaction_type');
    const panel   = document.getElementById('property-matcher');
    const results = document.getElementById('matcher-results');
    const emptyEl = document.getElementById('matcher-empty');
    const countEl = document.getElementById('matcher-count');
    const hidden  = document.getElementById('matched_property_id');
    const followUp = document.getElementById('follow_up_date');
    const statusEl = document.getElementById('status');

    if (!cityEl || !catEl || !typeEl || !panel) return;

    function highlightCallback() {
        if (followUp) {
            const box = followUp.closest('.mb-3');
            if (box) {
                box.classList.add('border', 'border-warning', 'rounded', 'p-2');
            }
        }
        if (statusEl) statusEl.value = 'callback';
    }

    function clearHighlight() {
        if (followUp) {
            const box = followUp.closest('.mb-3');
            if (box) {
                box.classList.remove('border', 'border-warning', 'rounded', 'p-2');
            }
        }
    }

    function fetchMatches() {
        const cityId = cityEl.value;
        const category = catEl.value;

        if (!cityId || !category) {
            panel.style.display = 'none';
            return;
        }

        const params = new URLSearchParams({ city_id: cityId, category: category });
        if (typeEl.value) params.set('transaction_type', typeEl.value);

        fetch('{{ route('call-logs.match-properties') }}?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            const matches = data.matches || [];
            results.innerHTML = '';
            countEl.textContent = matches.length;

            if (matches.length === 0) {
                emptyEl.style.display = 'block';
                panel.style.display = 'block';
                highlightCallback();
                return;
            }

            emptyEl.style.display = 'none';
            clearHighlight();
            panel.style.display = 'block';

            matches.forEach(m => {
                const id = 'pm_' + m.id;
                const wrap = document.createElement('label');
                wrap.className = 'border rounded p-2 d-flex align-items-center gap-2';
                const price = new Intl.NumberFormat(undefined).format(Number(m.price || 0));
                wrap.innerHTML =
                    '<input type="radio" name="property_radio" id="' + id + '" value="' + m.id + '" class="form-check-input">' +
                    '<div class="flex-grow-1">' +
                        '<div class="fw-semibold">' + (m.label || '') + '</div>' +
                        '<div class="small text-secondary">' +
                            [m.city, m.sector_town, m.currency + ' ' + price].filter(Boolean).join(' · ') +
                        '</div>' +
                    '</div>' +
                    '<a href="' + m.url + '" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>';

                wrap.querySelector('input').addEventListener('change', function (e) {
                    hidden.value = e.target.value;
                });

                results.appendChild(wrap);
            });
        })
        .catch(() => { panel.style.display = 'none'; });
    }

    [cityEl, catEl, typeEl].forEach(function (el) {
        el.addEventListener('change', function () {
            hidden.value = '';
            fetchMatches();
        });
    });
})();
</script>
@endpush

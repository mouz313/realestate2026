{{-- Global search box in the top header — navigates to search.index with ?q= --}}
<form class="nav-search position-relative me-auto" role="search" action="{{ route('search.index') }}" method="GET" id="globalSearchForm">
    <i class="ti ti-search nav-search-icon"></i>
    <input type="text" name="q" id="globalSearch" class="form-control nav-search-input"
           placeholder="Search anything..." autocomplete="off" value="{{ request('q', '') }}">
    <div id="searchResults" class="search-dropdown"></div>
</form>

<script>
(function () {
    const input = document.getElementById('globalSearch');
    const form = document.getElementById('globalSearchForm');
    const results = document.getElementById('searchResults');
    if (!input || !form) return;

    // Submit the form (navigate to route('search.index')?q=...) on Enter.
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const q = input.value.trim();
            if (q.length >= 1) form.submit();
        }
    });

    // Lightweight offline-friendly autosuggest using the same route.
    let timer;
    input.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 2) { if (results) results.classList.remove('show'); return; }
        timer = setTimeout(() => {
            fetch('{{ route('search.index') }}?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (!results) return;
                if (!data.length) {
                    results.innerHTML = '<div class="search-dropdown-empty">No results found</div>';
                    results.classList.add('show');
                    return;
                }
                results.innerHTML = '';
                data.forEach(item => {
                    const el = document.createElement('a');
                    el.href = item.url;
                    el.className = 'search-dropdown-item';
                    el.innerHTML = '<i class="' + item.icon + '"></i>' +
                        '<div class="search-dropdown-text">' +
                            '<div class="search-dropdown-label"></div>' +
                            '<div class="search-dropdown-sub"></div>' +
                        '</div>';
                    el.querySelector('.search-dropdown-label').textContent = item.label;
                    el.querySelector('.search-dropdown-sub').textContent = item.type + (item.sub ? ' · ' + item.sub : '');
                    results.appendChild(el);
                });
                results.classList.add('show');
            })
            .catch(() => { if (results) results.classList.remove('show'); });
        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (results && !input.contains(e.target) && !results.contains(e.target)) {
            results.classList.remove('show');
        }
    });
})();
</script>

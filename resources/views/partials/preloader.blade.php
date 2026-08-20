{{-- Global circular percentage preloader overlay --}}
<div class="app-preloader" id="appPreloader" aria-hidden="true">
    <div style="position:relative;width:90px;height:90px;display:flex;align-items:center;justify-content:center;">
        <svg class="preloader-ring" width="90" height="90" viewBox="0 0 90 90">
            <circle class="preloader-ring__bg" cx="45" cy="45" r="38" fill="none" stroke-width="7"></circle>
            <circle class="preloader-ring__fg" id="preloaderArc" cx="45" cy="45" r="38" fill="none" stroke-width="7" stroke-linecap="round"></circle>
        </svg>
        <span class="preloader-text" id="preloaderText">0%</span>
    </div>
</div>

<script>
(function () {
    const overlay = document.getElementById('appPreloader');
    const arc = document.getElementById('preloaderArc');
    const text = document.getElementById('preloaderText');
    if (!overlay || !arc) return;

    const RADIUS = 38;
    const CIRC = 2 * Math.PI * RADIUS;
    arc.style.strokeDasharray = CIRC;
    arc.style.strokeDashoffset = CIRC;

    let progress = 0;
    let timer = null;
    let activeXhr = 0;
    let hidden = false;

    function setProgress(p) {
        progress = Math.max(0, Math.min(100, p));
        arc.style.strokeDashoffset = CIRC - (progress / 100) * CIRC;
        if (text) text.textContent = Math.round(progress) + '%';
    }

    function show() {
        if (hidden) return;
        overlay.classList.remove('hide');
        overlay.classList.add('active');
    }

    function finish() {
        setProgress(100);
        setTimeout(hide, 250);
    }

    function hide() {
        overlay.classList.add('hide');
        setTimeout(() => {
            overlay.classList.remove('active');
            hidden = true;
        }, 300);
    }

    // Animate 0 -> 90 over ~800ms on navigation start
    function startNavigation() {
        if (hidden) return;
        show();
        clearInterval(timer);
        timer = setInterval(() => {
            if (progress < 90) setProgress(progress + 3);
            else clearInterval(timer);
        }, 25);
    }

    // Internal <a> navigation (ignore hash, external, new-tab, logout)
    document.addEventListener('click', function (e) {
        const a = e.target.closest('a');
        if (!a) return;
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') ||
            a.target === '_blank' || a.hasAttribute('download') ||
            e.defaultPrevented) return;
        if (/^https?:\/\//i.test(href) && !href.includes(window.location.hostname)) return;
        if (href.indexOf('logout') !== -1) return;
        startNavigation();
    });

    // Wrap fetch / XHR to drive the preloader
    const originalFetch = window.fetch;
    if (originalFetch) {
        window.fetch = function () {
            startNavigation();
            return originalFetch.apply(this, arguments)
                .then(res => { finish(); return res; })
                .catch(err => { hide(); throw err; });
        };
    }
    const origOpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function () {
        activeXhr++;
        startNavigation();
        this.addEventListener('loadend', function () {
            activeXhr = Math.max(0, activeXhr - 1);
            if (activeXhr === 0) finish();
        });
        return origOpen.apply(this, arguments);
    };

    // Hide once everything is ready
    window.addEventListener('load', finish);
    document.addEventListener('DOMContentLoaded', function () {
        if (document.readyState === 'complete') finish();
    });

    // Hard safety timeout so the UI is never blocked
    setTimeout(hide, 4000);

    // Expose for manual use
    window.appPreloader = { show: startNavigation, finish: finish, hide: hide };
})();
</script>

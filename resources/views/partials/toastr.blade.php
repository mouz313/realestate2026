{{-- Self-contained vanilla toastr (no external CDN) --}}
<div id="toastrContainer" aria-live="polite" aria-atomic="true"></div>

<style>
#toastrContainer {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 10000;
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    max-width: 360px;
}
.app-toast {
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
    background: #fff;
    border: 1px solid var(--border, #e2e8f0);
    border-left: 4px solid var(--toast-color, #4f46e5);
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.14);
    padding: 0.75rem 0.9rem;
    font-size: 0.875rem;
    color: #1e293b;
    animation: toastIn .25s ease;
    position: relative;
    overflow: hidden;
}
.app-toast .app-toast-icon {
    font-size: 1.1rem;
    color: var(--toast-color, #4f46e5);
    flex-shrink: 0;
    line-height: 1.4;
}
.app-toast .app-toast-body { flex: 1; min-width: 0; }
.app-toast .app-toast-title { font-weight: 600; margin-bottom: 0.1rem; }
.app-toast .app-toast-msg { color: #475569; word-break: break-word; }
.app-toast .app-toast-close {
    background: none; border: none; cursor: pointer;
    color: #94a3b8; font-size: 1rem; line-height: 1; padding: 0;
}
.app-toast .app-toast-bar {
    position: absolute; bottom: 0; left: 0; height: 3px;
    background: var(--toast-color, #4f46e5); width: 100%;
    transform-origin: left; animation: toastBar linear forwards;
}
.app-toast.toast-success { --toast-color: #10b981; }
.app-toast.toast-error { --toast-color: #ef4444; }
.app-toast.toast-info { --toast-color: #0ea5e9; }
.app-toast.toast-warning { --toast-color: #f59e0b; }
.app-toast.hide { animation: toastOut .25s ease forwards; }

@keyframes toastIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
@keyframes toastOut { to { opacity: 0; transform: translateX(20px); } }
@keyframes toastBar { from { transform: scaleX(1); } to { transform: scaleX(0); } }

[data-theme="dark"] .app-toast { background: #1e293b; color: #e2e8f0; border-color: #334155; }
[data-theme="dark"] .app-toast .app-toast-msg { color: #94a3b8; }
</style>

<script>
(function () {
    if (window._toastrReady) return;
    window._toastrReady = true;

    const palette = {
        success: { icon: 'ti ti-circle-check', title: 'Success' },
        error:   { icon: 'ti ti-alert-circle', title: 'Error' },
        info:    { icon: 'ti ti-info-circle', title: 'Info' },
        warning: { icon: 'ti ti-alert-triangle', title: 'Warning' },
    };

    function toast(type, message, title) {
        const cfg = palette[type] || palette.info;
        const container = document.getElementById('toastrContainer');
        if (!container) return;
        const el = document.createElement('div');
        el.className = 'app-toast toast-' + type;
        el.innerHTML =
            '<i class="' + cfg.icon + ' app-toast-icon"></i>' +
            '<div class="app-toast-body">' +
                (title ? '<div class="app-toast-title"></div>' : '') +
                '<div class="app-toast-msg"></div>' +
            '</div>' +
            '<button class="app-toast-close" aria-label="Close">&times;</button>' +
            '<div class="app-toast-bar"></div>';
        el.querySelector('.app-toast-msg').textContent = message;
        if (title) el.querySelector('.app-toast-title').textContent = title;
        const close = () => {
            el.classList.add('hide');
            setTimeout(() => el.remove(), 250);
        };
        el.querySelector('.app-toast-close').addEventListener('click', close);
        container.appendChild(el);
        const dur = (toast.options && toast.options.timeOut) || 4000;
        const bar = el.querySelector('.app-toast-bar');
        if (bar) bar.style.animationDuration = dur + 'ms';
        setTimeout(close, dur);
        return el;
    }

    const toast = function (message, title) { return toast('info', message, title); };
    toast.success = (m, t) => toast('success', m, t);
    toast.error   = (m, t) => toast('error', m, t);
    toast.info    = (m, t) => toast('info', m, t);
    toast.warning = (m, t) => toast('warning', m, t);
    toast.options = { timeOut: 4000, positionClass: 'toast-top-right', progressBar: true };

    window.toastr = toast;
})();
</script>

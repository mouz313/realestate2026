{{-- Shared Bootstrap confirmation modal (replaces native confirm()) --}}
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger-subtle border-0">
                <h6 class="modal-title d-flex align-items-center gap-2 mb-0" id="deleteConfirmTitle">
                    <i class="ti ti-alert-triangle text-danger"></i>
                    <span>Delete Record</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="deleteConfirmMessage"></p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn btn-danger" id="deleteConfirmBtn">
                    <i class="ti ti-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    let pendingForm = null;
    const modalEl = document.getElementById('deleteConfirmModal');
    const titleEl = document.getElementById('deleteConfirmTitle');
    const msgEl = document.getElementById('deleteConfirmMessage');
    const confirmBtn = document.getElementById('deleteConfirmBtn');

    function showModal(form, message) {
        pendingForm = form;
        msgEl.textContent = message || 'Are you sure you want to delete this record?';
        titleEl.innerHTML = form.getAttribute('data-confirm-title')
            || '<i class="ti ti-alert-triangle text-danger"></i> <span>Delete Record</span>';
        confirmBtn.className = form.getAttribute('data-confirm-btn-class') || 'btn btn-danger';
        confirmBtn.innerHTML = (form.getAttribute('data-confirm-btn-icon') || '<i class="ti ti-trash me-1"></i> ')
            + (form.getAttribute('data-confirm-btn-label') || 'Delete');
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }

    // Converts every <form onsubmit="return confirm('...')"> into a styled modal trigger.
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form[onsubmit^="return confirm"]').forEach(function (form) {
            const m = (form.getAttribute('onsubmit') || '').match(/confirm\(['"]([\s\S]+?)['"]\)/);
            form.removeAttribute('onsubmit');
            form.classList.add('delete-form');
            form.setAttribute('data-confirm', m ? m[1] : 'Are you sure you want to delete this record?');
            const btn = form.querySelector('button[type="submit"]');
            if (btn) { btn.setAttribute('type', 'button'); btn.setAttribute('data-delete-trigger', ''); }
        });
    });

    // Open the modal when a converted trigger is clicked.
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-delete-trigger]');
        if (!trigger) return;
        const form = trigger.closest('form.delete-form');
        if (!form) return;
        showModal(form, form.getAttribute('data-confirm'));
    });

    // Confirm -> submit the captured form (programmatic submit skips the spinner handler).
    confirmBtn.addEventListener('click', function () {
        if (pendingForm) pendingForm.submit();
    });
})();
</script>
@endpush

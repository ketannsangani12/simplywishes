(() => {
  const DEFAULT_TITLE = 'Are you sure?';
  const DEFAULT_CONFIRM_LABEL = 'Delete';

  // Same markup as partials/confirm-modal.blade.php, built here only if a
  // page is missing that partial, so the site's own dialog (with its "Block"
  // / "Unfriend" / "Delete" button) is always used, never the browser's
  // plain OK/Cancel confirm() box, whose button labels can't be changed.
  const buildModal = () => {
    const wrapper = document.createElement('div');
    wrapper.innerHTML = `
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4" id="global-confirm-modal" aria-hidden="true">
  <div class="absolute inset-0" id="global-confirm-modal-backdrop"></div>
  <div class="relative w-full max-w-sm rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-surface-dark shadow-xl p-6 space-y-4">
    <div class="flex items-start gap-3">
      <span class="material-icons text-red-500 !text-2xl" aria-hidden="true">warning</span>
      <div>
        <h2 class="text-lg font-semibold text-text-light dark:text-text-dark" id="global-confirm-modal-title">Are you sure?</h2>
        <p class="mt-1 text-sm text-text-muted-light dark:text-text-muted-dark hidden" id="global-confirm-modal-message"></p>
      </div>
    </div>
    <div class="flex justify-end gap-3 pt-2">
      <button type="button" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700" id="global-confirm-modal-confirm">Delete</button>
      <button type="button" class="px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-sm font-semibold text-text-light dark:text-text-dark hover:bg-slate-50 dark:hover:bg-slate-700" id="global-confirm-modal-cancel">Cancel</button>
    </div>
  </div>
</div>`;
    const modal = wrapper.firstElementChild;
    document.body.appendChild(modal);
    return modal;
  };

  // The dialog is looked up when it's needed rather than once at page load,
  // and every click is handled through one document-level listener. So a
  // confirm button works whether it was on the page at load or added later,
  // and never ends up as a dead type="button" that silently ignores clicks.
  const dialog = () => {
    const modal = document.getElementById('global-confirm-modal') || buildModal();
    return {
      modal,
      titleEl: document.getElementById('global-confirm-modal-title'),
      messageEl: document.getElementById('global-confirm-modal-message'),
      confirmButton: document.getElementById('global-confirm-modal-confirm'),
    };
  };

  // Exactly one of these is ever in play at a time, depending on which mode
  // opened the modal (see the two usage patterns below).
  let pendingForm = null;
  let pendingResolve = null;

  const open = ({ title, message, confirmLabel } = {}) => {
    const d = dialog();
    d.titleEl.textContent = title || DEFAULT_TITLE;
    // The title alone ("Are you sure you want to delete this wish?") is the
    // whole question — this subtext is only shown when a caller explicitly
    // has something to add, not filled with a generic default.
    if (message) {
      d.messageEl.textContent = message;
      d.messageEl.classList.remove('hidden');
    } else {
      d.messageEl.textContent = '';
      d.messageEl.classList.add('hidden');
    }
    d.confirmButton.textContent = confirmLabel || DEFAULT_CONFIRM_LABEL;
    d.modal.classList.remove('hidden');
    d.modal.classList.add('flex');
    d.modal.setAttribute('aria-hidden', 'false');
  };

  const isOpen = () => {
    const modal = document.getElementById('global-confirm-modal');
    return !!modal && !modal.classList.contains('hidden');
  };

  const close = (confirmed) => {
    const modal = document.getElementById('global-confirm-modal');
    if (modal) {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      modal.setAttribute('aria-hidden', 'true');
    }

    const formToSubmit = confirmed ? pendingForm : null;
    const resolve = pendingResolve;
    pendingForm = null;
    pendingResolve = null;

    if (resolve) {
      resolve(confirmed);
    }
    if (formToSubmit) {
      formToSubmit.submit();
    }
  };

  document.addEventListener('click', (event) => {
    const target = event.target instanceof Element ? event.target : null;
    if (!target) return;

    // Buttons inside the dialog itself.
    if (target.closest('#global-confirm-modal-confirm')) {
      close(true);
      return;
    }
    if (target.closest('#global-confirm-modal-cancel') || target.closest('#global-confirm-modal-backdrop')) {
      close(false);
      return;
    }

    // Declarative mode: a button (or anything inside a <form>) marked
    // data-confirm-delete opens the dialog instead of submitting right
    // away; confirming submits that same form.
    const trigger = target.closest('[data-confirm-delete]');
    if (!trigger) return;

    event.preventDefault();
    pendingForm = trigger.closest('form');
    pendingResolve = null;
    open({
      title: trigger.getAttribute('data-confirm-title'),
      message: trigger.getAttribute('data-confirm-message'),
      confirmLabel: trigger.getAttribute('data-confirm-label'),
    });
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && isOpen()) {
      close(false);
    }
  });

  // Programmatic mode: for JS/fetch-driven deletes (e.g. the inbox), await
  // window.confirmDialog({...}) — resolves true/false, same as the old
  // window.confirm() it replaces, just with the site's own styled dialog.
  window.confirmDialog = (options = {}) => new Promise((resolve) => {
    pendingForm = null;
    pendingResolve = resolve;
    open(options);
  });
})();

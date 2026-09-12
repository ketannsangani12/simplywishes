document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('global-confirm-modal');
  if (!modal) {
    return;
  }

  const backdrop = document.getElementById('global-confirm-modal-backdrop');
  const titleEl = document.getElementById('global-confirm-modal-title');
  const messageEl = document.getElementById('global-confirm-modal-message');
  const cancelButton = document.getElementById('global-confirm-modal-cancel');
  const confirmButton = document.getElementById('global-confirm-modal-confirm');

  const DEFAULT_TITLE = 'Are you sure?';
  const DEFAULT_CONFIRM_LABEL = 'Delete';

  // Exactly one of these is ever in play at a time, depending on which mode
  // opened the modal (see the two usage patterns below).
  let pendingForm = null;
  let pendingResolve = null;

  const open = ({ title, message, confirmLabel } = {}) => {
    titleEl.textContent = title || DEFAULT_TITLE;
    // The title alone ("Are you sure you want to delete this wish?") is the
    // whole question — this subtext is only shown when a caller explicitly
    // has something to add, not filled with a generic default.
    if (message) {
      messageEl.textContent = message;
      messageEl.classList.remove('hidden');
    } else {
      messageEl.textContent = '';
      messageEl.classList.add('hidden');
    }
    confirmButton.textContent = confirmLabel || DEFAULT_CONFIRM_LABEL;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.setAttribute('aria-hidden', 'false');
  };

  const close = (confirmed) => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    modal.setAttribute('aria-hidden', 'true');

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

  cancelButton.addEventListener('click', () => close(false));
  backdrop?.addEventListener('click', () => close(false));
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
      close(false);
    }
  });
  confirmButton.addEventListener('click', () => close(true));

  // Declarative mode: a submit button (or anything inside a <form>) marked
  // data-confirm-delete opens the modal instead of submitting right away;
  // confirming submits that same form.
  document.querySelectorAll('[data-confirm-delete]').forEach((trigger) => {
    trigger.addEventListener('click', (event) => {
      event.preventDefault();
      pendingForm = trigger.closest('form');
      pendingResolve = null;
      open({
        title: trigger.getAttribute('data-confirm-title'),
        message: trigger.getAttribute('data-confirm-message'),
        confirmLabel: trigger.getAttribute('data-confirm-label'),
      });
    });
  });

  // Programmatic mode: for JS/fetch-driven deletes (e.g. the inbox), await
  // window.confirmDialog({...}) — resolves true/false, same as the old
  // window.confirm() it replaces, just with the site's own styled dialog.
  window.confirmDialog = (options = {}) => new Promise((resolve) => {
    pendingForm = null;
    pendingResolve = resolve;
    open(options);
  });
});

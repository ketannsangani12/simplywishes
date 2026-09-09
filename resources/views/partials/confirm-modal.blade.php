{{--
  One shared delete/action-confirmation dialog for the whole site, replacing
  the plain browser confirm() popups that used to blend into the page (no
  dark backdrop, easy to miss). Included once from layouts/app.blade.php so
  every page gets it automatically.

  Two ways to use it (see public/js/confirm-modal.js):
   - Declarative: add `data-confirm-delete` (plus optional
     `data-confirm-title` / `data-confirm-message` / `data-confirm-label`)
     to the submit button inside the form you want confirmed — the modal
     submits that form for you once the user confirms.
   - Programmatic: `await window.confirmDialog({ title, message, confirmLabel })`
     resolves to true/false, for JS-driven (fetch-based) deletes like the
     inbox's message/conversation delete.
--}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4" id="global-confirm-modal" aria-hidden="true">
  <div class="absolute inset-0" id="global-confirm-modal-backdrop"></div>
  <div class="relative w-full max-w-sm rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-surface-dark shadow-xl p-6 space-y-4">
    <div class="flex items-start gap-3">
      <span class="material-icons text-red-500 !text-2xl" aria-hidden="true">warning</span>
      <div>
        <h2 class="text-lg font-semibold text-text-light dark:text-text-dark" id="global-confirm-modal-title">Are you sure?</h2>
        <p class="mt-1 text-sm text-text-muted-light dark:text-text-muted-dark" id="global-confirm-modal-message">This action cannot be undone.</p>
      </div>
    </div>
    <div class="flex justify-end gap-3 pt-2">
      <button type="button" class="px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-sm font-semibold text-text-light dark:text-text-dark hover:bg-slate-50 dark:hover:bg-slate-700" id="global-confirm-modal-cancel">
        Cancel
      </button>
      <button type="button" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700" id="global-confirm-modal-confirm">
        Delete
      </button>
    </div>
  </div>
</div>

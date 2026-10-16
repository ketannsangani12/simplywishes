// Comment, reply and comment-edit boxes (every <textarea name="comment"> in a
// form on the wish, donation, forum and happy story pages): Enter posts,
// Shift+Enter adds a new line. One document-level listener, so it also covers
// reply/edit boxes that are only revealed later.
(() => {
  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Enter' || event.shiftKey || event.altKey || event.ctrlKey || event.metaKey) return;
    // Enter while an input method (e.g. Chinese/Japanese) is composing only
    // confirms the characters being typed.
    if (event.isComposing || event.keyCode === 229) return;

    const textarea = event.target instanceof Element ? event.target.closest('textarea[name="comment"]') : null;
    const form = textarea?.form;
    if (!form) return;

    event.preventDefault();
    if (!textarea.value.trim() || form.dataset.submitting === '1') return;

    // requestSubmit() behaves exactly like clicking the form's Post/Save
    // button: the form's own submit handlers and validation still run.
    if (typeof form.requestSubmit === 'function') {
      form.requestSubmit();
    } else {
      form.submit();
    }
  });

  // Holding Enter (key repeat) must not post the same comment twice.
  document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!event.defaultPrevented && form instanceof HTMLFormElement && form.querySelector('textarea[name="comment"]')) {
      form.dataset.submitting = '1';
    }
  });
})();

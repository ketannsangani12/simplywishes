document.addEventListener('DOMContentLoaded', () => {
  // Keep these in lockstep with the 'max:' rules in ForumController::store()/update()
  // (values there are in KB, where 1 KB = 1024 bytes per Laravel's file-size rule).
  const MAX_VIDEO_BYTES = 51200 * 1024; // 50MB
  const MAX_THUMBNAIL_BYTES = 10240 * 1024; // 10MB

  // A video (or image) that's too large but still gets sent to the server
  // can be rejected by the hosting/CDN layer (e.g. Cloudflare) before it
  // ever reaches Laravel's own validation — which shows up to the user as
  // a raw "413 Payload Too Large" page instead of a form error. Checking
  // the file size up front, before the browser starts uploading anything,
  // is the only way to guarantee a friendly, in-page message instead.
  const formatSize = (bytes) => `${(bytes / (1024 * 1024)).toFixed(1)}MB`;

  const guardFileSize = (inputId, maxBytes, kind) => {
    const input = document.getElementById(inputId);
    if (!input) {
      return;
    }

    const errorEl = document.querySelector(`[data-error-for="${inputId}"]`);
    const showError = (message) => {
      if (!errorEl) {
        return;
      }
      errorEl.textContent = message;
      errorEl.classList.toggle('hidden', !message);
    };

    const check = () => {
      const file = input.files && input.files[0];
      if (!file) {
        showError('');
        return true;
      }

      if (file.size > maxBytes) {
        showError(`This ${kind} is ${formatSize(file.size)}, which is over the ${formatSize(maxBytes)} limit. Please choose a smaller file (or compress it) and try again.`);
        input.value = '';
        return false;
      }

      showError('');
      return true;
    };

    input.addEventListener('change', check);

    const form = input.closest('form');
    if (form) {
      form.addEventListener('submit', (event) => {
        if (!check()) {
          event.preventDefault();
        }
      });
    }
  };

  guardFileSize('video-file', MAX_VIDEO_BYTES, 'video');
  guardFileSize('article-video-file', MAX_VIDEO_BYTES, 'video');
  guardFileSize('thumbnail-image', MAX_THUMBNAIL_BYTES, 'image');
  guardFileSize('article-video-thumbnail', MAX_THUMBNAIL_BYTES, 'image');
});

// Facebook sharing for the wish / donation / story / forum share menus.
//
// Opening sharer.php in a plain new tab (window.open with '_blank' and
// 'noopener') breaks: once Facebook finishes posting it tries to close its
// window, the browser refuses because of 'noopener' (and on phones the page is
// a full tab), so Facebook navigates away instead and its composer's own
// "Leave site? Changes you made may not be saved" prompt fires again and again.
// The post never goes through.
//
// So:
//  - On phones/tablets, use the native share sheet (Web Share API). The
//    Facebook app does the posting, so there's no web composer and no
//    Facebook login redirect.
//  - Everywhere else, open sharer.php as a real popup that the script owns.
//    Facebook can then close it cleanly once the post is published.
(() => {
  const isTouchDevice = () =>
    window.matchMedia && window.matchMedia('(pointer: coarse)').matches;

  const openPopup = (shareUrl) => {
    const width = 626;
    const height = 436;
    const left = Math.max(0, Math.round(window.screenX + (window.outerWidth - width) / 2));
    const top = Math.max(0, Math.round(window.screenY + (window.outerHeight - height) / 2));
    const popup = window.open(
      shareUrl,
      'simplywishes-facebook-share',
      `popup=yes,width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`
    );

    if (!popup) {
      // Popup blocked: fall back to a normal tab so the user can still share.
      window.open(shareUrl, '_blank');
      return;
    }

    // Keep the popup closable by script but cut its link back to our page.
    try {
      popup.opener = null;
    } catch (e) {
      // ignore
    }
    popup.focus();
  };

  const facebook = (url, text) => {
    const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;

    // Must run synchronously inside the click handler: both navigator.share
    // and window.open need the click's user activation.
    if (isTouchDevice() && typeof navigator.share === 'function') {
      navigator.share({ title: text || document.title, text: text || '', url }).catch((error) => {
        // AbortError just means the user closed the share sheet.
        if (error && error.name !== 'AbortError') {
          window.location.href = shareUrl;
        }
      });
      return;
    }

    openPopup(shareUrl);
  };

  window.SimplyShare = { facebook };
})();

// Shows dates/times in the visitor's own time zone, always in 12-hour format.
//
// The server stores and renders times in UTC. Blade outputs them through
// <x-local-time>, i.e. <time datetime="<ISO 8601 UTC, e.g. 2026-09-18T15:30:00Z>"
// data-local-time="<style>">UTC fallback</time>, and this script rewrites the
// text using the browser's time zone. Without JavaScript the UTC fallback
// stays. The inbox uses SimplyTime.format() directly for messages it builds.
(() => {
  const MONTHS_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  const MONTHS_LONG = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

  const pad = (n) => String(n).padStart(2, '0');

  const time12 = (d, upper) => {
    const hours = d.getHours() % 12 || 12;
    const meridiem = d.getHours() < 12 ? 'am' : 'pm';
    return `${hours}:${pad(d.getMinutes())} ${upper ? meridiem.toUpperCase() : meridiem}`;
  };

  // Each style mirrors the PHP format the page used before.
  const STYLES = {
    // M j, Y g:i A  → Oct 2, 2026 2:35 PM
    datetime: (d) => `${MONTHS_SHORT[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()} ${time12(d, true)}`,
    // F j, Y g:i a  → October 2, 2026 2:35 pm
    'datetime-long': (d) => `${MONTHS_LONG[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()} ${time12(d, false)}`,
    // M d, g:i A    → Oct 02, 2:35 PM
    'datetime-short': (d) => `${MONTHS_SHORT[d.getMonth()]} ${pad(d.getDate())}, ${time12(d, true)}`,
    // M d, Y        → Oct 02, 2026
    date: (d) => `${MONTHS_SHORT[d.getMonth()]} ${pad(d.getDate())}, ${d.getFullYear()}`,
    // M d           → Oct 02
    day: (d) => `${MONTHS_SHORT[d.getMonth()]} ${pad(d.getDate())}`,
    // M Y           → Oct 2026
    month: (d) => `${MONTHS_SHORT[d.getMonth()]} ${d.getFullYear()}`,
  };

  const format = (iso, style = 'datetime') => {
    if (!iso) return '';
    const d = new Date(iso);
    if (Number.isNaN(d.getTime())) return '';
    return (STYLES[style] || STYLES.datetime)(d);
  };

  const apply = (root = document) => {
    root.querySelectorAll('time[data-local-time]').forEach((el) => {
      const text = format(el.getAttribute('datetime'), el.getAttribute('data-local-time'));
      if (text) el.textContent = text;
    });
  };

  window.SimplyTime = { format, apply };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => apply());
  } else {
    apply();
  }
})();

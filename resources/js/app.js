import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

/**
 * Alpine rejects an internal promise with { isFromCancelledTransition: true }
 * when an x-transition's element is removed from the DOM mid-animation (e.g.
 * an AJAX grid refresh replacing a card while its dropdown was closing).
 * That's Alpine's own signal to abort the transition, not an app error, so
 * it's the one rejection reason silenced here rather than left to spam the
 * console as "unhandled".
 */
window.addEventListener('unhandledrejection', (event) => {
    if (event.reason && event.reason.isFromCancelledTransition) {
        event.preventDefault();
    }
});

/**
 * Shared toast notifications for the admin panel. Reads/writes the
 * #toast-stack container rendered once in components/admin/shell.blade.php.
 */
window.showToast = function showToast(type, title, message) {
    const stack = document.getElementById('toast-stack');
    if (!stack) return;

    const tone = {
        success: { border: 'border-l-success', bg: 'bg-accent-light', icon: 'M5 13l4 4L19 7', iconColor: 'text-accent-dark' },
        error: { border: 'border-l-danger', bg: 'bg-danger-light', icon: 'M12 8v4m0 4h.01M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z', iconColor: 'text-danger' },
        warning: { border: 'border-l-warning', bg: 'bg-warning-light', icon: 'M12 8v4m0 4h.01M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z', iconColor: 'text-warning' },
    }[type] || {};

    const el = document.createElement('div');
    el.className = `toast-slide-in flex w-80 items-start gap-3 rounded-2xl border-l-4 ${tone.border} bg-surface p-4 shadow-lg`;
    el.innerHTML = `
        <span class="flex size-8 shrink-0 items-center justify-center rounded-full ${tone.bg} ${tone.iconColor}">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="${tone.icon}"/></svg>
        </span>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold text-ink">${title}</p>
            <p class="mt-0.5 truncate text-xs text-ink-muted">${message}</p>
            <div class="mt-2 h-0.5 w-full overflow-hidden rounded-full bg-surface-soft"><div class="toast-progress h-full ${tone.bg}"></div></div>
        </div>
        <button type="button" class="shrink-0 text-ink-muted transition hover:text-ink" aria-label="Dismiss">
            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
        </button>`;
    el.querySelector('button').addEventListener('click', () => window.dismissToast(el));
    stack.appendChild(el);
    setTimeout(() => window.dismissToast(el), 4000);
};

window.dismissToast = function dismissToast(el) {
    if (!el.isConnected) return;
    el.classList.add('toast-slide-out');
    setTimeout(() => el.remove(), 250);
};

/**
 * Logs every click on the public site to /visit/click for the admin
 * Visitors page. Not sendBeacon: it can't carry the CSRF header the route
 * needs, and fetch's keepalive flag exists specifically to survive a
 * navigation the same way sendBeacon does.
 *
 * Walks up from the actual click target to the nearest element worth
 * describing (a link, button, or other control) so a tap on an icon inside
 * a button reads as "the button", not as an unlabelled <svg>.
 */
if (!location.pathname.startsWith('/admin')) {
    const describableSelector = 'a,button,[role="button"],label,input,select,textarea,summary';

    document.addEventListener('click', (event) => {
        const described = event.target.closest?.(describableSelector) || event.target;
        const link = described.closest?.('a[href]');

        // textContent walks every descendant — fine for a button or link,
        // but a click that falls through to <body> or <html> with no closer
        // match would otherwise turn "label" into the entire page's text.
        const isBroadContainer = described === document.body || described === document.documentElement;
        const label = (described.getAttribute?.('aria-label') || (isBroadContainer ? '' : described.textContent) || '')
            .trim().replace(/\s+/g, ' ').slice(0, 255) || null;

        const classes = described.classList ? Array.from(described.classList).slice(0, 2).join('.') : '';
        const selector = described.tagName
            ? described.tagName.toLowerCase() + (described.id ? `#${described.id}` : classes ? `.${classes}` : '')
            : null;

        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!token) return;

        fetch('/visit/click', {
            method: 'POST',
            keepalive: true,
            headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                path: location.pathname,
                selector: selector?.slice(0, 255) || null,
                label,
                href: link ? link.getAttribute('href')?.slice(0, 2048) : null,
            }),
        }).catch(() => {});
    });
}

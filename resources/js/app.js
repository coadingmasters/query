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

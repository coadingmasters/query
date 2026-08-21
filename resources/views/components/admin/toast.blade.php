@props(['message'])

@if ($message)
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)"
         x-show="show" x-cloak
         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed top-20 right-4 z-[60] flex items-center gap-3 rounded-2xl border border-line bg-surface py-3 pr-3 pl-4 shadow-lg">
        <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-accent-light text-accent-dark">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
        </span>
        <p class="text-sm font-semibold text-ink">{{ $message }}</p>
        <button type="button" x-on:click="show = false" class="flex size-6 shrink-0 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
            <span class="sr-only">Dismiss</span>
        </button>
    </div>
@endif

@php
    $statCards = [
        ['label' => 'Total messages', 'value' => $counts['total'], 'tone' => 'primary', 'icon' => 'm3 7 8.5 6L20 7M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z'],
        ['label' => 'New', 'value' => $counts['new'], 'tone' => 'warning', 'icon' => 'M12 9v4m0 4h.01M10.3 3.9 2.5 17.5A2 2 0 0 0 4.2 20.5h15.6a2 2 0 0 0 1.7-3l-7.8-13.6a2 2 0 0 0-3.4 0Z'],
        ['label' => 'Handled', 'value' => $counts['handled'], 'tone' => 'accent', 'icon' => 'M5 13l4 4L19 7'],
    ];
    $toneClasses = [
        'primary' => 'bg-primary-light text-primary',
        'accent' => 'bg-accent-light text-accent-dark',
        'warning' => 'bg-warning-light text-warning',
    ];
@endphp

<x-admin.shell active="messages" title="Messages">

    <div class="flex flex-wrap items-start justify-between gap-4 animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <div>
            <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Contact messages</h2>
            <p class="mt-1 text-sm text-ink-muted">Everything submitted through the contact form, newest first.</p>
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        @foreach ($statCards as $i => $stat)
            <div class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md"
                 style="--pop-delay: {{ $i * 70 }}ms">
                <span class="flex size-10 items-center justify-center rounded-xl {{ $toneClasses[$stat['tone']] }}">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="{{ $stat['icon'] }}"/>
                    </svg>
                </span>
                <p class="mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $stat['label'] }}</p>
                <p class="mt-1 font-heading text-3xl font-extrabold text-ink"
                   x-data="{ n: 0 }" x-init="let t = setInterval(() => { n < {{ $stat['value'] }} ? n++ : clearInterval(t) }, Math.max(600 / Math.max({{ $stat['value'] }}, 1), 12))"
                   x-text="n">0</p>
            </div>
        @endforeach
    </div>

    {{-- Chart --}}
    <div class="mt-6 rounded-2xl border border-line bg-surface p-6 shadow-sm">
        <h3 class="font-heading text-base font-bold text-ink">New vs. handled</h3>
        <p class="text-sm text-ink-muted">How the {{ $counts['total'] }} messages on file are split right now.</p>
        <div class="mt-6 max-w-lg">
            <x-admin.wave-chart :data="$statusChart" id="messages-status"/>
        </div>
    </div>

    <div x-data="messagesAdmin({{ \Illuminate\Support\Js::from($filters) }})"
         x-on:messages:refresh.window="fetchMessages()"
         x-on:messages:delete.window="deleteOpen = true; deleteId = $event.detail.id; deleteName = $event.detail.name">

        {{-- Filter bar --}}
        <div class="mt-6 flex flex-wrap items-center gap-3">
            <div class="relative min-w-[220px] flex-1">
                <svg class="pointer-events-none absolute top-1/2 left-3.5 size-4 -translate-y-1/2 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>
                </svg>
                <label for="message-search" class="sr-only">Search messages</label>
                <input id="message-search" type="text" x-model.debounce.300ms="q" placeholder="Search name, email, subject or message..." autocomplete="off"
                       class="w-full rounded-xl border border-line-strong bg-surface py-2.5 pr-4 pl-10 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
            </div>

            <label for="message-status" class="sr-only">Filter by status</label>
            <select id="message-status" x-model="status" class="rounded-xl border border-line-strong bg-surface px-4 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                <option value="">All status</option>
                <option value="new">New</option>
                <option value="handled">Handled</option>
            </select>

            <label for="message-from" class="sr-only">From date</label>
            <input id="message-from" type="date" x-model="from"
                   class="rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
            <span class="text-sm text-ink-muted">to</span>
            <label for="message-to" class="sr-only">To date</label>
            <input id="message-to" type="date" x-model="to"
                   class="rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

            <button type="button" x-on:click="clear()" x-bind:disabled="!active"
                    class="rounded-xl border border-line-strong bg-surface px-5 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-line-strong disabled:hover:text-ink-muted">
                Clear filters
            </button>
        </div>

        {{-- List: server-rendered, swapped via AJAX on filter/page change --}}
        <div class="relative mt-6" data-messages-container>
            <div data-messages-grid-wrap>
                @include('admin.messages-partials.grid', ['messages' => $messages, 'filters' => $filters])
            </div>

            <div x-cloak x-show="loading" x-transition.opacity class="absolute inset-0 flex flex-col gap-3 bg-surface-section">
                @for ($i = 0; $i < 4; $i++)
                    <div class="flex items-start gap-4 rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <div class="flex-1 space-y-2.5">
                            <div class="skeleton h-3 w-1/3 rounded"></div>
                            <div class="skeleton h-4 w-1/2 rounded"></div>
                            <div class="skeleton h-3 w-full rounded"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- Delete confirmation modal --}}
        <div x-cloak x-show="deleteOpen" x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm"
             x-on:keydown.escape.window="deleteOpen = false">
            <div x-show="deleteOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="deleteOpen = false"
                 class="w-full max-w-sm rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex justify-end">
                    <button type="button" x-on:click="deleteOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>
                <div class="-mt-2 flex flex-col items-center text-center">
                    <span class="flex size-14 items-center justify-center rounded-full bg-danger-light text-danger">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/>
                        </svg>
                    </span>
                    <h3 class="mt-4 font-heading text-lg font-extrabold text-ink">Delete message from "<span x-text="deleteName"></span>"?</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">
                        This action cannot be undone.
                    </p>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="deleteOpen = false"
                            class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">
                        Cancel
                    </button>
                    <button type="button" x-on:click="confirmDelete()" x-bind:disabled="deleting"
                            class="flex flex-1 items-center justify-center gap-2 rounded-full bg-danger px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:brightness-110 disabled:opacity-60">
                        <svg x-show="deleting" x-cloak class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-opacity="0.25"/><path d="M21 12a9 9 0 0 0-9-9"/></svg>
                        <span x-text="deleting ? 'Deleting…' : 'Yes, Delete'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
<script>
function messagesAdmin(initialFilters) {
    return {
        q: initialFilters.q || '',
        status: initialFilters.status || '',
        from: initialFilters.from || '',
        to: initialFilters.to || '',
        loading: false,
        deleteOpen: false, deleteId: null, deleteName: '',
        deleting: false,

        get active() {
            return this.q || this.status || this.from || this.to;
        },

        init() {
            this.$watch('q', () => this.fetchMessages());
            this.$watch('status', () => this.fetchMessages());
            this.$watch('from', () => this.fetchMessages());
            this.$watch('to', () => this.fetchMessages());

            document.querySelector('[data-messages-container]').addEventListener('click', (e) => {
                const link = e.target.closest('[data-messages-pagination] a');
                if (!link) return;
                e.preventDefault();
                this.fetchMessages(link.href);
            });
        },

        clear() {
            this.q = ''; this.status = ''; this.from = ''; this.to = '';
        },

        buildUrl() {
            const params = new URLSearchParams();
            if (this.q) params.set('q', this.q);
            if (this.status) params.set('status', this.status);
            if (this.from) params.set('from', this.from);
            if (this.to) params.set('to', this.to);
            const qs = params.toString();
            return '{{ route("admin.messages.index") }}' + (qs ? `?${qs}` : '');
        },

        async fetchMessages(url = null) {
            const target = url || this.buildUrl();
            this.loading = true;
            try {
                const res = await fetch(target, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                const wrap = document.querySelector('[data-messages-grid-wrap]');
                wrap.innerHTML = html;
                window.Alpine.initTree(wrap);
                window.history.pushState({}, '', target);
            } catch (e) {
                showToast('error', 'Could not load messages', 'Check your connection and try again.');
            } finally {
                this.loading = false;
            }
        },

        async confirmDelete() {
            this.deleting = true;
            try {
                const res = await fetch(`/admin/messages/${this.deleteId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: '_method=DELETE',
                });
                if (!res.ok) throw new Error();
                const row = document.querySelector(`[data-message-row="${this.deleteId}"]`);
                this.deleteOpen = false;
                if (row) {
                    row.style.transition = 'opacity 400ms ease, max-height 400ms ease, padding 400ms ease';
                    row.style.maxHeight = row.offsetHeight + 'px';
                    requestAnimationFrame(() => {
                        row.style.opacity = '0';
                        row.style.maxHeight = '0px';
                        row.style.paddingTop = '0px';
                        row.style.paddingBottom = '0px';
                    });
                    setTimeout(() => this.fetchMessages(), 420);
                } else {
                    this.fetchMessages();
                }
                showToast('success', 'Message deleted', this.deleteName);
            } catch (e) {
                showToast('error', 'Could not delete message', 'Please try again.');
            } finally {
                this.deleting = false;
            }
        },
    };
}
</script>
    @endpush

</x-admin.shell>

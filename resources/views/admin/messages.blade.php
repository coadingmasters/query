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

    <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Contact messages</h2>
        <p class="mt-1 text-sm text-ink-muted">Everything submitted through the contact form, newest first.</p>
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

    @php
        // Built ahead of the x-data attribute rather than inline: a
        // multi-key, multi-line array literal directly inside @json() hits
        // a Blade compiler regex limit and silently truncates.
        $messageItems = $messages->map(fn ($m) => [
            'name' => $m->name,
            'email' => $m->email,
            'subject' => $m->subject,
            'message' => $m->message,
            'date' => $m->created_at->format('M j, Y \a\t g:ia'),
            'handled' => (bool) $m->handled_at,
            'formAction' => route('admin.messages.handled', $m),
        ])->values();
    @endphp

    {{-- List, with a shared "view full message" popup instead of dumping
         every body inline. --}}
    <div class="mt-6 rounded-2xl border border-line bg-surface shadow-sm"
         x-data='{
             viewOpen: false,
             viewIndex: null,
             items: @json($messageItems),
             get current() { return this.viewIndex !== null ? this.items[this.viewIndex] : null; },
         }'>
        <div class="divide-y divide-line">
            @forelse ($messages as $i => $message)
                <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                            <p class="font-semibold text-ink">{{ $message->name }}</p>
                            <span class="text-ink-muted">&middot;</span>
                            <a href="mailto:{{ $message->email }}" class="text-sm text-primary hover:text-primary-hover">{{ $message->email }}</a>
                            @if (! $message->handled_at)
                                <span class="rounded-full bg-primary-light px-2 py-0.5 text-[11px] font-bold text-primary uppercase">New</span>
                            @endif
                        </div>
                        <p class="mt-1.5 text-sm font-semibold text-ink">{{ $message->subject }}</p>
                        <p class="mt-1 line-clamp-2 max-w-2xl text-sm whitespace-pre-line text-ink-muted">{{ $message->message }}</p>
                        <div class="mt-2 flex items-center gap-3">
                            <p class="text-xs text-ink-muted">{{ $message->created_at->format('M j, Y \a\t g:ia') }}</p>
                            <button type="button" x-on:click="viewOpen = true; viewIndex = {{ $i }}"
                                    class="flex items-center gap-1 text-xs font-semibold text-primary hover:text-primary-hover">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                View full message
                            </button>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.messages.handled', $message) }}" class="shrink-0">
                        @csrf
                        <button type="submit"
                                @class([
                                    'rounded-lg px-3 py-1.5 text-xs font-semibold transition',
                                    'bg-surface-soft text-ink-muted hover:bg-line' => $message->handled_at,
                                    'bg-accent-light text-accent hover:brightness-95' => ! $message->handled_at,
                                ])>
                            {{ $message->handled_at ? 'Mark unhandled' : 'Mark handled' }}
                        </button>
                    </form>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <span class="flex size-14 items-center justify-center rounded-full bg-surface-soft text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m3 7 8.5 6L20 7M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                        </svg>
                    </span>
                    <p class="mt-4 text-sm font-semibold text-ink">No messages yet</p>
                    <p class="mt-1 max-w-xs text-sm text-ink-muted">
                        Submissions from the contact form will show up here.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Full message popup. --}}
        <div x-cloak x-show="viewOpen" x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4"
             x-on:keydown.escape.window="viewOpen = false">
            <div x-show="viewOpen" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-on:click.outside="viewOpen = false"
                 class="w-full max-w-lg rounded-2xl bg-surface p-6 shadow-xl">
                <template x-if="current">
                    <div>
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="font-heading text-lg font-extrabold text-ink" x-text="current.subject"></h3>
                                <p class="mt-1 text-sm text-ink-muted">
                                    <span x-text="current.name"></span> &middot;
                                    <a class="text-primary hover:text-primary-hover" x-bind:href="'mailto:' + current.email" x-text="current.email"></a>
                                </p>
                            </div>
                            <button type="button" x-on:click="viewOpen = false" class="flex size-7 shrink-0 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                                <span class="sr-only">Close</span>
                            </button>
                        </div>

                        <p class="mt-4 max-h-80 overflow-y-auto text-sm leading-relaxed whitespace-pre-line text-ink" x-text="current.message"></p>

                        <div class="mt-5 flex items-center justify-between gap-3 border-t border-line pt-4">
                            <p class="text-xs text-ink-muted" x-text="current.date"></p>
                            <form method="POST" x-bind:action="current.formAction">
                                @csrf
                                <button type="submit"
                                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition"
                                        x-bind:class="current.handled ? 'bg-surface-soft text-ink-muted hover:bg-line' : 'bg-accent-light text-accent hover:brightness-95'"
                                        x-text="current.handled ? 'Mark unhandled' : 'Mark handled'">
                                </button>
                            </form>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    @if ($messages->hasPages())
        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    @endif

</x-admin.shell>

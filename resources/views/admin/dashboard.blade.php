@php
    $toneClasses = [
        'primary' => 'bg-primary-light text-primary',
        'accent' => 'bg-accent-light text-accent',
        'info' => 'bg-info-light text-info',
        'warning' => 'bg-warning-light text-warning',
    ];
    $washClasses = [
        'primary' => 'bg-primary-light',
        'accent' => 'bg-accent-light',
        'info' => 'bg-info-light',
        'warning' => 'bg-warning-light',
    ];
@endphp

<x-admin.shell active="dashboard" title="Dashboard">

    <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">
            Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋
        </h2>
        <p class="mt-1 text-sm text-ink-muted">
            Here's a snapshot of {{ config('app.name') }} right now.
        </p>
    </div>

    {{-- ══ Stat cards ═══════════════════════════════════════════════════ --}}
    <div class="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $i => $stat)
            <{{ isset($stat['href']) ? 'a' : 'div' }}
                @if (isset($stat['href'])) href="{{ $stat['href'] }}" @endif
                class="stagger-delay animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] relative block overflow-hidden rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                style="--stagger-delay: {{ $i * 70 }}ms"
            >
                <span aria-hidden="true" @class(['pointer-events-none absolute -top-10 -right-10 size-28 rounded-full blur-2xl opacity-50', $washClasses[$stat['tone']]])></span>
                <span class="relative flex size-10 items-center justify-center rounded-xl {{ $toneClasses[$stat['tone']] }}">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="{{ $stat['icon'] }}"/>
                    </svg>
                </span>
                <p class="relative mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $stat['label'] }}</p>
                <p class="relative mt-1 font-heading text-3xl font-extrabold text-ink"
                   x-data="{ n: 0 }" x-init="let t = setInterval(() => { n < {{ $stat['value'] }} ? n++ : clearInterval(t) }, Math.max(600 / Math.max({{ $stat['value'] }}, 1), 12))"
                   x-text="n">0</p>
            </{{ isset($stat['href']) ? 'a' : 'div' }}>
        @endforeach
    </div>

    {{-- ══ Activity + visitors ══════════════════════════════════════════ --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="stat-card-pop relative overflow-hidden rounded-2xl border border-line bg-surface p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md lg:col-span-2" style="--pop-delay: 280ms">
            <span aria-hidden="true" class="pointer-events-none absolute -top-14 -right-14 size-36 rounded-full bg-primary-light opacity-60 blur-2xl"></span>
            <div class="relative flex items-start gap-3">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 3v18h18M8 17V10M13 17V6M18 17v-4"/>
                    </svg>
                </span>
                <div>
                    <h3 class="font-heading text-base font-bold text-ink">Site activity</h3>
                    <p class="text-sm text-ink-muted">New contact messages and newsletter subscribers, last 14 days.</p>
                </div>
            </div>
            <div class="relative mt-6">
                <x-admin.line-chart :series="$activitySeries"/>
            </div>
        </div>

        <div class="stat-card-pop relative overflow-hidden rounded-2xl border border-line bg-surface p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" style="--pop-delay: 350ms">
            <span aria-hidden="true" class="pointer-events-none absolute -top-14 -right-14 size-36 rounded-full bg-accent-light opacity-60 blur-2xl"></span>
            <div class="relative flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-accent-light text-accent-dark">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21c-4.97-4-9-7.58-9-11.5A5.5 5.5 0 0 1 12 6a5.5 5.5 0 0 1 9 3.5C21 13.42 16.97 17 12 21Z M12 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="font-heading text-base font-bold text-ink">Visitors this week</h3>
                        <p class="text-sm text-ink-muted">{{ $visitorTotals['today'] }} today &middot; {{ $visitorTotals['total'] }} total on file.</p>
                    </div>
                </div>
                <a href="{{ route('admin.visitors.index') }}" class="shrink-0 text-sm font-semibold text-primary transition hover:text-primary-hover">All &rarr;</a>
            </div>
            <div class="relative mt-6">
                <x-admin.wave-chart :data="$visitorTrend" id="dashboard-visitors"/>
            </div>
        </div>
    </div>

    {{-- ══ Breakdown charts ═════════════════════════════════════════════ --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">

        <div class="stat-card-pop relative overflow-hidden rounded-2xl border border-line bg-surface p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" style="--pop-delay: 420ms">
            <span aria-hidden="true" class="pointer-events-none absolute -top-14 -right-14 size-36 rounded-full bg-warning-light opacity-60 blur-2xl"></span>
            <div class="relative flex items-start gap-3">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-warning-light text-warning">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="font-heading text-base font-bold text-ink">Posts by category</h3>
                    <p class="text-sm text-ink-muted">Every published article.</p>
                </div>
            </div>
            <div class="relative mt-6">
                <x-admin.pie-chart :data="$categoryData"/>
            </div>
        </div>

        <div class="stat-card-pop relative overflow-hidden rounded-2xl border border-line bg-surface p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" style="--pop-delay: 490ms">
            <span aria-hidden="true" class="pointer-events-none absolute -top-14 -right-14 size-36 rounded-full bg-accent-light opacity-60 blur-2xl"></span>
            <div class="relative flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-accent-light text-accent-dark">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z"/>
                        </svg>
                    </span>
                    <h3 class="font-heading text-base font-bold text-ink">Article feedback</h3>
                </div>
                <a href="{{ route('admin.feedback.index') }}" class="shrink-0 text-sm font-semibold text-primary transition hover:text-primary-hover">By post &rarr;</a>
            </div>
            <div class="relative mt-6">
                <x-admin.donut-chart :feedback="$feedback"/>
            </div>
        </div>

        <div class="stat-card-pop relative overflow-hidden rounded-2xl border border-line bg-surface p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" style="--pop-delay: 560ms">
            <span aria-hidden="true" class="pointer-events-none absolute -top-14 -right-14 size-36 rounded-full bg-info-light opacity-60 blur-2xl"></span>
            <div class="relative flex items-start gap-3">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-info-light text-info">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 5h16a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z M8 21h8M12 17v4"/>
                    </svg>
                </span>
                <div>
                    <h3 class="font-heading text-base font-bold text-ink">Visitors by device</h3>
                    <p class="text-sm text-ink-muted">Desktop, mobile and tablet.</p>
                </div>
            </div>
            <div class="relative mt-6">
                <x-admin.pie-chart :data="$deviceChart"/>
            </div>
        </div>
    </div>

    {{-- ══ Recent activity feeds ════════════════════════════════════════ --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-2">

        <div class="stat-card-pop rounded-2xl border border-line bg-surface p-6 shadow-sm" style="--pop-delay: 630ms">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading text-base font-bold text-ink">Recent messages</h3>
                    <p class="text-sm text-ink-muted">Latest submissions from the contact form.</p>
                </div>
                <a href="{{ route('admin.messages.index') }}" class="shrink-0 text-sm font-semibold text-primary transition hover:text-primary-hover">View all &rarr;</a>
            </div>

            <div class="mt-5 divide-y divide-line">
                @forelse ($recentMessages as $message)
                    <div class="flex items-start justify-between gap-4 py-3.5 first:pt-0 last:pb-0">
                        <div class="min-w-0">
                            <p class="flex items-center gap-2 text-sm font-semibold text-ink">
                                {{ $message->name }}
                                @if (! $message->handled_at)
                                    <span class="size-1.5 rounded-full bg-primary-vivid"></span>
                                @endif
                            </p>
                            <p class="truncate text-sm text-ink-muted">{{ $message->subject }}</p>
                        </div>
                        <span class="shrink-0 text-xs text-ink-muted">{{ $message->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-center">
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
        </div>

        <div class="stat-card-pop rounded-2xl border border-line bg-surface p-6 shadow-sm" style="--pop-delay: 700ms">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading text-base font-bold text-ink">Recent visitors</h3>
                    <p class="text-sm text-ink-muted">Who's actually been here, with a full click-by-click timeline.</p>
                </div>
                <a href="{{ route('admin.visitors.index') }}" class="shrink-0 text-sm font-semibold text-primary transition hover:text-primary-hover">View all &rarr;</a>
            </div>

            <div class="mt-5 divide-y divide-line">
                @forelse ($recentVisitors as $visitor)
                    <a href="{{ route('admin.visitors.show', $visitor) }}" class="flex items-center justify-between gap-4 py-3.5 first:pt-0 last:pb-0 transition hover:bg-surface-soft">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-ink">{{ $visitor->browser ?: 'Unknown browser' }} &middot; {{ ucfirst($visitor->device_type ?: 'unknown') }}</p>
                            <p class="truncate text-sm text-ink-muted">{{ $visitor->country_name ?: 'Unknown' }} &middot; {{ $visitor->ip_address }}</p>
                        </div>
                        <span class="shrink-0 text-xs text-ink-muted">{{ $visitor->last_seen_at?->diffForHumans() }}</span>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <span class="flex size-14 items-center justify-center rounded-full bg-surface-soft text-primary">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 21c-4.97-4-9-7.58-9-11.5A5.5 5.5 0 0 1 12 6a5.5 5.5 0 0 1 9 3.5C21 13.42 16.97 17 12 21Z M12 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                            </svg>
                        </span>
                        <p class="mt-4 text-sm font-semibold text-ink">No visitors identified yet</p>
                        <p class="mt-1 max-w-xs text-sm text-ink-muted">
                            Real visits to the site will start showing up here.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</x-admin.shell>

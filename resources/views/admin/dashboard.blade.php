@php
    $toneClasses = [
        'primary' => 'bg-primary-light text-primary',
        'accent' => 'bg-accent-light text-accent',
        'info' => 'bg-info-light text-info',
        'warning' => 'bg-warning-light text-warning',
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

    {{-- Stat cards --}}
    <div class="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $i => $stat)
            <{{ isset($stat['href']) ? 'a' : 'div' }}
                @if (isset($stat['href'])) href="{{ $stat['href'] }}" @endif
                class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] block rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md"
                style="animation-delay: {{ $i * 70 }}ms"
            >
                <span class="flex size-10 items-center justify-center rounded-xl {{ $toneClasses[$stat['tone']] }}">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="{{ $stat['icon'] }}"/>
                    </svg>
                </span>
                <p class="mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $stat['label'] }}</p>
                <p class="mt-1 font-heading text-3xl font-extrabold text-ink"
                   x-data="{ n: 0 }" x-init="let t = setInterval(() => { n < {{ $stat['value'] }} ? n++ : clearInterval(t) }, Math.max(600 / Math.max({{ $stat['value'] }}, 1), 12))"
                   x-text="n">0</p>
            </{{ isset($stat['href']) ? 'a' : 'div' }}>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm lg:col-span-2">
            <h3 class="font-heading text-base font-bold text-ink">Site activity — last 14 days</h3>
            <p class="text-sm text-ink-muted">New contact messages and newsletter subscribers, by day.</p>
            <div class="mt-6">
                <x-admin.line-chart :series="$activitySeries"/>
            </div>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h3 class="font-heading text-base font-bold text-ink">Article feedback</h3>
                <a href="{{ route('admin.feedback.index') }}" class="text-sm font-semibold text-primary hover:text-primary-hover">
                    By post &rarr;
                </a>
            </div>
            <p class="text-sm text-ink-muted">"Was this helpful?" votes, all articles.</p>
            <div class="mt-6">
                <x-admin.donut-chart :feedback="$feedback"/>
            </div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">

        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-base font-bold text-ink">Posts by category</h3>
            <p class="text-sm text-ink-muted">Every published blog article.</p>
            <div class="mt-6">
                <x-admin.bar-chart :data="$categoryData"/>
            </div>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading text-base font-bold text-ink">Recent messages</h3>
                    <p class="text-sm text-ink-muted">Latest submissions from the contact form.</p>
                </div>
                <a href="{{ route('admin.messages.index') }}" class="text-sm font-semibold text-primary hover:text-primary-hover">
                    View all &rarr;
                </a>
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
    </div>

</x-admin.shell>

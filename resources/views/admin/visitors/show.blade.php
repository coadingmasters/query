<x-admin.shell active="visitors" title="Visitor">

    <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <a href="{{ route('admin.visitors.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-ink-muted transition hover:text-primary">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            All visitors
        </a>
        <h2 class="mt-3 flex flex-wrap items-center gap-3 font-heading text-2xl font-extrabold tracking-tight text-ink">
            {{ $visitor->browser ?: 'Unknown browser' }} &middot; {{ ucfirst($visitor->device_type ?: 'unknown') }}
            @if ($visitor->is_likely_bot)
                <span class="rounded-full bg-danger-light px-2.5 py-1 text-xs font-bold tracking-wide text-danger uppercase">Likely bot</span>
            @endif
        </h2>
        <p class="mt-1 text-sm text-ink-muted">First seen {{ $visitor->first_seen_at?->format('M j, Y \a\t g:ia') }}, {{ $visitor->visits_count }} {{ Str::plural('visit', $visitor->visits_count) }} total.</p>
    </div>

    @php
        $facts = [
            ['label' => 'IP address', 'value' => $visitor->ip_address, 'icon' => 'M12 21c-4.97-4-9-7.58-9-11.5A5.5 5.5 0 0 1 12 6a5.5 5.5 0 0 1 9 3.5C21 13.42 16.97 17 12 21Z M12 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z'],
            ['label' => 'Country', 'value' => trim($visitor->country_flag.' '.($visitor->country_name ?: 'Unknown')), 'icon' => 'M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Z'],
            ['label' => 'Operating system', 'value' => $visitor->os ?: 'Unknown', 'icon' => 'M4 5h16a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z M8 21h8M12 17v4'],
            ['label' => 'Last seen', 'value' => $visitor->last_seen_at?->diffForHumans(), 'icon' => 'M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z'],
        ];
    @endphp

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($facts as $fact)
            <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                <span class="flex size-9 items-center justify-center rounded-xl bg-primary-light text-primary">
                    <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="{{ $fact['icon'] }}"/>
                    </svg>
                </span>
                <p class="mt-3 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $fact['label'] }}</p>
                <p class="mt-1 truncate font-heading text-base font-bold text-ink">{{ $fact['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 rounded-2xl border border-line bg-surface p-6 shadow-sm">
        <h3 class="font-heading text-base font-bold text-ink">Activity timeline</h3>
        <p class="text-sm text-ink-muted">Every page view and click from this visitor, newest first.</p>

        @if ($timeline->isEmpty())
            <p class="mt-6 text-sm text-ink-muted">Nothing logged yet.</p>
        @else
            <ol class="relative mt-6 space-y-1 border-l border-line pl-6">
                @foreach ($timeline as $event)
                    <li class="relative pb-4">
                        <span class="absolute top-1.5 -left-[27px] size-3 rounded-full border-2 border-surface {{ $event['type'] === 'click' ? 'bg-warning' : 'bg-primary' }}"></span>

                        @if ($event['type'] === 'view')
                            <p class="text-sm text-ink">
                                <span class="font-semibold">Viewed</span>
                                <span class="font-mono text-xs text-ink-muted">{{ $event['path'] }}</span>
                            </p>
                        @else
                            <p class="text-sm text-ink">
                                <span class="font-semibold">Clicked</span>
                                {{ $event['label'] ? '"'.Str::limit($event['label'], 60).'"' : 'an element' }}
                                @if ($event['href'])
                                    <span class="text-ink-muted">&rarr; <span class="font-mono text-xs">{{ $event['href'] }}</span></span>
                                @endif
                            </p>
                            <p class="text-xs text-ink-muted">on <span class="font-mono">{{ $event['path'] }}</span></p>
                        @endif

                        <p class="mt-0.5 text-xs text-ink-muted">{{ $event['at']?->format('M j, Y g:i:sa') }}</p>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>

</x-admin.shell>

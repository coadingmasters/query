<x-admin.shell active="visitors" title="Visitors">

    <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Visitors</h2>
        <p class="mt-1 text-sm text-ink-muted">Every browser that's been identified, with what they viewed and clicked.</p>
    </div>

    @php
        $statCards = [
            ['label' => 'Total visitors', 'value' => $counts['total'], 'tone' => 'primary', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M12.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z'],
            ['label' => 'New today', 'value' => $counts['today'], 'tone' => 'accent', 'icon' => 'M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8'],
            ['label' => 'Page views', 'value' => $counts['pageViews'], 'tone' => 'primary', 'icon' => 'M3 3v18h18M8 17V10M13 17V6M18 17v-4'],
            ['label' => 'Clicks logged', 'value' => $counts['clicks'], 'tone' => 'warning', 'icon' => 'M9 9v6l5-3-5-3Z M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z'],
        ];
        $toneClasses = [
            'primary' => 'bg-primary-light text-primary',
            'accent' => 'bg-accent-light text-accent-dark',
            'warning' => 'bg-warning-light text-warning',
        ];
    @endphp

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
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

    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-base font-bold text-ink">Device split</h3>
            <p class="text-sm text-ink-muted">Desktop, mobile and tablet, across every visitor on file.</p>
            @if ($deviceChart->isNotEmpty())
                <div class="mt-6">
                    <x-admin.wave-chart :data="$deviceChart" id="visitors-device"/>
                </div>
            @else
                <p class="mt-6 text-sm text-ink-muted">No visitors yet.</p>
            @endif
        </div>

        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-base font-bold text-ink">Top countries</h3>
            <p class="text-sm text-ink-muted">Where visitors are coming from, by IP.</p>
            @if ($countryChart->isNotEmpty())
                <div class="mt-6">
                    <x-admin.wave-chart :data="$countryChart" id="visitors-country"/>
                </div>
            @else
                <p class="mt-6 text-sm text-ink-muted">No located visitors yet.</p>
            @endif
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                        <th scope="col" class="px-5 py-3 font-semibold">Device</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Country</th>
                        <th scope="col" class="px-5 py-3 font-semibold">IP address</th>
                        <th scope="col" class="px-5 py-3 font-semibold">First seen</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Last seen</th>
                        <th scope="col" class="px-5 py-3 text-right font-semibold">Visits</th>
                        <th scope="col" class="px-5 py-3 text-right font-semibold">Views</th>
                        <th scope="col" class="px-5 py-3 text-right font-semibold">Clicks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($visitors as $visitor)
                        <tr class="cursor-pointer transition hover:bg-surface-soft" onclick="window.location='{{ route('admin.visitors.show', $visitor) }}'">
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-ink">{{ $visitor->browser ?: 'Unknown browser' }}</p>
                                <p class="text-xs text-ink-muted">{{ ucfirst($visitor->device_type ?: 'unknown') }} &middot; {{ $visitor->os ?: 'Unknown OS' }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-ink-muted">{{ $visitor->country_name ?: 'Unknown' }}</td>
                            <td class="px-5 py-3.5 font-mono text-xs text-ink-muted">{{ $visitor->ip_address }}</td>
                            <td class="px-5 py-3.5 text-ink-muted">{{ $visitor->first_seen_at?->format('M j, Y g:ia') }}</td>
                            <td class="px-5 py-3.5 text-ink-muted">{{ $visitor->last_seen_at?->format('M j, Y g:ia') }}</td>
                            <td class="px-5 py-3.5 text-right text-ink">{{ $visitor->visits_count }}</td>
                            <td class="px-5 py-3.5 text-right text-ink">{{ $visitor->page_views_count }}</td>
                            <td class="px-5 py-3.5 text-right text-ink">{{ $visitor->click_events_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <span class="flex size-14 items-center justify-center rounded-full bg-surface-soft text-primary">
                                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M12 21c-4.97-4-9-7.58-9-11.5A5.5 5.5 0 0 1 12 6a5.5 5.5 0 0 1 9 3.5C21 13.42 16.97 17 12 21Z M12 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                                        </svg>
                                    </span>
                                    <p class="mt-4 text-sm font-semibold text-ink">No visitors yet</p>
                                    <p class="mt-1 max-w-xs text-sm text-ink-muted">
                                        Real visits to the site will start showing up here.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($visitors->hasPages())
        <div class="mt-6">
            {{ $visitors->links() }}
        </div>
    @endif

</x-admin.shell>

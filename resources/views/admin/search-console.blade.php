@php
    $toneClasses = [
        'success' => 'bg-accent-light text-accent-dark',
        'danger' => 'bg-danger-light text-danger',
        'info' => 'bg-info-light text-info',
    ];
@endphp

<x-admin.shell active="search-console" title="Search Console">

    <div class="flex flex-wrap items-start justify-between gap-4 animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <div>
            <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Search Console</h2>
            <p class="mt-1 text-sm text-ink-muted">
                Live indexing status per URL and search performance, pulled directly from Google.
                @if ($lastChecked)
                    Last checked {{ $lastChecked->diffForHumans() }}.
                @endif
            </p>
        </div>
    </div>

    @if ($urls->isEmpty())
        <div class="mt-6 flex flex-col items-center justify-center rounded-2xl border border-line bg-surface py-16 text-center shadow-sm">
            <p class="text-sm font-semibold text-ink">No data yet</p>
            <p class="mt-1 max-w-sm text-sm text-ink-muted">
                Run <code class="rounded bg-surface-section px-1.5 py-0.5 text-xs">php artisan search-console:inspect</code>
                to pull the first sweep.
            </p>
        </div>
    @else
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            @foreach ([['Indexed', $counts['indexed'], 'success'], ['Not indexed', $counts['not_indexed'], 'danger'], ['URLs tracked', $counts['total'], 'info']] as $i => [$label, $value, $tone])
                <div class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 shadow-sm"
                     style="--pop-delay: {{ $i * 70 }}ms">
                    <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $label }}</p>
                    <p class="mt-1 font-heading text-3xl font-extrabold text-ink">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        @if ($metrics->isNotEmpty())
            <div class="mt-6 rounded-2xl border border-line bg-surface p-5 shadow-sm">
                <h3 class="font-heading text-base font-extrabold text-ink">Search performance</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-line text-xs tracking-wide text-ink-muted uppercase">
                                <th class="py-2 pr-4 font-semibold">Date</th>
                                <th class="py-2 pr-4 font-semibold">Clicks</th>
                                <th class="py-2 pr-4 font-semibold">Impressions</th>
                                <th class="py-2 pr-4 font-semibold">CTR</th>
                                <th class="py-2 font-semibold">Avg. position</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($metrics->sortByDesc('date')->take(14) as $m)
                                <tr>
                                    <td class="py-2 pr-4 text-ink">{{ $m->date->format('M j') }}</td>
                                    <td class="py-2 pr-4 text-ink-muted">{{ $m->clicks }}</td>
                                    <td class="py-2 pr-4 text-ink-muted">{{ $m->impressions }}</td>
                                    <td class="py-2 pr-4 text-ink-muted">{{ number_format($m->ctr * 100, 1) }}%</td>
                                    <td class="py-2 text-ink-muted">{{ number_format($m->position, 1) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="mt-6 rounded-2xl border border-line bg-surface shadow-sm">
            <div class="p-5 pb-0">
                <h3 class="font-heading text-base font-extrabold text-ink">URLs</h3>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-xs tracking-wide text-ink-muted uppercase">
                            <th class="px-5 py-2 font-semibold">URL</th>
                            <th class="px-5 py-2 font-semibold">Coverage state</th>
                            <th class="px-5 py-2 font-semibold">Last crawled</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($urls as $u)
                            <tr>
                                <td class="px-5 py-3 text-ink">/{{ Str::after($u->url, config('services.search_console.site_url')) }}</td>
                                <td class="px-5 py-3">
                                    <span @class([
                                        'rounded-full px-2.5 py-1 text-xs font-bold',
                                        $toneClasses[$u->isIndexed() ? 'success' : 'danger'],
                                    ])>{{ $u->coverage_state ?? 'Unknown' }}</span>
                                </td>
                                <td class="px-5 py-3 text-ink-muted">{{ $u->last_crawl_time?->diffForHumans() ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</x-admin.shell>

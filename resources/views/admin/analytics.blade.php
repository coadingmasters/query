@php
    $countCards = [
        ['label' => 'Pageviews today', 'value' => $counts['today'], 'tone' => 'primary', 'icon' => 'M3 3v18h18M8 17V10M13 17V6M18 17v-4'],
        ['label' => 'Pageviews this week', 'value' => $counts['week'], 'tone' => 'info', 'icon' => 'M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z'],
        ['label' => 'Pageviews this month', 'value' => $counts['month'], 'tone' => 'accent', 'icon' => 'M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z'],
    ];
    $insightCards = [
        ['label' => 'Most visited page', 'value' => $topPageLabel, 'tone' => 'warning', 'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z'],
        ['label' => 'Most used tool', 'value' => $topToolLabel, 'tone' => 'primary', 'icon' => 'M14.5 3.5a3.5 3.5 0 0 0-4.9 4.9L3 15l3 3 6.6-6.6a3.5 3.5 0 0 0 4.9-4.9L14.5 9.5 11.5 6.5l3-3Z'],
        ['label' => 'Top blog post', 'value' => $topPostLabel, 'tone' => 'accent', 'icon' => 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
    ];
    $toneClasses = [
        'primary' => 'bg-primary-light text-primary',
        'info' => 'bg-info-light text-info',
        'accent' => 'bg-accent-light text-accent-dark',
        'warning' => 'bg-warning-light text-warning',
    ];
@endphp

<x-admin.shell active="analytics" title="Analytics">

    <div class="flex flex-wrap items-start justify-between gap-4 animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <div>
            <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Analytics</h2>
            <p class="mt-1 text-sm text-ink-muted">
                Traffic on the site itself — page views, tool usage and where visitors come from, last 30 days unless
                noted. Search Console data (keywords, position, CTR) isn't connected yet.
            </p>
        </div>
    </div>

    @unless ($hasData)
        <div class="mt-6 flex flex-col items-center justify-center rounded-2xl border border-line bg-surface py-16 text-center shadow-sm">
            <p class="text-sm font-semibold text-ink">No traffic recorded yet</p>
            <p class="mt-1 max-w-sm text-sm text-ink-muted">
                Numbers will start filling in as soon as the site gets its next real visit — tracking went live with this page.
            </p>
        </div>
    @else
        {{-- Pageview counts --}}
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            @foreach ($countCards as $i => $stat)
                <div class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md"
                     style="--pop-delay: {{ $i * 70 }}ms">
                    <span class="flex size-10 items-center justify-center rounded-xl {{ $toneClasses[$stat['tone']] }}">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $stat['icon'] }}"/>
                        </svg>
                    </span>
                    <p class="mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $stat['label'] }}</p>
                    <p class="mt-1 font-heading text-3xl font-extrabold text-ink"
                       x-data="{ n: 0 }" x-init="let t = setInterval(() => { n < {{ $stat['value'] }} ? n++ : clearInterval(t) }, Math.max(1200 / Math.max({{ $stat['value'] }}, 1), 12))"
                       x-text="n">0</p>
                </div>
            @endforeach
        </div>

        {{-- Top-of-the-month insights --}}
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($insightCards as $i => $stat)
                <div class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md"
                     style="--pop-delay: {{ (3 + $i) * 70 }}ms">
                    <span class="flex size-10 items-center justify-center rounded-xl {{ $toneClasses[$stat['tone']] }}">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $stat['icon'] }}"/>
                        </svg>
                    </span>
                    <p class="mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $stat['label'] }}</p>
                    <p class="mt-1 line-clamp-2 font-heading text-base font-extrabold text-ink">{{ $stat['value'] ?: '—' }}</p>
                </div>
            @endforeach
            <div class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md"
                 style="--pop-delay: 490ms">
                <span class="flex size-10 items-center justify-center rounded-xl bg-info-light text-info">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M12.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"/>
                    </svg>
                </span>
                <p class="mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">New subscribers today</p>
                <p class="mt-1 font-heading text-3xl font-extrabold text-ink"
                   x-data="{ n: 0 }" x-init="let t = setInterval(() => { n < {{ $newSubscribersToday }} ? n++ : clearInterval(t) }, Math.max(1200 / Math.max({{ $newSubscribersToday }}, 1), 12))"
                   x-text="n">0</p>
            </div>
        </div>

        {{-- Traffic sources + top pages --}}
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
                <h3 class="font-heading text-base font-bold text-ink">Traffic sources</h3>
                <p class="text-sm text-ink-muted">Direct, organic search, social, or a referral link — last 30 days.</p>
                <div class="mt-6 max-w-lg">
                    <x-admin.wave-chart :data="$sourceChart" id="traffic-sources"/>
                </div>
            </div>

            <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
                <h3 class="font-heading text-base font-bold text-ink">Top pages</h3>
                <p class="text-sm text-ink-muted">Every page, ranked by views — last 30 days.</p>
                <div class="mt-6">
                    @forelse ($topPages as $i => $page)
                        <div class="{{ $i > 0 ? 'mt-4' : '' }}">
                            <div class="mb-1.5 flex items-center justify-between text-sm">
                                <span class="truncate font-semibold text-ink">{{ $page['label'] }}</span>
                                <span class="shrink-0 text-ink-muted">{{ $page['views'] }}</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-surface-soft">
                                <div class="admin-bar stagger-delay h-full rounded-full bg-primary-vivid"
                                     style="--admin-bar-percent: {{ (int) round($page['views'] / max($topPages[0]['views'], 1) * 100) }}%; --stagger-delay: {{ $i * 60 }}ms"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-ink-muted">No page views yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Tool usage + top posts --}}
        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
                <h3 class="font-heading text-base font-bold text-ink">Tool usage</h3>
                <p class="text-sm text-ink-muted">Visits to each calculator/tracker page — last 30 days.</p>
                <div class="mt-6">
                    @forelse ($toolUsage as $i => $tool)
                        <div class="{{ $i > 0 ? 'mt-4' : '' }}">
                            <div class="mb-1.5 flex items-center justify-between text-sm">
                                <span class="truncate font-semibold text-ink">{{ $tool['label'] }}</span>
                                <span class="shrink-0 text-ink-muted">{{ $tool['views'] }}</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-surface-soft">
                                <div class="admin-bar stagger-delay h-full rounded-full bg-accent-vivid"
                                     style="--admin-bar-percent: {{ (int) round($tool['views'] / max($toolUsage[0]['views'], 1) * 100) }}%; --stagger-delay: {{ $i * 60 }}ms"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-ink-muted">No tool visits yet.</p>
                    @endforelse
                </div>
                <p class="mt-5 text-xs text-ink-muted">
                    Completion rate and most-common-input stats aren't tracked yet — each tool's own JS would need to
                    report a "finished" event, which is a separate change from page-view tracking.
                </p>
            </div>

            <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
                <h3 class="font-heading text-base font-bold text-ink">Top blog posts</h3>
                <p class="text-sm text-ink-muted">Ranked by views — last 30 days.</p>
                <div class="mt-6">
                    @forelse ($topPosts as $i => $post)
                        <div class="{{ $i > 0 ? 'mt-4' : '' }}">
                            <div class="mb-1.5 flex items-center justify-between text-sm">
                                <span class="truncate font-semibold text-ink">{{ $post['label'] }}</span>
                                <span class="shrink-0 text-ink-muted">{{ $post['views'] }}</span>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-surface-soft">
                                <div class="admin-bar stagger-delay h-full rounded-full bg-warning-vivid"
                                     style="--admin-bar-percent: {{ (int) round($post['views'] / max($topPosts[0]['views'], 1) * 100) }}%; --stagger-delay: {{ $i * 60 }}ms"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-ink-muted">No post views yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endunless

</x-admin.shell>

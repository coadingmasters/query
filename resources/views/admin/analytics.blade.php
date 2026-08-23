@php
    $insightCards = [
        ['label' => 'Most visited page', 'value' => $topPageLabel, 'tone' => 'warning', 'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z'],
        ['label' => 'Most used tool', 'value' => $topToolLabel, 'tone' => 'primary', 'icon' => 'M14.5 3.5a3.5 3.5 0 0 0-4.9 4.9L3 15l3 3 6.6-6.6a3.5 3.5 0 0 0 4.9-4.9L14.5 9.5 11.5 6.5l3-3Z'],
        ['label' => 'Top blog post', 'value' => $topPostLabel, 'tone' => 'accent', 'icon' => 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
        ['label' => 'New subscribers today', 'value' => (string) $newSubscribersToday, 'tone' => 'info', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M12.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z'],
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
            @foreach ([['Pageviews today', $counts['today'], 'primary', 'M3 3v18h18M8 17V10M13 17V6M18 17v-4'], ['Pageviews this week', $counts['week'], 'info', 'M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z'], ['Pageviews this month', $counts['month'], 'accent', 'M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z']] as $i => [$cardLabel, $value, $tone, $icon])
                <div class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md"
                     style="--pop-delay: {{ $i * 70 }}ms">
                    <span class="flex size-10 items-center justify-center rounded-xl {{ $toneClasses[$tone] }}">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $icon }}"/>
                        </svg>
                    </span>
                    <p class="mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $cardLabel }}</p>
                    <p class="mt-1 font-heading text-3xl font-extrabold text-ink"
                       x-data="{ n: 0 }" x-init="let t = setInterval(() => { n < {{ $value }} ? n++ : clearInterval(t) }, Math.max(1200 / Math.max({{ $value }}, 1), 12))"
                       x-text="n">0</p>
                </div>
            @endforeach
        </div>

        {{-- Top-of-the-month insights — same card shape/height for all four --}}
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($insightCards as $i => $stat)
                <div class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] flex flex-col rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md"
                     style="--pop-delay: {{ (3 + $i) * 70 }}ms">
                    <span class="flex size-10 items-center justify-center rounded-xl {{ $toneClasses[$stat['tone']] }}">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $stat['icon'] }}"/>
                        </svg>
                    </span>
                    <p class="mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $stat['label'] }}</p>
                    <p class="mt-1 line-clamp-2 font-heading text-base font-extrabold text-ink">{{ $stat['value'] !== null && $stat['value'] !== '' ? $stat['value'] : '—' }}</p>
                </div>
            @endforeach
        </div>

        {{-- Chart cards: a colour wash and an icon chip per card, like the
             stat cards above, rather than a plain white box — and each chart
             type now actually fits its data. Traffic sources is four shares
             of a whole, which is what a pie chart is for; the three ranked
             lists become columns, which fill their card at every count
             instead of leaving a short list floating in empty space. --}}
        @php
            $chartCards = [
                ['key' => 'sources', 'title' => 'Traffic sources', 'sub' => 'Direct, organic search, social, or a referral link — last 30 days.', 'tone' => 'primary', 'icon' => 'M21 12a9 9 0 1 1-9-9M21 12h-9V3'],
                ['key' => 'pages', 'title' => 'Top pages', 'sub' => 'Every page, ranked by views — last 30 days.', 'tone' => 'warning', 'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z'],
                ['key' => 'tools', 'title' => 'Tool usage', 'sub' => 'Visits to each calculator/tracker page — last 30 days.', 'tone' => 'accent', 'icon' => 'M14.5 3.5a3.5 3.5 0 0 0-4.9 4.9L3 15l3 3 6.6-6.6a3.5 3.5 0 0 0 4.9-4.9L14.5 9.5 11.5 6.5l3-3Z'],
                ['key' => 'posts', 'title' => 'Top blog posts', 'sub' => 'Ranked by views — last 30 days.', 'tone' => 'info', 'icon' => 'M12 7.5v13M3.5 18.2a.8.8 0 0 1-.8-.8V4.9a.8.8 0 0 1 .8-.8h4.9A3.6 3.6 0 0 1 12 7.5a3.6 3.6 0 0 1 3.6-3.4h4.9a.8.8 0 0 1 .8.8v12.5a.8.8 0 0 1-.8.8h-5.4A3.1 3.1 0 0 0 12 20.5a3.1 3.1 0 0 0-3.1-2.3Z'],
            ];
            $washClasses = [
                'primary' => 'bg-primary-light',
                'warning' => 'bg-warning-light',
                'accent' => 'bg-accent-light',
                'info' => 'bg-info-light',
            ];
        @endphp

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            @foreach ($chartCards as $i => $card)
                <div class="stat-card-pop relative overflow-hidden rounded-2xl border border-line bg-surface p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                     style="--pop-delay: {{ (7 + $i) * 70 }}ms">
                    <span aria-hidden="true" @class([
                        'pointer-events-none absolute -top-14 -right-14 size-36 rounded-full blur-2xl opacity-60',
                        $washClasses[$card['tone']],
                    ])></span>

                    <div class="relative flex items-start gap-3">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl {{ $toneClasses[$card['tone']] }}">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $card['icon'] }}"/>
                            </svg>
                        </span>
                        <div>
                            <h3 class="font-heading text-base font-bold text-ink">{{ $card['title'] }}</h3>
                            <p class="text-sm text-ink-muted">{{ $card['sub'] }}</p>
                        </div>
                    </div>

                    <div class="relative mt-6">
                        @if ($card['key'] === 'sources')
                            <x-admin.pie-chart :data="$sourceChart"/>
                        @elseif ($card['key'] === 'pages')
                            <x-admin.column-chart :data="$topPages"/>
                        @elseif ($card['key'] === 'tools')
                            <x-admin.column-chart :data="$toolUsage"/>
                        @else
                            <x-admin.column-chart :data="$topPosts"/>
                        @endif
                    </div>

                    @if ($card['key'] === 'tools')
                        <p class="relative mt-5 text-xs text-ink-muted">
                            Completion rate and most-common-input stats aren't tracked yet — each tool's own JS would need
                            to report a "finished" event, which is a separate change from page-view tracking.
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @endunless

</x-admin.shell>

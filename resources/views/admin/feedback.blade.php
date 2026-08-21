<x-admin.shell active="feedback" title="Article Feedback">

    <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Article feedback</h2>
        <p class="mt-1 text-sm text-ink-muted">
            How readers answered "Was this helpful?" on every article, broken down by post.
        </p>
    </div>

    @php
        $statCards = [
            ['label' => 'Total votes', 'value' => $overall['total'], 'tone' => 'primary', 'icon' => 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
            ['label' => 'Helpful votes', 'value' => $totalHelpful, 'tone' => 'accent', 'icon' => 'M5 13l4 4L19 7'],
            ['label' => 'Not helpful votes', 'value' => $totalNotHelpful, 'tone' => 'warning', 'icon' => 'm6 6 12 12M18 6 6 18'],
        ];
        $toneClasses = [
            'primary' => 'bg-primary-light text-primary',
            'accent' => 'bg-accent-light text-accent-dark',
            'warning' => 'bg-warning-light text-warning',
        ];
    @endphp

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

    <div class="mt-6 rounded-2xl border border-line bg-surface p-6 shadow-sm">
        <h3 class="font-heading text-base font-bold text-ink">Overall sentiment</h3>
        <p class="text-sm text-ink-muted">"Was this helpful?" votes across every article combined.</p>
        <div class="mt-6">
            <x-admin.donut-chart :feedback="$overall"/>
        </div>
    </div>

    <div>
        <h3 class="font-heading text-base font-bold text-ink">By article</h3>

        @if ($articles->isEmpty())
            <div class="mt-4 flex flex-col items-center justify-center rounded-2xl border border-line bg-surface py-10 text-center shadow-sm">
                <span class="flex size-14 items-center justify-center rounded-full bg-surface-soft text-primary">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z"/>
                    </svg>
                </span>
                <p class="mt-4 text-sm font-semibold text-ink">No votes yet</p>
                <p class="mt-1 max-w-xs text-sm text-ink-muted">
                    "Was this helpful?" votes from articles will show up here, per post.
                </p>
            </div>
        @else
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($articles as $i => $article)
                    <a href="{{ route('blog.show', $article['slug']) }}" target="_blank" rel="noopener"
                       class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] block rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:border-primary/40 hover:shadow-md"
                       style="--pop-delay: {{ min($i, 8) * 60 }}ms">
                        <div class="flex items-start justify-between gap-2">
                            <p class="line-clamp-2 font-semibold text-ink">{{ $article['title'] }}</p>
                            <span class="shrink-0 text-xs font-semibold text-ink-muted">{{ $article['total'] }} {{ Str::plural('vote', $article['total']) }}</span>
                        </div>

                        <div class="mt-3 flex h-2 w-full overflow-hidden rounded-full bg-surface-soft">
                            <div class="admin-bar stagger-delay h-full bg-accent-vivid" style="--admin-bar-percent: {{ $article['helpful_percent'] }}%; --stagger-delay: {{ min($i, 8) * 90 }}ms"></div>
                        </div>

                        <div class="mt-3 flex items-center gap-3 text-xs text-ink-muted">
                            <span class="flex items-center gap-1.5">
                                <span class="size-2 rounded-full" style="background:var(--color-accent-vivid)"></span>
                                {{ $article['helpful'] }} helpful ({{ $article['helpful_percent'] }}%)
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="size-2 rounded-full bg-surface-soft ring-1 ring-inset ring-line"></span>
                                {{ $article['not_helpful'] }} not helpful
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</x-admin.shell>

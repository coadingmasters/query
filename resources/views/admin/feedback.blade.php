<x-admin.shell active="feedback" title="Article Feedback">

    <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Article feedback</h2>
        <p class="mt-1 text-sm text-ink-muted">
            How readers answered "Was this helpful?" on every article, broken down by post.
        </p>
    </div>

    <div class="mt-7 grid gap-4 sm:grid-cols-2 sm:max-w-md">
        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Helpful votes</p>
            <p class="mt-1 font-heading text-2xl font-extrabold text-ink">{{ $totalHelpful }}</p>
        </div>
        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Not helpful votes</p>
            <p class="mt-1 font-heading text-2xl font-extrabold text-ink">{{ $totalNotHelpful }}</p>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-line bg-surface p-6 shadow-sm">
        @forelse ($articles as $i => $article)
            <div class="{{ $i > 0 ? 'mt-6 border-t border-line pt-6' : '' }}">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <a href="{{ route('blog.show', $article['slug']) }}" target="_blank" rel="noopener"
                       class="font-semibold text-ink hover:text-primary">
                        {{ $article['title'] }}
                    </a>
                    <span class="text-sm font-semibold text-ink-muted">{{ $article['total'] }} {{ Str::plural('vote', $article['total']) }}</span>
                </div>

                <div class="mt-3 flex h-2.5 w-full overflow-hidden rounded-full bg-surface-soft">
                    <div class="admin-bar h-full bg-accent-vivid" style="--admin-bar-percent: {{ $article['helpful_percent'] }}%; animation-delay: {{ $i * 90 }}ms"></div>
                </div>

                <div class="mt-2 flex items-center gap-4 text-xs text-ink-muted">
                    <span class="flex items-center gap-1.5">
                        <span class="size-2 rounded-full" style="background:var(--color-accent-vivid)"></span>
                        {{ $article['helpful'] }} helpful ({{ $article['helpful_percent'] }}%)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="size-2 rounded-full bg-surface-soft ring-1 ring-inset ring-line"></span>
                        {{ $article['not_helpful'] }} not helpful
                    </span>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-10 text-center">
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
        @endforelse
    </div>

</x-admin.shell>

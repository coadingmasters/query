<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :noindex="true">

@php
    $typeMeta = [
        'Tool' => ['tone' => 'bg-primary-light text-primary', 'd' => 'M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0H5a2 2 0 0 1-2-2v-4m6 6h10a2 2 0 0 0 2-2v-4M3 9h18M3 15h18'],
        'Food guide' => ['tone' => 'bg-accent-light text-accent-dark', 'd' => 'M8 3v6a4 4 0 0 1-4 4 4 4 0 0 0 4 4v4|M16 3c-1.5 3-2 5-2 7a3 3 0 0 0 3 3h1v8'],
        'Blog' => ['tone' => 'bg-info-light text-info', 'd' => 'M12 7.5v13|M3.5 18.2a.8.8 0 0 1-.8-.8V4.9a.8.8 0 0 1 .8-.8h4.9A3.6 3.6 0 0 1 12 7.5a3.6 3.6 0 0 1 3.6-3.4h4.9a.8.8 0 0 1 .8.8v12.5a.8.8 0 0 1-.8.8h-5.4A3.1 3.1 0 0 0 12 20.5a3.1 3.1 0 0 0-3.1-2.3Z'],
    ];
@endphp

<section class="bg-surface-soft pt-10 pb-8">
    <div class="container-page">
        <h1 class="font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">Search</h1>

        <form method="GET" action="{{ route('search') }}" class="mt-6 max-w-lg" role="search">
            <label for="search-page-input" class="sr-only">Search cat care tools and guides</label>
            <div class="relative">
                <svg class="pointer-events-none absolute top-1/2 left-4 size-5 -translate-y-1/2 text-ink-muted"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/>
                </svg>
                <input id="search-page-input" name="q" type="search" value="{{ $query }}"
                       placeholder="Search cat care tools, guides & more…"
                       autocomplete="off"
                       class="w-full rounded-full border border-line bg-surface py-4 pr-16 pl-12 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                <button type="submit" aria-label="Search"
                        class="absolute top-1/2 right-2 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-primary-vivid text-ink transition hover:brightness-95">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </form>

        @if ($query !== '')
            <p class="mt-4 text-sm text-ink-muted">
                {{ $results->count() }} {{ Str::plural('result', $results->count()) }} for
                <span class="font-semibold text-ink">&ldquo;{{ $query }}&rdquo;</span>
            </p>
        @endif
    </div>
</section>

<section class="section-tight bg-surface">
    <div class="container-page max-w-3xl">
        @if ($query === '')
            <p class="rounded-xl border border-line bg-surface-soft p-6 text-center text-base text-ink-muted">
                Type something above to search tools, food guides and the blog.
            </p>
        @elseif ($results->isEmpty())
            <div class="rounded-xl border border-line bg-surface-soft p-8 text-center">
                <p class="text-base font-semibold text-ink">Nothing matches &ldquo;{{ $query }}&rdquo; yet</p>
                <p class="mt-1 text-sm text-ink-muted">
                    Try a shorter word, or
                    <a href="{{ route('contact') }}" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">ask for it</a>.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('tools.index') }}" class="btn-outline rounded-full px-6">Browse tools</a>
                    <a href="{{ route('blog.index') }}" class="btn-outline rounded-full px-6">Browse the blog</a>
                </div>
            </div>
        @else
            <ul class="space-y-3">
                @foreach ($results as $result)
                    <li>
                        <a href="{{ $result['url'] }}"
                           class="group flex items-start gap-4 rounded-xl border border-line bg-surface p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-line-strong hover:shadow-md sm:p-5">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-lg {{ $typeMeta[$result['type']]['tone'] }}">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    @foreach (explode('|', $typeMeta[$result['type']]['d']) as $d)
                                        <path d="{{ $d }}"/>
                                    @endforeach
                                </svg>
                            </span>
                            <span class="min-w-0">
                                <span class="text-xs font-bold tracking-wide text-ink-muted uppercase">{{ $result['type'] }}</span>
                                <span class="mt-0.5 block font-heading text-base leading-snug font-bold text-ink transition-colors group-hover:text-primary">
                                    {{ $result['title'] }}
                                </span>
                                <span class="mt-1 line-clamp-2 block text-sm leading-relaxed text-ink-muted">{{ $result['excerpt'] }}</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>

</x-layouts.app>

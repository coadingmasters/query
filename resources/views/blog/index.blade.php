<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    $featured = $live->first();
    // The featured article is shown large at the top, so it is not repeated
    // in the grid underneath.
    $rest = $posts->reject(fn (array $p): bool => $featured && $p['slug'] === $featured['slug']);
@endphp

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <x-paw-print class="paw absolute top-[18%] right-[6%] hidden size-9 text-primary-vivid/25 lg:block [animation-duration:24s]"/>
        <x-paw-print class="paw absolute bottom-[16%] left-[5%] hidden size-7 text-accent-vivid/30 lg:block [animation-delay:-7s] [animation-duration:21s]"/>
    </div>

    <div class="container-page relative max-w-3xl py-10 text-center lg:py-14">
        <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
            <x-paw-print class="size-4"/>
            The blog
        </p>

        <h1 class="mt-4 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
            Cat care, <span class="text-primary">explained properly</span>
        </h1>

        <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-ink-muted sm:text-lg">
            Longer answers for the questions a calculator cannot settle. Every
            guide is researched from published veterinary sources, and those
            sources are named at the foot of the page.
        </p>

        {{-- Counted, not claimed. It says how many are written rather than
             implying a library that is not there yet. --}}
        <p class="mt-6 inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2 text-sm font-medium text-ink shadow-sm">
            <span class="size-1.5 rounded-full bg-accent-vivid"></span>
            {{ $live->count() }} {{ Str::plural('guide', $live->count()) }} published,
            {{ $posts->count() - $live->count() }} in progress
        </p>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-10 w-full text-surface sm:h-14" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ 2. FEATURED ═══════════════════════════════════════════════════════ --}}
@if ($featured)
    <section data-post data-category="{{ $featured['category'] }}" class="bg-surface pt-4 pb-8 lg:pt-6">
        <div class="container-page">
            <a href="{{ $featured['url'] }}"
               class="reveal group grid overflow-hidden rounded-2xl border border-line bg-surface shadow-md transition hover:-translate-y-1 hover:shadow-lg lg:grid-cols-2">
                <div class="overflow-hidden bg-surface-section">
                    <x-img :name="$featured['image']" :alt="$featured['alt']"
                           sizes="(min-width: 1024px) 50vw, 100vw" :priority="true"/>
                </div>

                <div class="flex flex-col justify-center p-6 sm:p-9">
                    <div class="flex flex-wrap items-center gap-3 text-xs font-semibold">
                        <span class="rounded-full bg-primary-vivid px-2.5 py-1 text-ink">Latest</span>
                        <span class="rounded-full bg-primary-light px-2.5 py-1 text-primary-dark">{{ $featured['category'] }}</span>
                        <span class="text-ink-muted">{{ $featured['minutes'] }} min read</span>
                    </div>

                    <h2 class="mt-4 font-heading text-2xl leading-snug font-extrabold tracking-tight text-ink transition-colors group-hover:text-primary sm:text-3xl">
                        {{ $featured['title'] }}
                    </h2>

                    <p class="mt-3 text-base leading-relaxed text-ink-muted">{{ $featured['excerpt'] }}</p>

                    <span class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-primary">
                        Read the guide
                        <svg class="size-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                            <path d="m9 6 6 6-6 6"/>
                        </svg>
                    </span>
                </div>
            </a>
        </div>
    </section>
@endif

{{-- ══ 3. ALL GUIDES ═════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-12 lg:pb-16">
    <div class="container-page">
        <div class="reveal flex flex-wrap items-end justify-between gap-4">
            <h2 data-grid-heading class="font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                {{ $featured ? 'More guides' : 'All guides' }}
            </h2>

            {{-- Filters what is already on the page, so there is no request and
                 nothing arrives late. --}}
            <div class="flex flex-wrap gap-2" role="group" aria-label="Filter by category">
                <button type="button" data-filter-category="" aria-pressed="true"
                        class="rounded-full border border-line bg-surface px-3.5 py-1.5 text-sm font-semibold text-ink-muted transition aria-pressed:border-primary aria-pressed:bg-primary-light aria-pressed:text-primary">
                    All
                </button>
                @foreach ($categories as $category)
                    <button type="button" data-filter-category="{{ $category }}" aria-pressed="false"
                            class="rounded-full border border-line bg-surface px-3.5 py-1.5 text-sm font-semibold text-ink-muted transition aria-pressed:border-primary aria-pressed:bg-primary-light aria-pressed:text-primary">
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        </div>

        <ul class="mt-7 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($rest as $post)
                @php $isLive = isset($post['url']); @endphp
                <li data-post data-category="{{ $post['category'] }}">
                    <{{ $isLive ? 'a' : 'div' }}
                        @if ($isLive) href="{{ $post['url'] }}" @endif
                        @class([
                            'group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-surface shadow-sm transition',
                            'hover:-translate-y-1 hover:border-line-strong hover:shadow-md' => $isLive,
                        ])>
                        <div class="relative overflow-hidden bg-surface-section">
                            <x-img :name="$post['image']" :alt="$post['alt']"
                                   sizes="(max-width: 639px) 92vw, (max-width: 1023px) 46vw, 30vw"/>

                            @unless ($isLive)
                                <span class="absolute top-3 right-3 rounded-full bg-surface/90 px-3 py-1 text-xs font-bold text-ink-muted shadow-sm">
                                    Coming soon
                                </span>
                            @endunless
                        </div>

                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-center gap-3 text-xs font-semibold">
                                <span class="rounded-full bg-primary-light px-2.5 py-1 text-primary-dark">{{ $post['category'] }}</span>
                                <span class="text-ink-muted">{{ $post['minutes'] }} min read</span>
                            </div>

                            <h3 @class([
                                'mt-3 font-heading text-lg leading-snug font-bold text-ink transition-colors',
                                'group-hover:text-primary' => $isLive,
                            ])>{{ $post['title'] }}</h3>

                            <p class="mt-2 flex-1 text-sm leading-relaxed text-ink-muted">{{ $post['excerpt'] }}</p>

                            @if ($isLive)
                                <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary">
                                    Read the guide
                                    <svg class="size-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                         aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                                </span>
                            @else
                                <span class="mt-4 text-sm font-semibold text-ink-muted">Being written</span>
                            @endif
                        </div>
                    </{{ $isLive ? 'a' : 'div' }}>
                </li>
            @endforeach
        </ul>

        <p data-empty hidden class="mt-8 rounded-xl border border-line bg-surface-soft p-6 text-center text-base text-ink-muted">
            Nothing in that category yet.
        </p>
    </div>
</section>

{{-- ══ 4. CTA ════════════════════════════════════════════════════════════ --}}
<section class="bg-surface-section py-10 lg:py-14">
    <div class="container-page max-w-4xl">
        <div class="reveal grid overflow-hidden rounded-2xl bg-accent-light sm:grid-cols-[auto_minmax(0,1fr)]">
            <div aria-hidden="true" class="hidden w-52 self-end sm:block">
                <div class="aspect-[3/2]">
                    <x-img name="purrquery-cat-waving-paw" alt="Cat waving a paw" sizes="208px" fit="contain"/>
                </div>
            </div>

            <div class="px-6 py-9 text-center sm:px-8 sm:text-left">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">
                    Want a faster answer?
                </h2>
                <p class="mt-2 text-base leading-relaxed text-ink-muted">
                    The calculators handle age, due dates and portions in seconds,
                    without an account.
                </p>
                <div class="mt-5 flex flex-wrap justify-center gap-3 sm:justify-start">
                    <a href="/#tools" class="btn-primary rounded-full px-7">Try free tools</a>
                    <a href="{{ route('faq') }}" class="btn-outline rounded-full bg-surface px-7">Read the FAQ</a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        (() => {
            const buttons = [...document.querySelectorAll('[data-filter-category]')];
            const posts = [...document.querySelectorAll('[data-post]')];
            const empty = document.querySelector('[data-empty]');
            const heading = document.querySelector('[data-grid-heading]');
            if (!buttons.length) return;

            buttons.forEach(button => button.addEventListener('click', () => {
                const wanted = button.dataset.filterCategory;

                buttons.forEach(b => b.setAttribute('aria-pressed', String(b === button)));

                let shown = 0;
                let inGrid = 0;

                posts.forEach(post => {
                    const match = !wanted || post.dataset.category === wanted;
                    post.hidden = !match;
                    if (!match) return;
                    shown++;
                    if (post.closest('ul')) inGrid++;
                });

                // "More guides" describes the grid, so it goes when the grid
                // is empty even though the featured card above still matches.
                heading?.toggleAttribute('hidden', inGrid === 0);
                empty.toggleAttribute('hidden', shown > 0);
            }));
        })();
    </script>
@endpush

</x-layouts.app>

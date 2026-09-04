<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <div class="absolute -top-24 -left-20 size-96 rounded-full bg-primary-vivid opacity-[0.06] blur-3xl"></div>
        <x-paw-print class="paw absolute top-[14%] right-[36%] hidden size-8 text-primary lg:block [animation-duration:23s]"/>
        <x-paw-print class="paw absolute top-[30%] right-[3%] hidden size-10 text-primary lg:block [animation-delay:-6s] [animation-duration:26s]"/>
        <x-paw-print class="paw absolute bottom-[18%] right-[28%] hidden size-6 text-primary lg:block [animation-delay:-11s] [animation-duration:20s]"/>
    </div>

    <div class="container-page relative grid items-center gap-8 py-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.9fr)] lg:gap-6 lg:py-4">

        <div class="relative z-10 lg:py-10">
            <p class="inline-flex items-center gap-2 rounded-full bg-surface px-3.5 py-1.5 text-xs font-bold tracking-[0.14em] text-primary uppercase shadow-sm">
                <x-paw-print class="size-3.5"/>
                The {{ config('app.name') }} blog
            </p>

            <h1 class="mt-5 font-heading text-4xl leading-[1.06] font-extrabold tracking-tight text-ink sm:text-5xl lg:text-[3.4rem]">
                Cat care knowledge<br>
                <span class="text-primary">you can trust</span>
                <svg class="inline-block size-8 align-middle text-primary-vivid sm:size-10" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M12 20.5c-3.6-2.2-7-4.6-7-8.4A3.9 3.9 0 0 1 12 9.6a3.9 3.9 0 0 1 7 2.5c0 3.8-3.4 6.2-7 8.4Z"/>
                </svg>
            </h1>

            <p class="mt-5 max-w-md text-base leading-relaxed text-ink-muted">
                Practical guides written from published veterinary sources, with
                those sources named, so you can check the answer rather than
                trust it.
            </p>

            {{-- Filters the cards already on the page. Nothing is fetched, so
                 there is no request and no empty state arriving late. --}}
            <form class="mt-7 max-w-md" role="search" onsubmit="return false">
                <label for="blog-search" class="sr-only">Search the guides</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute top-1/2 left-4 size-5 -translate-y-1/2 text-ink-muted"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/>
                    </svg>
                    <input id="blog-search" type="search" data-blog-search autocomplete="off"
                           placeholder="Search blog articles…"
                           class="w-full rounded-full border border-line bg-surface py-3.5 pr-28 pl-12 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    <span aria-hidden="true"
                          class="absolute top-1/2 right-1.5 -translate-y-1/2 rounded-full bg-primary-vivid px-5 py-2 text-sm font-bold text-ink">
                        Search
                    </span>
                </div>
            </form>

            <p data-blog-status role="status" aria-live="polite" class="mt-3 text-sm text-ink-muted"></p>

            {{-- Three statements of fact. The design offered "vet-reviewed
                 content, trusted by experts"; no vet has reviewed this, so it
                 is not claimed. --}}
            <ul class="mt-6 flex flex-wrap gap-x-5 gap-y-3">
                @foreach ([
                    ['Published sources', 'Named on every guide', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z|m9.4 12.2 1.9 1.9 3.6-3.7'],
                    ['Practical and clear', 'No filler, no hedging', 'M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8Z|M14 3v5h5|M9 13h6M9 17h4'],
                    ['Made for cat owners', 'Free, and no sign-up', 'M12 20.5c-3.6-2.2-7-4.6-7-8.4A3.9 3.9 0 0 1 12 9.6a3.9 3.9 0 0 1 7 2.5c0 3.8-3.4 6.2-7 8.4Z'],
                ] as [$label, $sub, $paths])
                    <li class="flex items-center gap-2.5">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-surface text-primary shadow-sm">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                @foreach (explode('|', $paths) as $d)<path d="{{ $d }}"/>@endforeach
                            </svg>
                        </span>
                        <span class="leading-tight">
                            <span class="block text-sm font-bold text-ink">{{ $label }}</span>
                            <span class="mt-0.5 block text-xs text-ink-muted">{{ $sub }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="lg:-mr-8 lg:self-end">
            <div class="aspect-[3/2]">
                <x-img name="kitten-making-biscuits"
                       alt="Tabby kitten resting on a soft pink blanket"
                       sizes="(min-width: 1024px) 46vw, 92vw" fit="cover" :priority="true"/>
            </div>
        </div>
    </div>
</section>

{{-- ══ 2. TOPICS ═════════════════════════════════════════════════════════ --}}
<section class="bg-surface pt-8 pb-2">
    <div class="container-page">
        <div class="flex items-end justify-between gap-4">
            <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">Browse by topic</h2>
            <span class="text-sm text-ink-muted">{{ $posts->count() }} guides</span>
        </div>

        {{-- Scrolls sideways rather than wrapping, so the row keeps its shape
             as topics are added. --}}
        <div class="-mx-4 mt-5 overflow-x-auto px-4 pb-2 sm:mx-0 sm:px-0">
            <div class="flex w-max gap-2.5" role="group" aria-label="Filter by topic">
                <button type="button" data-filter-category="" aria-pressed="true"
                        class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted shadow-sm transition hover:border-line-strong aria-pressed:border-primary aria-pressed:bg-primary-light aria-pressed:text-primary">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z"/>
                    </svg>
                    All topics
                </button>

                @foreach ($categories as $category)
                    <button type="button" data-filter-category="{{ $category }}" aria-pressed="false"
                            class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted shadow-sm transition hover:border-line-strong aria-pressed:border-primary aria-pressed:bg-primary-light aria-pressed:text-primary">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            @foreach ($icons[$category] ?? [] as $d)<path d="{{ $d }}"/>@endforeach
                        </svg>
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══ 3. FEATURED ═══════════════════════════════════════════════════════ --}}
@if ($featured)
    <section class="bg-surface py-8">
        <div class="container-page">
            <h2 class="flex items-center gap-2 font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">
                <x-paw-print class="size-5 text-primary"/>
                Featured this week
            </h2>

            <div class="mt-5 grid gap-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(0,1fr)] lg:gap-8">

                {{-- The lead. Text sits on the photograph, so it carries a
                     gradient heavy enough to hold white type at any crop. --}}
                <article class="lg:h-full" data-post data-category="{{ $featured->category?->name }}"
                         data-terms="{{ Str::lower($featured->title.' '.$featured->excerpt) }}">
                    <a href="{{ route('blog.show', $featured->slug) }}"
                        class="group relative flex aspect-[4/3] flex-col justify-end overflow-hidden rounded-2xl shadow-lg sm:aspect-[16/10] lg:aspect-auto lg:h-full lg:min-h-[28rem]">
                        <div class="absolute inset-0">
                            <x-post-image :post="$featured"
                                   class="transition-transform duration-500 group-hover:scale-105"/>
                        </div>

                        <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/55 to-ink/5"></div>

                        <span class="absolute top-5 left-5 rounded-full bg-primary-vivid px-3 py-1 text-xs font-bold text-ink shadow-md">
                            Featured
                        </span>

                        <div class="relative p-6 sm:p-8">
                            <div class="flex flex-wrap items-center gap-2.5 text-xs font-semibold">
                                <span class="rounded-full bg-surface/95 px-2.5 py-1 text-ink">{{ $featured->category?->name }}</span>
                                <span class="text-ink-inverse">{{ $featured->reading_time }} min read</span>
                            </div>

                            <h3 class="mt-3 font-heading text-2xl leading-snug font-extrabold tracking-tight text-ink-inverse sm:text-3xl">
                                {{ $featured->title }}
                            </h3>

                            <p class="mt-2.5 max-w-lg text-sm leading-relaxed text-ink-inverse/85">
                                {{ $featured->excerpt }}
                            </p>

                            <span class="mt-5 inline-flex items-center gap-2 rounded-full bg-surface px-5 py-2.5 text-sm font-bold text-ink shadow-md transition group-hover:gap-3">
                                Read the guide
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                            </span>
                        </div>
                    </a>
                </article>

                {{-- The rest of the week, headline first. --}}
                <ul class="divide-y divide-line">
                    @foreach ($side as $post)
                        <li data-post data-category="{{ $post->category?->name }}"
                            data-terms="{{ Str::lower($post->title.' '.$post->excerpt) }}">
                            <a href="{{ route('blog.show', $post->slug) }}"
                                class="group flex items-start gap-4 rounded-xl py-4 transition hover:bg-surface-soft">
                                <div class="relative size-24 shrink-0 overflow-hidden rounded-xl bg-surface-section sm:size-28">
                                    <x-post-image :post="$post"/>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                                        <span class="rounded-full bg-primary-light px-2.5 py-0.5 text-primary-dark">{{ $post->category?->name }}</span>
                                        <span class="text-ink-muted">{{ $post->reading_time }} min read</span>
                                    </div>

                                    <h3 class="mt-1.5 font-heading text-base leading-snug font-bold text-ink transition-colors group-hover:text-primary">
                                        {{ $post->title }}</h3>

                                    <p class="mt-1 line-clamp-2 text-sm leading-relaxed text-ink-muted">{{ $post->excerpt }}</p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
@endif

{{-- ══ 4. ALL GUIDES ═════════════════════════════════════════════════════ --}}
<section class="bg-surface pt-4 pb-12">
    <div class="container-page">
        <div class="flex items-end justify-between gap-4">
            <h2 data-grid-heading class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">
                All guides
            </h2>
            <span class="text-sm text-ink-muted">
                {{ $posts->count() }} {{ Str::plural('guide', $posts->count()) }}
            </span>
        </div>

        {{-- A true mosaic, not a uniform grid: eight posts at a time fill an
             exact 4-column x 3-row block (one 2x2 feature, one 2x1 wide, six
             1x1 small tiles = 12 cells, zero leftover gaps), so mixed tile
             sizes sit inside the same row the way a news portal's grid does,
             not just "one big card then a uniform row" like a print layout.
             Below lg it collapses to a plain 2-up grid, since a spanning
             mosaic only reads clearly at real desktop width. --}}
        @php
            // Position within each group of 8 -> tile treatment. Units:
            // feature=2x2 (4 cells), wide=2x1 (2 cells), small=1x1 (1 cell).
            // 4 + 2 + 6x1 = 12 cells = a full 4x3 block, so the grid tiles
            // exactly instead of leaving a ragged edge at the end of a row.
            $tileTypes = ['feature', 'small', 'small', 'small', 'small', 'wide', 'small', 'small'];
        @endphp
        <div class="mt-5 space-y-5" data-magazine-sections>
            @foreach ($posts->chunk(8) as $chunk)
                <ul class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:auto-rows-[172px] lg:grid-flow-dense">
                    @foreach ($chunk->values() as $i => $post)
                        @php $type = $tileTypes[$i] ?? 'small'; @endphp
                        <li data-post data-category="{{ $post->category?->name }}"
                            data-terms="{{ Str::lower($post->title.' '.$post->excerpt) }}"
                            @class([
                                'col-span-2 sm:col-span-4 lg:col-span-2 lg:row-span-2' => $type === 'feature',
                                'col-span-2 sm:col-span-4 lg:col-span-2 lg:row-span-1' => $type === 'wide',
                                'col-span-1 lg:row-span-1' => $type === 'small',
                            ])>
                            <a href="{{ route('blog.show', $post->slug) }}"
                                @class([
                                    'group flex h-full overflow-hidden rounded-2xl border border-line bg-surface shadow-sm transition hover:-translate-y-1 hover:border-line-strong hover:shadow-lg',
                                    'flex-col' => $type !== 'wide',
                                    'items-center gap-4 p-3' => $type === 'wide',
                                ])>

                                @if ($type === 'feature')
                                    <div class="relative aspect-[16/10] shrink-0 overflow-hidden bg-surface-section lg:aspect-auto lg:h-[150px]">
                                        <x-post-image :post="$post" class="transition-transform duration-500 group-hover:scale-105"/>
                                    </div>
                                    <div class="flex flex-1 flex-col overflow-hidden p-4">
                                        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                                            <span class="rounded-full bg-primary-light px-2.5 py-0.5 text-primary-dark">{{ $post->category?->name }}</span>
                                            <span class="text-ink-muted">{{ $post->reading_time }} min read</span>
                                        </div>
                                        <h3 class="mt-2 line-clamp-2 font-heading text-lg leading-snug font-extrabold tracking-tight text-ink transition-colors group-hover:text-primary">
                                            {{ $post->title }}</h3>
                                        <p class="mt-1.5 line-clamp-2 text-xs leading-relaxed text-ink-muted">{{ $post->excerpt }}</p>
                                        <span class="mt-auto inline-flex w-fit items-center gap-1.5 pt-2 text-sm font-semibold text-primary">
                                            Read the guide
                                            <svg class="size-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                                        </span>
                                    </div>
                                @elseif ($type === 'wide')
                                    <div class="relative aspect-square h-full w-28 shrink-0 overflow-hidden rounded-xl bg-surface-section sm:w-32 lg:h-full lg:w-36">
                                        <x-post-image :post="$post" class="transition-transform duration-500 group-hover:scale-105"/>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-xs font-semibold text-primary-dark">{{ $post->category?->name }}</span>
                                        <h3 class="mt-1 line-clamp-2 font-heading text-sm leading-snug font-bold text-ink transition-colors group-hover:text-primary sm:text-base">
                                            {{ $post->title }}</h3>
                                    </div>
                                @else
                                    <div class="relative aspect-[4/3] shrink-0 overflow-hidden bg-surface-section lg:aspect-auto lg:h-[92px]">
                                        <x-post-image :post="$post" class="transition-transform duration-500 group-hover:scale-105"/>
                                    </div>
                                    <div class="flex flex-1 flex-col justify-center p-3">
                                        <span class="text-[10px] font-semibold tracking-wide text-primary-dark uppercase">{{ $post->category?->name }}</span>
                                        <h3 class="mt-1 line-clamp-2 font-heading text-xs leading-snug font-bold text-ink transition-colors group-hover:text-primary sm:text-sm">
                                            {{ $post->title }}</h3>
                                    </div>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>

        <p data-empty hidden class="mt-8 rounded-xl border border-line bg-surface-soft p-6 text-center text-base text-ink-muted">
            Nothing matches that yet. Try another topic, or
            <a href="{{ route('contact') }}" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">ask for it</a>.
        </p>
    </div>
</section>

{{-- ══ 5. NEWSLETTER ═════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-14">
    <div class="container-page">
        <div class="relative grid overflow-hidden rounded-2xl bg-surface-soft sm:grid-cols-[auto_minmax(0,1fr)]">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <x-paw-print class="paw absolute top-[18%] right-[6%] hidden size-8 text-primary-vivid/25 sm:block [animation-duration:24s]"/>
                <x-paw-print class="paw absolute bottom-[14%] left-[26%] hidden size-6 text-primary-vivid/20 sm:block [animation-delay:-8s] [animation-duration:21s]"/>
            </div>

            <div aria-hidden="true" class="relative hidden w-52 self-end sm:block lg:w-60">
                <div class="aspect-[3/2]">
                    <x-img name="purrquery-cat-waving-paw" alt="Cat waving a paw" sizes="240px" fit="contain"/>
                </div>
            </div>

            <div class="relative px-6 py-9 text-center sm:px-8 sm:text-left">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">
                    New guides, straight to your inbox
                </h2>
                <p class="mt-2 text-base leading-relaxed text-ink-muted">
                    One email a month. No selling your address, and one click to leave.
                </p>

                <form method="POST" action="{{ route('subscribe') }}"
                      class="mt-5 flex max-w-md flex-col gap-3 sm:flex-row">
                    @csrf
                    <label for="blog-email" class="sr-only">Email address</label>
                    <input id="blog-email" name="email" type="email" required
                           placeholder="Enter your email" autocomplete="email"
                           class="w-full rounded-full border border-line bg-surface px-5 py-3 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                    {{-- Honeypot: a real person never sees this, so anything that
                         fills it in is a bot. --}}
                    <div class="absolute left-[-9999px]" aria-hidden="true">
                        <label for="blog-website">Leave this empty</label>
                        <input id="blog-website" name="website" type="text" tabindex="-1" autocomplete="off">
                    </div>

                    <button type="submit" class="btn-primary shrink-0 rounded-full px-7">Subscribe</button>
                </form>
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
            const search = document.querySelector('[data-blog-search]');
            const status = document.querySelector('[data-blog-status]');

            // Arriving from a topic link elsewhere on the site.
            const wanted = new URLSearchParams(location.search).get('topic') ?? '';
            let category = buttons.some(b => b.dataset.filterCategory === wanted) ? wanted : '';

            const apply = () => {
                const term = (search?.value ?? '').trim().toLowerCase();
                let shown = 0;

                posts.forEach(post => {
                    const byCategory = !category || post.dataset.category === category;
                    const byTerm = !term || (post.dataset.terms ?? '').includes(term);
                    const match = byCategory && byTerm;

                    post.hidden = !match;
                    if (match) shown++;
                });

                // Every card appears in more than one section, so the count is
                // of distinct guides rather than of cards on screen.
                const distinct = new Set(
                    posts.filter(p => !p.hidden).map(p => p.dataset.terms)
                ).size;

                empty.toggleAttribute('hidden', shown > 0);
                heading?.toggleAttribute('hidden', shown === 0);

                status.textContent = (term || category)
                    ? `${distinct} ${distinct === 1 ? 'guide' : 'guides'} match`
                    : '';
            };

            buttons.forEach(button => button.addEventListener('click', () => {
                category = button.dataset.filterCategory;
                buttons.forEach(b => b.setAttribute('aria-pressed', String(b === button)));
                apply();
            }));

            search?.addEventListener('input', apply);

            if (category) {
                buttons.forEach(b => b.setAttribute('aria-pressed', String(b.dataset.filterCategory === category)));
                apply();
            }
        })();
    </script>
@endpush

</x-layouts.app>

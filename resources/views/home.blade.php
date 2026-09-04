<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema" footer-wave="text-surface-soft">

@push('head')
    {{-- The hero image is the largest thing painted on load, so the browser is
         told about it in the head. Without this it is only discovered once the
         markup is parsed, which delays the Largest Contentful Paint. --}}
    <link rel="preload" as="image" fetchpriority="high"
          href="{{ \App\Support\Images::get('purrquery-hero-cat-owner-smiling')['src'] }}"
          imagesrcset="{{ \App\Support\Images::get('purrquery-hero-cat-owner-smiling')['srcset'] }}"
          imagesizes="(max-width: 1023px) 92vw, 780px">
@endpush

{{-- ══ 1. Hero ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft pb-10 lg:pb-14">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-24 size-96 rounded-full bg-primary-vivid opacity-[0.07] blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 size-80 rounded-full bg-accent-vivid opacity-[0.12] blur-3xl"></div>

        @foreach ([
            'left-[4%] top-[16%] size-10 text-primary [animation-delay:0s] [animation-duration:22s]',
            'left-[13%] top-[68%] size-7 text-accent-vivid [animation-delay:-6s] [animation-duration:26s]',
            'left-[30%] top-[8%] size-6 text-primary [animation-delay:-11s] [animation-duration:19s]',
            'right-[6%] top-[10%] size-9 text-accent-vivid [animation-delay:-3s] [animation-duration:24s]',
            'left-[46%] bottom-[16%] size-6 text-accent-vivid [animation-delay:-8s] [animation-duration:21s]',
        ] as $classes)
            <x-paw-print class="paw absolute {{ $classes }}"/>
        @endforeach
    </div>

    <div class="container-page relative grid items-center gap-8 py-8 lg:grid-cols-2 lg:gap-10 xl:grid-cols-[1fr_1.14fr] lg:py-10">
        <div>
            <h1 class="hero-in font-heading text-4xl leading-[1.08] font-extrabold tracking-tight text-ink sm:text-5xl" style="--i: 0">
                Everything You Need for a
                <span class="text-primary">Healthy,</span>
                <span class="text-accent">Happy</span> Cat
            </h1>

            <p class="hero-in mt-4 max-w-xl text-base leading-relaxed text-ink-muted sm:text-lg" style="--i: 1">
                Free smart tools and clear, sourced answers, all in one place,
                with no account and nothing to install.
            </p>

            {{-- A real search: suggestions as you type, Enter (or the button)
                 goes to a results page. Plain GET means it works with no JS
                 at all — the dropdown is a progressive enhancement on top. --}}
            <form method="GET" action="{{ route('search') }}" class="hero-in relative mt-6 max-w-lg" role="search" style="--i: 2"
                  x-data="heroSearch()" @click.outside="open = false">
                <label for="site-search" class="sr-only">Search cat care tools and guides</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute top-1/2 left-4 size-5 -translate-y-1/2 text-ink-muted"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/>
                    </svg>
                    <input id="site-search" name="q" type="search"
                           x-model="query"
                           @input.debounce.250ms="fetchSuggestions()"
                           @focus="if (query.trim()) open = true"
                           @keydown.down.prevent="move(1)"
                           @keydown.up.prevent="move(-1)"
                           @keydown.enter="onEnter($event)"
                           placeholder="Search cat care tools, guides & more…"
                           autocomplete="off" role="combobox" aria-expanded="open" aria-controls="site-search-results"
                           class="w-full rounded-full border border-line bg-surface py-4 pr-16 pl-12 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    <button type="submit" aria-label="Search"
                            class="absolute top-1/2 right-2 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-primary-vivid text-ink transition hover:brightness-95">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <div id="site-search-results" x-show="open" x-cloak x-transition.opacity.duration.150ms
                     class="absolute inset-x-0 top-full z-20 mt-2 overflow-hidden rounded-2xl border border-line bg-surface shadow-lg">
                    <template x-if="loading">
                        <p class="px-4 py-3 text-sm text-ink-muted">Searching…</p>
                    </template>
                    <template x-if="!loading && items.length === 0">
                        <p class="px-4 py-3 text-sm text-ink-muted">No matches yet, press Enter to search anyway.</p>
                    </template>
                    <template x-for="(item, i) in items" :key="item.url">
                        <a :href="item.url"
                           class="flex items-center gap-3 border-b border-line px-4 py-3 text-left transition last:border-b-0"
                           :class="i === active ? 'bg-primary-light' : 'hover:bg-surface-soft'"
                           @mouseenter="active = i">
                            <span class="shrink-0 rounded-full bg-primary-light px-2 py-0.5 text-[11px] font-bold text-primary-dark" x-text="item.type"></span>
                            <span class="truncate text-sm font-semibold text-ink" x-text="item.title"></span>
                        </a>
                    </template>
                    <a :href="'{{ route('search') }}?q=' + encodeURIComponent(query)"
                       class="block border-t border-line bg-surface-soft px-4 py-2.5 text-center text-sm font-semibold text-primary hover:bg-primary-light">
                        View all results for &ldquo;<span x-text="query"></span>&rdquo;
                    </a>
                </div>

                <p data-search-status class="sr-only" role="status" aria-live="polite" x-text="statusText"></p>
            </form>

            <div class="hero-in mt-5 flex flex-wrap gap-3" style="--i: 3">
                <a href="#tools" class="btn-primary rounded-full px-7 transition-transform duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                    Explore Free Tools
                    <svg class="size-4 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M13 2 4.5 12.5a.6.6 0 0 0 .5 1h4.5l-1 7.5a.3.3 0 0 0 .54.24L17.5 10.5a.6.6 0 0 0-.47-1H12.5l1-7.2a.3.3 0 0 0-.5-.3Z"/>
                    </svg>
                </a>
                <a href="#food-guides" class="btn-outline rounded-full px-7 transition-transform duration-200 hover:-translate-y-0.5 hover:shadow-md">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 7.5v13"/>
                        <path d="M3.5 18.2a.8.8 0 0 1-.8-.8V4.9a.8.8 0 0 1 .8-.8h4.9A3.6 3.6 0 0 1 12 7.5a3.6 3.6 0 0 1 3.6-3.4h4.9a.8.8 0 0 1 .8.8v12.5a.8.8 0 0 1-.8.8h-5.4A3.1 3.1 0 0 0 12 20.5a3.1 3.1 0 0 0-3.1-2.3Z"/>
                    </svg>
                    Browse Food Guides
                </a>
            </div>

            {{-- One slim line rather than a block of stat cards: every claim
                 here is still true of the site, it just no longer costs the
                 hero half its height to say so. --}}
            <ul class="hero-in mt-6 flex flex-wrap items-center gap-x-5 gap-y-2.5" style="--i: 4">
                @foreach ([
                    'Always free',
                    'No sign-up',
                    'Nothing stored',
                    'Vet-sourced',
                ] as $claim)
                    <li class="group flex items-center gap-1.5 text-sm font-medium text-ink-muted">
                        <svg class="size-4 shrink-0 text-accent-dark transition-transform duration-300 group-hover:scale-125"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        {{ $claim }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="relative">
            {{-- A soft coral stage behind the photo, echoing the sample's
                 backdrop circle. Only visible around a cutout with real
                 transparency — the launch photo below is opaque and hides it. --}}
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10 flex items-center justify-center">
                <div class="size-[80%] rounded-full bg-primary-vivid opacity-30 blur-3xl"></div>
            </div>

            @php
                // Same admin-uploaded-beats-static-fallback pattern as the
                // image below, checked first: a video in this slot is the
                // strongest signal of intent, so it wins if one exists.
                $heroVideo = \App\Models\Video::where('name', 'home-page-hero-section')->latest()->first();
                $heroPoster = $heroVideo ? Illuminate\Support\Str::replaceLast('.mp4', '-poster.webp', $heroVideo->path) : null;

                // Admin-uploaded via Media (category "general", name below)
                // rather than the resources/images manifest, so swapping the
                // photo is an upload, not a deploy. Falls back to the launch
                // photo, in its framed card, until one is uploaded.
                $heroMedia = \App\Models\Media::where('name', 'home-page-hero-section')->latest()->first();
            @endphp
            @if ($heroVideo)
                {{-- A real clip, not a cutout, so it gets the same framed
                     card treatment as the static fallback photo rather than
                     the borderless cutout style below. Muted/looped/no
                     controls so it reads as a living photograph, not a
                     player. autoplay is deliberately left off the tag: it
                     only starts via the script below, and only when the
                     visitor hasn't asked for reduced motion — so a
                     reduced-motion visitor, or one with JS disabled, just
                     sees the poster frame as a still photo instead. --}}
                <div class="cat-walk">
                    <div class="cat-breathe relative overflow-hidden rounded-[4.5rem_2rem_4.5rem_2rem] sm:rounded-[6rem_2.5rem_6rem_2.5rem] border-4 border-primary/15 bg-surface shadow-lg">
                        <video class="hero-video relative block h-full w-full object-cover"
                               width="1280" height="720"
                               poster="{{ Illuminate\Support\Facades\Storage::url($heroPoster) }}"
                               muted loop playsinline preload="auto"
                               aria-label="A cat walking, filmed for the PurrQuery home page">
                            <source src="{{ $heroVideo->url }}" type="video/mp4">
                        </video>
                        <script>
                            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                                document.currentScript.previousElementSibling.play().catch(() => {});
                            }
                        </script>
                    </div>
                </div>
            @elseif ($heroMedia)
                {{-- This photo is an alpha-transparent cutout, not a framed
                     shot, so it sits directly on the hero background rather
                     than inside a card: no fill, no border, no box-shadow —
                     just a shadow that follows the cat's own silhouette. --}}
                <div class="cat-walk">
                    <img src="{{ $heroMedia->url }}"
                         width="{{ $heroMedia->width }}" height="{{ $heroMedia->height }}"
                         alt="Cat reaching up to play with a feather wand toy"
                         sizes="(max-width: 1023px) 92vw, 780px"
                         fetchpriority="high" decoding="sync"
                         class="cat-breathe relative h-full w-full object-contain drop-shadow-xl">
                </div>
            @else
                <div class="cat-walk">
                    <div class="cat-breathe relative overflow-hidden rounded-[4.5rem_2rem_4.5rem_2rem] sm:rounded-[6rem_2.5rem_6rem_2.5rem] border-4 border-primary/15 bg-surface shadow-lg">
                        <x-img name="purrquery-hero-cat-owner-smiling"
                               alt="Cat owner smiling as she holds her fluffy tabby"
                               sizes="(max-width: 1023px) 92vw, 780px"
                               :priority="true"/>
                    </div>
                </div>
            @endif

            {{-- Floating context badges: purely decorative, so hidden from
                 screen readers and dropped below the breakpoint where the
                 photo is too small for them to sit on cleanly. The third
                 (top-right) badge from the first pass collided with the
                 feather wand and was dropped rather than shuffled around it. --}}
            <div aria-hidden="true" style="--i: 0" class="cat-badge absolute top-2 -left-4 hidden size-14 items-center justify-center rounded-full bg-surface shadow-lg sm:flex">
                <svg class="size-6 text-primary-vivid" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 21c-.28 0-.53-.11-.71-.29C7.4 16.98 3.5 13.1 3.5 9.36 3.5 6.4 5.9 4 8.85 4c1.68 0 3.24.83 4.15 2.14C13.91 4.83 15.47 4 17.15 4 20.1 4 22.5 6.4 22.5 9.36c0 3.74-3.9 7.62-7.79 11.35a1 1 0 0 1-.71.29Z"/>
                </svg>
            </div>

            <div aria-hidden="true" style="--i: 1" class="cat-badge absolute top-[46%] -right-5 hidden size-14 items-center justify-center rounded-full bg-surface shadow-lg sm:flex">
                <svg class="size-6 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <ellipse cx="12" cy="8" rx="8" ry="3"/>
                    <path d="M4 8v2.5c0 3.6 3.6 6.5 8 6.5s8-2.9 8-6.5V8"/>
                </svg>
            </div>

            {{-- Duplicates a line from the trust row below, so it is hidden
                 from screen readers rather than read out twice. --}}
            <div aria-hidden="true" style="--i: 2"
                 class="cat-badge absolute -bottom-5 right-4 hidden items-center gap-3 rounded-full bg-accent px-4 py-3 shadow-lg sm:flex">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-surface text-accent">
                    <x-paw-print class="size-5"/>
                </span>
                <span>
                    <span class="block font-heading text-sm font-extrabold text-ink-inverse">Free forever</span>
                    <span class="mt-0.5 block text-xs text-ink-inverse/85">No account, no paywall</span>
                </span>
            </div>
        </div>
    </div>

    {{-- Wave into the next section. Inline SVG so it scales to any width and
         costs no request; aria-hidden because it is pure decoration. --}}
    <svg class="absolute inset-x-0 bottom-0 h-16 w-full text-surface sm:h-24" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ 2. Category cards ═════════════════════════════════════════════════ --}}
@php
    // These replace the old "easy to use / fast results" row. Those made a
    // claim; these take you somewhere, which is what the page needs at this
    // point. Each tint and its text color are asserted in PaletteContrastTest.
    $categories = [
        ['Smart Tools', 'Instant calculators, checkers and trackers for your cat.', 'Try tools', route('tools.index'),
         'primary', ['M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0H5a2 2 0 0 1-2-2v-4m6 6h10a2 2 0 0 0 2-2v-4M3 9h18M3 15h18'],
         'home-smart-tools-icon'],
        ['Food Guides', 'What is safe, what needs care, and what to never feed.', 'View guides', route('food-guides.index'),
         'accent', ['M8 3v6a4 4 0 0 1-4 4 4 4 0 0 0 4 4v4', 'M16 3c-1.5 3-2 5-2 7a3 3 0 0 0 3 3h1v8'],
         'home-food-guides-icon'],
        ['How It Works', 'Three steps from a question to an answer you can act on.', 'See how', route('how-it-works'),
         'warning', ['M12 6v6l4 2', 'M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20Z'],
         'home-how-it-works-icon'],
        ['Cat Care Blog', 'Longer reads for the questions a calculator cannot settle.', 'Read blog', route('blog.index'),
         'info', ['M12 7.5v13', 'M3.5 18.2a.8.8 0 0 1-.8-.8V4.9a.8.8 0 0 1 .8-.8h4.9A3.6 3.6 0 0 1 12 7.5a3.6 3.6 0 0 1 3.6-3.4h4.9a.8.8 0 0 1 .8.8v12.5a.8.8 0 0 1-.8.8h-5.4A3.1 3.1 0 0 0 12 20.5a3.1 3.1 0 0 0-3.1-2.3Z'],
         'home-cat-care-blog-icon'],
    ];

    // Keyed by the same name each card looks up below, so one query covers
    // all four instead of one per card.
    $categoryMedia = \App\Models\Media::whereIn('name', collect($categories)->pluck(6))
        ->latest()->get()->keyBy('name');
@endphp
<section class="bg-surface pb-4">
    <div class="container-page grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($categories as [$title, $text, $cta, $href, $tone, $paths, $iconName])
            @php $iconMedia = $categoryMedia->get($iconName); @endphp
            <a href="{{ $href }}"
               class="group relative flex flex-col items-center overflow-hidden rounded-2xl border border-line bg-surface p-6 text-center shadow-sm transition duration-200 hover:-translate-y-1 hover:border-line-strong hover:shadow-lg">

                {{-- A wash of the card's colour bled in from the corner, so the
                     four stay distinguishable without becoming four flat
                     rectangles of paint. --}}
                <span aria-hidden="true" @class([
                    'pointer-events-none absolute -top-12 -right-12 size-32 rounded-full blur-2xl transition-opacity duration-200 opacity-60 group-hover:opacity-100',
                    'bg-primary-light' => $tone === 'primary',
                    'bg-accent-light' => $tone === 'accent',
                    'bg-warning-light' => $tone === 'warning',
                    'bg-info-light' => $tone === 'info',
                ])></span>

                @if ($iconMedia)
                    {{-- A real, relevant photo once one is uploaded for this
                         card (admin Media, category "general", name matching
                         $iconName above) — cropped into an avatar circle
                         rather than boxed like a generic icon. --}}
                    <span @class([
                        'relative size-20 overflow-hidden rounded-full ring-4 shadow-sm',
                        'ring-primary-light' => $tone === 'primary',
                        'ring-accent-light' => $tone === 'accent',
                        'ring-warning-light' => $tone === 'warning',
                        'ring-info-light' => $tone === 'info',
                    ])>
                        <img src="{{ $iconMedia->url }}" alt=""
                             width="{{ $iconMedia->width }}" height="{{ $iconMedia->height }}"
                             loading="lazy" decoding="async"
                             class="h-full w-full object-cover">
                    </span>
                @else
                    <span @class([
                        'relative flex size-20 items-center justify-center rounded-full shadow-sm',
                        'bg-primary-light text-primary' => $tone === 'primary',
                        'bg-accent-light text-accent' => $tone === 'accent',
                        'bg-warning-light text-warning' => $tone === 'warning',
                        'bg-info-light text-info' => $tone === 'info',
                    ])>
                        <svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            @foreach ($paths as $d)
                                <path d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </span>
                @endif

                <h2 class="relative mt-5 font-heading text-lg font-bold text-ink">{{ $title }}</h2>
                <p class="relative mt-2 flex-1 text-sm leading-relaxed text-ink-muted">{{ $text }}</p>

                <span @class([
                    'relative mt-5 inline-flex items-center gap-1.5 text-sm font-semibold',
                    'text-primary' => $tone === 'primary',
                    'text-accent' => $tone === 'accent',
                    'text-warning' => $tone === 'warning',
                    'text-info' => $tone === 'info',
                ])>
                    {{ $cta }}
                    <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                </span>
            </a>
        @endforeach
    </div>
</section>

{{-- ══ 3. Tools ══════════════════════════════════════════════════════════ --}}
<section id="tools" class="section scroll-mt-20 bg-surface">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">Free tools</p>
            <h2 class="section-title">Answers in seconds, not searches</h2>
            <p class="section-intro">
                {{ ucfirst(\Illuminate\Support\Number::spell(count(config('catalog.tools')))) }} calculators built for the questions cat owners actually ask.
                Nothing to install, no account, no limits.
            </p>
        </div>

        {{-- A horizontal, snap-scrolling row on a phone; the same grid as
             always from sm: up. One scroll axis on mobile is what keeps a
             six-card section from costing six card-heights of scrolling. --}}
        <div class="mt-8 -mx-3 flex snap-x snap-mandatory gap-4 overflow-x-auto px-3 pb-2 sm:mx-0 sm:mt-12 sm:grid sm:snap-none sm:grid-cols-2 sm:gap-6 sm:overflow-visible sm:px-0 sm:pb-0 lg:grid-cols-3">
            @foreach (config('catalog.tools') as $tool)
                {{-- A tool with a page behind it becomes a link; the rest stay
                     as plain cards until theirs exist, rather than pointing at
                     a URL that is not there. --}}
                @php $isLive = isset($tool['url']); @endphp
                <{{ $isLive ? 'a' : 'article' }}
                    @if ($isLive) href="{{ $tool['url'] }}" @endif
                    class="card group w-[78vw] shrink-0 snap-center sm:w-auto sm:shrink">
                    <div class="card-media">
                        <x-img :name="$tool['image']" :alt="$tool['alt']"
                               sizes="(max-width: 639px) 92vw, (max-width: 1023px) 46vw, 30vw"/>

                        @unless ($isLive)
                            <span class="absolute top-3 right-3 rounded-full bg-surface/90 px-3 py-1 text-xs font-bold text-ink-muted shadow-sm">
                                Coming soon
                            </span>
                        @endunless
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $tool['title'] }}</h3>
                        <p class="card-text flex-1">{{ $tool['blurb'] }}</p>
                        <span @class([
                            'mt-4 inline-flex items-center gap-1.5 text-sm font-semibold',
                            'text-primary' => $isLive,
                            'text-ink-muted' => ! $isLive,
                        ])>
                            {{ $isLive ? 'Open tool' : 'In development' }}
                            @if ($isLive)
                                <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                            @endif
                        </span>
                    </div>
                </{{ $isLive ? 'a' : 'article' }}>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 4. How it works ═══════════════════════════════════════════════════ --}}
<section id="how-it-works" class="section scroll-mt-20 bg-primary-dark">
    <div class="container-page">
        <div class="text-center">
            <p class="inline-flex items-center rounded-full border border-white/25 bg-white/10 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-white uppercase">
                How it works
            </p>
            <h2 class="mt-4 font-heading text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                Three steps to a clear answer
            </h2>
        </div>

        <ol class="mt-12 grid gap-8 md:grid-cols-3">
            @foreach ([
                ['Pick a tool', 'Choose the calculator or guide that matches your question.'],
                ['Enter the details', 'Weight, age, breed, whatever that tool needs. It stays on your device.'],
                ['Get your answer', 'A clear result you can act on, and save as a PDF report.'],
            ] as $i => [$title, $text])
                <li class="relative text-center sm:text-left">
                    <span class="mx-auto flex size-12 items-center justify-center rounded-xl bg-white/15 font-heading text-xl font-extrabold text-white sm:mx-0">
                        {{ $i + 1 }}
                    </span>
                    <h3 class="mt-5 font-heading text-xl font-bold text-white">{{ $title }}</h3>
                    {{-- White-on-primary-dark is 10.3:1, so this stays well
                         clear of AA even at this reduced opacity. --}}
                    <p class="mt-2 text-sm leading-relaxed text-white/80 sm:text-base">{{ $text }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ══ 5. Food categories ════════════════════════════════════════════════ --}}
<section id="food-guides" class="section scroll-mt-20 bg-surface-soft">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">Food guides</p>
            <h2 class="section-title">Can cats eat that?</h2>
            <p class="section-intro">
                The answer, before the article.
                {{ ucfirst(\Illuminate\Support\Number::spell(count(config('catalog.foods')))) }}
                categories covering what is safe, what needs care, and what to
                never let near your cat.
            </p>
        </div>

        <div class="mt-8 -mx-3 flex snap-x snap-mandatory gap-4 overflow-x-auto px-3 pb-2 sm:mx-0 sm:mt-12 sm:grid sm:snap-none sm:grid-cols-2 sm:gap-5 sm:overflow-visible sm:px-0 sm:pb-0 lg:grid-cols-3 xl:grid-cols-4">
            @php
                $verdictLabel = ['safe' => 'Safe', 'caution' => 'In moderation', 'unsafe' => 'Never'];
            @endphp
            @foreach (config('catalog.foods') as $food)
                <a href="{{ route('food-guides.show', $food['slug']) }}" id="food-{{ $food['slug'] }}"
                   class="card w-[78vw] shrink-0 snap-center scroll-mt-24 sm:w-auto sm:shrink">
                    <div class="card-media">
                        <x-img :name="$food['image']" :alt="$food['alt']"
                               sizes="(max-width: 639px) 92vw, (max-width: 1023px) 46vw, (max-width: 1279px) 30vw, 23vw"/>

                        {{-- The verdict rides on the image so it is the first
                             thing read. Solid rather than tinted, because it
                             sits over a photograph: white on each of these
                             three clears 5.5:1. --}}
                        <span @class([
                            'absolute top-3 right-3 rounded-full px-3 py-1 text-xs font-bold text-ink-inverse shadow-md',
                            'bg-accent' => $food['verdict'] === 'safe',
                            'bg-warning' => $food['verdict'] === 'caution',
                            'bg-danger' => $food['verdict'] === 'unsafe',
                        ])>{{ $verdictLabel[$food['verdict']] }}</span>
                    </div>

                    <div class="flex flex-1 flex-col p-5">
                        <p class="text-xs font-bold tracking-wide text-primary uppercase">{{ $food['title'] }}</p>

                        {{-- The heading is the question people type into
                             Google, and the answer sits under it. That is also
                             what makes the FAQ structured data legitimate: it
                             describes content on the page. --}}
                        <h3 class="mt-1.5 font-heading text-lg leading-snug font-bold text-ink">
                            {{ $food['question'] }}
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $food['answer'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 6. All in one platform ════════════════════════════════════════════ --}}
<section class="section bg-surface">
    <div class="container-page grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
        <div>
            <p class="eyebrow">One place</p>
            <h2 class="section-title">Everything for your cat, in one place</h2>
            <p class="mt-4 text-base leading-relaxed text-ink-muted sm:text-lg">
                Most cat questions end in a dozen open tabs and conflicting
                answers. PurrQuery puts the calculators and the guidance in the
                same place, written the same way, so you can stop cross-checking.
            </p>

            <ul class="mt-8 space-y-4">
                @foreach ([
                    ucfirst(\Illuminate\Support\Number::spell(count(config('catalog.tools')))).' calculators covering age, weight, calories and health',
                    ucfirst(\Illuminate\Support\Number::spell(count(config('catalog.foods')))).' food-safety guides with the verdict up front',
                    'Guides reviewed against published veterinary sources',
                    'Free forever, with no account, no paywall and no email wall',
                ] as $point)
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-accent-light">
                            <svg class="size-3.5 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                        </span>
                        <span class="text-base text-ink">{{ $point }}</span>
                    </li>
                @endforeach
            </ul>

            <a href="#tools" class="btn-primary mt-8">Start with a tool</a>
        </div>

        <div class="grid grid-cols-2 gap-5">
            @foreach ([
                [(string) count(config('catalog.tools')), 'Free tools'],
                [(string) count(config('catalog.foods')), 'Food guides'],
                ['0', 'Sign-ups needed'],
                ['100%', 'Free to use'],
            ] as [$figure, $label])
                <div class="rounded-2xl border border-line bg-surface p-5 text-center shadow-md transition duration-200 hover:-translate-y-1 hover:shadow-lg sm:p-8">
                    <p class="font-heading text-3xl font-extrabold tracking-tight text-primary sm:text-5xl">{{ $figure }}</p>
                    <p class="mt-2 text-sm font-medium text-ink-muted">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 7. Blog ═══════════════════════════════════════════════════════════ --}}
@if ($posts->isNotEmpty())
    <section id="blog" class="section scroll-mt-20 bg-surface-soft">
        <div class="container-page">
            <div class="text-center">
                <p class="eyebrow">From the blog</p>
                <h2 class="section-title">Guides worth the read</h2>
                <p class="section-intro">
                    Longer answers for the questions a calculator cannot settle.
                </p>
            </div>

            {{-- A magazine front page, not a grid of identical tiles: the
                 newest post runs as a full hero with its title and excerpt
                 sitting on the photo; the next two stack beside it at half
                 the height each; the four after that read as an even row
                 underneath. Straight off $posts (latest-first, no cache),
                 so this always reflects whatever was published most
                 recently — no separate "featured" flag to keep in sync. --}}
            @php
                $latestPosts = $posts->take(7);
                $featuredPost = $latestPosts->get(0);
                $stackedPosts = $latestPosts->slice(1, 2);
                $gridPosts = $latestPosts->slice(3, 4);
            @endphp

            {{-- Mobile: one horizontal, auto-advancing carousel instead of a
                 tall vertical stack. Desktop keeps the magazine grid below,
                 untouched. --}}
            <div class="mt-10 lg:hidden" x-data="postCarousel()" x-init="start()">
                <div x-ref="track" class="-mx-3 flex snap-x snap-mandatory gap-4 overflow-x-auto px-3 pb-2"
                     x-on:touchstart="pause()" x-on:pointerdown="pause()">
                    @foreach ($latestPosts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="card reveal group w-[78%] max-w-80 shrink-0 snap-center">
                            <div class="card-media aspect-[3/2]">
                                <x-post-image :post="$post" class="transition-transform duration-500 group-hover:scale-105"/>

                                <span aria-hidden="true" class="pointer-events-none absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-ink/70 via-ink/10 to-transparent"></span>

                                <span class="absolute top-3 left-3 rounded-full bg-primary-vivid px-2.5 py-1 text-[11px] font-bold text-ink shadow-sm">
                                    {{ $post->category?->name }}
                                </span>

                                <span class="absolute right-3 bottom-3 left-3 flex items-center gap-1.5 text-[11px] font-semibold text-ink-inverse">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/>
                                    </svg>
                                    {{ $post->reading_time }} min read
                                </span>
                            </div>

                            <div class="card-body">
                                <h3 class="line-clamp-2 font-heading text-base leading-snug font-bold text-ink transition-colors group-hover:text-primary">
                                    {{ $post->title }}
                                </h3>
                                <p class="card-text line-clamp-2 flex-1">{{ $post->excerpt }}</p>

                                <span class="mt-auto inline-flex items-center gap-1.5 pt-4 text-sm font-semibold text-primary">
                                    Read the guide
                                    <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                         stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-12 hidden gap-5 lg:grid lg:grid-cols-5 lg:gap-6">
                @if ($featuredPost)
                    <a href="{{ route('blog.show', $featuredPost->slug) }}"
                       class="card reveal group relative aspect-[4/3] sm:aspect-[16/9] lg:col-span-3 lg:aspect-auto">
                        <x-post-image :post="$featuredPost" class="absolute inset-0 transition-transform duration-500 group-hover:scale-105"/>

                        <span aria-hidden="true" class="pointer-events-none absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/25 to-transparent"></span>

                        <span class="absolute top-4 left-4 rounded-full bg-primary-vivid px-3 py-1 text-xs font-bold text-ink shadow-sm">
                            {{ $featuredPost->category?->name }}
                        </span>

                        <span class="absolute right-4 left-4 bottom-4 sm:right-8 sm:bottom-6 sm:left-8">
                            <span class="flex items-center gap-1.5 text-xs font-semibold text-ink-inverse/80">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/>
                                </svg>
                                {{ $featuredPost->reading_time }} min read
                            </span>
                            <span class="mt-2 block font-heading text-xl leading-tight font-extrabold text-ink-inverse sm:text-2xl lg:text-3xl">
                                {{ $featuredPost->title }}
                            </span>
                            <span class="mt-2 line-clamp-2 max-w-lg text-sm leading-relaxed text-ink-inverse/85 sm:block">
                                {{ $featuredPost->excerpt }}
                            </span>
                            <span class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-ink-inverse">
                                Read the guide
                                <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                     stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                            </span>
                        </span>
                    </a>
                @endif

                {{-- Two cards stacked to match the hero's height: flex-1 on
                     each splits that stretched height evenly, and the
                     card-body centers its title in whatever's left over
                     after the image, so a short title never looks stranded. --}}
                <div class="flex flex-col gap-5 lg:col-span-2 lg:gap-6">
                    @foreach ($stackedPosts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}"
                           class="card reveal group flex-1"
                           style="--reveal-delay: {{ ($loop->index + 1) * 80 }}ms">
                            <div class="card-media aspect-[16/9]">
                                <x-post-image :post="$post" class="transition-transform duration-500 group-hover:scale-105"/>

                                <span aria-hidden="true" class="pointer-events-none absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-ink/70 via-ink/10 to-transparent"></span>

                                <span class="absolute top-3 left-3 rounded-full bg-primary-vivid px-2.5 py-1 text-[11px] font-bold text-ink shadow-sm">
                                    {{ $post->category?->name }}
                                </span>

                                <span class="absolute right-3 bottom-3 left-3 flex items-center gap-1.5 text-[11px] font-semibold text-ink-inverse">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/>
                                    </svg>
                                    {{ $post->reading_time }} min read
                                </span>
                            </div>

                            <div class="card-body flex-1 justify-center py-3">
                                <h3 class="line-clamp-2 font-heading text-sm leading-snug font-bold text-ink transition-colors group-hover:text-primary sm:text-base">
                                    {{ $post->title }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- The even row underneath: same card language as the two
                 above, just four in a row and roomy enough for the excerpt
                 and a "read the guide" line. --}}
            <div class="mt-5 hidden gap-4 sm:gap-6 lg:mt-6 lg:grid lg:grid-cols-4">
                @foreach ($gridPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}"
                        class="card reveal group" style="--reveal-delay: {{ ($loop->index + 3) * 80 }}ms">
                        <div class="card-media aspect-[3/2]">
                            <x-post-image :post="$post" class="transition-transform duration-500 group-hover:scale-105"/>

                            <span aria-hidden="true" class="pointer-events-none absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-ink/70 via-ink/10 to-transparent"></span>

                            <span class="absolute top-3 left-3 rounded-full bg-primary-vivid px-2.5 py-1 text-[11px] font-bold text-ink shadow-sm">
                                {{ $post->category?->name }}
                            </span>

                            <span class="absolute right-3 bottom-3 left-3 flex items-center gap-1.5 text-[11px] font-semibold text-ink-inverse">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/>
                                </svg>
                                {{ $post->reading_time }} min read
                            </span>
                        </div>

                        <div class="card-body">
                            <h3 class="line-clamp-2 font-heading text-base leading-snug font-bold text-ink transition-colors group-hover:text-primary sm:text-lg">
                                {{ $post->title }}
                            </h3>
                            <p class="card-text line-clamp-2 flex-1">{{ $post->excerpt }}</p>

                            <span class="mt-auto inline-flex items-center gap-1.5 pt-4 text-sm font-semibold text-primary">
                                Read the guide
                                <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                     stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($posts->count() > 7)
                <div class="mt-10 text-center">
                    <a href="{{ route('blog.index') }}" class="btn-outline rounded-full px-7">
                        View all {{ $posts->count() }} guides
                    </a>
                </div>
            @endif
        </div>
    </section>
@endif

{{-- ══ 8. CTA banner ═════════════════════════════════════════════════════ --}}
<section class="section bg-surface">
    <div class="container-page">
        <div class="relative overflow-hidden rounded-2xl border border-line bg-accent-light px-6 py-14 text-center shadow-lg sm:px-12">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -top-16 -right-10 size-64 rounded-full bg-primary-vivid/20 blur-2xl"></div>
                <div class="absolute -bottom-20 -left-10 size-72 rounded-full bg-accent-vivid/20 blur-2xl"></div>
            </div>
            {{-- Decorative, and the copy stands on its own without them, so
                 they drop out at the widths where they would crowd it.

                 The box matches the artwork's own 3:2 so object-contain fits
                 it exactly; the column it sits in is the full height of the
                 band, and letting the image fill that would letterbox it. --}}
            <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-0 hidden w-48 items-center lg:flex xl:w-60">
                <div class="aspect-[3/2] w-full">
                    <x-img name="purrquery-cat-food-bowl-heart"
                           alt="Pink cat food bowl filled with kibble beside a heart-shaped toy"
                           sizes="240px" fit="contain"/>
                </div>
            </div>
            <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 right-0 hidden w-52 items-center lg:flex xl:w-64">
                <div class="aspect-[3/2] w-full">
                    <x-img name="purrquery-happy-tabby-cat-relaxing"
                           alt="Happy tabby cat relaxing comfortably with its paws raised"
                           sizes="256px" fit="contain"/>
                </div>
            </div>

            <div class="relative mx-auto max-w-xl">
                <h2 class="font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                    Ready to care smarter?
                </h2>
                <p class="mx-auto mt-4 text-base leading-relaxed text-ink-muted sm:text-lg">
                    Start with your cat’s age or ideal weight. It takes about
                    thirty seconds, and you will know where you stand.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="#tools"
                       class="btn-primary rounded-full px-6">
                        Try Free Tools
                    </a>
                    <a href="#blog"
                       class="btn-outline rounded-full px-6">
                        Read the guides
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        // Drives the hero search box's live dropdown. The form itself is a
        // plain GET to /search, so it works with no JS; this only adds the
        // suggestions on top once Alpine is up.
        function heroSearch() {
            return {
                query: '',
                items: [],
                open: false,
                loading: false,
                active: -1,
                statusText: '',

                async fetchSuggestions() {
                    const q = this.query.trim();

                    if (!q) {
                        this.items = [];
                        this.open = false;
                        return;
                    }

                    this.open = true;
                    this.loading = true;

                    try {
                        const response = await fetch(`{{ route('search.suggest') }}?q=${encodeURIComponent(q)}`);
                        const data = await response.json();
                        this.items = data.results;
                        this.active = -1;
                        this.statusText = `${this.items.length} suggestion${this.items.length === 1 ? '' : 's'}`;
                    } catch (e) {
                        this.items = [];
                    } finally {
                        this.loading = false;
                    }
                },

                move(delta) {
                    if (!this.items.length) return;
                    this.open = true;
                    this.active = (this.active + delta + this.items.length) % this.items.length;
                },

                onEnter(event) {
                    if (this.active >= 0 && this.items[this.active]) {
                        event.preventDefault();
                        window.location.href = this.items[this.active].url;
                    }
                    // Otherwise the form submits as normal, straight to /search.
                },
            };
        }

        // Mobile blog carousel: advances on its own, but a touch pauses it
        // for a few seconds so a swipe or a tap into an article never fights
        // the auto-advance.
        function postCarousel() {
            return {
                timer: null,
                resumeTimer: null,
                paused: false,

                start() {
                    this.timer = setInterval(() => this.advance(), 3500);
                },

                advance() {
                    if (this.paused) return;

                    const track = this.$refs.track;
                    const card = track.querySelector('a');
                    if (!card) return;

                    const step = card.getBoundingClientRect().width + 16;
                    const atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 10;

                    track.scrollTo({ left: atEnd ? 0 : track.scrollLeft + step, behavior: 'smooth' });
                },

                pause() {
                    this.paused = true;
                    clearTimeout(this.resumeTimer);
                    this.resumeTimer = setTimeout(() => { this.paused = false; }, 5000);
                },
            };
        }
    </script>
@endpush

</x-layouts.app>

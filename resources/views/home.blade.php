<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@push('head')
    {{-- The hero image is the largest thing painted on load, so the browser is
         told about it in the head. Without this it is only discovered once the
         markup is parsed, which delays the Largest Contentful Paint. --}}
    <link rel="preload" as="image" fetchpriority="high"
          href="{{ \App\Support\Images::get('purrquery-hero-cat-owner-smiling')['src'] }}"
          imagesrcset="{{ \App\Support\Images::get('purrquery-hero-cat-owner-smiling')['srcset'] }}"
          imagesizes="(max-width: 1023px) 92vw, 700px">
@endpush

{{-- ══ 1. Hero ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-24 size-96 rounded-full bg-primary opacity-10 blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 size-80 rounded-full bg-accent-vivid opacity-10 blur-3xl"></div>

        @foreach ([
            ['left-[4%]  top-[16%]', 'size-10', 0,    22, 'text-primary'],
            ['left-[13%] top-[68%]', 'size-7',  -6,   26, 'text-accent-vivid'],
            ['left-[30%] top-[8%]',  'size-6',  -11,  19, 'text-primary'],
            ['right-[6%] top-[12%]', 'size-9',  -3,   24, 'text-accent-vivid'],
            ['right-[2%] bottom-[8%]','size-11',-15,  28, 'text-primary'],
            ['left-[46%] bottom-[4%]','size-6', -8,   21, 'text-accent-vivid'],
        ] as [$position, $size, $delay, $duration, $tone])
            <x-paw-print class="paw absolute {{ $position }} {{ $size }} {{ $tone }}"
                         style="animation-delay: {{ $delay }}s; animation-duration: {{ $duration }}s"/>
        @endforeach
    </div>

    <div class="container-page relative grid items-center gap-12 py-14 lg:grid-cols-[1fr_1.15fr] lg:gap-14 lg:py-20">
        <div>
            <p class="eyebrow">
                <span class="size-1.5 rounded-full bg-accent-vivid"></span>
                Tools + Guides
            </p>

            <h1 class="mt-5 font-heading text-4xl leading-[1.1] font-extrabold tracking-tight text-ink sm:text-5xl lg:text-6xl">
                Smarter Cat Care<br>
                <span class="text-primary">Starts Here</span>
            </h1>

            <p class="mt-5 max-w-xl text-lg leading-relaxed text-ink-muted">
                Smart tools. Clear answers. Better decisions for your cat —
                free, and without handing over an email address.
            </p>

            {{-- Filters the tools and guides already on this page, so it does
                 something useful the moment it is typed in. --}}
            <form class="mt-7 max-w-lg" role="search" onsubmit="return false">
                <label for="site-search" class="sr-only">Search cat care tools and guides</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute top-1/2 left-4 size-5 -translate-y-1/2 text-ink-muted"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/>
                    </svg>
                    <input id="site-search" type="search" data-search
                           placeholder="Search cat care tools and guides…"
                           autocomplete="off"
                           class="w-full rounded-xl border border-line bg-surface py-3.5 pr-4 pl-12 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                </div>
                <p data-search-status class="sr-only" role="status" aria-live="polite"></p>
            </form>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="#tools" class="btn-primary">
                    Try Free Tools
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                </a>
                <a href="#food-guides" class="btn-outline">Explore Food Guides</a>
            </div>

            <ul class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3">
                @foreach (config('brand.promises') as $promise)
                    <li class="flex items-center gap-2 text-sm font-medium text-ink">
                        <svg class="size-4 shrink-0 text-accent" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z"/>
                        </svg>
                        {{ $promise }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="relative">
            <div class="overflow-hidden rounded-2xl border border-line bg-surface shadow-lg">
                <x-img name="purrquery-hero-cat-owner-smiling"
                       alt="Happy cat owner sitting with her fluffy cat on a couch — PurrQuery cat care guides"
                       sizes="(max-width: 1023px) 92vw, 700px"
                       :priority="true"/>
            </div>
        </div>
    </div>
</section>

{{-- ══ 2. Trust badges ═══════════════════════════════════════════════════ --}}
@php
    // Properly drawn multi-path icons rather than one improvised outline each:
    // a phone, a shield with a tick, a bolt and an open book read as what they
    // are at 24px, which single-stroke approximations do not.
    $trust = [
        ['Easy to use', 'Answers in a few taps, on any device.', 'primary', [
            'M7 2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z',
            'M11 18.5h2',
        ]],
        ['Accurate data', 'Built on published veterinary guidance.', 'accent', [
            'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z',
            'm9.4 12.2 1.9 1.9 3.6-3.7',
        ]],
        ['Fast results', 'Everything runs instantly in your browser.', 'primary', [
            'M13.2 2.4 4.5 12.9a.7.7 0 0 0 .5 1.1h5l-1.2 7.6a.35.35 0 0 0 .62.28l8.7-10.5a.7.7 0 0 0-.54-1.15h-5l1.2-7.6a.35.35 0 0 0-.62-.28Z',
        ]],
        ['Care guides', 'Plain-English answers, not a wall of jargon.', 'accent', [
            'M12 7.5v13',
            'M3.5 18.2a.8.8 0 0 1-.8-.8V4.9a.8.8 0 0 1 .8-.8h4.9A3.6 3.6 0 0 1 12 7.5a3.6 3.6 0 0 1 3.6-3.4h4.9a.8.8 0 0 1 .8.8v12.5a.8.8 0 0 1-.8.8h-5.4A3.1 3.1 0 0 0 12 20.5a3.1 3.1 0 0 0-3.1-2.3Z',
        ]],
    ];
@endphp
<section class="border-b border-line bg-surface py-14">
    <div class="container-page grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($trust as [$title, $text, $tone, $paths])
            <div class="group rounded-xl border border-line bg-surface p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-line-strong hover:shadow-md">
                <span @class([
                    'flex size-12 items-center justify-center rounded-xl transition-colors duration-200',
                    'bg-primary-light text-primary group-hover:bg-primary group-hover:text-ink-inverse' => $tone === 'primary',
                    'bg-accent-light text-accent-dark group-hover:bg-accent group-hover:text-ink-inverse' => $tone === 'accent',
                ])>
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        @foreach ($paths as $d)
                            <path d="{{ $d }}"/>
                        @endforeach
                    </svg>
                </span>
                <h2 class="mt-5 font-heading text-lg font-bold text-ink">{{ $title }}</h2>
                <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">{{ $text }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ══ 3. Tools ══════════════════════════════════════════════════════════ --}}
<section id="tools" class="section scroll-mt-16 bg-surface">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">Free tools</p>
            <h2 class="section-title">Answers in seconds, not searches</h2>
            <p class="section-intro">
                Six calculators built for the questions cat owners actually ask.
                Nothing to install, no account, no limits.
            </p>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (config('catalog.tools') as $tool)
                <article class="card" data-filter data-terms="{{ Str::lower($tool['title'].' '.$tool['blurb']) }}">
                    <div class="card-media">
                        <x-img :name="$tool['image']" :alt="$tool['alt']"
                               sizes="(max-width: 639px) 92vw, (max-width: 1023px) 46vw, 30vw"/>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $tool['title'] }}</h3>
                        <p class="card-text flex-1">{{ $tool['blurb'] }}</p>
                        <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary">
                            Open tool
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                        </span>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 4. How it works ═══════════════════════════════════════════════════ --}}
<section id="how-it-works" class="section scroll-mt-16 bg-primary-dark">
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
                ['Enter the details', 'Weight, age, breed — whatever that tool needs. It stays on your device.'],
                ['Get your answer', 'A clear result you can act on, and save as a PDF report.'],
            ] as $i => [$title, $text])
                <li class="relative">
                    <span class="flex size-12 items-center justify-center rounded-xl bg-white/15 font-heading text-xl font-extrabold text-white">
                        {{ $i + 1 }}
                    </span>
                    <h3 class="mt-5 font-heading text-xl font-bold text-white">{{ $title }}</h3>
                    {{-- White-on-primary-dark is 10.3:1, so this stays well
                         clear of AA even at this reduced opacity. --}}
                    <p class="mt-2 leading-relaxed text-white/80">{{ $text }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ══ 5. Food categories ════════════════════════════════════════════════ --}}
<section id="food-guides" class="section scroll-mt-16 bg-surface-soft">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">Food guides</p>
            <h2 class="section-title">Can cats eat that?</h2>
            <p class="section-intro">
                The answer, before the article. Ten categories covering what is
                safe, what needs care, and what to never let near your cat.
            </p>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 xl:grid-cols-4">
            @php
                $verdictLabel = ['safe' => 'Safe', 'caution' => 'In moderation', 'unsafe' => 'Never'];
            @endphp
            @foreach (config('catalog.foods') as $food)
                <article class="card" data-filter
                         data-terms="{{ Str::lower($food['question'].' '.$food['answer'].' '.$food['title']) }}">
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
                </article>
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
                    'Six calculators covering age, weight, calories and health',
                    'Ten food-safety guides with the verdict up front',
                    'Guides reviewed against published veterinary sources',
                    'Free forever — no account, no paywall, no email wall',
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
                ['6', 'Free tools'],
                ['10', 'Food guides'],
                ['0', 'Sign-ups needed'],
                ['100%', 'Free to use'],
            ] as [$figure, $label])
                <div class="rounded-2xl border border-line bg-surface p-8 text-center shadow-md transition duration-200 hover:-translate-y-1 hover:shadow-lg">
                    <p class="font-heading text-5xl font-extrabold tracking-tight text-primary">{{ $figure }}</p>
                    <p class="mt-2 text-sm font-medium text-ink-muted">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 7. Blog ═══════════════════════════════════════════════════════════ --}}
@php
    $posts = config('catalog.posts');
    $featured = $posts[0];
    $rest = array_slice($posts, 1);
@endphp
<section id="blog" class="section scroll-mt-16 bg-surface-soft">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">From the blog</p>
            <h2 class="section-title">Guides worth the read</h2>
            <p class="section-intro">
                Longer answers for the questions a calculator cannot settle.
            </p>
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-2">
            <article class="card" data-filter data-terms="{{ Str::lower($featured['title'].' '.$featured['excerpt']) }}">
                <div class="card-media lg:min-h-0 lg:flex-1">
                    <x-img :name="$featured['image']" :alt="$featured['alt']"
                           sizes="(max-width: 1023px) 92vw, 46vw"
                           class="lg:absolute lg:inset-0"/>
                </div>

                {{-- card-body carries flex-1 by default. On the featured card
                     that made the body and the image split the spare height
                     between them, which is where the gap under the text came
                     from. The body sizes to its content; the image takes the
                     rest. --}}
                <div class="card-body lg:flex-none">
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <span class="rounded-full bg-primary-light px-2.5 py-1 text-primary-dark">{{ $featured['category'] }}</span>
                        <span class="text-ink-muted">{{ $featured['minutes'] }} min read</span>
                    </div>
                    <h3 class="mt-3 font-heading text-2xl font-bold text-ink">{{ $featured['title'] }}</h3>
                    <p class="card-text">{{ $featured['excerpt'] }}</p>
                    <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary">
                        Read the guide
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                    </span>
                </div>
            </article>

            <div class="grid gap-4 sm:grid-cols-2 lg:content-start">
                @foreach ($rest as $post)
                    <article class="card" data-filter data-terms="{{ Str::lower($post['title'].' '.$post['excerpt']) }}">
                        <div class="card-media">
                            <x-img :name="$post['image']" :alt="$post['alt']"
                                   sizes="(max-width: 639px) 92vw, (max-width: 1023px) 46vw, 23vw"/>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center gap-2 text-xs font-semibold">
                                <span class="rounded-full bg-primary-light px-2 py-0.5 text-primary-dark">{{ $post['category'] }}</span>
                                <span class="text-ink-muted">{{ $post['minutes'] }} min</span>
                            </div>
                            <h3 class="mt-2 font-heading text-base leading-snug font-bold text-ink">{{ $post['title'] }}</h3>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══ 8. CTA banner ═════════════════════════════════════════════════════ --}}
<section class="section bg-surface">
    <div class="container-page">
        <div class="relative overflow-hidden rounded-2xl bg-primary px-6 py-14 text-center shadow-lg sm:px-12">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -top-16 -right-10 size-64 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -bottom-20 -left-10 size-72 rounded-full bg-accent-vivid/20 blur-2xl"></div>
            </div>
            <div class="relative">
                <h2 class="font-heading text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    Ready to care smarter?
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-white/85 sm:text-lg">
                    Start with your cat’s age or ideal weight — it takes about
                    thirty seconds, and you will know where you stand.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="#tools"
                       class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold text-primary-dark shadow-md transition hover:bg-primary-light">
                        Try Free Tools
                    </a>
                    <a href="#blog"
                       class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/40 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Read the guides
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ 9. Newsletter ═════════════════════════════════════════════════════ --}}
<section class="border-t border-line bg-surface-soft py-16">
    <div class="container-page max-w-3xl text-center">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
            New guides, once a month
        </h2>
        <p class="mx-auto mt-3 max-w-xl text-base leading-relaxed text-ink-muted">
            One email a month with new tools and guides. No selling, no sharing
            your address, and one click to leave.
        </p>

        @if (session('subscribed'))
            <p class="mx-auto mt-6 max-w-md rounded-lg bg-accent-light px-4 py-3 text-sm font-medium text-accent-dark" role="status">
                You are on the list — thank you.
            </p>
        @else
            <form method="POST" action="{{ route('subscribe') }}"
                  class="mx-auto mt-7 flex max-w-md flex-col gap-3 sm:flex-row">
                @csrf
                <label for="newsletter-email" class="sr-only">Email address</label>
                <input id="newsletter-email" name="email" type="email" required
                       placeholder="you@example.com" autocomplete="email"
                       class="w-full rounded-lg border border-line bg-surface px-4 py-3 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                {{-- Honeypot: a real person never sees this, so anything that
                     fills it in is a bot. Cheaper and less hostile than a captcha. --}}
                <div class="absolute left-[-9999px]" aria-hidden="true">
                    <label for="website">Leave this empty</label>
                    <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                </div>

                <button type="submit" class="btn-primary shrink-0">Subscribe</button>
            </form>

            @error('email')
                <p class="mt-3 text-sm font-medium text-danger" role="alert">{{ $message }}</p>
            @enderror
        @endif
    </div>
</section>

@push('scripts')
    <script>
        // Filters the cards already on the page. Everything it searches is in
        // the markup, so there is no request and no empty-state surprise.
        (() => {
            const input = document.querySelector('[data-search]');
            const status = document.querySelector('[data-search-status]');
            const cards = [...document.querySelectorAll('[data-filter]')];

            const apply = () => {
                const q = input.value.trim().toLowerCase();
                let shown = 0;

                cards.forEach(card => {
                    const hit = !q || card.dataset.terms.includes(q);
                    card.hidden = !hit;
                    if (hit) shown++;
                });

                status.textContent = q
                    ? `${shown} result${shown === 1 ? '' : 's'} for “${input.value.trim()}”`
                    : '';
            };

            input.addEventListener('input', apply);
        })();
    </script>
@endpush

</x-layouts.app>

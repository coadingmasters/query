<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- A reading-progress bar. Purely decorative feedback, so it is aria-hidden
     and it degrades to an invisible empty div without JavaScript. --}}
<div aria-hidden="true" class="sticky top-20 z-40 h-1 w-full bg-transparent">
    <div data-reading-progress class="h-full w-0 bg-primary-vivid transition-[width] duration-150 ease-out"></div>
</div>

{{-- ══ 1. HERO ═════════════════════════════════════════════════════════ --}}
<article>
    <header class="relative overflow-hidden bg-surface-soft">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -top-32 -left-24 size-96 rounded-full bg-primary-vivid opacity-[0.07] blur-3xl"></div>
            <div class="absolute -right-20 bottom-0 size-80 rounded-full bg-accent-vivid opacity-[0.10] blur-3xl"></div>
            <x-paw-print class="paw absolute bottom-[12%] left-[1.5%] hidden size-9 text-primary lg:block [animation-duration:23s]"/>
            <x-paw-print class="paw absolute top-[10%] right-[1.5%] hidden size-6 text-accent-vivid lg:block [animation-delay:-7s] [animation-duration:26s]"/>
        </div>

        <div class="container-page relative grid items-center gap-8 pt-8 pb-14 lg:grid-cols-2 lg:gap-12 lg:pt-6 lg:pb-20">

            <div class="min-w-0">
                <nav aria-label="Breadcrumb" class="reveal text-sm text-ink-muted">
                    <ol class="flex flex-wrap items-center gap-1.5">
                        <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ route('blog.index') }}" class="transition-colors hover:text-primary">Blog</a></li>
                        <li aria-hidden="true">/</li>
                        <li class="font-medium text-ink">{{ $post['category'] }}</li>
                    </ol>
                </nav>

                <div class="reveal mt-5 flex flex-wrap items-center gap-2.5" style="--reveal-delay: 60ms">
                    <span class="rounded-full bg-primary-vivid px-3 py-1 text-xs font-bold text-ink">{{ $post['category'] }}</span>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-line bg-surface px-3 py-1 text-xs font-semibold text-ink-muted shadow-sm">
                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 6v6l4 2"/><path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20Z"/>
                        </svg>
                        {{ $post['minutes'] }} min read
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-line bg-surface px-3 py-1 text-xs font-semibold text-ink-muted shadow-sm">
                        Updated
                        <time datetime="{{ $post['updated'] }}">
                            {{ \Illuminate\Support\Carbon::parse($post['updated'])->format('M j, Y') }}
                        </time>
                    </span>
                </div>

                <h1 class="reveal mt-5 font-heading text-4xl leading-[1.08] font-extrabold tracking-tight text-ink sm:text-5xl"
                    style="--reveal-delay: 120ms">
                    {{ $post['title'] }}
                </h1>

                <p class="reveal mt-5 max-w-xl text-base leading-relaxed text-ink-muted sm:text-lg"
                   style="--reveal-delay: 180ms">
                    {{ $post['excerpt'] }}
                </p>

                <div class="reveal mt-7 flex flex-wrap items-center gap-4 border-t border-line pt-5"
                     style="--reveal-delay: 240ms">
                    <x-byline :reviewed="true"/>
                </div>
            </div>

            {{-- The image is not given a reveal: it is the largest thing on
                 screen and therefore what Largest Contentful Paint measures,
                 and fading it in delays the moment that metric records. --}}
            @if (\App\Support\Images::get($post['image']))
                <div class="relative">
                    <figure class="overflow-hidden rounded-[3.5rem_1.5rem_3.5rem_1.5rem] border-4 border-primary/15 bg-surface shadow-xl sm:rounded-[5rem_2rem_5rem_2rem]">
                        <x-img :name="$post['image']" :alt="$post['alt']"
                               sizes="(min-width: 1024px) 46vw, 92vw" :priority="true"/>
                    </figure>

                    {{-- Repeats the read time above, so it is hidden from screen
                         readers rather than announced twice. --}}
                    <div aria-hidden="true"
                         class="absolute -bottom-5 right-5 hidden items-center gap-3 rounded-2xl border border-line bg-surface px-4 py-3 shadow-lg sm:flex">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary">
                            <x-paw-print class="size-5"/>
                        </span>
                        <span>
                            <span class="block font-heading text-sm font-extrabold text-ink">{{ $post['minutes'] }} minute read</span>
                            <span class="mt-0.5 block text-xs text-ink-muted">Sources named at the foot</span>
                        </span>
                    </div>
                </div>
            @endif
        </div>

        <svg class="absolute inset-x-0 bottom-0 h-8 w-full text-surface sm:h-12" viewBox="0 0 1440 120"
             preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
            <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
        </svg>
    </header>

    {{-- ══ 3. BODY ═══════════════════════════════════════════════════════ --}}
    <div class="bg-surface py-10 lg:py-12">
        <div class="container-page grid max-w-6xl gap-10 lg:grid-cols-[minmax(0,1fr)_16rem] lg:gap-12">

            <div class="min-w-0">
                {{-- The answer, before anything else. It is what the reader came
                     for and it is what Google lifts for a featured snippet;
                     making them scroll past four paragraphs of preamble for it
                     is how a page loses both. --}}
                <div class="rounded-2xl border border-accent-light bg-accent-light p-5 sm:p-6">
                    <p class="flex items-center gap-2 text-xs font-bold tracking-wider text-accent-dark uppercase">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21.5a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19Z"/><path d="m8.4 12.2 2.4 2.4 4.8-4.8"/>
                        </svg>
                        The short answer
                    </p>
                    <p class="mt-3 text-base leading-relaxed font-medium text-ink sm:text-lg">
                        {{ $post['answer'] }}
                    </p>
                </div>

                {{-- Contents, inline on mobile where a sticky rail has nowhere
                     to sit. --}}
                <details class="mt-6 rounded-2xl border border-line bg-surface-section px-5 lg:hidden" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-4 font-heading font-bold text-ink marker:content-['']">
                        On this page
                        <svg class="size-4 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <nav data-toc-mobile aria-label="On this page" class="pb-4"></nav>
                </details>

                <div data-article class="article mt-8">
                    @include('blog.posts.'.$post['slug'])
                </div>

                {{-- ══ FAQ ═══════════════════════════════════════════════ --}}
                <section id="faq" class="mt-12 scroll-mt-28">
                    <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                        Frequently asked questions
                    </h2>

                    <div class="mt-5 space-y-3">
                        @foreach ($post['faq'] as $item)
                            {{-- Open or shut, the answer stays in the markup,
                                 which is what lets the FAQ structured data
                                 describe it honestly. --}}
                            <details class="group rounded-xl border border-line bg-surface px-5 shadow-sm transition hover:border-line-strong open:shadow-md">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-4 font-heading font-bold text-ink marker:content-['']">
                                    {{ $item['q'] }}
                                    <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-primary-light text-primary transition-transform duration-200 group-open:rotate-45">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                                            <path d="M12 5v14M5 12h14"/>
                                        </svg>
                                    </span>
                                </summary>
                                <p class="pb-5 text-base leading-relaxed text-ink-muted">{{ $item['a'] }}</p>
                            </details>
                        @endforeach
                    </div>
                </section>

                {{-- ══ SOURCES ═══════════════════════════════════════════ --}}
                <section class="mt-10 rounded-2xl border border-line bg-surface-section p-6">
                    <h2 class="font-heading text-lg font-extrabold text-ink">Sources</h2>
                    <ul class="mt-4 space-y-3">
                        @foreach ($post['sources'] as $source)
                            <li class="text-sm leading-relaxed text-ink-muted">
                                <a href="{{ $source['url'] }}" rel="noopener" target="_blank"
                                   class="font-semibold text-primary underline decoration-line-strong underline-offset-4 transition-colors hover:text-primary-hover">
                                    {{ $source['name'] }}
                                </a>
                                <span class="block">{{ $source['note'] }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <p class="mt-5 flex items-start gap-2.5 rounded-xl border border-warning-light bg-warning-light p-4 text-sm leading-relaxed text-ink">
                        <svg class="mt-0.5 size-4 shrink-0 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M10.3 3.9 2.5 17.5A2 2 0 0 0 4.2 20.5h15.6a2 2 0 0 0 1.7-3l-7.8-13.6a2 2 0 0 0-3.4 0Z"/>
                            <path d="M12 9.5v4M12 17h.01"/>
                        </svg>
                        <span>
                            General information, not veterinary advice. If your cat's
                            behavior has changed suddenly, speak to a vet rather than
                            an article.
                        </span>
                    </p>
                </section>
            </div>

            {{-- ══ 4. SIDEBAR ════════════════════════════════════════════ --}}
            <aside class="hidden lg:block">
                <div class="sticky top-28 space-y-6">
                    <nav aria-labelledby="toc-heading" class="rounded-2xl border border-line bg-surface-section p-5">
                        <h2 id="toc-heading" class="font-heading text-sm font-bold tracking-wider text-ink uppercase">
                            On this page
                        </h2>
                        <div data-toc class="mt-4"></div>
                    </nav>

                    @if ($tools->isNotEmpty())
                        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                            <h2 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Try a tool</h2>
                            <ul class="mt-3 space-y-2">
                                @foreach ($tools as $tool)
                                    <li>
                                        <a href="{{ $tool['url'] ?? '/#tools' }}"
                                           class="flex items-start gap-2.5 rounded-xl px-2.5 py-2 transition-colors hover:bg-surface-soft">
                                            <span class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary-light text-primary">
                                                <x-paw-print class="size-3.5"/>
                                            </span>
                                            <span class="text-sm font-medium text-ink">{{ $tool['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</article>

{{-- ══ 5. KEEP READING ═══════════════════════════════════════════════════ --}}
@if ($posts->isNotEmpty())
    <section class="bg-surface-soft py-10 lg:py-14">
        <div class="container-page max-w-4xl">
            <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">Keep reading</h2>

            <ul class="mt-6 grid gap-4 sm:grid-cols-2">
                @foreach ($posts as $next)
                    <li>
                        <a href="{{ $next['url'] ?? route('blog.index') }}"
                           class="group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-surface shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                            <div class="overflow-hidden bg-surface-section">
                                <x-img :name="$next['image']" :alt="$next['alt']" sizes="(min-width: 640px) 22rem, 92vw"/>
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <span class="text-xs font-semibold text-primary">{{ $next['category'] }}</span>
                                <span class="mt-2 font-heading text-base leading-snug font-bold text-ink transition-colors group-hover:text-primary">
                                    {{ $next['title'] }}
                                </span>
                                <span class="mt-2 flex-1 text-sm leading-relaxed text-ink-muted">{{ $next['excerpt'] }}</span>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif

@push('scripts')
    <script>
        (() => {
            const article = document.querySelector('[data-article]');
            if (!article) return;

            /* ── Contents, built from the headings actually in the article ── */
            const entries = [...article.querySelectorAll('h2[id]')]
                .map(h => ({ id: h.id, text: h.textContent.trim() }));

            // The FAQ is its own section outside the body, but it belongs in
            // the list: it is where half the specific questions get answered.
            const faqHeading = document.querySelector('#faq h2');
            if (faqHeading) entries.push({ id: 'faq', text: faqHeading.textContent.trim() });

            const build = (target) => {
                if (!target || !entries.length) return;

                const ol = document.createElement('ol');
                ol.className = 'space-y-0.5 text-sm';

                entries.forEach((entry, i) => {
                    const a = document.createElement('a');
                    a.href = '#' + entry.id;
                    a.className = 'flex gap-2 rounded-md py-1.5 leading-snug text-ink-muted transition-colors hover:text-primary';

                    const number = document.createElement('span');
                    number.className = 'shrink-0 tabular-nums text-primary';
                    number.textContent = (i + 1) + '.';

                    a.append(number, document.createTextNode(entry.text));

                    const li = document.createElement('li');
                    li.append(a);
                    ol.append(li);
                });

                target.append(ol);
            };

            build(document.querySelector('[data-toc]'));
            build(document.querySelector('[data-toc-mobile]'));

            /* ── Reading progress ─────────────────────────────────────── */
            const bar = document.querySelector('[data-reading-progress]');
            if (bar) {
                const update = () => {
                    const start = article.offsetTop;
                    const total = article.scrollHeight - window.innerHeight;
                    const done = total > 0 ? (window.scrollY - start) / total : 0;
                    bar.style.width = Math.min(100, Math.max(0, done * 100)) + '%';
                };
                update();
                addEventListener('scroll', update, { passive: true });
                addEventListener('resize', update);
            }
        })();
    </script>
@endpush

</x-layouts.app>

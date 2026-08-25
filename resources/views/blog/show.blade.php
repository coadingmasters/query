<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- A reading-progress bar. Purely decorative feedback, so it is aria-hidden
     and it degrades to an invisible empty div without JavaScript. --}}
<div aria-hidden="true" class="sticky top-20 z-40 h-1 w-full bg-transparent">
    <div data-reading-progress class="h-full w-0 bg-primary-vivid transition-[width] duration-150 ease-out"></div>
</div>

@php
    $shareUrl = urlencode($canonical);
    $shareText = urlencode($post->title);
    $author = config('author.founder');
@endphp

<article>
    {{-- ══ 1. HERO ═══════════════════════════════════════════════════════ --}}
    <div class="container-page max-w-[1600px] pt-6">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('blog.index') }}" class="transition-colors hover:text-primary">Blog</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('blog.index') }}?topic={{ urlencode($post->category?->name) }}" class="transition-colors hover:text-primary">{{ $post->category?->name }}</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink">{{ $post->title }}</li>
            </ol>
        </nav>

        {{-- One card rather than a full-width band. The page is a document,
             and a document has edges. --}}
        <header class="relative mt-5 grid overflow-hidden rounded-2xl bg-surface-soft lg:grid-cols-[minmax(0,1fr)_minmax(0,0.85fr)]">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <x-paw-print class="paw absolute top-[14%] right-[42%] hidden size-8 text-primary-vivid/30 lg:block [animation-duration:23s]"/>
                <x-paw-print class="paw absolute bottom-[18%] right-[38%] hidden size-6 text-primary-vivid/25 lg:block [animation-delay:-7s] [animation-duration:26s]"/>
            </div>

            <div class="relative z-10 p-6 sm:p-9">
                <span class="rounded-full bg-primary-vivid px-3 py-1 text-xs font-bold text-ink">{{ $post->category?->name }}</span>

                <h1 class="mt-4 font-heading text-3xl leading-[1.12] font-extrabold tracking-tight text-ink sm:text-4xl">
                    {{ $post->title }}
                </h1>

                <p class="mt-4 max-w-lg text-base leading-relaxed text-ink-muted">{{ $post->excerpt }}</p>

                <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-ink-muted">
                    <x-byline/>
                    <span aria-hidden="true" class="hidden size-1 rounded-full bg-line-strong sm:block"></span>
                    <time datetime="{{ $post->updated_at->toDateString() }}">
                        {{ $post->updated_at->format('F j, Y') }}
                    </time>
                    <span aria-hidden="true" class="size-1 rounded-full bg-line-strong"></span>
                    <span>{{ $post->reading_time }} min read</span>
                </div>
            </div>

            {{-- Not animated: it is the largest thing on screen, so it is what
                 Largest Contentful Paint measures, and fading it in delays the
                 moment that metric records. --}}
            @if ($post->featured_image_url)
                <div class="relative min-h-56 lg:min-h-0">
                    <div class="absolute inset-0">
                        <x-post-image :post="$post" priority/>
                    </div>
                </div>
            @endif
        </header>
    </div>

    {{-- ══ 2. BODY AND SIDEBAR ═══════════════════════════════════════════ --}}
    <div class="py-8 lg:py-10">
        <div class="container-page grid max-w-[1600px] gap-8 lg:grid-cols-[minmax(0,1fr)_19rem] lg:gap-10">

            <div class="min-w-0">
                {{-- The answer, before any preamble. It is what the reader came
                     for and what Google lifts for a snippet. --}}
                @if ($post->quick_answer)
                    <div class="relative overflow-hidden rounded-2xl border border-line border-l-4 border-l-primary-vivid bg-surface-section p-5 sm:p-6">
                        <p class="flex items-center gap-2 text-xs font-bold tracking-wider text-primary uppercase">
                            <x-paw-print class="size-4"/>
                            Quick answer
                        </p>
                        <p class="mt-2.5 text-base leading-relaxed font-medium text-ink">{{ $post->quick_answer }}</p>
                    </div>
                @endif

                {{-- Contents inline on mobile, where a sticky rail has nowhere
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
                    {!! $post->content !!}
                </div>

                {{-- ══ FAQ ═══════════════════════════════════════════════ --}}
                @if ($post->faqs->isNotEmpty())
                    <section id="faq" class="mt-12 scroll-mt-28">
                        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">
                            Frequently asked questions
                        </h2>

                        <div class="mt-5 space-y-3">
                            @foreach ($post->faqs as $item)
                                <details class="group rounded-xl border border-line bg-surface px-5 shadow-sm transition hover:border-line-strong open:shadow-md">
                                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-4 font-heading text-sm font-bold text-ink marker:content-[''] sm:text-base">
                                        {{ $item->question }}
                                        <svg class="size-5 shrink-0 text-primary transition-transform duration-200 group-open:rotate-180"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                             stroke-linecap="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                                    </summary>
                                    <p class="pb-5 text-base leading-relaxed text-ink-muted">{{ $item->answer }}</p>
                                </details>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- ══ SOURCES ═══════════════════════════════════════════ --}}
                @if ($post->sources)
                    <section class="mt-10 rounded-2xl border border-line bg-surface-section p-6">
                        <h2 class="font-heading text-lg font-extrabold text-ink">Sources</h2>
                        <ul class="mt-4 space-y-3">
                            @foreach ($post->sources as $source)
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
                @endif

                {{-- ══ TOOL CTA ══════════════════════════════════════════ --}}
                <section class="mt-8">
                    <div class="grid overflow-hidden rounded-2xl bg-accent-light sm:grid-cols-[auto_minmax(0,1fr)]">
                        <div aria-hidden="true" class="hidden w-40 self-end sm:block">
                            <div class="aspect-[3/2]">
                                <x-img name="purrquery-cat-waving-paw" alt="" sizes="160px" fit="contain"/>
                            </div>
                        </div>
                        <div class="px-6 py-7 text-center sm:pl-2 sm:text-left">
                            <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink">
                                Want an answer in seconds?
                            </h2>
                            <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">
                                The calculators handle age, due dates and portions without an account.
                            </p>
                            <div class="mt-4 flex flex-wrap justify-center gap-2.5 sm:justify-start">
                                <a href="{{ route('tools.cat-age-calculator') }}" class="btn-primary rounded-full px-5 py-2.5 text-sm">Cat age calculator</a>
                                <a href="{{ route('tools.index') }}" class="btn-outline rounded-full bg-surface px-5 py-2.5 text-sm">All free tools</a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            {{-- ══ 3. SIDEBAR ════════════════════════════════════════════ --}}
            <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">

                {{-- Contents, desktop only. --}}
                <nav aria-labelledby="toc-heading" class="hidden rounded-2xl border border-line bg-surface-section p-5 lg:block">
                    <h2 id="toc-heading" class="font-heading text-sm font-bold tracking-wider text-ink uppercase">On this page</h2>
                    <div data-toc class="mt-3"></div>
                </nav>

                @if ($author['name'])
                    <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <h2 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">About the author</h2>
                        <div class="mt-4 flex items-start gap-3">
                            @if ($author['image'])
                                <span class="size-12 shrink-0 overflow-hidden rounded-xl bg-surface-soft">
                                    <x-img :name="$author['image']" :alt="$author['name']" sizes="48px"/>
                                </span>
                            @else
                                <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-primary-light">
                                    <x-paw-print class="size-6 text-primary"/>
                                </span>
                            @endif
                            <div class="min-w-0">
                                <p class="font-heading text-base font-bold text-ink">{{ $author['name'] }}</p>
                                <p class="mt-1 text-sm leading-relaxed text-ink-muted">{{ $author['tagline'] }}</p>
                            </div>
                        </div>
                        <a href="{{ route('author') }}" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary">
                            Read the full profile
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                        </a>
                    </div>
                @endif

                {{-- Plain links, not embedded widgets. A share button that
                     loads a third-party script would put a tracker on a page
                     whose privacy policy says there are none. --}}
                <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                    <h2 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Share this guide</h2>
                    <ul class="mt-3 flex gap-2">
                        @foreach ([
                            ['Facebook', 'https://www.facebook.com/sharer/sharer.php?u='.$shareUrl, 'M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5H16.7V3.6a22 22 0 0 0-2.4-.12c-2.4 0-4 1.46-4 4.14v2.3H7.6V13h2.7v8Z'],
                            ['X', 'https://twitter.com/intent/tweet?url='.$shareUrl.'&text='.$shareText, 'M17.5 3h3l-6.6 7.5L21.7 21h-6l-4.7-6.1L5.6 21h-3l7-8L2.6 3h6.2l4.2 5.6Zm-1 16h1.7L8.1 4.7H6.3Z'],
                            ['Pinterest', 'https://pinterest.com/pin/create/button/?url='.$shareUrl.'&description='.$shareText, 'M12 2a10 10 0 0 0-3.6 19.3c-.1-.8-.2-2 0-2.9l1.2-5s-.3-.6-.3-1.5c0-1.4.8-2.5 1.8-2.5.9 0 1.3.6 1.3 1.4 0 .9-.5 2.2-.8 3.4-.2 1 .5 1.8 1.5 1.8 1.8 0 3.2-1.9 3.2-4.7 0-2.4-1.7-4.1-4.2-4.1a4.4 4.4 0 0 0-4.6 4.4c0 .9.3 1.8.8 2.3l-.3 1.3c0 .2-.2.3-.4.2-1.2-.6-2-2.4-2-3.9 0-3.2 2.3-6.1 6.6-6.1 3.5 0 6.2 2.5 6.2 5.8 0 3.5-2.2 6.3-5.2 6.3-1 0-2-.5-2.3-1.2l-.6 2.4c-.2.9-.8 2-1.2 2.6A10 10 0 1 0 12 2Z'],
                            ['Email', 'mailto:?subject='.$shareText.'&body='.$shareUrl, 'M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Zm.5.5 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5'],
                        ] as [$label, $href, $path])
                            <li>
                                <a href="{{ $href }}" rel="noopener nofollow" target="_blank"
                                   class="flex size-10 items-center justify-center rounded-xl border border-line bg-surface text-ink-muted transition hover:border-primary hover:bg-primary-light hover:text-primary">
                                    <span class="sr-only">Share on {{ $label }}</span>
                                    <svg class="size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="{{ $path }}"/>
                                    </svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if ($posts->isNotEmpty())
                    <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <h2 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Related guides</h2>
                        <ul class="mt-3 divide-y divide-line">
                            @foreach ($posts as $related)
                                <li>
                                    <a href="{{ route('blog.show', $related->slug) }}"
                                        class="group flex items-center gap-3 py-3 transition-colors">
                                        <span class="size-14 shrink-0 overflow-hidden rounded-lg bg-surface-section">
                                            <x-post-image :post="$related"/>
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block text-sm leading-snug font-bold text-ink transition-colors group-hover:text-primary">
                                                {{ $related->title }}</span>
                                            <span class="mt-0.5 block text-xs text-ink-muted">
                                                {{ $related->reading_time }} min read
                                            </span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- The design offered "Join 10,000+ cat parents receiving
                     weekly tips". There is no such list, so this says what is
                     actually on offer. --}}
                <div class="rounded-2xl border border-line bg-surface-soft p-5 text-center shadow-sm">
                    <span class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-surface shadow-sm">
                        <svg class="size-6 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Z"/>
                            <path d="m3.5 8 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5"/>
                        </svg>
                    </span>
                    <h2 class="mt-3 font-heading text-lg font-extrabold text-ink">New guides by email</h2>
                    <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">
                        One email a month. No selling your address, one click to leave.
                    </p>

                    <form method="POST" action="{{ route('subscribe') }}" class="mt-4 space-y-2.5">
                        @csrf
                        <label for="aside-email" class="sr-only">Email address</label>
                        <input id="aside-email" name="email" type="email" required
                               placeholder="Enter your email" autocomplete="email"
                               class="w-full rounded-lg border border-line-strong bg-surface px-4 py-2.5 text-sm text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        <div class="absolute left-[-9999px]" aria-hidden="true">
                            <label for="aside-website">Leave this empty</label>
                            <input id="aside-website" name="website" type="text" tabindex="-1" autocomplete="off">
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
                            Subscribe
                        </button>
                    </form>
                </div>

                <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                    <h2 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Browse topics</h2>
                    <ul class="mt-3 flex flex-wrap gap-2">
                        @foreach ($topics as $topic)
                            <li>
                                <a href="{{ route('blog.index') }}?topic={{ urlencode($topic) }}"
                                   class="inline-block rounded-full border border-line px-3 py-1.5 text-xs font-semibold text-ink-muted transition hover:border-primary hover:bg-primary-light hover:text-primary">
                                    {{ $topic }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Records a real vote. A widget that thanks you and stores
                     nothing is worse than not asking. --}}
                <div data-feedback data-slug="{{ $post->slug }}"
                     class="rounded-2xl border border-line bg-surface-section p-5 text-center">
                    <h2 class="font-heading text-base font-extrabold text-ink">Was this helpful?</h2>
                    <p class="mt-1 text-sm text-ink-muted">It decides what gets written next.</p>

                    <div data-feedback-buttons class="mt-4 flex justify-center gap-2.5">
                        <button type="button" data-feedback-vote="1"
                                class="inline-flex items-center gap-1.5 rounded-full border border-line bg-surface px-4 py-2 text-sm font-semibold text-ink transition hover:border-accent hover:bg-accent-light">
                            <svg class="size-4 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M7 20.5V10l4.5-7 .8.5a2 2 0 0 1 .8 2.2L12.4 9h5.4a2 2 0 0 1 2 2.5l-1.7 7A2 2 0 0 1 16 20.5Z"/>
                                <path d="M7 10H4.5v10.5H7"/>
                            </svg>
                            Yes
                        </button>
                        <button type="button" data-feedback-vote="0"
                                class="inline-flex items-center gap-1.5 rounded-full border border-line bg-surface px-4 py-2 text-sm font-semibold text-ink transition hover:border-primary hover:bg-primary-light">
                            <svg class="size-4 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17 3.5V14l-4.5 7-.8-.5a2 2 0 0 1-.8-2.2l1.5-3.3H6.2a2 2 0 0 1-2-2.5l1.7-7A2 2 0 0 1 8 3.5Z"/>
                                <path d="M17 14h2.5V3.5H17"/>
                            </svg>
                            No
                        </button>
                    </div>

                    <p data-feedback-thanks hidden role="status" class="mt-4 rounded-xl bg-accent-light px-4 py-3 text-sm font-medium text-ink"></p>
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
                        <a href="{{ route('blog.show', $next->slug) }}"
                           class="group flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-surface shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                            <div class="overflow-hidden bg-surface-section">
                                <x-post-image :post="$next"/>
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <span class="text-xs font-semibold text-primary">{{ $next->category?->name }}</span>
                                <span class="mt-2 font-heading text-base leading-snug font-bold text-ink transition-colors group-hover:text-primary">
                                    {{ $next->title }}
                                </span>
                                <span class="mt-2 flex-1 text-sm leading-relaxed text-ink-muted">{{ $next->excerpt }}</span>
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

            /* ── Was this helpful ────────────────────────────────────── */
            const feedback = document.querySelector('[data-feedback]');
            if (feedback) {
                const buttons = feedback.querySelector('[data-feedback-buttons]');
                const thanks = feedback.querySelector('[data-feedback-thanks]');

                buttons.addEventListener('click', async (event) => {
                    const button = event.target.closest('[data-feedback-vote]');
                    if (!button) return;

                    const helpful = button.dataset.feedbackVote === '1';
                    buttons.setAttribute('hidden', '');
                    thanks.removeAttribute('hidden');
                    thanks.textContent = helpful
                        ? 'Thank you. Guides like this one get written first.'
                        : 'Noted, and thank you. Tell us what was missing on the contact page.';

                    try {
                        await fetch('/blog/feedback', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']')?.content ?? '',
                            },
                            body: JSON.stringify({ slug: feedback.dataset.slug, helpful }),
                        });
                    } catch {
                        // The thank-you already showed. A failed write is not
                        // worth telling the reader about; it is our problem.
                    }
                });
            }

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

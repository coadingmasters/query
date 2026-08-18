<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema" footer-wave="text-primary-dark">

@php
    // Written out in full: Tailwind only generates classes it can find as
    // literal strings, so "bg-{$tone}-light" would produce nothing.
    $tones = [
        'primary' => ['band' => 'bg-primary-light/45', 'tile' => 'bg-primary-light text-primary'],
        'accent' => ['band' => 'bg-accent-light/60', 'tile' => 'bg-accent-light text-accent-dark'],
        'info' => ['band' => 'bg-info-light/55', 'tile' => 'bg-info-light text-info'],
        'warning' => ['band' => 'bg-warning-light/55', 'tile' => 'bg-warning-light text-warning'],
    ];
@endphp

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div class="container-page grid items-center gap-6 pt-10 pb-16 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)] lg:pt-6 lg:pb-20">

        <div class="relative z-10 lg:py-8">
            <h1 class="font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl lg:text-[3.2rem] lg:leading-[1.08]">
                Cat care questions,<br>
                <span class="text-primary">answered</span>
            </h1>

            <p class="mt-5 max-w-md text-base leading-relaxed text-ink-muted">
                {{ $count }} answers to the things cat owners actually ask.
                Browse by topic, or search for your question.
            </p>

            {{-- Filters the questions already on the page as you type. Nothing
                 it searches is fetched, so there is no request and no empty
                 state that arrives late. --}}
            <form class="mt-7 max-w-md" role="search" onsubmit="return false">
                <label for="faq-search" class="sr-only">Search the questions</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute top-1/2 left-4 size-5 -translate-y-1/2 text-ink-muted"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/>
                    </svg>
                    <input id="faq-search" type="search" data-faq-search autocomplete="off"
                           placeholder="Search your question…"
                           class="w-full rounded-full border border-line bg-surface py-3.5 pr-14 pl-12 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    <span aria-hidden="true"
                          class="absolute top-1/2 right-2 flex size-9 -translate-y-1/2 items-center justify-center rounded-full bg-primary-vivid text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </div>
            </form>

            <p data-faq-status role="status" aria-live="polite" class="mt-3 text-sm text-ink-muted"></p>

            {{-- Three plain statements of fact. "Vet reviewed" and "trusted by
                 cat parents" were in the design and are not used: no vet has
                 reviewed this, and the site has no audience figures to stand
                 a trust claim on. --}}
            <ul class="mt-6 flex flex-wrap gap-x-6 gap-y-3">
                @foreach ([
                    ['Published sources', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z'],
                    ['Free, no sign-up', 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
                    ['Plain English', 'M12 21.5a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19Zm-3.6-9.6 2.6 2.6 5-5.2'],
                ] as [$label, $d])
                    <li class="flex items-center gap-2 text-sm font-medium text-ink-muted">
                        <svg class="size-4 shrink-0 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $d }}"/>
                        </svg>
                        {{ $label }}
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- -mr-8 cancels the container padding so the artwork runs to the edge
             of the band; the aspect box matches the file's own 3:2 so
             object-contain fits it exactly at any width. --}}
        <div class="lg:-mr-8 lg:self-center">
            <div class="aspect-[3/2]">
                <x-img name="purrquery-faq-hero-kitten-blanket"
                       alt="Ginger kitten wrapped in a soft pink blanket beside a heart speech bubble"
                       sizes="(min-width: 1024px) 50vw, 100vw" fit="contain" :priority="true"/>
            </div>
        </div>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-12 w-full text-surface sm:h-20" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ 2. TOPIC INDEX ════════════════════════════════════════════════════ --}}
<section class="bg-surface pt-4 pb-8 lg:pt-6">
    <div class="container-page">
        <h2 class="reveal text-center font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
            Browse questions by topic
        </h2>

        <ul class="mt-7 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
            @foreach ($groups as $group)
                <li class="reveal" style="--reveal-delay: {{ $loop->index * 80 }}ms">
                    <a href="#{{ $group['id'] }}"
                       class="flex h-full items-center gap-3.5 rounded-2xl border border-line bg-surface p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-line-strong hover:shadow-md">
                        <span @class(['flex size-11 shrink-0 items-center justify-center rounded-xl', $tones[$group['tone']]['tile']])>
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                @foreach ($group['paths'] as $d)<path d="{{ $d }}"/>@endforeach
                            </svg>
                        </span>
                        <span>
                            <span class="block font-heading text-sm font-bold text-ink">{{ $group['title'] }}</span>
                            <span class="mt-0.5 block text-xs text-ink-muted">{{ count($group['items']) }} questions</span>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ══ 3. QUESTIONS ══════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-10">
    <div class="container-page space-y-6">
        @foreach ($groups as $group)
            <div data-faq-group id="{{ $group['id'] }}"
                 class="reveal scroll-mt-24 overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">

                {{-- Header band. The artwork is decorative and sits behind the
                     heading, so it drops out on the widths where it would
                     start crowding the words. --}}
                <div @class(['relative flex items-center gap-4 p-5 sm:p-6', $tones[$group['tone']]['band']])>
                    <span @class(['flex size-12 shrink-0 items-center justify-center rounded-xl bg-surface shadow-sm', $tones[$group['tone']]['tile']])>
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            @foreach ($group['paths'] as $d)<path d="{{ $d }}"/>@endforeach
                        </svg>
                    </span>
                    <div class="relative z-10">
                        <h2 class="font-heading text-lg font-extrabold tracking-tight text-ink sm:text-xl">{{ $group['title'] }}</h2>
                        <p class="mt-0.5 text-sm text-ink-muted">{{ $group['blurb'] }}</p>
                    </div>

                    <div aria-hidden="true" class="pointer-events-none absolute -top-2 right-3 hidden h-[120%] w-40 sm:block lg:w-48">
                        <x-img :name="$group['image']" :alt="$group['image_alt']" sizes="192px" fit="contain"/>
                    </div>
                </div>

                <div class="divide-y divide-line border-t border-line">
                    @foreach ($group['items'] as $item)
                        {{-- details/summary rather than a scripted accordion: it
                             opens with a keyboard, works without JavaScript, and
                             keeps the answer in the DOM either way — which is
                             what lets the FAQ markup describe it honestly. --}}
                        <details data-faq-item
                                 data-terms="{{ Str::lower($item['q'].' '.$item['a']) }}"
                                 class="group px-5 transition-colors open:bg-surface-section sm:px-6">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-3.5 text-sm font-semibold text-ink marker:content-['']">
                                {{ $item['q'] }}
                                <svg class="size-5 shrink-0 text-primary transition-transform duration-200 group-open:rotate-180"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </summary>
                            <p class="pb-4 text-sm leading-relaxed text-ink-muted">{{ $item['a'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Shown only when a search matches nothing. --}}
        <p data-faq-empty hidden class="rounded-xl border border-line bg-surface-soft p-6 text-center text-base text-ink-muted">
            Nothing here matches that. Try a different word, or
            <a href="{{ route('contact') }}" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">ask us directly</a>.
        </p>
    </div>
</section>

{{-- ══ 4. STILL STUCK ════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-12">
    <div class="container-page">
        <div class="reveal grid overflow-hidden rounded-2xl bg-surface-soft sm:grid-cols-[minmax(0,1fr)_auto]">
            <div class="px-6 py-10 text-center sm:px-10 sm:text-left">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                    Still have a question?
                </h2>
                <p class="mt-3 max-w-sm text-base leading-relaxed text-ink-muted">
                    Ask it. The ones that come up more than once end up on this
                    page, so you would be doing the next person a favour too.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-3 sm:justify-start">
                    <a href="{{ route('contact') }}" class="btn-primary rounded-full px-7">Contact us</a>
                    <a href="/#tools" class="btn-outline rounded-full bg-surface px-7">Explore tools</a>
                </div>
            </div>

            <div aria-hidden="true" class="hidden w-56 self-end sm:block lg:w-64">
                <div class="aspect-[3/2]">
                    <x-img name="purrquery-cat-saying-hi"
                           alt="Curious gray tabby cat peeking over a surface with a heart speech bubble"
                           sizes="256px" fit="contain"/>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ 5. NUMBERS ════════════════════════════════════════════════════════ --}}
{{-- Sits directly on the footer's colour, so the two read as one block and
     the footer's wave is set to match. --}}
<section class="bg-primary-dark py-8 text-ink-inverse">
    <div class="container-page">
        <ul class="grid grid-cols-2 gap-6 lg:grid-cols-4">
            @foreach ($stats as [$figure, $label])
                <li class="reveal flex items-center gap-3" style="--reveal-delay: {{ $loop->index * 70 }}ms">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-surface/10 ring-1 ring-surface/20">
                        <x-paw-print class="size-5 text-primary-vivid"/>
                    </span>
                    <span>
                        <span class="block font-heading text-xl font-extrabold tracking-tight sm:text-2xl">{{ $figure }}</span>
                        <span class="mt-0.5 block text-xs text-ink-inverse/75">{{ $label }}</span>
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</section>

@push('scripts')
    <script>
        (() => {
            const input = document.querySelector('[data-faq-search]');
            const status = document.querySelector('[data-faq-status]');
            const empty = document.querySelector('[data-faq-empty]');
            const items = [...document.querySelectorAll('[data-faq-item]')];
            const groups = [...document.querySelectorAll('[data-faq-group]')];
            if (!input) return;

            const apply = () => {
                const q = input.value.trim().toLowerCase();
                let shown = 0;

                items.forEach(item => {
                    const hit = !q || item.dataset.terms.includes(q);
                    item.hidden = !hit;
                    // Opening matches saves a second click when someone has
                    // already told us what they are looking for.
                    if (q && hit) item.open = true;
                    if (!q) item.open = false;
                    if (hit) shown++;
                });

                // A heading with nothing under it reads as a broken section.
                groups.forEach(group => {
                    group.hidden = ![...group.querySelectorAll('[data-faq-item]')].some(i => !i.hidden);
                });

                empty.hidden = shown > 0;
                status.textContent = q
                    ? `${shown} ${shown === 1 ? 'question matches' : 'questions match'} “${input.value.trim()}”`
                    : '';
            };

            input.addEventListener('input', apply);
        })();
    </script>
@endpush

</x-layouts.app>

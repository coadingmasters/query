<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ Hero ══════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft pb-12 lg:pb-14">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-24 size-96 rounded-full bg-primary opacity-10 blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 size-80 rounded-full bg-accent-vivid opacity-10 blur-3xl"></div>
        <x-paw-print class="paw absolute top-[20%] left-[6%] hidden size-10 text-primary sm:block" style="animation-duration: 23s"/>
        <x-paw-print class="paw absolute top-[26%] right-[7%] hidden size-8 text-accent-vivid sm:block" style="animation-delay: -8s; animation-duration: 20s"/>
    </div>

    <div class="container-page relative py-10 text-center lg:py-12">
        <p class="eyebrow">
            <span class="size-1.5 rounded-full bg-accent-vivid"></span>
            {{ $count }} questions answered
        </p>
        <h1 class="mt-5 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
            Cat care questions, answered
        </h1>
        <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-ink-muted sm:text-lg">
            Feeding amounts, warning signs, age and weight, litter tray trouble —
            the things cat owners actually ask, answered plainly.
        </p>

        {{-- Filters the questions on the page as you type. Everything it
             searches is already in the markup, so there is no request and no
             empty state that arrives late. --}}
        <form class="mx-auto mt-7 max-w-lg" role="search" onsubmit="return false">
            <label for="faq-search" class="sr-only">Search the questions</label>
            <div class="relative">
                <svg class="pointer-events-none absolute top-1/2 left-4 size-5 -translate-y-1/2 text-ink-muted"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/>
                </svg>
                <input id="faq-search" type="search" data-faq-search autocomplete="off"
                       placeholder="Search questions…"
                       class="w-full rounded-full border border-line bg-surface py-3.5 pr-4 pl-12 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
            </div>
        </form>

        <p data-faq-status role="status" aria-live="polite" class="mt-3 text-sm text-ink-muted"></p>

        <nav aria-label="Question categories" class="mt-6 flex flex-wrap justify-center gap-2">
            @foreach ($groups as $group)
                <a href="#{{ $group['id'] }}"
                   class="rounded-full border border-line bg-surface px-4 py-2 text-sm font-medium text-ink-muted shadow-sm transition hover:border-primary hover:text-primary">
                    {{ $group['title'] }}
                </a>
            @endforeach
        </nav>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-12 w-full text-surface sm:h-20" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ Questions ═════════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface">
    <div class="container-page max-w-3xl">
        @foreach ($groups as $group)
            <div data-faq-group @class(['scroll-mt-28', 'mt-14' => ! $loop->first]) id="{{ $group['id'] }}">
                <div class="reveal">
                    <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                        {{ $group['title'] }}
                    </h2>
                    <p class="mt-2 text-base text-ink-muted">{{ $group['blurb'] }}</p>
                </div>

                <div class="mt-6 space-y-3">
                    @foreach ($group['items'] as $item)
                        {{-- details/summary rather than a scripted accordion:
                             it opens with a keyboard, works without JavaScript,
                             and keeps the answer in the DOM either way — which
                             is what lets the FAQ markup describe it honestly. --}}
                        <details data-faq-item
                                 data-terms="{{ Str::lower($item['q'].' '.$item['a']) }}"
                                 class="reveal group rounded-xl border border-line bg-surface px-5 shadow-sm transition hover:border-line-strong open:border-line-strong open:shadow-md">
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
            </div>
        @endforeach

        {{-- Shown only when a search matches nothing. --}}
        <p data-faq-empty hidden class="mt-10 rounded-xl border border-line bg-surface-soft p-6 text-center text-base text-ink-muted">
            Nothing here matches that. Try a different word, or
            <a href="{{ route('contact') }}" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">ask us directly</a>.
        </p>
    </div>
</section>

{{-- ══ Still stuck ═══════════════════════════════════════════════════════ --}}
<section class="bg-surface pt-4 pb-14">
    <div class="container-page max-w-3xl">
        <div class="reveal relative overflow-hidden rounded-2xl bg-primary px-6 py-12 text-center shadow-lg sm:px-12">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -top-16 -right-10 size-64 rounded-full bg-surface/10 blur-2xl"></div>
                <div class="absolute -bottom-20 -left-10 size-72 rounded-full bg-accent-vivid/20 blur-2xl"></div>
            </div>
            <div class="relative">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink-inverse sm:text-3xl">
                    Question not here?
                </h2>
                <p class="mx-auto mt-3 max-w-xl text-base leading-relaxed text-ink-inverse/85">
                    Ask it. The ones that come up more than once end up on this page,
                    so you would be doing the next person a favour too.
                </p>
                <div class="mt-7 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-full bg-surface px-7 py-3 text-sm font-semibold text-primary-dark shadow-md transition hover:bg-primary-light">
                        Ask a question
                    </a>
                    <a href="/#tools"
                       class="inline-flex items-center justify-center gap-2 rounded-full border border-surface/40 px-7 py-3 text-sm font-semibold text-ink-inverse transition hover:bg-surface/10">
                        Try the free tools
                    </a>
                </div>
            </div>
        </div>
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

<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ Hero ══════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft pb-12 lg:pb-14">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-24 size-96 rounded-full bg-primary opacity-10 blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 size-80 rounded-full bg-accent-vivid opacity-10 blur-3xl"></div>
    </div>

    <div class="container-page relative py-10 text-center lg:py-12">
        <p class="eyebrow">Legal</p>
        <h1 class="mt-5 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
            Terms &amp; Conditions
        </h1>
        <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-ink-muted sm:text-lg">
            What PurrQuery provides, what it does not, and the limits of relying
            on general cat care information. Written to be read, not to be
            skipped.
        </p>
        <p class="mt-5 inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2 text-sm text-ink-muted shadow-sm">
            <svg class="size-4 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 6v6l4 2"/><path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20Z"/>
            </svg>
            In effect from
            <time datetime="{{ $effective }}" class="font-semibold text-ink">
                {{ \Illuminate\Support\Carbon::parse($effective)->format('j F Y') }}
            </time>
        </p>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-12 w-full text-surface sm:h-20" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ Terms ═════════════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface">
    <div class="container-page grid gap-10 lg:grid-cols-[260px_1fr] lg:gap-14">

        {{-- Contents. Sticky on desktop so a long document stays navigable;
             a plain list on mobile, where a sticky panel would eat the screen. --}}
        <aside class="lg:sticky lg:top-24 lg:self-start">
            <nav aria-labelledby="toc-heading"
                 class="rounded-2xl border border-line bg-surface-soft p-5">
                <h2 id="toc-heading" class="font-heading text-sm font-bold tracking-wider text-ink uppercase">
                    On this page
                </h2>
                <ol class="mt-4 space-y-2.5">
                    @foreach ($sections as $i => $section)
                        <li>
                            <a href="#{{ $section['id'] }}"
                               class="flex gap-2.5 text-sm leading-snug text-ink-muted transition-colors hover:text-primary">
                                <span class="shrink-0 tabular-nums text-primary">{{ $i + 1 }}.</span>
                                {{ $section['heading'] }}
                            </a>
                        </li>
                    @endforeach
                </ol>
            </nav>
        </aside>

        <div>
            @foreach ($sections as $i => $section)
                {{-- scroll-mt clears the sticky header, or a jumped-to heading
                     lands underneath it. --}}
                <article id="{{ $section['id'] }}"
                         @class(['reveal scroll-mt-28', 'mt-12' => ! $loop->first])>
                    <div class="flex items-baseline gap-3">
                        <span class="font-heading text-sm font-extrabold tabular-nums text-primary">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">
                            {{ $section['heading'] }}
                        </h2>
                    </div>

                    <div @class([
                        'mt-4 space-y-4 text-base leading-relaxed text-ink-muted',
                        // The veterinary clause is the one that matters most
                        // here, so it is set apart rather than left to blend
                        // into ten other paragraphs of legal text.
                        'rounded-2xl border border-warning-light bg-warning-light p-6' => $section['id'] === 'not-veterinary-advice',
                    ])>
                        @foreach ($section['body'] as $paragraph)
                            <p @class(['font-medium text-ink' => $section['id'] === 'not-veterinary-advice' && $loop->first])>
                                {{ $paragraph }}
                            </p>
                        @endforeach
                    </div>
                </article>
            @endforeach

            {{-- Closing note --}}
            <div class="reveal mt-12 rounded-2xl border border-line bg-surface-soft p-6 sm:p-8">
                <h2 class="font-heading text-xl font-bold text-ink">Still have a question?</h2>
                <p class="mt-2 text-base leading-relaxed text-ink-muted">
                    If any of the above is unclear, ask. We would rather explain a
                    clause than have you guess at what it means.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('contact') }}" class="btn-primary rounded-full px-6">Contact us</a>
                    <a href="{{ route('about') }}" class="btn-outline rounded-full px-6">About PurrQuery</a>
                </div>
            </div>
        </div>
    </div>
</section>

</x-layouts.app>

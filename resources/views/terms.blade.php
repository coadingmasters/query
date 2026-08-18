<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div class="container-page grid items-center gap-6 pt-8 pb-10 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)] lg:pt-6 lg:pb-4">

        <div class="relative z-10">
            <p class="inline-flex items-center gap-2 rounded-full bg-surface px-3.5 py-1.5 text-xs font-bold tracking-[0.14em] text-primary uppercase shadow-sm">
                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z"/>
                </svg>
                Legal
            </p>

            <h1 class="mt-4 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
                Terms &amp; Conditions
            </h1>
            <span aria-hidden="true" class="mt-3 block h-1 w-40 rounded-full bg-primary-vivid"></span>

            <p class="mt-5 max-w-lg text-base leading-relaxed text-ink-muted">
                Using {{ config('app.name') }} means agreeing to these terms. They
                are short, written in plain English, and worth the two minutes it
                takes to read them.
            </p>

            <p class="mt-6 inline-flex items-center gap-2 rounded-full bg-surface px-4 py-2 text-sm font-medium text-ink shadow-sm">
                <svg class="size-4 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5.5 5h13A1.5 1.5 0 0 1 20 6.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19.5v-13A1.5 1.5 0 0 1 5.5 5Z"/>
                    <path d="M4 10h16M8.5 3v4M15.5 3v4"/>
                </svg>
                Last updated
                <time datetime="{{ $effective }}" class="font-semibold">
                    {{ \Illuminate\Support\Carbon::parse($effective)->format('F j, Y') }}
                </time>
            </p>
        </div>

        <div class="mx-auto w-full max-w-sm lg:max-w-none">
            <div class="aspect-[3/2]">
                <x-img name="purrquery-cat-waving-paw"
                       alt="Cute gray and white tabby cat waving its paw"
                       sizes="(min-width: 1024px) 44vw, 90vw" fit="contain" :priority="true"/>
            </div>
        </div>
    </div>
</section>

{{-- ══ 2. THE TERMS ══════════════════════════════════════════════════════ --}}
<section class="bg-surface py-10 lg:py-14">
    <div class="container-page">
        <h2 class="reveal text-center font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
            Our agreement in simple terms
        </h2>

        {{-- Two columns of cards rather than one long document: each clause is
             short enough to stand on its own, and a reader looking for one of
             them finds it faster than in a wall of prose. --}}
        <div class="mt-8 grid gap-4 lg:grid-cols-2">
            @foreach ($sections as $i => $section)
                @php $warm = $i % 2 === 0; @endphp

                <article id="{{ $section['id'] }}"
                         @class([
                             'reveal scroll-mt-24 flex h-full gap-4 rounded-2xl border p-5 shadow-sm transition hover:shadow-md sm:gap-5 sm:p-6',
                             // The highlighted clause carries more weight than
                             // the rest and is set apart rather than left to
                             // take its turn in the alternating tints.
                             'border-warning-light bg-warning-light' => $section['highlight'] ?? false,
                             'border-line bg-surface' => ! ($section['highlight'] ?? false),
                         ])
                         style="--reveal-delay: {{ ($i % 2) * 70 }}ms">

                    <span @class([
                        'flex size-12 shrink-0 items-center justify-center rounded-full',
                        'bg-primary-light text-primary' => $warm,
                        'bg-accent-light text-accent-dark' => ! $warm,
                    ])>
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            @foreach ($section['paths'] as $d)<path d="{{ $d }}"/>@endforeach
                        </svg>
                    </span>

                    <div class="min-w-0 flex-1">
                        <span aria-hidden="true" class="block font-heading text-xs font-extrabold tabular-nums text-primary">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <h3 class="mt-1 font-heading text-lg font-extrabold tracking-tight text-ink">
                            {{ $section['heading'] }}
                        </h3>

                        <div class="mt-2 space-y-3 text-sm leading-relaxed text-ink-muted">
                            @foreach ($section['body'] as $paragraph)
                                <p @class(['font-medium text-ink' => ($section['highlight'] ?? false) && $loop->first])>
                                    {{ $paragraph }}
                                </p>
                            @endforeach

                            @isset($section['list'])
                                <ul class="space-y-2 pt-0.5">
                                    @foreach ($section['list'] as $item)
                                        <li class="flex items-start gap-2.5">
                                            <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-primary"></span>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endisset
                        </div>

                        @if ($section['id'] === 'contact')
                            <a href="mailto:{{ config('brand.email') }}"
                               class="mt-4 inline-flex items-center gap-2 rounded-full bg-accent-light px-4 py-2 text-sm font-semibold text-ink transition hover:brightness-95">
                                <svg class="size-4 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Z"/>
                                    <path d="m3.5 8 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5"/>
                                </svg>
                                {{ config('brand.email') }}
                            </a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 3. CTA ════════════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-14">
    <div class="container-page">
        <div class="reveal grid overflow-hidden rounded-2xl bg-surface-soft sm:grid-cols-[auto_minmax(0,1fr)]">
            <div aria-hidden="true" class="hidden w-56 self-end sm:block lg:w-64">
                <div class="aspect-[3/2]">
                    <x-img name="purrquery-happy-tabby-cat-relaxing"
                           alt="Happy tabby cat relaxing comfortably with its paws raised"
                           sizes="256px" fit="contain"/>
                </div>
            </div>

            <div class="px-6 py-9 text-center sm:px-8 sm:text-left">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                    Have questions?
                </h2>
                <p class="mt-2 max-w-md text-base leading-relaxed text-ink-muted">
                    If anything here is unclear, ask. We would rather explain a
                    clause than have you guess at what it means.
                </p>
                <div class="mt-5 flex flex-wrap justify-center gap-3 sm:justify-start">
                    <a href="{{ route('contact') }}" class="btn-primary rounded-full px-7">Contact us</a>
                    <a href="{{ route('privacy') }}" class="btn-outline rounded-full bg-surface px-7">Read the privacy policy</a>
                </div>
            </div>
        </div>
    </div>
</section>

</x-layouts.app>

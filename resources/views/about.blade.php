<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ Hero ══════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft pb-12 lg:pb-14">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-24 size-96 rounded-full bg-primary-vivid opacity-[0.07] blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 size-80 rounded-full bg-accent-vivid opacity-[0.12] blur-3xl"></div>
        <x-paw-print class="paw absolute top-[18%] left-[5%] hidden size-10 text-primary sm:block" style="animation-duration: 24s"/>
        <x-paw-print class="paw absolute top-[24%] right-[8%] hidden size-8 text-accent-vivid sm:block" style="animation-delay: -7s; animation-duration: 21s"/>
    </div>

    <div class="container-page relative py-10 text-center lg:py-12">
        <h1 class="font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
            About {{ config('app.name') }}
        </h1>
        <p class="mt-4 font-heading text-xl font-bold text-primary sm:text-2xl">
            Your trusted hub for smarter, safer cat care
        </p>
        <p class="mx-auto mt-5 max-w-3xl text-base leading-relaxed text-ink-muted sm:text-lg">
            Every cat deserves the best care its owner can give, and every owner
            deserves clear, honest, research-backed answers to make that happen.
            PurrQuery is a free platform for cat owners who want reliable tools,
            safe feeding guides and practical health writing in one place.
        </p>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-12 w-full text-surface sm:h-20" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ Our story ═════════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface">
    <div class="container-page grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
        <div>
            <p class="eyebrow">Our story</p>
            <h2 class="section-title">Built because the answers were scattered</h2>

            <div class="mt-5 space-y-4 text-base leading-relaxed text-ink-muted sm:text-lg">
                <p>
                    PurrQuery started with one aim: make solid cat care knowledge
                    available to every owner, free of charge.
                </p>
                <p>
                    The same questions come up constantly — how old is my cat in
                    human years, how many calories does she actually need, can he
                    safely eat broccoli — and the answers are spread across forums,
                    product pages and articles that contradict each other.
                </p>
                <p>
                    So we built one place for them. Free, accurate, quick to use,
                    with no account and no payment. From the Cat Age Calculator to
                    the Vaccination Tracker, every tool and guide here exists to
                    help you give your cat a healthier, happier life.
                </p>
            </div>
        </div>

        <div class="relative">
            <div class="overflow-hidden rounded-[4rem_2rem_4rem_2rem] border-4 border-primary/15 shadow-lg">
                <x-img name="about-our-story"
                       alt="Persian, Siamese and Maine Coon cats sitting together"
                       sizes="(max-width: 1023px) 92vw, 560px"/>
            </div>
            <span aria-hidden="true"
                  class="absolute -bottom-5 -left-4 flex size-16 items-center justify-center rounded-full bg-accent shadow-lg">
                <x-paw-print class="size-8 text-ink-inverse"/>
            </span>
        </div>
    </div>
</section>

{{-- ══ Mission and vision ═══════════════════════════════════════════════ --}}
<section class="section-tight bg-surface-soft">
    <div class="container-page">
        <div class="grid gap-6 lg:grid-cols-2">
            @foreach (config('about.purpose') as $i => $block)
                <article class="reveal group relative overflow-hidden rounded-2xl border border-line bg-surface p-8 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg sm:p-10"
                         style="--reveal-delay: {{ $i * 120 }}ms">
                    <div aria-hidden="true" @class([
                        'pointer-events-none absolute -top-16 -right-16 size-48 rounded-full opacity-40 blur-3xl transition-opacity duration-300 group-hover:opacity-60',
                        'bg-primary-light' => $block['tone'] === 'primary',
                        'bg-accent-light' => $block['tone'] === 'accent',
                    ])></div>

                    <div class="relative">
                        <span @class([
                            'flex size-12 items-center justify-center rounded-xl',
                            'bg-primary-light text-primary' => $block['tone'] === 'primary',
                            'bg-accent-light text-accent' => $block['tone'] === 'accent',
                        ])>
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                @foreach ($block['paths'] as $d)
                                    <path d="{{ $d }}"/>
                                @endforeach
                            </svg>
                        </span>

                        <p @class([
                            'mt-6 text-xs font-bold tracking-wider uppercase',
                            'text-primary' => $block['tone'] === 'primary',
                            'text-accent' => $block['tone'] === 'accent',
                        ])>{{ $block['label'] }}</p>

                        <h2 class="mt-2 font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                            {{ $block['title'] }}
                        </h2>
                        <p class="mt-4 text-base leading-relaxed text-ink-muted">{{ $block['body'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ What we offer ═════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">What we offer</p>
            <h2 class="section-title">Three things, done properly</h2>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach (config('about.offers') as $offer)
                <article class="reveal card p-6">
                    <span @class([
                        'flex size-12 items-center justify-center rounded-xl',
                        'bg-primary-light text-primary' => $offer['tone'] === 'primary',
                        'bg-accent-light text-accent' => $offer['tone'] === 'accent',
                        'bg-info-light text-info' => $offer['tone'] === 'info',
                    ])>
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            @foreach ($offer['paths'] as $d)
                                <path d="{{ $d }}"/>
                            @endforeach
                        </svg>
                    </span>
                    <h3 class="mt-5 font-heading text-lg font-bold text-ink">{{ $offer['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $offer['body'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ Values ════════════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface-soft">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">What we stand for</p>
            <h2 class="section-title">Four commitments</h2>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2">
            @foreach (config('about.values') as $i => $value)
                <div class="reveal rounded-2xl border border-line bg-surface p-6 shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary font-heading text-sm font-extrabold text-ink-inverse">
                            {{ $i + 1 }}
                        </span>
                        <h3 class="font-heading text-lg font-bold text-ink">{{ $value['title'] }}</h3>
                    </div>
                    <p class="mt-3 text-sm leading-relaxed text-ink-muted">{{ $value['body'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ By the numbers ════════════════════════════════════════════════════ --}}
<section class="section-tight bg-primary-dark">
    <div class="container-page">
        <div class="text-center">
            <p class="inline-flex items-center rounded-full border border-surface/25 bg-surface/10 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-ink-inverse uppercase">
                By the numbers
            </p>
            <h2 class="mt-4 font-heading text-3xl font-extrabold tracking-tight text-ink-inverse sm:text-4xl">
                Where PurrQuery is today
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-ink-inverse/75">
                Counted from what is actually published, and updated as the site
                grows — not rounded up.
            </p>
        </div>

        <dl class="mt-10 grid grid-cols-2 gap-5 lg:grid-cols-4">
            @foreach ($stats as [$figure, $label])
                <div class="reveal rounded-2xl bg-surface/10 p-6 text-center ring-1 ring-surface/15">
                    <dt class="sr-only">{{ $label }}</dt>
                    <dd>
                        <span class="block font-heading text-4xl font-extrabold tracking-tight text-ink-inverse sm:text-5xl">{{ $figure }}</span>
                        <span class="mt-2 block text-sm text-ink-inverse/75">{{ $label }}</span>
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>

{{-- ══ Disclaimer ════════════════════════════════════════════════════════
     Kept, but compact. This is health guidance about someone's animal, so
     saying plainly that it is not veterinary advice matters — for the reader,
     and for the ad networks the site is heading towards. The footer carries
     the short form on every page; this is the fuller sentence in one line.
     ═══════════════════════════════════════════════════════════════════════ --}}
<section class="bg-surface pt-2 pb-10">
    <div class="container-page">
        <p class="mx-auto flex max-w-3xl items-start gap-3 rounded-xl border border-line bg-surface-soft px-5 py-4 text-sm leading-relaxed text-ink-muted">
            <svg class="mt-0.5 size-4 shrink-0 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 9v4.5M12 17h.01"/>
                <path d="M10.3 3.9 2.4 17.5A2 2 0 0 0 4.1 20.5h15.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
            </svg>
            <span>
                <strong class="font-semibold text-ink">General information, not veterinary advice.</strong>
                Always speak to a qualified vet about a specific concern — and in an
                emergency, contact your vet or an emergency clinic straight away.
            </span>
        </p>
    </div>
</section>

{{-- ══ CTA ═══════════════════════════════════════════════════════════════ --}}
<section class="pb-14 bg-surface">
    <div class="container-page">
        <div class="relative overflow-hidden rounded-2xl border border-line bg-accent-light px-6 py-14 text-center shadow-lg sm:px-12">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -top-16 -right-10 size-64 rounded-full bg-primary-vivid/20 blur-2xl"></div>
                <div class="absolute -bottom-20 -left-10 size-72 rounded-full bg-accent-vivid/20 blur-2xl"></div>
            </div>
            <div class="relative">
                <h2 class="font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                    Ready to care smarter for your cat?
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-ink-muted sm:text-lg">
                    Start with your cat’s age or ideal weight. It takes about thirty
                    seconds, and there is nothing to sign up for.
                </p>

                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="/#tools"
                       class="btn-primary rounded-full px-7">
                        Try our free tools
                    </a>
                    <a href="/#food-guides"
                       class="btn-outline rounded-full px-7">
                        Explore food guides
                    </a>
                </div>

                <ul class="mt-8 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-ink-muted">
                    @foreach (['No sign-up required', 'Instant results', 'Free forever'] as $pill)
                        <li class="flex items-center gap-2">
                            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            {{ $pill }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

</x-layouts.app>

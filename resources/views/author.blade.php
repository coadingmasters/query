<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ 1. PROFILE ════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <x-paw-print class="paw absolute top-[22%] left-[5%] hidden size-9 text-primary-vivid/25 lg:block [animation-duration:23s]"/>
        <x-paw-print class="paw absolute right-[7%] bottom-[18%] hidden size-7 text-accent-vivid/30 lg:block [animation-delay:-8s] [animation-duration:26s]"/>
    </div>

    <div class="container-page relative py-10 lg:py-14">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('about') }}" class="transition-colors hover:text-primary">About</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink">{{ $author['name'] }}</li>
            </ol>
        </nav>

        <div class="mt-6 grid gap-8 lg:grid-cols-[auto_minmax(0,1fr)] lg:items-start lg:gap-12">
            {{-- Photo, with a circular badge overlapping its corner: the
                 kind of small trust signal that reads as considered rather
                 than a plain framed headshot. --}}
            <div class="relative mx-auto w-fit sm:mx-0">
                @if ($author['image'])
                    <div class="size-40 overflow-hidden rounded-2xl bg-surface shadow-md ring-1 ring-line sm:size-48 lg:size-56">
                        <x-img :name="$author['image']" :alt="$author['name']" sizes="224px" :priority="true"/>
                    </div>
                @else
                    <div class="flex size-40 items-center justify-center rounded-2xl bg-primary-light sm:size-48 lg:size-56">
                        <x-paw-print class="size-16 text-primary"/>
                    </div>
                @endif

                <div class="absolute -bottom-4 -left-4 flex size-[4.75rem] items-center justify-center">
                    <svg class="badge-spin absolute inset-0 size-full text-primary" viewBox="0 0 100 100" aria-hidden="true">
                        <defs>
                            <path id="author-badge-ring" d="M 50,50 m -38,0 a 38,38 0 1,1 76,0 a 38,38 0 1,1 -76,0"/>
                        </defs>
                        <circle cx="50" cy="50" r="48" fill="var(--color-surface)" stroke="currentColor" stroke-width="1.5"/>
                        <text font-size="8" font-weight="700" letter-spacing="1.6" fill="currentColor">
                            <textPath href="#author-badge-ring">CAT CARE ADVOCATE &nbsp;&bull;&nbsp;&nbsp; CAT CARE ADVOCATE &nbsp;&bull;&nbsp;&nbsp; </textPath>
                        </text>
                    </svg>
                    <span class="relative flex size-9 items-center justify-center rounded-full bg-primary-vivid text-ink shadow-sm">
                        <x-paw-print class="size-4"/>
                    </span>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold tracking-[0.14em] text-primary uppercase">Author</p>
                <h1 class="mt-2 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
                    {{ $author['name'] }}
                </h1>
                <p class="mt-2 text-base text-ink-muted">{{ $author['role'] }} at {{ config('app.name') }}</p>
                <span aria-hidden="true" class="mt-4 block h-1 w-14 rounded-full bg-primary-vivid"></span>

                <div class="reveal mt-6 max-w-2xl space-y-4 text-base leading-relaxed text-ink-muted">
                    @foreach ($author['bio'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>

                {{-- The links are the part that makes the name checkable, which is
                     worth more to a reader than the paragraphs above them. --}}
                @if ($author['profiles'])
                    <div class="reveal mt-6 flex flex-wrap items-center gap-3">
                        @foreach ($author['profiles'] as $profile)
                            <a href="{{ $profile }}" rel="me noopener" target="_blank"
                               class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2 text-sm font-semibold text-ink shadow-sm transition hover:border-line-strong">
                                {{ Str::of($profile)->after('//')->after('www.')->before('/')->toString() }}
                                <svg class="size-3.5 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M7 17 17 7M9 7h8v8"/>
                                </svg>
                            </a>
                        @endforeach

                        <a href="mailto:{{ config('brand.email') }}"
                           class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2 text-sm font-semibold text-ink shadow-sm transition hover:border-line-strong">
                            <svg class="size-4 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Z"/>
                                <path d="m3.5 8 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5"/>
                            </svg>
                            {{ config('brand.email') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ══ 2. BY THE NUMBERS ═════════════════════════════════════════════════ --}}
<section class="bg-surface py-10 lg:py-14">
    <div class="container-page">
        <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as $i => $stat)
                <li class="reveal rounded-2xl border border-line bg-surface p-6 text-center shadow-sm"
                    style="--reveal-delay: {{ $i * 70 }}ms">
                    <span class="block font-heading text-3xl font-extrabold tracking-tight text-primary">{{ $stat['value'] }}</span>
                    <span class="mt-1.5 block text-sm font-bold text-ink">{{ $stat['label'] }}</span>
                    <span class="mt-1.5 block text-sm leading-relaxed text-ink-muted">{{ $stat['body'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ══ 3. HOW THE WRITING WORKS ══════════════════════════════════════════ --}}
{{-- The editorial standards, stated plainly. This is the part a quality rater
     looks for on an author page and most small sites never write down. --}}
<section class="bg-surface-section py-10 lg:py-14">
    <div class="container-page grid items-center gap-8 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,1fr)] lg:gap-12">
        <div>
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <x-paw-print class="size-4"/>
                How the guides are written
            </p>
            <h2 class="mt-3 font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                Where the answers come from
            </h2>

            <ul class="mt-7 grid gap-4 sm:grid-cols-2">
                @foreach ([
                    ['Published sources, named', 'Guidance is researched from veterinary bodies such as the AAFP, the AVMA and Cornell Feline Health Center, and the tools that use their figures say so on the page.'],
                    ['Limits stated, not hidden', 'Where evidence is thin or genuinely disputed, that is written down rather than smoothed over into a tidier answer.'],
                    ['Not a substitute for a vet', 'Anything approaching diagnosis or dosage says to speak to a vet. A calculator cannot examine your cat and this site never pretends otherwise.'],
                    ['Corrections come first', 'A wrong answer about what a cat can eat is worth fixing quickly. Corrections go to the front of the queue, and the page records when it last changed.'],
                ] as $i => [$heading, $body])
                    <li class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm"
                        style="--reveal-delay: {{ $i * 70 }}ms">
                        <h3 class="font-heading text-base font-bold text-ink">{{ $heading }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $body }}</p>
                    </li>
                @endforeach
            </ul>

            @if ($reviewer['name'])
                <div class="reveal mt-6 rounded-2xl border border-accent-light bg-accent-light p-5">
                    <x-byline :reviewed="true"/>
                </div>
            @endif
        </div>

        @if ($catImage)
            <div class="reveal mx-auto hidden w-full max-w-sm lg:block lg:max-w-none" style="--reveal-delay: 100ms">
                <div class="aspect-[4/5] overflow-hidden rounded-2xl bg-surface-soft">
                    <img src="{{ $catImage->url }}" width="{{ $catImage->width }}" height="{{ $catImage->height }}"
                         alt="{{ $catImage->alt_text ?: 'Fluffy tabby cat sitting beside a potted flowering plant' }}"
                         class="h-full w-full object-cover" loading="lazy" decoding="async">
                </div>
            </div>
        @endif
    </div>
</section>

{{-- ══ 4. CTA ════════════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-14">
    <div class="container-page">
        <div class="reveal grid overflow-hidden rounded-2xl bg-surface-soft sm:grid-cols-[auto_minmax(0,1fr)]">
            <div aria-hidden="true" class="hidden w-56 self-end sm:block">
                <div class="aspect-[3/2]">
                    <x-img name="purrquery-cat-saying-hi"
                           alt="Curious gray tabby cat peeking over a surface"
                           sizes="224px" fit="contain"/>
                </div>
            </div>

            <div class="px-6 py-9 text-center sm:px-8 sm:text-left">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">
                    Found something wrong?
                </h2>
                <p class="mt-2 text-base leading-relaxed text-ink-muted">
                    Tell me. Corrections are the most useful message this site gets,
                    and they get looked at first.
                </p>
                <div class="mt-5 flex flex-wrap justify-center gap-3 sm:justify-start">
                    <a href="{{ route('contact') }}" class="btn-primary rounded-full px-7">Get in touch</a>
                    <a href="{{ route('about') }}" class="btn-outline rounded-full bg-surface px-7">About {{ config('app.name') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

</x-layouts.app>

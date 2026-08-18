<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div class="container-page grid items-center gap-6 pt-10 pb-4 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)] lg:pt-6 lg:pb-0">

        <div class="relative z-10 lg:py-10">
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <svg class="size-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z"/>
                </svg>
                About {{ config('app.name') }}
            </p>

            <h1 class="mt-5 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl lg:text-[3.2rem] lg:leading-[1.08]">
                Built for cat lovers.<br>
                <span class="text-primary">Backed by care.</span>
            </h1>

            <p class="mt-5 max-w-md text-base leading-relaxed text-ink-muted">
                {{ config('app.name') }} exists to make cat care easier to get right.
                Free tools that answer in seconds, and guides that say where the
                answer came from.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="/#tools" class="btn-primary rounded-full px-7">Try our free tools</a>
                <a href="/#food-guides" class="btn-outline rounded-full bg-surface px-7">Explore care guides</a>
            </div>
        </div>

        {{-- -mr-8 cancels the container padding so the artwork runs to the edge
             of the band; the aspect box matches the file's own 3:2 so
             object-contain fits it exactly at any width. --}}
        <div class="lg:-mr-8 lg:self-end">
            <div class="aspect-[3/2]">
                <x-img name="purrquery-about-hero-three-kittens"
                       alt="Three adorable kittens sitting together on a soft pink blanket"
                       sizes="(min-width: 1024px) 50vw, 100vw" fit="contain" :priority="true"/>
            </div>
        </div>
    </div>
</section>

{{-- ══ 2. MISSION ════════════════════════════════════════════════════════ --}}
<section class="bg-surface py-10 lg:py-14">
    <div class="container-page grid items-center gap-8 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)] lg:gap-12">

        {{-- Hidden on phones: the mission reads perfectly well without it
             there, and it was the tallest thing between the hero and
             the pillars. --}}
        <div class="reveal mx-auto hidden w-full max-w-sm sm:block lg:max-w-none">
            <div class="aspect-[3/2] overflow-hidden rounded-2xl bg-surface-soft">
                <x-img name="purrquery-cat-cozy-blanket"
                       alt="Tabby kitten resting comfortably in a soft blanket"
                       sizes="(min-width: 1024px) 400px, 90vw" fit="cover"/>
            </div>
        </div>

        <div class="reveal" style="--reveal-delay: 100ms">
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <x-paw-print class="size-4"/>
                Our mission
            </p>
            <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                Smarter tools. <span class="text-primary">Happier cats.</span>
            </h2>
            <p class="mt-4 max-w-xl text-base leading-relaxed text-ink-muted">
                {{ config('about.purpose.0.body') }}
            </p>

            <ul class="mt-8 grid gap-x-6 gap-y-7 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (config('about.pillars') as $pillar)
                    <li class="reveal lg:border-l lg:border-line lg:pl-5 lg:first:border-l-0 lg:first:pl-0"
                        style="--reveal-delay: {{ $loop->index * 90 }}ms">
                        <span @class([
                            'flex size-10 items-center justify-center rounded-xl',
                            'bg-primary-light text-primary' => $pillar['tone'] === 'primary',
                            'bg-accent-light text-accent-dark' => $pillar['tone'] === 'accent',
                        ])>
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                @foreach ($pillar['paths'] as $d)<path d="{{ $d }}"/>@endforeach
                            </svg>
                        </span>
                        <span class="mt-3 block font-heading text-sm font-bold text-ink">{{ $pillar['title'] }}</span>
                        <span class="mt-1.5 block text-sm leading-relaxed text-ink-muted">{{ $pillar['body'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- ══ 3. STORY ══════════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-10 lg:pb-14">
    <div class="container-page">
        <div class="reveal grid overflow-hidden rounded-2xl bg-accent-light lg:grid-cols-2">

            <div class="order-2 p-6 sm:p-10 lg:order-1 lg:self-center">
                <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-accent-dark uppercase">
                    <x-paw-print class="size-4"/>
                    Our story
                </p>
                <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                    It started with a<br class="hidden sm:inline">
                    <span class="text-primary">simple frustration</span>
                </h2>
                <p class="mt-4 text-base leading-relaxed text-ink-muted">
                    Looking up something as ordinary as whether a cat can eat a
                    particular food meant wading through nine pages that contradicted
                    each other, and none of them said where the answer came from.
                </p>
                <p class="mt-3 text-base leading-relaxed text-ink-muted">
                    So we built the opposite: the verdict first, the reasoning
                    underneath it, and the source named. Free, with no account, and
                    no email needed to see a result.
                </p>
            </div>

            <div class="order-1 lg:order-2">
                <div class="aspect-[3/2] lg:h-full">
                    <x-img name="purrquery-cat-lover-cuddling-cat"
                           alt="Cat lover cuddling a tabby cat at home"
                           sizes="(min-width: 1024px) 50vw, 100vw" fit="cover"/>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ 3b. WHO WRITES THIS ══════════════════════════════════════════════ --}}
{{-- The whole section is behind a configured name. Cat health is YMYL, where
     Google's guidance leans hardest on who wrote a thing and whether they can
     be checked, and an invented author is precisely what that guidance exists
     to catch. No name configured, no section. --}}
@if (config('author.founder.name'))
    @php $author = config('author.founder'); @endphp

    <section id="founder" class="scroll-mt-24 bg-surface pb-10 lg:pb-14">
        <div class="container-page">
            <div class="reveal grid gap-8 rounded-2xl border border-line bg-surface-section p-6 shadow-sm sm:p-9 lg:grid-cols-[auto_minmax(0,1fr)] lg:gap-10">

                <div class="flex items-center gap-4 lg:block">
                    @if ($author['image'])
                        <div class="size-24 shrink-0 overflow-hidden rounded-2xl bg-surface-soft lg:size-44">
                            <x-img :name="$author['image']" :alt="$author['name']" sizes="176px"/>
                        </div>
                    @else
                        <div class="flex size-24 shrink-0 items-center justify-center rounded-2xl bg-primary-light lg:size-44">
                            <x-paw-print class="size-10 text-primary lg:size-16"/>
                        </div>
                    @endif

                    <div class="lg:mt-4">
                        <p class="font-heading text-lg font-extrabold tracking-tight text-ink">{{ $author['name'] }}</p>
                        <p class="mt-0.5 text-sm text-ink-muted">{{ $author['role'] }}</p>
                    </div>
                </div>

                <div>
                    <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                        <x-paw-print class="size-4"/>
                        Who writes this
                    </p>

                    <div class="mt-4 space-y-3 text-base leading-relaxed text-ink-muted">
                        @foreach ($author['bio'] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="{{ route('contact') }}" class="btn-primary rounded-full px-6">Get in touch</a>

                        @foreach ($author['profiles'] as $profile)
                            {{-- rel=me is what ties this page to that profile, and
                                 it is the link that makes the name checkable. --}}
                            <a href="{{ $profile }}" rel="me noopener" target="_blank"
                               class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2 text-sm font-semibold text-ink transition hover:border-line-strong">
                                {{ Str::of($profile)->after('//')->after('www.')->before('/')->toString() }}
                                <svg class="size-3.5 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M7 17 17 7M9 7h8v8"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>

                    @if (config('author.reviewer.name'))
                        <div class="mt-6 border-t border-line pt-5">
                            <x-byline :reviewed="true"/>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif

{{-- ══ 4. BY THE NUMBERS ═════════════════════════════════════════════════ --}}
<section class="bg-surface-section py-10 lg:py-14">
    <div class="container-page">
        <div class="reveal text-center">
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <x-paw-print class="size-4"/>
                {{ config('app.name') }} by the numbers
            </p>
            <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                Growing, <span class="text-primary">one guide at a time</span>
            </h2>
        </div>

        {{-- Every figure is counted from config/catalog.php at render time, so
             it cannot drift from what is actually published. --}}
        <ul class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as [$figure, $label])
                <li class="reveal rounded-2xl border border-line bg-surface p-6 text-center shadow-sm"
                    style="--reveal-delay: {{ $loop->index * 70 }}ms">
                    <span class="block font-heading text-4xl font-extrabold tracking-tight text-primary">{{ $figure }}</span>
                    <span class="mt-2 block text-sm font-semibold text-ink">{{ $label }}</span>
                </li>
            @endforeach
        </ul>

        <p class="mt-5 text-center text-sm text-ink-muted">
            Counted from what is published today, not projected.
        </p>
    </div>
</section>

{{-- ══ 5. WHY TRUST ══════════════════════════════════════════════════════ --}}
<section class="bg-surface py-10 lg:py-14">
    <div class="container-page grid items-center gap-8 lg:grid-cols-2 lg:gap-12">

        <div class="reveal">
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <x-paw-print class="size-4"/>
                Why trust {{ config('app.name') }}
            </p>
            <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                Because your cat <span class="text-primary">deserves the best</span>
            </h2>

            <ul class="mt-6 space-y-3.5">
                @foreach (config('about.trust') as $point)
                    <li class="reveal flex items-start gap-3" style="--reveal-delay: {{ $loop->index * 70 }}ms">
                        <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-primary-vivid text-ink">
                            <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </span>
                        <span class="text-base leading-relaxed text-ink-muted">{{ $point }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="reveal mx-auto w-full max-w-md lg:max-w-none" style="--reveal-delay: 100ms">
            <div class="aspect-[3/2]">
                <x-img name="purrquery-happy-tabby-cat-relaxing"
                       alt="Happy tabby cat relaxing comfortably with its paws raised"
                       sizes="(min-width: 1024px) 48vw, 90vw" fit="contain"/>
            </div>
        </div>
    </div>
</section>

{{-- ══ 6. CTA ════════════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-12 lg:pb-16">
    <div class="container-page">
        <div class="reveal grid overflow-hidden rounded-2xl bg-accent-light shadow-lg sm:grid-cols-[minmax(0,1fr)_auto]">

            <div class="px-6 py-10 text-center sm:px-10 sm:text-left">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                    Ready to care smarter for your cat?
                </h2>
                <p class="mt-3 max-w-md text-base leading-relaxed text-ink-muted">
                    Start with small steps today, for a healthier, happier tomorrow.
                </p>
                <a href="/#tools" class="btn-primary mt-6 rounded-full px-7">Explore our tools</a>
            </div>

            {{-- The file carries its own headline across the top, which would
                 repeat the live one above. The box crops to the artwork below
                 that text, and drops out entirely where it will not fit. --}}
            <div aria-hidden="true" class="hidden w-60 self-end sm:block lg:w-72">
                {{-- The artwork sits on its own cream ground, which reads as a
                     pale panel on this band. Feathering the edge lets it sit on
                     the sage instead of on top of it. --}}
                <div class="aspect-[380/301]"
                     style="mask-image: radial-gradient(92% 92% at 50% 47%, #000 40%, transparent 96%);
                            -webkit-mask-image: radial-gradient(92% 92% at 50% 47%, #000 40%, transparent 96%)">
                    <x-img name="purrquery-care-smarter-cat-illustration"
                           alt="Playful orange kitten relaxing in a cozy bed with cat care essentials"
                           sizes="288px" fit="contain"/>
                </div>
            </div>
        </div>
    </div>
</section>

</x-layouts.app>

<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    $verdictLabel = ['safe' => 'Safe', 'caution' => 'In moderation', 'unsafe' => 'Never'];

    $toc = [
        ['id' => 'why-cats-are-different', 'label' => "Why a cat's list is different"],
        ['id' => 'how-verdicts-work', 'label' => 'How the verdicts work'],
        ['id' => 'the-ten-categories', 'label' => 'What the categories cover'],
        ['id' => 'faq', 'label' => 'Frequently asked questions'],
    ];

    $recommendedTools = collect(config('catalog.tools'))->take(3);
@endphp

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-24 -left-20 size-96 rounded-full bg-accent-vivid opacity-[0.08] blur-3xl"></div>
        <div class="absolute -right-16 bottom-0 size-80 rounded-full bg-primary-vivid opacity-[0.1] blur-3xl"></div>
        <x-paw-print class="paw absolute top-[16%] right-[8%] hidden size-9 text-accent-vivid lg:block [animation-duration:23s]"/>
        <x-paw-print class="paw absolute top-[62%] right-[32%] hidden size-6 text-primary lg:block [animation-delay:-9s] [animation-duration:20s]"/>
        <x-paw-print class="paw absolute bottom-[12%] left-[6%] hidden size-7 text-accent-vivid lg:block [animation-delay:-5s] [animation-duration:25s]"/>
    </div>

    <div class="container-page relative pt-8 pb-16 lg:pt-6 lg:pb-20">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink">Food Guides</li>
            </ol>
        </nav>

        <div class="mt-4 grid items-center gap-8 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)] lg:gap-6">
            <div class="relative z-10">
                <p class="hero-in inline-flex items-center gap-2 rounded-full bg-surface px-3.5 py-1.5 text-xs font-bold tracking-[0.14em] text-accent-dark uppercase shadow-sm" style="--i: 0">
                    <x-paw-print class="size-3.5"/>
                    Food safety guides
                </p>

                <h1 class="hero-in mt-5 font-heading text-4xl leading-[1.08] font-extrabold tracking-tight text-ink sm:text-5xl" style="--i: 1">
                    What can cats
                    <span class="text-accent">eat?</span>
                </h1>

                <p class="hero-in mt-4 max-w-md text-base leading-relaxed text-ink-muted sm:text-lg" style="--i: 2">
                    The answer, before the article. {{ $foods->count() }} categories covering what is
                    safe, what needs care, and what to never let near your cat.
                </p>

                <ul class="hero-in mt-7 flex flex-wrap gap-x-6 gap-y-3" style="--i: 3">
                    @foreach ([
                        ['Published sources', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z|m9.4 12.2 1.9 1.9 3.6-3.7'],
                        ['Verdict up front', 'M20 6 9 17l-5-5'],
                        ['Free, no sign-up', 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
                    ] as [$label, $paths])
                        <li class="flex items-center gap-2.5">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-surface text-accent-dark shadow-sm">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    @foreach (explode('|', $paths) as $d)<path d="{{ $d }}"/>@endforeach
                                </svg>
                            </span>
                            <span class="text-sm font-semibold text-ink">{{ $label }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative lg:self-center">
                <div class="cat-breathe relative overflow-hidden rounded-[4rem_1.75rem_4rem_1.75rem] border-4 border-accent-light bg-surface shadow-lg">
                    <div class="aspect-[3/2]">
                        <x-img name="purrquery-cat-food-bowl-heart"
                               alt="Heart-shaped bowl filled with cat kibble"
                               sizes="(max-width: 1023px) 92vw, 640px" :priority="true"/>
                    </div>
                </div>

                <div aria-hidden="true" style="--i: 0"
                     class="cat-badge absolute -bottom-5 right-4 hidden items-center gap-3 rounded-2xl border border-line bg-surface px-4 py-3 shadow-lg sm:flex">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-accent-light text-accent-dark">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                    </span>
                    <span>
                        <span class="block font-heading text-sm font-extrabold text-ink">Safe, caution or never</span>
                        <span class="mt-0.5 block text-xs text-ink-muted">Every guide, at a glance</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-12 w-full text-surface sm:h-16" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ 2. OVERVIEW + FAQ (left) / SIDEBAR (right) ═══════════════════════════ --}}
<section class="section-tight bg-surface">
    <div class="container-page">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">

            {{-- ── Main ─────────────────────────────────────────────── --}}
            <div class="space-y-6">
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <p class="text-base leading-relaxed text-ink-muted">What can cats eat? Quite a lot of ordinary food, from a specific list: plain cooked chicken, a little tuna, a spoonful of pumpkin, a bite of melon. The harder question is what human food is bad for cats, since a handful of ordinary kitchen staples, harmless to a person or a dog, are dangerous to a cat. This page indexes human foods cats can and cannot eat, organized into ten categories, so you can go straight to what you need.</p>

                    <h2 id="why-cats-are-different" class="mt-8 flex items-center gap-2.5 font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">Why a cat's food safety list isn't a dog's, or a person's</h2>
                    <p class="mt-4 text-base leading-relaxed text-ink-muted">Cats are obligate carnivores, not just picky eaters. Their bodies run on animal protein and fat, not the broader diet a dog or a person can process, and they need nutrients like taurine that occur almost exclusively in meat. Cats also lack some of the liver enzymes other mammals use to break down plant compounds and sugars, which is why a dog's food safety list doesn't transfer cleanly to a cat. Onion and garlic show this well: both damage a cat's red blood cells at a smaller dose than they would a dog of similar size, and body size adds to the gap. An amount of chocolate a large dog shrugs off is proportionally much bigger for a four-kilogram cat, so the line between foods poisonous to cats and foods that are merely rich sits somewhere most people don't expect.</p>

                    <h2 id="how-verdicts-work" class="mt-8 flex items-center gap-2.5 font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">How the safe, caution and never verdicts work</h2>
                    <p class="mt-4 text-base leading-relaxed text-ink-muted">Every guide opens with one of three verdicts, stated before any explanation. Safe means fine for a healthy adult cat in a reasonable amount. Caution means not toxic, but with a real catch: a prep step, a small portion, or a part of the food, a pit, a peel, a skin, removed first. Never means no safe amount exists, and the guide covers what to do if it already happened. Each verdict is tied to a reason drawn from veterinary and nutrition sources, not stated as an unexplained rule.</p>

                    <h2 id="the-ten-categories" class="mt-8 flex items-center gap-2.5 font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">What the ten categories cover</h2>
                    <p class="mt-4 text-base leading-relaxed text-ink-muted">The categories here split the question by type of food, since almost everything in front of a cat fits one of ten groups. <a href="/food-guides/fruits" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">Fruits</a> covers what fruits can cats eat, from a safe bite of melon or blueberry to the grapes and raisins that never belong in a bowl. <a href="/food-guides/vegetables" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">Vegetables</a> covers what vegetables can cats eat, plain cooked carrot and pumpkin included, and why raw onion and garlic never make the list. <a href="/food-guides/meat-and-seafood" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">Meat and seafood</a> answers what meat can cats eat, chicken and tuna included, and why fish works better as an occasional extra than a daily habit. <a href="/food-guides/toxic-foods" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">Toxic foods</a> gathers every genuine never verdict, chocolate, grapes and alcohol among them, into one list with what to do if it happens. The remaining six work the same way: dairy and eggs for cheese and eggs, grains and seeds for rice and bread, and sweets, junk food, herbs and spices, and treats and snacks for the rest of a normal counter.</p>
                </div>

                @if (count($faqs) > 1)
                    <div id="faq" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                        <h2 class="flex items-center gap-2.5 font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">
                            Frequently asked questions
                        </h2>
                        <div class="mt-5 space-y-2.5">
                            @foreach ($faqs as $item)
                                <details name="faq" class="group border-b border-line last:border-b-0">
                                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-4 text-base font-bold text-ink transition-colors hover:text-primary marker:content-['']">
                                        {{ $item['q'] }}
                                        <svg class="size-4 shrink-0 text-ink-muted transition-transform duration-200 group-open:rotate-180"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                                            <path d="m6 9 6 6 6-6"/>
                                        </svg>
                                    </summary>
                                    <p class="pb-4 text-base leading-relaxed text-ink-muted">{{ $item['a'] }}</p>
                                </details>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── Sidebar ──────────────────────────────────────────── --}}
            <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">
                <nav aria-label="On this page" class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
                    <h2 class="font-heading text-base font-extrabold text-ink">On This Page</h2>
                    <ol class="mt-4 space-y-1">
                        @foreach ($toc as $entry)
                            <li>
                                <a href="#{{ $entry['id'] }}"
                                   class="group flex items-center gap-2.5 rounded-lg px-2 py-2 text-sm text-ink-muted transition hover:bg-surface-soft hover:text-primary">
                                    <svg class="size-3 shrink-0 text-primary-vivid transition-transform duration-200 group-hover:translate-x-0.5"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
                                        <path d="m9 6 6 6-6 6"/>
                                    </svg>
                                    {{ $entry['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </nav>

                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
                    <h2 class="font-heading text-base font-extrabold text-ink">Recommended Tools</h2>
                    <ul class="mt-4 space-y-2">
                        @foreach ($recommendedTools as $tool)
                            <li>
                                <a href="{{ $tool['url'] }}" class="group flex flex-col rounded-xl p-1.5 transition hover:bg-surface-soft">
                                    <span class="text-sm font-semibold text-ink transition-colors group-hover:text-primary">{{ $tool['title'] }}</span>
                                    <span class="mt-0.5 line-clamp-2 text-xs leading-relaxed text-ink-muted">{{ $tool['blurb'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('tools.index') }}" class="btn-outline mt-4 w-full justify-center rounded-full py-2 text-sm">
                        Explore all tools
                    </a>
                </div>

                <div class="reveal overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">
                    <div class="aspect-[4/3]">
                        <x-img name="purrquery-happy-tabby-cat-relaxing" alt="Relaxed tabby cat resting" sizes="340px"/>
                    </div>
                    <div class="bg-surface-soft p-5">
                        <p class="font-heading text-lg leading-tight font-extrabold text-primary">
                            Healthy choices,<br>happy cats
                        </p>
                        <p class="mt-2 text-sm leading-relaxed text-ink-muted">
                            Free tools and clear answers to help you make the right call for your cat.
                        </p>
                        <a href="{{ route('tools.index') }}" class="btn-primary mt-4 w-full justify-center rounded-full py-2.5 text-sm">
                            Explore tools
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- ══ 3. CATEGORIES ═════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface-section">
    <div class="container-page">
        <h2 class="reveal font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
            Every category, at a glance
        </h2>
        <p class="reveal mt-2 max-w-2xl text-base leading-relaxed text-ink-muted">
            Ten categories, each with its own verdict, safety table and sources.
        </p>

        <div class="mt-8 grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @foreach ($foods as $food)
                <a href="{{ route('food-guides.show', $food['slug']) }}" class="card reveal">
                    <div class="card-media aspect-[3/2]">
                        <x-img :name="$food['image']" :alt="$food['alt']"
                               sizes="(max-width: 639px) 46vw, (max-width: 1023px) 30vw, 23vw"/>

                        <span @class([
                            'absolute top-3 right-3 rounded-full px-3 py-1 text-xs font-bold text-ink-inverse shadow-md',
                            'bg-accent' => $food['verdict'] === 'safe',
                            'bg-warning' => $food['verdict'] === 'caution',
                            'bg-danger' => $food['verdict'] === 'unsafe',
                        ])>{{ $verdictLabel[$food['verdict']] }}</span>
                    </div>

                    <div class="card-body">
                        <p class="text-xs font-bold tracking-wide text-primary uppercase">{{ $food['title'] }}</p>
                        <h3 class="mt-1.5 line-clamp-2 font-heading text-base leading-snug font-bold text-ink">
                            {{ $food['question'] }}
                        </h3>
                        <p class="card-text line-clamp-2 flex-1">{{ $food['answer'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 4. CTA ═════════════════════════════════════════════════════════════ --}}
<section class="section bg-surface">
    <div class="container-page">
        <div class="relative overflow-hidden rounded-2xl border border-line bg-accent-light px-6 py-14 text-center shadow-lg sm:px-12">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -top-16 -right-10 size-64 rounded-full bg-primary-vivid/20 blur-2xl"></div>
                <div class="absolute -bottom-20 -left-10 size-72 rounded-full bg-accent-vivid/20 blur-2xl"></div>
            </div>
            <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-0 hidden w-48 items-center lg:flex xl:w-60">
                <div class="aspect-[3/2] w-full">
                    <x-img name="purrquery-cat-food-bowl-heart"
                           alt="Pink cat food bowl filled with kibble"
                           sizes="240px" fit="contain"/>
                </div>
            </div>
            <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 right-0 hidden w-52 items-center lg:flex xl:w-64">
                <div class="aspect-[3/2] w-full">
                    <x-img name="purrquery-happy-tabby-cat-relaxing"
                           alt="Happy tabby cat relaxing comfortably with its paws raised"
                           sizes="256px" fit="contain"/>
                </div>
            </div>

            <div class="relative mx-auto max-w-xl">
                <h2 class="font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                    Not sure what your cat just ate?
                </h2>
                <p class="mx-auto mt-4 text-base leading-relaxed text-ink-muted sm:text-lg">
                    Search any food by name, or work out a safe portion with our
                    free calculators. Both take about thirty seconds.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('search') }}" class="btn-primary rounded-full px-6">
                        Search a food
                    </a>
                    <a href="{{ route('tools.index') }}" class="btn-outline rounded-full px-6">
                        Try our tools
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

</x-layouts.app>

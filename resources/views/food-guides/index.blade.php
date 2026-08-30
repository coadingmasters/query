<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    $verdictLabel = ['safe' => 'Safe', 'caution' => 'In moderation', 'unsafe' => 'Never'];
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
                <p class="inline-flex items-center gap-2 rounded-full bg-surface px-3.5 py-1.5 text-xs font-bold tracking-[0.14em] text-accent-dark uppercase shadow-sm">
                    <x-paw-print class="size-3.5"/>
                    Food safety guides
                </p>

                <h1 class="mt-5 font-heading text-4xl leading-[1.08] font-extrabold tracking-tight text-ink sm:text-5xl">
                    What can cats
                    <span class="text-accent">eat?</span>
                </h1>

                <p class="mt-4 max-w-md text-base leading-relaxed text-ink-muted sm:text-lg">
                    The answer, before the article. {{ $foods->count() }} categories covering what is
                    safe, what needs care, and what to never let near your cat.
                </p>

                <ul class="mt-7 flex flex-wrap gap-x-6 gap-y-3">
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
                <div class="relative overflow-hidden rounded-[4rem_1.75rem_4rem_1.75rem] border-4 border-accent-light bg-surface shadow-lg">
                    <div class="aspect-[3/2]">
                        <x-img name="purrquery-cat-food-bowl-heart"
                               alt="Heart-shaped bowl filled with cat kibble"
                               sizes="(max-width: 1023px) 92vw, 640px" :priority="true"/>
                    </div>
                </div>

                <div aria-hidden="true"
                     class="absolute -bottom-5 right-4 hidden items-center gap-3 rounded-2xl border border-line bg-surface px-4 py-3 shadow-lg sm:flex">
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

{{-- ══ 2. OVERVIEW ═══════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface">
    <div class="container-page">
        <div class="reveal article mx-auto">
            <p>What can cats eat? Quite a lot of ordinary food, from a specific list: plain cooked chicken, a little tuna, a spoonful of pumpkin, a bite of melon. The harder question is what human food is bad for cats, since a handful of ordinary kitchen staples, harmless to a person or a dog, are dangerous to a cat. This page indexes human foods cats can and cannot eat, organized into ten categories, so you can go straight to what you need.</p>

            <h2 id="why-cats-are-different">Why a cat's food safety list isn't a dog's, or a person's</h2>
            <p>Cats are obligate carnivores, not just picky eaters. Their bodies run on animal protein and fat, not the broader diet a dog or a person can process, and they need nutrients like taurine that occur almost exclusively in meat. Cats also lack some of the liver enzymes other mammals use to break down plant compounds and sugars, which is why a dog's food safety list doesn't transfer cleanly to a cat. Onion and garlic show this well: both damage a cat's red blood cells at a smaller dose than they would a dog of similar size, and body size adds to the gap. An amount of chocolate a large dog shrugs off is proportionally much bigger for a four-kilogram cat, so the line between foods poisonous to cats and foods that are merely rich sits somewhere most people don't expect.</p>

            <h2 id="how-verdicts-work">How the safe, caution and never verdicts work</h2>
            <p>Every guide opens with one of three verdicts, stated before any explanation. Safe means fine for a healthy adult cat in a reasonable amount. Caution means not toxic, but with a real catch: a prep step, a small portion, or a part of the food, a pit, a peel, a skin, removed first. Never means no safe amount exists, and the guide covers what to do if it already happened. Each verdict is tied to a reason drawn from veterinary and nutrition sources, not stated as an unexplained rule.</p>

            <h2 id="the-ten-categories">What the ten categories cover</h2>
            <p>The categories here split the question by type of food, since almost everything in front of a cat fits one of ten groups. <a href="/food-guides/fruits" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">Fruits</a> covers what fruits can cats eat, from a safe bite of melon or blueberry to the grapes and raisins that never belong in a bowl. <a href="/food-guides/vegetables" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">Vegetables</a> covers what vegetables can cats eat, plain cooked carrot and pumpkin included, and why raw onion and garlic never make the list. <a href="/food-guides/meat-and-seafood" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">Meat and seafood</a> answers what meat can cats eat, chicken and tuna included, and why fish works better as an occasional extra than a daily habit. <a href="/food-guides/toxic-foods" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">Toxic foods</a> gathers every genuine never verdict, chocolate, grapes and alcohol among them, into one list with what to do if it happens. The remaining six work the same way: dairy and eggs for cheese and eggs, grains and seeds for rice and bread, and sweets, junk food, herbs and spices, and treats and snacks for the rest of a normal counter.</p>
        </div>
    </div>
</section>

{{-- ══ 3. CATEGORIES ═════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface-section">
    <div class="container-page">
        <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @foreach ($foods as $food)
                <a href="{{ route('food-guides.show', $food['slug']) }}" class="card">
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
                        <h2 class="mt-1.5 line-clamp-2 font-heading text-base leading-snug font-bold text-ink">
                            {{ $food['question'] }}
                        </h2>
                        <p class="card-text line-clamp-2 flex-1">{{ $food['answer'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 4. FAQ ═════════════════════════════════════════════════════════════ --}}
@if (count($faqs) > 1)
    <section class="section-tight bg-surface">
        <div class="container-page">
            <div class="reveal mx-auto max-w-3xl rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                <h2 class="flex items-center gap-2.5 font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">
                    Frequently asked questions
                </h2>
                <div class="mt-5 space-y-2.5">
                    @foreach ($faqs as $item)
                        <details class="group border-b border-line last:border-b-0">
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
        </div>
    </section>
@endif

</x-layouts.app>

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
                    Can cats eat
                    <span class="text-accent">that?</span>
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

<section class="section-tight bg-surface">
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

</x-layouts.app>

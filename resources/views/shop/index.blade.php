<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ 1. Hero ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft pb-16 lg:pb-20">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-24 size-96 rounded-full bg-primary-vivid opacity-[0.07] blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 size-80 rounded-full bg-accent-vivid opacity-[0.12] blur-3xl"></div>
    </div>

    <div class="container-page relative grid items-center gap-10 py-10 lg:grid-cols-2 lg:gap-10 lg:py-14">
        <div>
            <p class="eyebrow">
                <x-paw-print class="size-3.5"/>
                PurrQuery Shop
            </p>

            <h1 class="mt-5 font-heading text-4xl leading-[1.1] font-extrabold tracking-tight text-ink sm:text-5xl">
                Carefully Selected Products for Your Cat's
                <span class="text-primary">Health &amp; Happiness</span>
            </h1>

            <p class="mt-4 max-w-lg text-base leading-relaxed text-ink-muted sm:text-lg">
                Discover thoughtfully selected products for feeding, grooming,
                comfort, play and everyday cat care.
            </p>

            <ul class="mt-7 flex flex-wrap gap-x-7 gap-y-3">
                @foreach ([
                    ['Handpicked Products', 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
                    ['Trusted Brands', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z'],
                    ['Made for Cat Parents', 'M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z'],
                ] as [$label, $iconPath])
                    <li class="flex items-center gap-2 text-sm font-semibold text-ink">
                        <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-primary-light text-primary">
                            <svg class="size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="{{ $iconPath }}"/></svg>
                        </span>
                        {{ $label }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="relative">
            <div class="relative overflow-hidden rounded-[4.5rem_2rem_4.5rem_2rem] sm:rounded-[6rem_2.5rem_6rem_2.5rem] border-4 border-primary/15 bg-surface shadow-lg">
                @php $heroMedia = \App\Models\Media::where('name', 'shop-hero-section')->latest()->first(); @endphp
                @if ($heroMedia)
                    <img src="{{ $heroMedia->url }}" width="{{ $heroMedia->width }}" height="{{ $heroMedia->height }}"
                         alt="Cat beside a basket of cat care products" sizes="(max-width: 1023px) 92vw, 780px"
                         fetchpriority="high" decoding="sync" class="h-full w-full object-cover">
                @else
                    <x-img name="purrquery-hero-cat-owner-smiling" alt="Cat owner smiling as she holds her fluffy tabby"
                           sizes="(max-width: 1023px) 92vw, 780px" :priority="true"/>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ══ 2. Shop by category ═══════════════════════════════════════════════ --}}
<section class="section bg-surface">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">Categories</p>
            <h2 class="section-title">Shop by Category</h2>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categories as $category)
                @php $media = \App\Models\Media::where('name', $category['media'])->latest()->first(); @endphp
                <a href="#popular-picks" @class([
                    'group relative flex flex-col overflow-hidden rounded-2xl border border-line p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-line-strong hover:shadow-lg',
                    'bg-primary-light' => $category['tone'] === 'primary',
                    'bg-accent-light' => $category['tone'] === 'accent',
                    'bg-warning-light' => $category['tone'] === 'warning',
                    'bg-info-light' => $category['tone'] === 'info',
                    'bg-danger-light' => $category['tone'] === 'danger',
                ])>
                    @if ($media)
                        <span class="relative mb-4 aspect-[3/2] w-full overflow-hidden rounded-xl">
                            <img src="{{ $media->url }}" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover">
                        </span>
                    @else
                        <span @class([
                            'relative mb-4 flex size-14 items-center justify-center rounded-xl bg-surface shadow-sm',
                            'text-primary' => $category['tone'] === 'primary',
                            'text-accent' => $category['tone'] === 'accent',
                            'text-warning' => $category['tone'] === 'warning',
                            'text-info' => $category['tone'] === 'info',
                            'text-danger' => $category['tone'] === 'danger',
                        ])>
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                @foreach (explode('|', $category['icon']) as $d)<path d="{{ $d }}"/>@endforeach
                            </svg>
                        </span>
                    @endif

                    <h3 class="font-heading text-lg font-bold text-ink">{{ $category['title'] }}</h3>
                    <p class="mt-1.5 flex-1 text-sm leading-relaxed text-ink-muted">{{ $category['description'] }}</p>

                    <span class="btn-outline mt-4 self-start bg-surface px-4 py-2 text-xs">
                        Explore
                        <svg class="size-3.5 transition-transform duration-200 group-hover:translate-x-1"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 3. Popular picks ══════════════════════════════════════════════════ --}}
<section id="popular-picks" class="section scroll-mt-20 bg-surface-soft">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">Coming soon</p>
            <h2 class="section-title">Popular Picks for Cat Parents</h2>
            <p class="section-intro">
                We're finalizing the products worth recommending — checking
                real reviews and value before anything goes on this list.
            </p>
        </div>

        <div class="mt-10 -mx-3 flex snap-x snap-mandatory gap-4 overflow-x-auto px-3 pb-2 sm:mx-0 sm:px-0">
            @foreach ($picks as $pick)
                @php
                    $media = \App\Models\Media::where('name', $pick['media'])->latest()->first();
                    $category = $categories->firstWhere('slug', $pick['category']);
                @endphp
                <div class="card w-64 shrink-0 snap-center sm:w-72">
                    <div class="card-media aspect-square bg-surface-section">
                        @if ($media)
                            <img src="{{ $media->url }}" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-line-strong">
                                <x-paw-print class="size-10"/>
                            </div>
                        @endif
                        <span class="absolute top-3 left-3 rounded-full bg-surface px-2.5 py-1 text-[11px] font-bold text-ink-muted shadow-sm">
                            Coming soon
                        </span>
                    </div>
                    <div class="card-body">
                        <p class="text-xs font-bold tracking-wide text-primary uppercase">{{ $category['title'] ?? '' }}</p>
                        <h3 class="mt-1 font-heading text-base font-bold text-ink">{{ $pick['name'] }}</h3>
                        <p class="card-text flex-1">Being reviewed for PurrQuery's shop.</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 4. Why shop with us ═══════════════════════════════════════════════ --}}
<section class="section bg-surface">
    <div class="container-page grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
        <div>
            <p class="eyebrow">Why PurrQuery</p>
            <h2 class="section-title">Why Shop with PurrQuery?</h2>

            <div class="mt-8 grid gap-5 sm:grid-cols-2">
                @foreach ([
                    ['Handpicked Products', 'Carefully selected for cat parents.', 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
                    ['Trusted & Safe', 'Products from recognizable brands and reliable retailers.', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z'],
                    ['For Every Cat', 'Useful options for kittens, adults, and senior cats.', 'M12 21.5a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19Z|M12 10.5v6M12 7.5h.01'],
                    ['Helpful Guidance', 'Our guides and tools help you choose with confidence.', 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z'],
                ] as [$title, $text, $iconPath])
                    <div class="rounded-2xl border border-line bg-surface-soft p-5">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-primary-light text-primary">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                @foreach (explode('|', $iconPath) as $d)<path d="{{ $d }}"/>@endforeach
                            </svg>
                        </span>
                        <h3 class="mt-3 font-heading text-base font-bold text-ink">{{ $title }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-ink-muted">{{ $text }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative overflow-hidden rounded-[4.5rem_2rem_4.5rem_2rem] sm:rounded-[6rem_2.5rem_6rem_2.5rem] border-4 border-accent/15 bg-surface shadow-lg">
            <x-img name="purrquery-cat-waving-paw" alt="Cat looking curiously at the camera"
                   sizes="(max-width: 1023px) 92vw, 600px"/>
        </div>
    </div>
</section>

{{-- ══ 5. CTA banner ═════════════════════════════════════════════════════ --}}
<section class="pb-16 sm:pb-20">
    <div class="container-page">
        <div class="relative overflow-hidden rounded-3xl bg-primary-dark px-6 py-10 text-center sm:px-12 sm:py-14">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden opacity-10">
                <x-paw-print class="absolute top-6 left-10 size-16 -rotate-12"/>
                <x-paw-print class="absolute right-10 bottom-6 size-20 rotate-12"/>
            </div>

            <h2 class="relative font-heading text-2xl font-extrabold text-ink-inverse sm:text-3xl">
                Not Sure What Your Cat Needs?
            </h2>
            <p class="relative mx-auto mt-3 max-w-xl text-base leading-relaxed text-ink-inverse/85">
                Use our free cat-care tools and guides to make a more informed choice.
            </p>
            <a href="{{ route('tools.index') }}" class="btn-primary relative mt-6">
                Explore Tools &amp; Guides
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
            </a>
        </div>
    </div>
</section>

</x-layouts.app>

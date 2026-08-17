@php
    // The span is written out in full: Tailwind only generates classes it can
    // find as literal strings, so "lg:col-span-{$n}" would produce nothing.
    $columns = [
        [
            'heading' => 'Free Tools',
            'span' => 'lg:col-span-3',
            'items' => collect(config('catalog.tools'))
                ->map(fn ($t) => [$t['title'], '/#tools'])->all(),
        ],
        [
            // Short labels here, not the full questions: a footer column is
            // for scanning, and the questions are already on the cards.
            'heading' => 'Food Guides',
            'span' => 'lg:col-span-3',
            'items' => collect(config('catalog.foods'))->take(6)
                ->map(fn ($f) => [$f['title'], '/#food-guides'])->all(),
        ],
        [
            'heading' => 'Site',
            'span' => 'lg:col-span-2',
            'items' => [
                ['About Us', route('about')],
                ['How it works', '/#how-it-works'],
                ['Blog', '/#blog'],
                ['All food guides', '/#food-guides'],
                ['Contact Us', route('contact')],
                ['Terms & Conditions', route('terms')],
                ['Privacy Policy', route('privacy')],
            ],
        ],
    ];
@endphp

{{-- Deep purple rather than another pale band: it closes the page instead of
     letting it fade out, and white on this reads at 10.3:1. --}}
<footer class="relative overflow-hidden bg-primary-dark text-ink-inverse">

    {{-- The same curve as the wave under the hero, rotated a half turn and
         painted in the colour of the section above, so it reads as that band
         dipping into this one. It sits inside the footer on purpose: the
         overflow-hidden here would clip anything placed above it. --}}
    <svg class="absolute inset-x-0 top-0 h-10 w-full text-surface-soft sm:h-14"
         viewBox="0 0 1440 80" preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path transform="rotate(180 720 40)"
              d="M0 80c180-30 360-30 540-8s360 36 540 13 300-36 360-40V80Z"/>
    </svg>

    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <div class="absolute -top-24 -left-20 size-72 rounded-full bg-primary opacity-30 blur-3xl"></div>
        <div class="absolute -right-16 bottom-0 size-64 rounded-full bg-accent-vivid opacity-20 blur-3xl"></div>
        <x-paw-print class="paw absolute top-[30%] right-[3%] hidden size-20 text-ink-inverse opacity-[0.07] sm:block" style="animation-duration: 26s"/>
        <x-paw-print class="paw absolute bottom-[8%] left-[2%] hidden size-12 text-ink-inverse opacity-[0.07] sm:block" style="animation-delay: -9s; animation-duration: 22s"/>
    </div>

    <div class="container-page relative py-14 sm:py-16">
        <div class="grid gap-x-8 gap-y-10 min-[480px]:grid-cols-2 lg:grid-cols-12 lg:gap-8">

            {{-- Brand --}}
            <div class="min-[480px]:col-span-2 lg:col-span-4 lg:pr-8">
                <div class="flex items-center gap-3">
                    {{-- The badge is dark purple on transparent, so it needs a
                         light chip behind it to stay visible on this ground. --}}
                    <span class="flex size-16 shrink-0 items-center justify-center rounded-2xl bg-surface p-2 shadow-md">
                        <x-img name="purrquerylogo" alt="{{ config('app.name') }}" sizes="56px" fit="contain"/>
                    </span>
                    <span class="font-heading text-2xl font-extrabold tracking-tight">
                        {{ config('app.name') }}
                    </span>
                </div>

                <p class="mt-5 max-w-sm text-sm leading-relaxed text-ink-inverse/75">
                    {{ config('brand.tagline') }}. Free to use, no account required,
                    and nothing about your cat leaves your device.
                </p>

                <a href="mailto:{{ config('brand.email') }}"
                   class="mt-6 inline-flex items-center gap-2 rounded-full bg-surface/10 px-4 py-2.5 text-sm font-semibold ring-1 ring-surface/20 transition hover:bg-surface/20">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Z"/>
                        <path d="m3.5 8 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5"/>
                    </svg>
                    {{ config('brand.email') }}
                </a>
            </div>

            {{-- Link columns --}}
            @foreach ($columns as $column)
                <nav class="{{ $column['span'] }}" aria-label="{{ $column['heading'] }}">
                    <h2 class="font-heading text-sm font-bold tracking-wider text-ink-inverse uppercase">{{ $column['heading'] }}</h2>
                    <ul class="mt-4 space-y-3">
                        @foreach ($column['items'] as [$label, $href])
                            <li>
                                <a href="{{ $href }}"
                                   class="text-sm text-ink-inverse/75 transition hover:text-ink-inverse">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endforeach
        </div>

        {{-- Bottom bar --}}
        <div class="mt-12 flex flex-col gap-4 border-t border-surface/15 pt-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-ink-inverse/70">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            <p class="text-sm text-ink-inverse/70">
                General information, not veterinary advice.
            </p>
        </div>
    </div>
</footer>

<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-24 -right-20 size-96 rounded-full bg-primary-vivid opacity-[0.08] blur-3xl"></div>
        <div class="absolute -left-16 bottom-0 size-80 rounded-full bg-accent-vivid opacity-[0.1] blur-3xl"></div>
        <x-paw-print class="paw absolute top-[14%] left-[6%] hidden size-9 text-primary lg:block [animation-duration:22s]"/>
        <x-paw-print class="paw absolute top-[64%] left-[30%] hidden size-6 text-accent-vivid lg:block [animation-delay:-8s] [animation-duration:24s]"/>
        <x-paw-print class="paw absolute bottom-[14%] right-[6%] hidden size-7 text-primary lg:block [animation-delay:-4s] [animation-duration:21s]"/>
    </div>

    <div class="container-page relative py-8 lg:py-6">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink">Tools</li>
            </ol>
        </nav>

        <div class="mt-4 grid items-center gap-8 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)] lg:gap-6">
            <div class="relative z-10">
                <p class="inline-flex items-center gap-2 rounded-full bg-surface px-3.5 py-1.5 text-xs font-bold tracking-[0.14em] text-primary uppercase shadow-sm">
                    <x-paw-print class="size-3.5"/>
                    Free cat care tools
                </p>

                <h1 class="mt-5 font-heading text-4xl leading-[1.08] font-extrabold tracking-tight text-ink sm:text-5xl">
                    Smart tools for
                    <span class="text-primary">every question</span>
                </h1>

                <p class="mt-4 max-w-md text-base leading-relaxed text-ink-muted sm:text-lg">
                    {{ ucfirst(\Illuminate\Support\Number::spell($tools->count())) }} calculators and checkers built for
                    the questions cat owners actually ask. Nothing to install, no account, no limits.
                </p>

                <ul class="mt-7 flex flex-wrap gap-x-6 gap-y-3">
                    @foreach ([
                        ['100% free', 'M12.6 2.9 20.8 11a1.5 1.5 0 0 1 0 2.1l-7.7 7.7a1.5 1.5 0 0 1-2.1 0L2.9 12.6a1.5 1.5 0 0 1-.4-1.1V4.3a1.7 1.7 0 0 1 1.7-1.7h7.2c.4 0 .8.1 1.1.3Z|M7.4 7.4h.01'],
                        ['No sign-up', 'M4.5 20.5a7.5 7.5 0 0 1 12-6|M12 11.5a4.2 4.2 0 1 0 0-8.5 4.2 4.2 0 0 0 0 8.5Z|m15.5 18.5 2 2 4-4'],
                        ['Instant results', 'M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z'],
                    ] as [$label, $paths])
                        <li class="flex items-center gap-2.5">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-surface text-primary shadow-sm">
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
                <div class="relative overflow-hidden rounded-[4rem_1.75rem_4rem_1.75rem] border-4 border-primary/15 bg-surface shadow-lg">
                    <div class="aspect-[3/2]">
                        <x-img name="purrquery-care-smarter-cat-illustration"
                               alt="Illustration of a cat beside smart cat-care tools"
                               sizes="(max-width: 1023px) 92vw, 640px" :priority="true"/>
                    </div>
                </div>

                <div aria-hidden="true"
                     class="absolute -bottom-5 left-4 hidden items-center gap-3 rounded-2xl border border-line bg-surface px-4 py-3 shadow-lg sm:flex">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary">
                        <x-paw-print class="size-5"/>
                    </span>
                    <span>
                        <span class="block font-heading text-sm font-extrabold text-ink">Built to actually use</span>
                        <span class="mt-0.5 block text-xs text-ink-muted">No account, no paywall</span>
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
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($tools as $tool)
                @php $isLive = isset($tool['url']); @endphp
                <{{ $isLive ? 'a' : 'article' }}
                    @if ($isLive) href="{{ $tool['url'] }}" @endif
                    class="card group">
                    <div class="card-media aspect-[4/3]">
                        <x-img :name="$tool['image']" :alt="$tool['alt']"
                               sizes="(max-width: 639px) 92vw, (max-width: 1023px) 46vw, 30vw"/>

                        @unless ($isLive)
                            <span class="absolute top-3 right-3 rounded-full bg-surface/90 px-3 py-1 text-xs font-bold text-ink-muted shadow-sm">
                                Coming soon
                            </span>
                        @endunless
                    </div>
                    <div class="card-body">
                        <h2 class="card-title">{{ $tool['title'] }}</h2>
                        <p class="card-text flex-1">{{ $tool['blurb'] }}</p>
                        <span @class([
                            'mt-4 inline-flex items-center gap-1.5 text-sm font-semibold',
                            'text-primary' => $isLive,
                            'text-ink-muted' => ! $isLive,
                        ])>
                            {{ $isLive ? 'Open tool' : 'In development' }}
                            @if ($isLive)
                                <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                            @endif
                        </span>
                    </div>
                </{{ $isLive ? 'a' : 'article' }}>
            @endforeach
        </div>
    </div>
</section>

</x-layouts.app>

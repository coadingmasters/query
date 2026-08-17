@php
    $columns = [
        'Tools' => collect(config('catalog.tools'))->take(4)
            ->map(fn ($t) => [$t['title'], '#tools'])->all(),
        'Food Guides' => collect(config('catalog.foods'))->take(4)
            ->map(fn ($f) => ['Can cats eat '.strtolower($f['title']).'?', '#food-guides'])->all(),
        'Site' => [
            ['All tools', '#tools'],
            ['Blog', '#blog'],
            ['How it works', '#how-it-works'],
            ['Contact', 'mailto:'.config('brand.email')],
        ],
    ];
@endphp

<footer class="border-t border-line bg-surface-soft">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">

            <div class="lg:pr-8">
                {{-- Room here for the seal at full size, where the rim
                     actually reads. --}}
                <span class="block size-24 shrink-0">
                    <x-img name="purrquerylogo" alt="{{ config('app.name') }}" sizes="96px" fit="contain"/>
                </span>
                <p class="mt-4 text-sm leading-relaxed text-ink-muted">
                    {{ config('brand.tagline') }}. Free to use, no account required.
                </p>
                <a href="mailto:{{ config('brand.email') }}"
                   class="mt-4 inline-block text-sm font-medium text-primary underline decoration-line-strong underline-offset-4 transition-colors hover:text-primary-hover">
                    {{ config('brand.email') }}
                </a>
            </div>

            @foreach ($columns as $heading => $items)
                <div>
                    <h2 class="font-heading text-sm font-bold tracking-wide text-ink uppercase">{{ $heading }}</h2>
                    <ul class="mt-4 space-y-2.5">
                        @foreach ($items as [$label, $href])
                            <li>
                                <a href="{{ $href }}"
                                   class="text-sm text-ink-muted transition-colors hover:text-primary">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex flex-col gap-3 border-t border-line pt-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-ink-muted">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            <p class="text-sm text-ink-muted">
                Guidance here is general information, not veterinary advice.
            </p>
        </div>
    </div>
</footer>

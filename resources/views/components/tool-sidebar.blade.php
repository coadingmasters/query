@props([
    // Current tool's slug in config/catalog.php, so it never recommends itself.
    'slug',

    // [['id' => 'how-it-works', 'label' => 'How it works'], ...]
    // Pass an empty array to drop the contents panel entirely.
    'toc' => [],

    // Optional heading + body for a tool-specific panel above the tool list.
    'noteTitle' => null,
])

@php
    $recommended = collect(config('catalog.tools'))
        ->reject(fn (array $t): bool => $t['slug'] === $slug)
        ->take(3);
@endphp

<aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">

    @if (count($toc))
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
    @endif

    {{-- Tool-specific panel, if the page supplied one. --}}
    @if ($noteTitle && trim($slot) !== '')
        <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <h2 class="font-heading text-base font-extrabold text-ink">{{ $noteTitle }}</h2>
            <div class="mt-3 text-sm leading-relaxed text-ink-muted">{{ $slot }}</div>
        </div>
    @endif

    <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
        <h2 class="font-heading text-base font-extrabold text-ink">Recommended Tools</h2>
        <ul class="mt-4 space-y-2">
            @foreach ($recommended as $tool)
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

    <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
        <h2 class="font-heading text-base font-extrabold text-ink">Food Guides</h2>
        <ul class="mt-4 space-y-2">
            @foreach (collect(config('catalog.foods'))->take(4) as $food)
                <li>
                    <a href="{{ route('food-guides.show', $food['slug']) }}"
                       class="group flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-surface-soft">
                        <span class="size-12 shrink-0 overflow-hidden rounded-lg">
                            <x-img :name="$food['image']" :alt="$food['alt']" sizes="48px"
                                   class="transition-transform duration-300 group-hover:scale-110"/>
                        </span>
                        <span class="line-clamp-2 text-sm font-semibold text-ink transition-colors group-hover:text-primary">
                            {{ $food['question'] }}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
        <a href="{{ route('food-guides.index') }}" class="btn-outline mt-4 w-full justify-center rounded-full py-2 text-sm">
            View all food guides
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

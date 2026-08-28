<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    $verdictMeta = [
        'safe' => ['label' => 'Safe', 'tone' => 'bg-accent-light text-accent-dark', 'solid' => 'bg-accent'],
        'caution' => ['label' => 'In moderation', 'tone' => 'bg-warning-light text-warning', 'solid' => 'bg-warning'],
        'unsafe' => ['label' => 'Never', 'tone' => 'bg-danger-light text-danger', 'solid' => 'bg-danger'],
    ][$food['verdict']];
@endphp

{{-- Shared heading style so every section reads the same down the page. --}}
@php
    $h2 = 'flex items-center gap-2.5 font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl';
@endphp

<article>

    {{-- ══ HEADER ════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden bg-surface-soft">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-16 size-72 rounded-full bg-primary-vivid opacity-[0.07] blur-3xl"></div>
            <x-paw-print class="paw absolute top-[18%] right-[6%] hidden size-9 text-primary-vivid/20 lg:block [animation-duration:24s]"/>
        </div>

        <div class="relative mx-auto w-full max-w-[1600px] px-5 pt-6 pb-8 sm:px-8 lg:px-[50px]">
            <nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
                <ol class="flex flex-wrap items-center gap-1.5">
                    <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="{{ route('food-guides.index') }}" class="transition-colors hover:text-primary">Food Guides</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="font-medium text-ink">{{ $food['title'] }}</li>
                </ol>
            </nav>

            <div class="mt-5 max-w-3xl">
                <span @class(['inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold', $verdictMeta['tone']])>
                    <x-paw-print class="size-3.5"/>
                    {{ $verdictMeta['label'] }}
                </span>

                <h1 class="mt-4 font-heading text-3xl leading-[1.14] font-extrabold tracking-tight text-ink sm:text-4xl lg:text-5xl">
                    {{ $food['question'] }}
                </h1>

                <p class="mt-4 text-lg leading-relaxed font-semibold text-ink">{{ $food['answer'] }}</p>

                <x-byline :reviewed="true" class="mt-5"/>
            </div>
        </div>
    </div>

    {{-- ══ BODY ══════════════════════════════════════════════════════════ --}}
    <div class="bg-surface-section py-8 lg:py-12">
        <div class="mx-auto w-full max-w-[1600px] px-5 sm:px-8 lg:px-[50px]">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">

                {{-- ── Main ─────────────────────────────────────────────── --}}
                <div class="space-y-6">

                    <div class="reveal overflow-hidden rounded-2xl border border-line shadow-sm">
                        <div class="aspect-[16/9]">
                            <x-img :name="$food['image']" :alt="$food['alt']"
                                   sizes="(max-width: 1023px) 92vw, 900px" :priority="true"/>
                        </div>
                    </div>

                    {{-- Trust row --}}
                    <div class="reveal grid gap-3 rounded-2xl border border-line bg-surface p-4 shadow-sm sm:grid-cols-3">
                        @foreach ([
                            ['Vet reviewed', 'Sources you can check', 'primary', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z|m9.4 12.2 1.9 1.9 3.6-3.7'],
                            ['Portion guidance', 'How much and how often', 'accent', 'M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z'],
                            ['Plain answers', 'No hedging, no filler', 'info', 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
                        ] as [$label, $sub, $tone, $paths])
                            <div class="group flex items-center gap-3">
                                <span @class([
                                    'flex size-10 shrink-0 items-center justify-center rounded-xl transition-transform duration-300 group-hover:scale-110',
                                    'bg-primary-light text-primary' => $tone === 'primary',
                                    'bg-accent-light text-accent-dark' => $tone === 'accent',
                                    'bg-info-light text-info' => $tone === 'info',
                                ])>
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        @foreach (explode('|', $paths) as $d)<path d="{{ $d }}"/>@endforeach
                                    </svg>
                                </span>
                                <span>
                                    <span class="block font-heading text-sm font-bold text-ink">{{ $label }}</span>
                                    <span class="mt-0.5 block text-xs text-ink-muted">{{ $sub }}</span>
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Why --}}
                    @if (! empty($food['why']))
                        <section id="why" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                            <h2 class="{{ $h2 }}">
                                <x-paw-print class="size-5 shrink-0 text-primary-vivid"/>
                                Why
                            </h2>
                            <p class="mt-4 text-base leading-relaxed text-ink-muted">{{ $food['why'] }}</p>

                            @if (! empty($food['note']))
                                <div class="mt-5 flex items-start gap-3 rounded-xl border border-line bg-surface-soft px-4 py-3.5">
                                    <span @class(['mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full text-ink-inverse', $verdictMeta['solid']])>
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            @if ($food['verdict'] === 'unsafe')
                                                <path d="M12 9v4M12 17h.01"/><path d="m10.3 3.9-8 14A1.5 1.5 0 0 0 3.6 20h16.8a1.5 1.5 0 0 0 1.3-2.2l-8-14a1.5 1.5 0 0 0-2.6 0Z"/>
                                            @else
                                                <path d="M20 6 9 17l-5-5"/>
                                            @endif
                                        </svg>
                                    </span>
                                    <p class="text-sm font-semibold text-ink">{{ $food['note'] }}</p>
                                </div>
                            @endif
                        </section>
                    @endif

                    {{-- Per-item table --}}
                    @if (! empty($food['items']))
                        <section id="each-one" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                            <h2 class="{{ $h2 }}">
                                <x-paw-print class="size-5 shrink-0 text-primary-vivid"/>
                                {{ $food['title'] }}, one at a time
                            </h2>
                            <p class="mt-3 text-base leading-relaxed text-ink-muted">
                                The verdict above covers {{ strtolower($food['title']) }} as a whole. For the
                                specific one in front of you, this is the breakdown.
                            </p>

                            <div class="mt-5 overflow-hidden rounded-xl border border-line">
                                <table class="w-full text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                                            <th scope="col" class="px-4 py-3 font-semibold">Item</th>
                                            <th scope="col" class="px-4 py-3 font-semibold">Verdict</th>
                                            <th scope="col" class="hidden px-4 py-3 font-semibold sm:table-cell">Note</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-line">
                                        @foreach ($food['items'] as $item)
                                            @php $itemMeta = [
                                                'safe' => ['label' => 'Safe', 'tone' => 'bg-accent-light text-accent-dark'],
                                                'caution' => ['label' => 'Caution', 'tone' => 'bg-warning-light text-warning'],
                                                'unsafe' => ['label' => 'Never', 'tone' => 'bg-danger-light text-danger'],
                                            ][$item['verdict']] @endphp
                                            <tr class="align-top transition-colors hover:bg-surface-soft">
                                                <td class="px-4 py-3 font-semibold text-ink">{{ $item['name'] }}</td>
                                                <td class="px-4 py-3">
                                                    <span @class(['rounded-full px-2.5 py-1 text-xs font-bold whitespace-nowrap', $itemMeta['tone']])>
                                                        {{ $itemMeta['label'] }}
                                                    </span>
                                                    <p class="mt-1.5 text-ink-muted sm:hidden">{{ $item['note'] }}</p>
                                                </td>
                                                <td class="hidden px-4 py-3 text-ink-muted sm:table-cell">{{ $item['note'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs text-ink-muted">
                                @foreach ([['Safe in small amounts', 'bg-accent'], ['Caution, prep matters', 'bg-warning'], ['Never feed', 'bg-danger']] as [$legend, $dot])
                                    <span class="inline-flex items-center gap-1.5">
                                        <span aria-hidden="true" class="size-2 rounded-full {{ $dot }}"></span>
                                        {{ $legend }}
                                    </span>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- How much --}}
                    @if (! empty($food['guidance']))
                        <section id="how-much" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                            <h2 class="{{ $h2 }}">
                                <x-paw-print class="size-5 shrink-0 text-primary-vivid"/>
                                {{ $food['verdict'] === 'unsafe' ? 'What to do if your cat ate this' : 'How much is actually safe' }}
                            </h2>
                            <p class="mt-4 text-base leading-relaxed text-ink-muted">{{ $food['guidance'] }}</p>
                        </section>
                    @endif

                    {{-- Introducing --}}
                    @if (! empty($food['introduce']))
                        <section id="introduce" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                            <h2 class="{{ $h2 }}">
                                <x-paw-print class="size-5 shrink-0 text-primary-vivid"/>
                                Introducing a new {{ Str::of($food['title'])->lower()->rtrim('s') }} safely
                            </h2>
                            <p class="mt-4 text-base leading-relaxed text-ink-muted">{{ $food['introduce'] }}</p>
                        </section>
                    @endif

                    {{-- Avoid --}}
                    @if (! empty($food['avoid']))
                        <section id="avoid" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                            <h2 class="{{ $h2 }}">
                                <svg class="size-5 shrink-0 text-danger" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9"/><path d="m5.6 5.6 12.8 12.8"/>
                                </svg>
                                {{ $food['title'] }} to avoid entirely
                            </h2>
                            <ul class="mt-4 space-y-2.5 rounded-xl border border-danger/20 bg-danger-light p-5">
                                @foreach ($food['avoid'] as $item)
                                    <li class="flex items-start gap-2.5 text-sm leading-relaxed text-ink">
                                        <span aria-hidden="true" class="mt-2 size-1.5 shrink-0 rounded-full bg-danger"></span>
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    {{-- Deep dives --}}
                    @if (! empty($food['deep_dives']))
                        <div class="reveal flex flex-wrap gap-2.5">
                            @foreach ($food['deep_dives'] as $dive)
                                <a href="{{ route('blog.show', $dive['slug']) }}"
                                   class="group inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2.5 text-sm font-semibold text-ink shadow-sm transition hover:-translate-y-0.5 hover:border-primary/40 hover:text-primary hover:shadow-md">
                                    Full guide: {{ $dive['label'] }}
                                    <svg class="size-3.5 text-ink-muted transition-transform group-hover:translate-x-0.5 group-hover:text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m9 6 6 6-6 6"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Signs --}}
                    @if (! empty($food['watch_for']))
                        <section id="signs" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                            <h2 class="{{ $h2 }}">
                                <x-paw-print class="size-5 shrink-0 text-primary-vivid"/>
                                Signs to watch for
                            </h2>
                            <ul class="mt-4 space-y-2.5">
                                @foreach ($food['watch_for'] as $sign)
                                    <li class="flex items-start gap-2.5 text-base leading-relaxed text-ink-muted">
                                        <span aria-hidden="true" class="mt-1 flex size-4 shrink-0 items-center justify-center rounded-full bg-warning-light text-warning">
                                            <svg class="size-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" aria-hidden="true">
                                                <path d="M12 7v6M12 17h.01"/>
                                            </svg>
                                        </span>
                                        {{ $sign }}
                                    </li>
                                @endforeach
                            </ul>
                            <p class="mt-4 text-sm leading-relaxed text-ink-muted">
                                Any of these, or anything that seems off beyond what is listed here, is worth a call to
                                your vet. Our guide to
                                <a href="{{ route('blog.show', 'signs-your-cat-is-sick') }}" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">the early signs a cat is sick</a>
                                covers the wider list of what to watch for at any time, not just after eating something new.
                            </p>
                        </section>
                    @endif

                    {{-- FAQ --}}
                    @if (count($faq) > 1)
                        <section id="faq" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                            <h2 class="{{ $h2 }}">
                                <x-paw-print class="size-5 shrink-0 text-primary-vivid"/>
                                Frequently asked questions
                            </h2>
                            <div class="mt-5 space-y-2.5">
                                @foreach ($faq as $item)
                                    <details class="group rounded-xl border border-line bg-surface-soft px-4 transition hover:border-line-strong">
                                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-3.5 text-sm font-bold text-ink marker:content-['']">
                                            {{ $item['q'] }}
                                            <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-primary-light text-primary transition-transform duration-200 group-open:rotate-45">
                                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                                                    <path d="M12 5v14M5 12h14"/>
                                                </svg>
                                            </span>
                                        </summary>
                                        <p class="pb-4 text-sm leading-relaxed text-ink-muted">{{ $item['a'] }}</p>
                                    </details>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- Sources + byline --}}
                    <section class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                        <h2 class="font-heading text-base font-extrabold text-ink">Where this comes from</h2>
                        <ul class="mt-3 space-y-2">
                            @foreach ($sources as $source)
                                <li class="text-sm leading-relaxed text-ink-muted">
                                    <a href="{{ $source['url'] }}" rel="noopener" target="_blank" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">{{ $source['name'] }}</a>
                                    <span class="block">{{ $source['note'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-5 border-t border-line pt-5">
                            <x-byline :reviewed="true"/>
                        </div>
                    </section>
                </div>

                {{-- ── Sidebar ──────────────────────────────────────────── --}}
                <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">

                    {{-- On this page --}}
                    @if ($toc->isNotEmpty())
                        <nav aria-label="On this page" class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
                            <h2 class="flex items-center gap-2 font-heading text-base font-extrabold text-ink">
                                <x-paw-print class="size-4 text-primary-vivid"/>
                                On This Page
                            </h2>
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

                    {{-- Quick safety guide --}}
                    <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <h2 class="font-heading text-base font-extrabold text-ink">Quick Safety Guide</h2>

                        @if ($safeList->isNotEmpty() || $avoidList->isNotEmpty())
                            @if ($safeList->isNotEmpty())
                                <div class="mt-4">
                                    <p class="flex items-center gap-2 text-sm font-bold text-ink">
                                        <span class="flex size-5 shrink-0 items-center justify-center rounded-full bg-accent text-ink-inverse">
                                            <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                        </span>
                                        Safe choices
                                    </p>
                                    <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $safeList->implode(', ') }}</p>
                                </div>
                            @endif

                            @if ($avoidList->isNotEmpty())
                                <div class="mt-4 border-t border-line pt-4">
                                    <p class="flex items-center gap-2 text-sm font-bold text-ink">
                                        <span class="flex size-5 shrink-0 items-center justify-center rounded-full bg-danger text-ink-inverse">
                                            <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                                        </span>
                                        Never feed
                                    </p>
                                    <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $avoidList->implode(', ') }}</p>
                                </div>
                            @endif
                        @else
                            <div class="mt-4 flex items-start gap-2.5">
                                <span @class(['mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full text-ink-inverse', $verdictMeta['solid']])>
                                    <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        @if ($food['verdict'] === 'unsafe')
                                            <path d="M6 6l12 12M18 6 6 18"/>
                                        @else
                                            <path d="M20 6 9 17l-5-5"/>
                                        @endif
                                    </svg>
                                </span>
                                <p class="text-sm leading-relaxed text-ink-muted">{{ $food['answer'] }}</p>
                            </div>
                        @endif

                        <p class="mt-4 flex items-start gap-2 rounded-xl bg-surface-soft px-3.5 py-3 text-xs leading-relaxed text-ink-muted">
                            <x-paw-print class="mt-0.5 size-3.5 shrink-0 text-primary-vivid"/>
                            Introduce anything new slowly, one item at a time, and stop if anything seems off.
                        </p>
                    </div>

                    {{-- Related guides --}}
                    @if ($related->isNotEmpty())
                        <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
                            <h2 class="flex items-center gap-2 font-heading text-base font-extrabold text-ink">
                                <x-paw-print class="size-4 text-accent-dark"/>
                                Related Guides
                            </h2>
                            <ul class="mt-4 space-y-2">
                                @foreach ($related as $other)
                                    <li>
                                        <a href="{{ route('food-guides.show', $other['slug']) }}"
                                           class="group flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-surface-soft">
                                            <span class="size-12 shrink-0 overflow-hidden rounded-lg">
                                                <x-img :name="$other['image']" :alt="$other['alt']" sizes="48px"
                                                       class="transition-transform duration-300 group-hover:scale-110"/>
                                            </span>
                                            <span class="line-clamp-2 text-sm font-semibold text-ink transition-colors group-hover:text-primary">
                                                {{ $other['question'] }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('food-guides.index') }}" class="btn-outline mt-4 w-full justify-center rounded-full py-2 text-sm">
                                View all food guides
                            </a>
                        </div>
                    @endif

                    {{-- Recommended tools --}}
                    <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <h2 class="flex items-center gap-2 font-heading text-base font-extrabold text-ink">
                            <x-paw-print class="size-4 text-primary-vivid"/>
                            Recommended Tools
                        </h2>
                        <ul class="mt-4 space-y-2">
                            @foreach ($recommendedTools as $tool)
                                <li>
                                    <a href="{{ $tool['url'] }}" class="group flex items-start gap-3 rounded-xl p-1.5 transition hover:bg-surface-soft">
                                        <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary-light text-primary transition-transform duration-300 group-hover:scale-110">
                                            <x-paw-print class="size-4"/>
                                        </span>
                                        <span>
                                            <span class="block text-sm font-semibold text-ink transition-colors group-hover:text-primary">{{ $tool['title'] }}</span>
                                            <span class="mt-0.5 line-clamp-2 block text-xs leading-relaxed text-ink-muted">{{ $tool['blurb'] }}</span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('tools.index') }}" class="btn-outline mt-4 w-full justify-center rounded-full py-2 text-sm">
                            Explore all tools
                        </a>
                    </div>

                    {{-- CTA --}}
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
                                <x-paw-print class="size-4"/>
                                Explore tools
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</article>

</x-layouts.app>

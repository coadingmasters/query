<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    $verdictMeta = [
        'safe' => ['label' => 'Safe', 'tone' => 'bg-accent-light text-accent-dark'],
        'caution' => ['label' => 'In moderation', 'tone' => 'bg-warning-light text-warning'],
        'unsafe' => ['label' => 'Never', 'tone' => 'bg-danger-light text-danger'],
    ][$food['verdict']];
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
                <span @class(['inline-flex items-center rounded-full px-3 py-1 text-xs font-bold', $verdictMeta['tone']])>
                    {{ $verdictMeta['label'] }}
                </span>

                <h1 class="mt-4 font-heading text-3xl leading-[1.14] font-extrabold tracking-tight text-ink sm:text-4xl lg:text-5xl">
                    {{ $food['question'] }}
                </h1>

                <p class="mt-4 text-lg leading-relaxed font-semibold text-ink">{{ $food['answer'] }}</p>

                <x-byline :reviewed="true" :published-at="$publishedAt" :updated-at="$updatedAt" class="mt-5"/>
            </div>
        </div>
    </div>

    {{-- ══ BODY ══════════════════════════════════════════════════════════ --}}
    <div class="bg-surface-section py-8 lg:py-12">
        <div class="mx-auto w-full max-w-[1600px] px-5 sm:px-8 lg:px-[50px]">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">

                {{-- ── Main ─────────────────────────────────────────────── --}}
                <div>
                    <div class="reveal overflow-hidden rounded-2xl border border-line shadow-sm">
                        <div class="aspect-[16/9]">
                            <x-img :name="$food['image']" :alt="$food['alt']"
                                   sizes="(max-width: 1023px) 92vw, 900px" :priority="true"/>
                        </div>
                    </div>

                    @php
                        $h2 = 'mt-10 flex items-center gap-2.5 font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl';
                        $linkClass = 'font-semibold text-primary underline decoration-line-strong underline-offset-4';
                    @endphp

                    <div class="reveal mt-8">

                        {{-- Why --}}
                        @if (! empty($food['why']))
                            <h2 id="why" class="{{ $h2 }} mt-0">Why</h2>
                            <p class="mt-4 text-base leading-relaxed text-ink-muted">{!! $food['why'] !!}</p>

                            @if (! empty($food['note']))
                                <p class="mt-4 text-base font-semibold text-ink">{{ $food['note'] }}</p>
                            @endif
                        @endif

                        {{-- Per-item table --}}
                        @if (! empty($food['items']))
                            <h2 id="each-one" class="{{ $h2 }}">{{ $food['title'] }}, one at a time</h2>
                            <p class="mt-4 text-base leading-relaxed text-ink-muted">
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
                                            @php $itemLabel = ['safe' => 'Safe', 'caution' => 'Caution', 'unsafe' => 'Never'][$item['verdict']] @endphp
                                            <tr class="align-top transition-colors hover:bg-surface-soft">
                                                <td class="px-4 py-3 font-semibold text-ink">{{ $item['name'] }}</td>
                                                <td class="px-4 py-3 font-semibold whitespace-nowrap text-ink">
                                                    {{ $itemLabel }}
                                                    <p class="mt-1.5 font-normal text-ink-muted sm:hidden">{{ $item['note'] }}</p>
                                                </td>
                                                <td class="hidden px-4 py-3 text-ink-muted sm:table-cell">{{ $item['note'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <p class="mt-3 text-xs text-ink-muted">Safe in small amounts, Caution: prep matters, Never feed.</p>
                        @endif

                        {{-- How much --}}
                        @if (! empty($food['guidance']))
                            <h2 id="how-much" class="{{ $h2 }}">
                                {{ $food['verdict'] === 'unsafe' ? 'What to do if your cat ate this' : 'How much is actually safe' }}
                            </h2>
                            <p class="mt-4 text-base leading-relaxed text-ink-muted">{!! $food['guidance'] !!}</p>
                        @endif

                        {{-- Introducing --}}
                        @if (! empty($food['introduce']))
                            <h2 id="introduce" class="{{ $h2 }}">
                                {{ $food['verdict'] === 'unsafe' ? 'If it already happened' : 'Introducing a new '.Str::of($food['title'])->lower()->rtrim('s').' safely' }}
                            </h2>
                            <p class="mt-4 text-base leading-relaxed text-ink-muted">{!! $food['introduce'] !!}</p>
                        @endif

                        {{-- Avoid --}}
                        @if (! empty($food['avoid']))
                            <h2 id="avoid" class="{{ $h2 }}">{{ $food['title'] }} to avoid entirely</h2>
                            <ul class="mt-4 list-disc space-y-2.5 pl-5 marker:text-line-strong">
                                @foreach ($food['avoid'] as $item)
                                    <li class="text-base leading-relaxed text-ink-muted">{!! $item !!}</li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- Deep dives --}}
                        @if (! empty($food['deep_dives']))
                            <p class="mt-4 text-base leading-relaxed text-ink-muted">
                                Full guides:
                                @foreach ($food['deep_dives'] as $dive)
                                    <a href="{{ route('blog.show', $dive['slug']) }}" class="{{ $linkClass }}">{{ $dive['label'] }}</a>{{ ! $loop->last ? ',' : '' }}
                                @endforeach
                            </p>
                        @endif

                        {{-- Signs --}}
                        @if (! empty($food['watch_for']))
                            <h2 id="signs" class="{{ $h2 }}">Signs to watch for</h2>
                            <ul class="mt-4 list-disc space-y-2.5 pl-5 marker:text-line-strong">
                                @foreach ($food['watch_for'] as $sign)
                                    <li class="text-base leading-relaxed text-ink-muted">{{ $sign }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-4 text-base leading-relaxed text-ink-muted">
                                Any of these, or anything that seems off beyond what is listed here, is worth a call to
                                your vet. Our guide to
                                <a href="{{ route('blog.show', 'signs-your-cat-is-sick') }}" class="{{ $linkClass }}">the early signs a cat is sick</a>
                                covers the wider list of what to watch for at any time, not just after eating something new.
                            </p>
                        @endif

                        {{-- FAQ --}}
                        @if (count($faq) > 1)
                            <h2 id="faq" class="{{ $h2 }}">Frequently asked questions</h2>
                            <div class="mt-4 space-y-2.5">
                                @foreach ($faq as $item)
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
                        @endif

                        {{-- Sources --}}
                        <h2 class="{{ $h2 }}">Where this comes from</h2>
                        <ul class="mt-4 space-y-2">
                            @foreach ($sources as $source)
                                <li class="text-sm leading-relaxed text-ink-muted">
                                    <a href="{{ $source['url'] }}" rel="noopener" target="_blank" class="{{ $linkClass }}">{{ $source['name'] }}</a>
                                    {{ $source['note'] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="reveal mt-8 border-t border-line pt-6">
                        <x-byline :reviewed="true" :published-at="$publishedAt" :updated-at="$updatedAt"/>
                    </div>
                </div>

                {{-- ── Sidebar ──────────────────────────────────────────── --}}
                <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">

                    {{-- On this page --}}
                    @if ($toc->isNotEmpty())
                        <nav aria-label="On this page" class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
                            <h2 class="font-heading text-base font-extrabold text-ink">
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
                                    <p class="text-sm font-bold text-ink">Safe choices</p>
                                    <p class="mt-1 text-sm leading-relaxed text-ink-muted">{{ $safeList->implode(', ') }}</p>
                                </div>
                            @endif

                            @if ($avoidList->isNotEmpty())
                                <div class="mt-4 border-t border-line pt-4">
                                    <p class="text-sm font-bold text-ink">Never feed</p>
                                    <p class="mt-1 text-sm leading-relaxed text-ink-muted">{{ $avoidList->implode(', ') }}</p>
                                </div>
                            @endif
                        @else
                            <p class="mt-4 text-sm leading-relaxed text-ink-muted">{{ $food['answer'] }}</p>
                        @endif

                        <p class="mt-4 border-t border-line pt-4 text-xs leading-relaxed text-ink-muted">
                            Introduce anything new slowly, one item at a time, and stop if anything seems off.
                        </p>
                    </div>

                    {{-- Related guides --}}
                    @if ($related->isNotEmpty())
                        <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
                            <h2 class="font-heading text-base font-extrabold text-ink">
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
                        <h2 class="font-heading text-base font-extrabold text-ink">
                                Recommended Tools
                        </h2>
                        <ul class="mt-4 space-y-2">
                            @foreach ($recommendedTools as $tool)
                                <li>
                                    <a href="{{ $tool['url'] }}" class="group flex items-start gap-3 rounded-xl p-1.5 transition hover:bg-surface-soft">
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

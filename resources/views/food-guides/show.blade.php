<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    $verdictMeta = [
        'safe' => ['label' => 'Safe', 'tone' => 'bg-accent-light text-accent-dark', 'solid' => 'bg-accent'],
        'caution' => ['label' => 'In moderation', 'tone' => 'bg-warning-light text-warning', 'solid' => 'bg-warning'],
        'unsafe' => ['label' => 'Never', 'tone' => 'bg-danger-light text-danger', 'solid' => 'bg-danger'],
    ][$food['verdict']];
@endphp

<article>
    <div class="container-page max-w-4xl pt-6">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('food-guides.index') }}" class="transition-colors hover:text-primary">Food Guides</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink">{{ $food['title'] }}</li>
            </ol>
        </nav>

        <header class="mt-5">
            <span @class(['rounded-full px-3 py-1 text-xs font-bold', $verdictMeta['tone']])>
                {{ $verdictMeta['label'] }}
            </span>

            <h1 class="mt-4 font-heading text-3xl leading-[1.14] font-extrabold tracking-tight text-ink sm:text-4xl">
                {{ $food['question'] }}
            </h1>
        </header>

        <div class="mt-6 overflow-hidden rounded-2xl">
            <div class="aspect-[3/2] sm:aspect-[21/9]">
                <x-img :name="$food['image']" :alt="$food['alt']" sizes="(max-width: 1023px) 92vw, 780px" :priority="true"/>
            </div>
        </div>

        <div class="mt-8 max-w-2xl">
            {{-- The answer, before anything else. It is what the reader came
                 for and what Google lifts for a snippet. --}}
            <div>
                <p class="flex items-center gap-2 text-xs font-bold tracking-wider text-primary uppercase">
                    <x-paw-print class="size-4"/>
                    Quick answer
                </p>
                <p class="mt-2.5 text-lg leading-relaxed font-semibold text-ink">{{ $food['answer'] }}</p>
            </div>

            @if (! empty($food['note']))
                <div @class(['mt-5 flex items-start gap-3 rounded-xl border px-4 py-3.5', 'border-line bg-surface-soft'])>
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

            @if (! empty($food['why']))
                <h2 class="mt-10 font-heading text-xl font-extrabold tracking-tight text-ink">Why</h2>
                <p class="mt-3 text-base leading-relaxed text-ink-muted">{{ $food['why'] }}</p>
            @endif

            {{-- A category page like fruits covers many individual items, so
                 the single verdict badge up top is not enough on its own;
                 this is the actual reference table someone scans for their
                 specific fruit. --}}
            @if (! empty($food['items']))
                <h2 class="mt-10 font-heading text-xl font-extrabold tracking-tight text-ink">
                    {{ $food['title'] }}, one at a time
                </h2>
                <p class="mt-3 text-base leading-relaxed text-ink-muted">
                    The verdict above covers {{ strtolower($food['title']) }} as a whole. For the
                    specific one in front of you, this is the breakdown.
                </p>
                <div class="mt-5 overflow-hidden rounded-2xl border border-line">
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
                                <tr class="align-top">
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
            @endif

            @if (! empty($food['guidance']))
                <h2 class="mt-8 font-heading text-xl font-extrabold tracking-tight text-ink">
                    {{ $food['verdict'] === 'unsafe' ? 'What to do if your cat ate this' : 'How much is actually safe' }}
                </h2>
                <p class="mt-3 text-base leading-relaxed text-ink-muted">{{ $food['guidance'] }}</p>
            @endif

            @if (! empty($food['introduce']))
                <h2 class="mt-8 font-heading text-xl font-extrabold tracking-tight text-ink">
                    Introducing a new {{ Str::of($food['title'])->lower()->rtrim('s') }} safely
                </h2>
                <p class="mt-3 text-base leading-relaxed text-ink-muted">{{ $food['introduce'] }}</p>
            @endif

            @if (! empty($food['avoid']))
                <h2 class="mt-8 font-heading text-xl font-extrabold tracking-tight text-ink">
                    {{ $food['title'] }} to avoid entirely
                </h2>
                <ul class="mt-3 space-y-2.5 rounded-xl border border-danger/20 bg-danger-light p-5">
                    @foreach ($food['avoid'] as $item)
                        <li class="flex items-start gap-2.5 text-sm leading-relaxed text-ink">
                            <span aria-hidden="true" class="mt-2 size-1.5 shrink-0 rounded-full bg-danger"></span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            @endif

            {{-- Points at the full single-food article for anything covered
                 here that has one, rather than this overview page trying to
                 rank for that specific question too. --}}
            @if (! empty($food['deep_dives']))
                <div class="mt-6 flex flex-wrap gap-2.5">
                    @foreach ($food['deep_dives'] as $dive)
                        <a href="{{ route('blog.show', $dive['slug']) }}"
                           class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2 text-sm font-semibold text-ink shadow-sm transition hover:border-line-strong hover:text-primary">
                            Full guide: {{ $dive['label'] }}
                            <svg class="size-3.5 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m9 6 6 6-6 6"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            @endif

            @if (! empty($food['watch_for']))
                <h2 class="mt-8 font-heading text-xl font-extrabold tracking-tight text-ink">Signs to watch for</h2>
                <ul class="mt-3 space-y-2.5">
                    @foreach ($food['watch_for'] as $sign)
                        <li class="flex items-start gap-2.5 text-base leading-relaxed text-ink-muted">
                            <span aria-hidden="true" class="mt-2.5 size-1.5 shrink-0 rounded-full bg-primary-vivid"></span>
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
            @endif

            {{-- A single question is already covered by the quick answer up
                 top, so this only shows once a food supplies its own set of
                 real questions worth a section of their own. --}}
            @if (count($faq) > 1)
                <h2 class="mt-10 font-heading text-xl font-extrabold tracking-tight text-ink">
                    {{ $food['title'] }}, answered
                </h2>
                <div class="mt-4 space-y-2.5">
                    @foreach ($faq as $item)
                        <details class="group rounded-xl border border-line bg-surface px-5">
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
            @endif
        </div>
    </div>

    {{-- ══ Sources ═══════════════════════════════════════════════════════ --}}
    <div class="container-page max-w-2xl mt-10">
        <div class="rounded-2xl border border-line bg-surface-soft p-6">
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
        </div>
    </div>

    {{-- ══ Related guides ════════════════════════════════════════════════ --}}
    @if ($related->isNotEmpty())
        <div class="section-tight bg-surface-soft mt-12">
            <div class="container-page">
                <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">
                    More food safety guides
                </h2>

                <div class="mt-6 grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
                    @foreach ($related as $other)
                        <a href="{{ route('food-guides.show', $other['slug']) }}" class="card">
                            <div class="card-media aspect-[3/2]">
                                <x-img :name="$other['image']" :alt="$other['alt']"
                                       sizes="(max-width: 639px) 46vw, (max-width: 1023px) 30vw, 23vw"/>
                            </div>
                            <div class="card-body">
                                <p class="text-xs font-bold tracking-wide text-primary uppercase">{{ $other['title'] }}</p>
                                <h3 class="mt-1.5 line-clamp-2 font-heading text-sm leading-snug font-bold text-ink">
                                    {{ $other['question'] }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('food-guides.index') }}" class="btn-outline rounded-full px-7">
                        View all food guides
                    </a>
                </div>
            </div>
        </div>
    @endif
</article>

</x-layouts.app>

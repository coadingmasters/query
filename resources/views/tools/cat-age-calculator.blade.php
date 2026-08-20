<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    // Written out in full: Tailwind only generates classes it can find as
    // literal strings, so "bg-{$tone}-light" would produce nothing.
    $tones = [
        'primary' => ['tile' => 'bg-primary-light text-primary', 'dot' => 'bg-primary'],
        'accent' => ['tile' => 'bg-accent-light text-accent-dark', 'dot' => 'bg-accent'],
        'warning' => ['tile' => 'bg-warning-light text-warning', 'dot' => 'bg-warning'],
        'info' => ['tile' => 'bg-info-light text-info', 'dot' => 'bg-info'],
    ];
@endphp

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <x-paw-print class="paw absolute top-[18%] left-[4%] hidden size-10 text-primary-vivid/30 lg:block [animation-duration:24s]"/>
        <x-paw-print class="paw absolute bottom-[22%] left-[9%] hidden size-7 text-primary-vivid/25 lg:block [animation-delay:-7s] [animation-duration:21s]"/>
    </div>

    <div class="container-page relative grid items-center gap-8 pt-8 pb-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.05fr)] lg:pt-6">

        <div class="relative z-10">
            <nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
                <ol class="flex flex-wrap items-center gap-1.5">
                    <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/#tools" class="transition-colors hover:text-primary">Tools</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="font-medium text-ink">Cat Age Calculator</li>
                </ol>
            </nav>

            <h1 class="mt-4 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
                Cat Age Calculator
            </h1>

            <p class="mt-4 max-w-lg text-base leading-relaxed text-ink-muted sm:text-lg">
                How old is your cat in human years? Enter a birthday or an age and
                get the answer, the life stage they are in, and what that stage
                changes about their care.
            </p>

            <ul class="mt-6 flex flex-wrap gap-x-6 gap-y-3">
                @foreach ([
                    ['AAFP life stages', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z'],
                    ['Free, no sign-up', 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
                    ['Runs in your browser', 'M5.5 5h13A1.5 1.5 0 0 1 20 6.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 15.5v-9A1.5 1.5 0 0 1 5.5 5Z'],
                ] as [$label, $d])
                    <li class="flex items-center gap-2 text-sm font-medium text-ink-muted">
                        <svg class="size-4 shrink-0 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $d }}"/>
                        </svg>
                        {{ $label }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mx-auto w-full max-w-lg lg:max-w-none">
            <div class="overflow-hidden rounded-2xl bg-surface shadow-lg ring-1 ring-line">
                <x-img name="cat-age-life-stages"
                       alt="Five cats at three months, one year, three years, seven years and twelve years old, side by side"
                       sizes="(min-width: 1024px) 46vw, 90vw" :priority="true"/>
            </div>
        </div>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-10 w-full text-surface sm:h-16" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ 2. CALCULATOR ═════════════════════════════════════════════════════ --}}
<section class="bg-surface pt-4 pb-10 lg:pb-14">
    <div class="container-page">
        <div class="grid gap-6 lg:grid-cols-12 lg:gap-7">

            {{-- ── Inputs ──────────────────────────────────────────────── --}}
            <form data-age-form class="rounded-2xl border border-line bg-surface p-6 shadow-md sm:p-7 lg:col-span-7" novalidate>
                <div class="flex items-center gap-4">
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 6v6l4 2"/><path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20Z"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink">Your cat's details</h2>
                        <p class="mt-1 text-sm text-ink-muted">Nothing is sent anywhere. This runs entirely in your browser.</p>
                    </div>
                </div>

                {{-- Mode. Radios rather than buttons: they are what this is,
                     they work with a keyboard, and screen readers announce the
                     group. The labels are styled to read as a segmented control. --}}
                <fieldset class="mt-6">
                    <legend class="text-sm font-semibold text-ink">How do you want to enter it?</legend>
                    <div class="mt-2.5 grid gap-2 sm:grid-cols-3">
                        @foreach ([
                            ['birthday', 'Birthday', 'You know the date'],
                            ['age', 'Age', 'Years and months'],
                            ['unknown', 'Not sure', 'Estimate it'],
                        ] as $i => [$value, $label, $hint])
                            <label class="group relative flex cursor-pointer flex-col rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 transition has-checked:border-primary has-checked:bg-primary-light has-checked:ring-1 has-checked:ring-primary">
                                <input type="radio" name="mode" value="{{ $value }}" class="sr-only" @checked($i === 0)>
                                <span class="font-heading text-sm font-bold text-ink">{{ $label }}</span>
                                <span class="mt-0.5 text-xs text-ink-muted">{{ $hint }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                {{-- Birthday --}}
                <div data-panel="birthday" class="mt-5">
                    <label for="birthday" class="block text-sm font-semibold text-ink">Date of birth</label>
                    <input id="birthday" name="birthday" type="date"
                           class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    <p class="mt-1.5 text-xs text-ink-muted">Adoption day works too if that is all you have.</p>
                </div>

                {{-- Years and months --}}
                <div data-panel="age" hidden class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="years" class="block text-sm font-semibold text-ink">Years</label>
                        <input id="years" name="years" type="number" min="0" max="30" step="1" inputmode="numeric"
                               placeholder="0"
                               class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>
                    <div>
                        <label for="months" class="block text-sm font-semibold text-ink">Months</label>
                        <input id="months" name="months" type="number" min="0" max="11" step="1" inputmode="numeric"
                               placeholder="0"
                               class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>
                </div>

                {{-- Estimate. Every competitor explains how to do this in prose
                     and then leaves the reader to do it themselves. --}}
                <div data-panel="unknown" hidden class="mt-5">
                    <label for="estimate" class="block text-sm font-semibold text-ink">What best describes your cat?</label>
                    <select id="estimate" name="estimate"
                            class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        <option value="0.25">Tiny kitten, eyes open, still unsteady</option>
                        <option value="0.5">Kitten, baby teeth, very playful</option>
                        <option value="1">Adult teeth in, slim and fully active</option>
                        <option value="3" selected>Fully grown, clean white teeth</option>
                        <option value="6">Some tartar on the back teeth</option>
                        <option value="9">Noticeable tartar, coat coarser than it was</option>
                        <option value="13">Worn teeth or gum disease, cloudier eyes, sleeps more</option>
                        <option value="16">Thin, bony spine, very little jumping</option>
                    </select>
                    <p class="mt-1.5 text-xs text-ink-muted">
                        A rough range, based on what a vet looks at. It is an estimate, not a birthday.
                    </p>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="breed" class="block text-sm font-semibold text-ink">Breed</label>
                        <select id="breed" name="breed"
                                class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            @foreach ($breeds as $breed)
                                <option value="{{ $breed['id'] }}">{{ $breed['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="lifestyle" class="block text-sm font-semibold text-ink">Lifestyle</label>
                        <select id="lifestyle" name="lifestyle"
                                class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            @foreach ($lifestyles as $id => $lifestyle)
                                <option value="{{ $id }}">{{ $lifestyle['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <p data-age-error hidden role="alert"
                   class="mt-4 rounded-xl border border-danger/30 bg-danger-light px-4 py-3 text-sm font-medium text-danger"></p>

                <button type="submit" data-age-submit class="btn-primary mt-6 w-full rounded-full sm:w-auto sm:px-8">
                    <svg data-age-spinner hidden class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" opacity="0.25"/>
                        <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    Calculate age
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                </button>
            </form>

            {{-- ── Result ──────────────────────────────────────────────── --}}
            <div data-age-result aria-live="polite" class="lg:col-span-5">
                {{-- Placeholder, so the column is not an empty hole before the
                     first calculation. Competitors leave this blank. --}}
                <div data-age-placeholder class="flex h-full flex-col justify-center rounded-2xl border border-dashed border-line-strong bg-surface-section p-8 text-center">
                    <span class="mx-auto flex size-14 items-center justify-center rounded-full bg-primary-light">
                        <x-paw-print class="size-7 text-primary"/>
                    </span>
                    <p class="mt-4 font-heading text-lg font-bold text-ink">Your cat's answer appears here</p>
                    <p class="mt-2 text-sm leading-relaxed text-ink-muted">
                        Human years, the life stage they are in, and what to do about it.
                    </p>
                </div>

                <div data-age-panel hidden class="overflow-hidden rounded-2xl border border-line bg-surface shadow-md">
                    <div class="bg-primary-vivid px-6 py-6 text-center text-ink">
                        <p class="text-xs font-bold tracking-[0.14em] uppercase opacity-80">In human years</p>
                        <p class="mt-1 font-heading text-6xl font-extrabold tracking-tight tabular-nums">
                            <span data-age-human>0</span>
                        </p>
                        <p data-age-actual class="mt-1 text-sm font-medium opacity-90"></p>
                    </div>

                    <div class="space-y-5 p-6">
                        <div>
                            <div class="flex items-center gap-3">
                                <span data-age-stage-tile class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary">
                                    <x-paw-print class="size-5"/>
                                </span>
                                <div>
                                    <p class="font-heading text-lg font-extrabold text-ink" data-age-stage-name></p>
                                    <p class="text-sm text-ink-muted" data-age-stage-range></p>
                                </div>
                            </div>
                            <p class="mt-3 text-sm leading-relaxed text-ink-muted" data-age-stage-summary></p>
                        </div>

                        <div class="rounded-xl bg-surface-section p-4">
                            <p class="text-xs font-bold tracking-wider text-ink uppercase">Vet visits at this stage</p>
                            <p class="mt-1.5 text-sm leading-relaxed text-ink-muted" data-age-vet></p>
                        </div>

                        <div>
                            <p class="text-xs font-bold tracking-wider text-ink uppercase">What matters now</p>
                            <ul class="mt-2.5 space-y-2" data-age-care></ul>
                        </div>

                        <div class="rounded-xl border border-line bg-surface-soft p-4">
                            <p class="text-xs font-bold tracking-wider text-ink uppercase">Typical life expectancy</p>
                            <p class="mt-1.5 font-heading text-lg font-bold text-ink" data-age-life></p>
                            <p class="mt-1 text-sm leading-relaxed text-ink-muted" data-age-life-note></p>
                            <p class="mt-2 text-sm leading-relaxed text-ink-muted" data-age-breed-note hidden></p>
                        </div>

                        <button type="button" data-age-copy class="btn-outline w-full rounded-full">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 9V5.5A1.5 1.5 0 0 1 10.5 4h8A1.5 1.5 0 0 1 20 5.5v8a1.5 1.5 0 0 1-1.5 1.5H15"/>
                                <path d="M5.5 9h8A1.5 1.5 0 0 1 15 10.5v8a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 18.5v-8A1.5 1.5 0 0 1 5.5 9Z"/>
                            </svg>
                            <span data-age-copy-label>Copy result</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ 3. LIFE STAGES ════════════════════════════════════════════════════ --}}
<section id="life-stages" class="scroll-mt-24 bg-surface-section py-10 lg:py-14">
    <div class="container-page">
        <div class="reveal text-center">
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <x-paw-print class="size-4"/>
                The four life stages
            </p>
            <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                A number is not the useful part
            </h2>
            <p class="section-intro">
                What matters is the stage it lands in. These are the four the
                2021 AAHA and AAFP guidelines define, and what each one changes.
            </p>
        </div>

        <ul class="mt-9 grid gap-4 lg:grid-cols-2">
            @foreach ($stages as $stage)
                <li data-stage-card="{{ $stage['id'] }}"
                    class="reveal rounded-2xl border border-line bg-surface p-6 shadow-sm transition"
                    style="--reveal-delay: {{ $loop->index * 70 }}ms">
                    <div class="flex items-center gap-3">
                        <span @class(['flex size-11 shrink-0 items-center justify-center rounded-xl', $tones[$stage['tone']]['tile']])>
                            <x-paw-print class="size-5"/>
                        </span>
                        <div>
                            <h3 class="font-heading text-lg font-extrabold text-ink">{{ $stage['name'] }}</h3>
                            <p class="text-sm text-ink-muted">
                                @if ($stage['until'])
                                    {{ $stage['from'] }} to {{ $stage['until'] - 1 }} years
                                @else
                                    {{ $stage['from'] }} years and older
                                @endif
                            </p>
                        </div>
                    </div>

                    <p class="mt-3 text-sm leading-relaxed text-ink-muted">{{ $stage['summary'] }}</p>

                    <p class="mt-4 rounded-lg bg-surface-section px-3.5 py-2.5 text-sm text-ink-muted">
                        <span class="font-semibold text-ink">Vet visits:</span> {{ $stage['vet'] }}
                    </p>

                    <ul class="mt-4 space-y-2">
                        @foreach ($stage['care'] as $tip)
                            <li class="flex items-start gap-2.5 text-sm leading-relaxed text-ink-muted">
                                <span @class(['mt-1.5 size-1.5 shrink-0 rounded-full', $tones[$stage['tone']]['dot']])></span>
                                {{ $tip }}
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ══ 4. CHART ══════════════════════════════════════════════════════════ --}}
<section id="chart" class="scroll-mt-24 bg-surface py-10 lg:py-14">
    <div class="container-page max-w-4xl">
        <div class="reveal text-center">
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <x-paw-print class="size-4"/>
                Conversion chart
            </p>
            <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                Cat years to human years
            </h2>
        </div>

        {{-- Real tables, not an image of one. A picture of a chart cannot be
             read by a search engine, translated, or resized by someone who
             needs larger text. --}}
        <div class="reveal mt-8 grid gap-6 sm:grid-cols-2">
            <div class="overflow-hidden rounded-2xl border border-line">
                <table class="w-full text-sm">
                    <caption class="bg-surface-section px-4 py-3 text-left font-heading text-sm font-bold text-ink">
                        The first year, month by month
                    </caption>
                    <thead>
                        <tr class="border-y border-line bg-surface text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-4 py-2.5 text-left font-semibold">Kitten age</th>
                            <th scope="col" class="px-4 py-2.5 text-right font-semibold">Human age</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ([1, 2, 3, 4, 6, 9, 12] as $m)
                            <tr>
                                <td class="px-4 py-2.5 text-ink">{{ $m }} {{ Str::plural('month', $m) }}</td>
                                <td class="px-4 py-2.5 text-right font-semibold tabular-nums text-ink">
                                    {{ config('cat-age.kitten_months')[$m] }} years
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="overflow-hidden rounded-2xl border border-line">
                <table class="w-full text-sm">
                    <caption class="bg-surface-section px-4 py-3 text-left font-heading text-sm font-bold text-ink">
                        After the first year
                    </caption>
                    <thead>
                        <tr class="border-y border-line bg-surface text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-4 py-2.5 text-left font-semibold">Cat age</th>
                            <th scope="col" class="px-4 py-2.5 text-right font-semibold">Human age</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ([1, 2, 3, 5, 7, 10, 12, 15, 18, 20] as $y)
                            @php
                                $human = $y === 1
                                    ? config('cat-age.first_year')
                                    : config('cat-age.second_year') + ($y - 2) * config('cat-age.per_year_after');
                            @endphp
                            <tr>
                                <td class="px-4 py-2.5 text-ink">{{ $y }} {{ Str::plural('year', $y) }}</td>
                                <td class="px-4 py-2.5 text-right font-semibold tabular-nums text-ink">{{ $human }} years</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- ══ 5. FAQ ════════════════════════════════════════════════════════════ --}}
<section id="faq" class="scroll-mt-24 bg-surface-section py-10 lg:py-14">
    <div class="container-page max-w-3xl">
        <div class="reveal text-center">
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <x-paw-print class="size-4"/>
                Common questions
            </p>
            <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                Cat age, answered
            </h2>
        </div>

        <div class="mt-8 space-y-3">
            @foreach ($faq as $item)
                {{-- details/summary: opens with a keyboard, works without
                     JavaScript, and keeps the answer in the DOM either way,
                     which is what lets the FAQ markup describe it honestly. --}}
                <details class="reveal group rounded-xl border border-line bg-surface px-5 shadow-sm transition hover:border-line-strong open:shadow-md">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-4 font-heading font-bold text-ink marker:content-['']">
                        {{ $item['q'] }}
                        <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-primary-light text-primary transition-transform duration-200 group-open:rotate-45">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                        </span>
                    </summary>
                    <p class="pb-5 text-base leading-relaxed text-ink-muted">{{ $item['a'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 5.5. RELATED TOOLS ════════════════════════════════════════════════ --}}
<section class="bg-surface py-10 lg:py-12">
    <div class="container-page max-w-3xl">
        <h2 class="font-heading text-lg font-extrabold text-ink">Keep going</h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('tools.cat-calorie-calculator') }}" class="rounded-xl border border-line bg-surface-section p-4 text-sm font-semibold text-ink transition hover:border-primary hover:text-primary">
                Cat Calorie Calculator &rarr;
            </a>
            <a href="{{ route('tools.cat-pregnancy-calculator') }}" class="rounded-xl border border-line bg-surface-section p-4 text-sm font-semibold text-ink transition hover:border-primary hover:text-primary">
                Cat Pregnancy Calculator &rarr;
            </a>
        </div>
    </div>
</section>

{{-- ══ 6. SOURCES AND BYLINE ═════════════════════════════════════════════ --}}
<section class="bg-surface py-10 lg:py-12">
    <div class="container-page max-w-3xl">
        <div class="reveal rounded-2xl border border-line bg-surface-section p-6 sm:p-8">
            <h2 class="font-heading text-lg font-extrabold text-ink">Where this comes from</h2>
            <ul class="mt-4 space-y-3">
                @foreach ($sources as $source)
                    <li class="text-sm leading-relaxed text-ink-muted">
                        <a href="{{ $source['url'] }}" rel="noopener" target="_blank"
                           class="font-semibold text-primary underline decoration-line-strong underline-offset-4 transition-colors hover:text-primary-hover">
                            {{ $source['name'] }}
                        </a>
                        <span class="block">{{ $source['note'] }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 border-t border-line pt-5">
                <x-byline :reviewed="true"/>
            </div>

            <p class="mt-5 flex items-start gap-2.5 rounded-xl border border-warning-light bg-warning-light p-4 text-sm leading-relaxed text-ink">
                <svg class="mt-0.5 size-4 shrink-0 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M10.3 3.9 2.5 17.5A2 2 0 0 0 4.2 20.5h15.6a2 2 0 0 0 1.7-3l-7.8-13.6a2 2 0 0 0-3.4 0Z"/>
                    <path d="M12 9.5v4M12 17h.01"/>
                </svg>
                <span>
                    This is general information, not veterinary advice. It cannot
                    examine your cat. If something has changed about your cat's
                    health, speak to a vet rather than a calculator.
                </span>
            </p>
        </div>
    </div>
</section>

{{-- The model, handed to the script as JSON. json_encode rather than a
     Blade directive: the directive emits a JSON.parse() call wrapped in
     quotes, which broke on an apostrophe the last time it was used here. --}}
<script type="application/json" data-age-model>{!! json_encode($model, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

@push('scripts')
    <script>
        (() => {
            const form = document.querySelector('[data-age-form]');
            const modelTag = document.querySelector('[data-age-model]');
            if (!form || !modelTag) return;

            const MODEL = JSON.parse(modelTag.textContent);
            const $ = (sel, root = document) => root.querySelector(sel);

            const panels = {
                birthday: $('[data-panel="birthday"]'),
                age: $('[data-panel="age"]'),
                unknown: $('[data-panel="unknown"]'),
            };
            const errorBox = $('[data-age-error]');
            const placeholder = $('[data-age-placeholder]');
            const panel = $('[data-age-panel]');
            const spinner = $('[data-age-spinner]');
            const submit = $('[data-age-submit]');

            const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;

            const mode = () => form.querySelector('input[name="mode"]:checked').value;

            const showPanel = () => {
                const active = mode();
                Object.entries(panels).forEach(([key, el]) => el.toggleAttribute('hidden', key !== active));
            };

            form.querySelectorAll('input[name="mode"]').forEach(radio =>
                radio.addEventListener('change', () => { showPanel(); hideError(); }));

            const hideError = () => errorBox.toggleAttribute('hidden', true);
            const showError = (message) => {
                errorBox.textContent = message;
                errorBox.removeAttribute('hidden');
            };

            /* Cat years from whichever mode is active. Returns null on bad
               input so the caller can say why rather than showing NaN. */
            const catYears = () => {
                if (mode() === 'birthday') {
                    const value = $('#birthday').value;
                    if (!value) return { error: 'Enter your cat\'s date of birth, or switch to entering an age.' };

                    /* Parsed as local parts, not with new Date(string). The
                       string form is treated as UTC midnight, which lands on
                       the previous day for anyone west of Greenwich. */
                    const [y, m, d] = value.split('-').map(Number);
                    const born = new Date(y, m - 1, d);
                    if (Number.isNaN(born.getTime())) return { error: 'That date did not look right.' };

                    const now = new Date();
                    if (born > now) return { error: 'That date is in the future.' };

                    const years = (now - born) / (365.2425 * 24 * 60 * 60 * 1000);
                    if (years > 30) return { error: 'That would make your cat over 30. Check the year.' };

                    return { years };
                }

                if (mode() === 'age') {
                    const y = parseFloat($('#years').value) || 0;
                    const m = parseFloat($('#months').value) || 0;
                    if (y === 0 && m === 0) return { error: 'Enter an age in years, months, or both.' };
                    if (y < 0 || m < 0) return { error: 'Ages cannot be negative.' };
                    if (m > 11) return { error: 'Months should be 0 to 11. Put whole years in the years box.' };
                    if (y > 30) return { error: 'That would make your cat over 30. Check the number.' };
                    return { years: y + m / 12 };
                }

                return { years: parseFloat($('#estimate').value), estimated: true };
            };

            /* The conversion. Year one is worth about 15 human years and year
               two about 9 more; after that each year adds 4. Below one year the
               milestones are per month, because a kitten changes faster than a
               yearly rate can describe. */
            const toHuman = (years) => {
                if (years < 1) {
                    const months = Math.max(1, Math.round(years * 12));
                    const table = MODEL.kittenMonths;
                    return table[months] ?? table[Math.min(12, months)];
                }
                if (years < 2) {
                    const t = years - 1;
                    return Math.round(MODEL.firstYear + t * (MODEL.secondYear - MODEL.firstYear));
                }
                return Math.round(MODEL.secondYear + (years - 2) * MODEL.perYearAfter);
            };

            const stageFor = (years) =>
                MODEL.stages.find(s => years >= s.from && (s.until === null || years < s.until))
                ?? MODEL.stages[MODEL.stages.length - 1];

            const describeAge = (years, estimated) => {
                if (estimated) return 'Estimated from what you described';
                if (years < 1) {
                    const m = Math.max(1, Math.round(years * 12));
                    return `About ${m} ${m === 1 ? 'month' : 'months'} old`;
                }
                const whole = Math.floor(years);
                const months = Math.round((years - whole) * 12);
                const parts = [`${whole} ${whole === 1 ? 'year' : 'years'}`];
                if (months > 0 && months < 12) parts.push(`${months} ${months === 1 ? 'month' : 'months'}`);
                return `${parts.join(' ')} old`;
            };

            const countUp = (el, target) => {
                if (reduceMotion) { el.textContent = target; return; }
                const started = performance.now();
                const step = (now) => {
                    const t = Math.min(1, (now - started) / 700);
                    const eased = 1 - Math.pow(1 - t, 3);
                    el.textContent = Math.round(target * eased);
                    if (t < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };

            const TONES = {
                primary: 'bg-primary-light text-primary',
                accent: 'bg-accent-light text-accent-dark',
                warning: 'bg-warning-light text-warning',
                info: 'bg-info-light text-info',
            };

            let lastSummary = '';

            const render = ({ years, estimated }) => {
                const human = toHuman(years);
                const stage = stageFor(years);
                const breed = MODEL.breeds.find(b => b.id === $('#breed').value) ?? MODEL.breeds[0];
                const lifestyle = MODEL.lifestyles[$('#lifestyle').value];

                placeholder.setAttribute('hidden', '');
                panel.removeAttribute('hidden');

                countUp($('[data-age-human]'), human);
                $('[data-age-actual]').textContent = describeAge(years, estimated);

                $('[data-age-stage-name]').textContent = stage.name;
                $('[data-age-stage-range]').textContent = stage.until
                    ? `${stage.from} to ${stage.until - 1} years`
                    : `${stage.from} years and older`;
                $('[data-age-stage-summary]').textContent = stage.summary;
                $('[data-age-stage-tile]').className =
                    `flex size-10 shrink-0 items-center justify-center rounded-xl ${TONES[stage.tone]}`;
                $('[data-age-vet]').textContent = stage.vet;

                const care = $('[data-age-care]');
                care.textContent = '';
                stage.care.forEach(tip => {
                    const li = document.createElement('li');
                    li.className = 'flex items-start gap-2.5 text-sm leading-relaxed text-ink-muted';
                    const dot = document.createElement('span');
                    dot.className = 'mt-1.5 size-1.5 shrink-0 rounded-full bg-primary';
                    li.append(dot, document.createTextNode(tip));
                    care.append(li);
                });

                const [low, high] = breed.life_expectancy;
                $('[data-age-life]').textContent = `${low} to ${high} years`;
                $('[data-age-life-note]').textContent = lifestyle.note;

                const breedNote = $('[data-age-breed-note]');
                if (breed.watch) {
                    breedNote.textContent = `${breed.name} owners are often advised to watch for: ${breed.watch} A predisposition is a reason to screen, not a diagnosis.`;
                    breedNote.removeAttribute('hidden');
                } else {
                    breedNote.setAttribute('hidden', '');
                }

                // Mark the matching card in the life stages section, so the
                // page below the tool answers the question it just asked.
                document.querySelectorAll('[data-stage-card]').forEach(card => {
                    const match = card.dataset.stageCard === stage.id;
                    card.classList.toggle('ring-2', match);
                    card.classList.toggle('ring-primary', match);
                    card.classList.toggle('shadow-md', match);
                });

                lastSummary = `${describeAge(years, estimated)}: about ${human} in human years. Life stage: ${stage.name}. ${stage.vet}`;
            };

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                hideError();

                const result = catYears();
                if (result.error) { showError(result.error); return; }

                // A short spin, so the result reads as something that was
                // worked out. Validation already ran, so nothing is being
                // hidden behind it.
                spinner.removeAttribute('hidden');
                submit.disabled = true;

                setTimeout(() => {
                    spinner.setAttribute('hidden', '');
                    submit.disabled = false;
                    render(result);

                    if (!reduceMotion && window.innerWidth < 1024) {
                        panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, reduceMotion ? 0 : 450);
            });

            $('[data-age-copy]').addEventListener('click', async () => {
                const label = $('[data-age-copy-label]');
                try {
                    await navigator.clipboard.writeText(lastSummary);
                    label.textContent = 'Copied';
                } catch {
                    label.textContent = 'Press Ctrl+C to copy';
                }
                setTimeout(() => { label.textContent = 'Copy result'; }, 2000);
            });

            showPanel();
        })();
    </script>
@endpush

</x-layouts.app>

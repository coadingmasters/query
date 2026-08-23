<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

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
                    <li><a href="{{ route('tools.index') }}" class="transition-colors hover:text-primary">Tools</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="font-medium text-ink">Cat Calorie Calculator</li>
                </ol>
            </nav>

            <h1 class="mt-4 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
                Cat Calorie Calculator
            </h1>

            <p class="mt-4 max-w-lg text-base leading-relaxed text-ink-muted sm:text-lg">
                How many calories does your cat actually need? Enter their weight,
                age, activity and body condition, and get a daily target plus
                exactly how much food that means, in cups, cans or grams.
            </p>

            <ul class="mt-6 flex flex-wrap gap-x-6 gap-y-3">
                @foreach ([
                    ['NRC & AAFCO formula', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z'],
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
                <x-img name="cat-calorie-calculator-hero"
                       alt="Cat sitting beside a bowl of dry food"
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
        <div class="grid gap-6 lg:grid-cols-12 lg:items-start lg:gap-7">

            {{-- ── Inputs ──────────────────────────────────────────────── --}}
            <form data-cal-form class="rounded-2xl border border-line bg-surface p-6 shadow-md sm:p-7 lg:col-span-7" novalidate>
                <div class="flex items-center gap-4">
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink">Your cat's details</h2>
                        <p class="mt-1 text-sm text-ink-muted">Nothing is sent anywhere. This runs entirely in your browser.</p>
                    </div>
                </div>

                {{-- Name --}}
                <div class="mt-6">
                    <label for="cat-name" class="block text-sm font-semibold text-ink">Cat's name <span class="font-normal text-ink-muted">(optional)</span></label>
                    <input id="cat-name" name="cat-name" type="text" maxlength="40" placeholder="e.g. Biscuit"
                           class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                </div>

                {{-- Weight --}}
                <div class="mt-5">
                    <label for="weight" class="block text-sm font-semibold text-ink">Weight</label>
                    <div class="mt-2 flex gap-2">
                        <input id="weight" name="weight" type="number" min="0.1" step="0.1" inputmode="decimal" placeholder="10"
                               class="w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        <div class="flex shrink-0 rounded-xl border border-line-strong p-1" role="group" aria-label="Weight unit">
                            <button type="button" data-unit="lb" aria-pressed="true" class="rounded-lg px-3.5 py-2 text-sm font-bold transition bg-primary-vivid text-ink">lb</button>
                            <button type="button" data-unit="kg" aria-pressed="false" class="rounded-lg px-3.5 py-2 text-sm font-bold text-ink-muted transition hover:text-ink">kg</button>
                        </div>
                    </div>
                </div>

                {{-- Life stage --}}
                <div class="mt-5">
                    <label for="life-stage" class="block text-sm font-semibold text-ink">Life stage</label>
                    <select id="life-stage" name="life-stage"
                            class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        @foreach ($stages as $stage)
                            <option value="{{ $stage['id'] }}" @selected($stage['id'] === 'adult')>{{ $stage['label'] }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-ink-muted" data-stage-note></p>
                </div>

                {{-- Pregnancy stage, shown only when life stage is Pregnant --}}
                <div data-pregnancy-panel hidden class="mt-5">
                    <label for="pregnancy-stage" class="block text-sm font-semibold text-ink">How far along?</label>
                    <select id="pregnancy-stage" name="pregnancy-stage"
                            class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        <option value="early">Early (first 3–4 weeks)</option>
                        <option value="late">Late (final 3 weeks)</option>
                    </select>
                </div>

                {{-- Neuter status --}}
                <div class="mt-5">
                    <span class="block text-sm font-semibold text-ink">Spay / neuter status</span>
                    <div class="mt-2 grid grid-cols-2 gap-2" role="radiogroup" aria-label="Spay or neuter status">
                        @foreach ([['neutered', 'Spayed / Neutered'], ['intact', 'Intact']] as $i => [$value, $label])
                            <label class="flex cursor-pointer items-center justify-center rounded-xl border border-line-strong bg-surface px-3 py-2.5 text-center text-sm font-semibold text-ink transition has-checked:border-primary has-checked:bg-primary-light has-checked:text-primary has-checked:ring-1 has-checked:ring-primary">
                                <input type="radio" name="neuter-status" value="{{ $value }}" class="sr-only" @checked($i === 0)>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    <p data-neuter-note hidden class="mt-1.5 text-xs text-ink-muted">Not used for this life stage.</p>
                </div>

                {{-- Activity level --}}
                <div class="mt-5">
                    <span class="block text-sm font-semibold text-ink">Activity level</span>
                    <div class="mt-2 grid gap-2 sm:grid-cols-3">
                        @foreach ($activity as $key => $level)
                            <label class="group flex cursor-pointer flex-col items-center gap-1.5 rounded-xl border border-line-strong bg-surface px-3 py-3 text-center transition has-checked:border-primary has-checked:bg-primary-light has-checked:ring-1 has-checked:ring-primary">
                                <input type="radio" name="activity" value="{{ $key }}" class="sr-only" @checked($key === 'moderate')>
                                <svg class="size-6 text-ink-muted transition group-has-checked:text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    @if ($key === 'low')
                                        <path d="M4 17h16M7 17v-3a5 5 0 0 1 10 0v3"/><circle cx="12" cy="7" r="2.5"/>
                                    @elseif ($key === 'moderate')
                                        <path d="M13 5 8 12h4l-1 7 6-9h-4l1-5Z"/>
                                    @else
                                        <path d="M5 20 12 4l7 16M9 14h6"/>
                                    @endif
                                </svg>
                                <span class="font-heading text-sm font-bold text-ink">{{ $level['label'] }}</span>
                                <span class="text-[11px] leading-snug text-ink-muted">{{ $level['description'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Body Condition Score --}}
                <div class="mt-5">
                    <span class="block text-sm font-semibold text-ink">Body Condition Score</span>
                    <p class="mt-1 text-xs text-ink-muted">The single biggest factor in the result. Not sure? See the guide below the calculator.</p>
                    <div class="mt-2.5 grid grid-cols-5 gap-1.5 sm:gap-2">
                        @foreach ($bcs as $score => $level)
                            @php
                                // Leg tops sit at y=50. Below that is a gentle
                                // ideal curve; well above is an underweight
                                // tuck; well below is an obese sag.
                                $bellyY = [1 => 26, 2 => 40, 3 => 52, 4 => 68, 5 => 84][$score];
                            @endphp
                            <label class="group flex cursor-pointer flex-col items-center gap-1 rounded-xl border border-line-strong bg-surface px-1.5 py-2.5 text-center transition has-checked:border-primary has-checked:bg-primary-light has-checked:ring-1 has-checked:ring-primary">
                                <input type="radio" name="bcs" value="{{ $score }}" class="sr-only" @checked($score === 3)>
                                <svg viewBox="0 0 120 94" class="h-11 w-full text-ink-muted transition group-has-checked:text-primary" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    {{-- Tail --}}
                                    <path d="M92 20q17 2 14 20q-2 13 -12 15"/>
                                    {{-- Body: one closed silhouette, back over the top,
                                         belly underneath, so the sag is unmistakable. --}}
                                    <path d="M32,50 Q26,26 40,16 L86,15 Q98,16 96,30 Q94,42 90,50 Q60,{{ $bellyY }} 32,50 Z"/>
                                    {{-- Head and ears --}}
                                    <circle cx="18" cy="24" r="11" fill="none"/>
                                    <path d="M10 16 6 6l10 8M26 16l4-10-10 8"/>
                                    {{-- Legs --}}
                                    <path d="M36 50 L36 76M46 50 L46 76M82 50 L82 76M92 50 L92 76"/>
                                    @if ($score === 1)
                                        {{-- Visible ribs --}}
                                        <path d="M46 26c4 5 9 6 14 6M56 24c4 5 9 6 14 6M66 22c4 5 9 6 14 6" opacity="0.55" stroke-width="2"/>
                                    @endif
                                    @if ($score === 5)
                                        {{-- A loose fat pad hanging under the sag --}}
                                        <path d="M46 {{ $bellyY - 2 }}q14 10 30 2" opacity="0.5" stroke-width="2.5"/>
                                    @endif
                                </svg>
                                <span class="font-heading text-xs font-extrabold text-ink">{{ $score }}</span>
                                <span class="text-[10px] leading-tight text-ink-muted">{{ $level['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Living situation --}}
                <div class="mt-5">
                    <span class="block text-sm font-semibold text-ink">Living situation</span>
                    <div class="mt-2 grid grid-cols-3 gap-2">
                        @foreach ($living as $key => $level)
                            <label class="flex cursor-pointer items-center justify-center rounded-xl border border-line-strong bg-surface px-2 py-2.5 text-center text-xs font-semibold text-ink transition has-checked:border-primary has-checked:bg-primary-light has-checked:text-primary has-checked:ring-1 has-checked:ring-primary sm:text-sm">
                                <input type="radio" name="living" value="{{ $key }}" class="sr-only" @checked($key === 'indoor')>
                                {{ $level['label'] }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Food type --}}
                <div class="mt-5">
                    <span class="block text-sm font-semibold text-ink">Food type</span>
                    <div class="mt-2 grid grid-cols-3 gap-2">
                        @foreach ([['dry', 'Dry Food'], ['wet', 'Wet Food'], ['mixed', 'Mixed']] as $i => [$value, $label])
                            <label class="flex cursor-pointer items-center justify-center rounded-xl border border-line-strong bg-surface px-2 py-2.5 text-center text-xs font-semibold text-ink transition has-checked:border-primary has-checked:bg-primary-light has-checked:text-primary has-checked:ring-1 has-checked:ring-primary sm:text-sm">
                                <input type="radio" name="food-type" value="{{ $value }}" class="sr-only" @checked($i === 0)>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Feeding frequency --}}
                <div class="mt-5">
                    <label for="frequency" class="block text-sm font-semibold text-ink">Feeding frequency</label>
                    <select id="frequency" name="frequency"
                            class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        <option value="1">Once a day</option>
                        <option value="2" selected>Twice a day</option>
                        <option value="3">3 times a day</option>
                        <option value="0">Free-fed</option>
                    </select>
                </div>

                <p data-cal-error hidden role="alert"
                   class="mt-4 rounded-xl border border-danger/30 bg-danger-light px-4 py-3 text-sm font-medium text-danger"></p>

                <button type="submit" data-cal-submit class="btn-primary mt-6 w-full rounded-full sm:w-auto sm:px-8">
                    <svg data-cal-spinner hidden class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" opacity="0.25"/>
                        <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    Calculate calories
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                </button>
            </form>

            {{-- ── Result ──────────────────────────────────────────────── --}}
            <div data-cal-result aria-live="polite" class="lg:col-span-5 lg:sticky lg:top-24">
                <div data-cal-placeholder class="rounded-2xl border border-dashed border-line-strong bg-surface-section p-8 text-center">
                    <span class="mx-auto flex size-14 items-center justify-center rounded-full bg-primary-light">
                        <x-paw-print class="size-7 text-primary"/>
                    </span>
                    <p class="mt-4 font-heading text-lg font-bold text-ink">Your cat's daily calories appear here</p>
                    <p class="mt-2 text-sm leading-relaxed text-ink-muted">
                        Fill in the details on the left and calculate. Here's what you'll get:
                    </p>

                    <ul class="mt-6 space-y-3 text-left">
                        @foreach ([
                            'A daily calorie target, plus a healthy range either side',
                            'RER, the baseline your cat burns doing nothing at all',
                            'Exact portions in cups, cans or grams for your food type',
                            'A per-meal amount, split across your feeding schedule',
                            'A body-condition check, color-coded to flag over or underweight',
                        ] as $item)
                            <li class="flex items-start gap-2.5 text-sm leading-relaxed text-ink-muted">
                                <svg class="mt-0.5 size-4 shrink-0 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-6 flex flex-wrap items-center justify-center gap-x-4 gap-y-1.5 border-t border-line pt-5 text-xs font-medium text-ink-muted">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="size-3.5 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                            Free, no sign-up
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="size-3.5 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                            Runs in your browser
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="size-3.5 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                            NRC & AAFCO formula
                        </span>
                    </div>
                </div>

                <div data-cal-panel hidden class="overflow-hidden rounded-2xl border border-line bg-surface shadow-md">
                    <div class="bg-primary-vivid px-6 py-6 text-center text-ink">
                        <p class="text-xs font-bold tracking-[0.14em] uppercase opacity-80">Daily calorie needs</p>
                        <p class="mt-1 font-heading text-5xl font-extrabold tracking-tight tabular-nums">
                            <span data-cal-daily>0</span> <span class="text-xl font-bold">kcal/day</span>
                        </p>
                        <p class="mt-1 text-sm font-medium opacity-90">Healthy range: <span data-cal-range></span> kcal/day</p>
                    </div>

                    <div class="space-y-5 p-6">
                        <p data-cal-note class="text-sm leading-relaxed text-ink"></p>

                        <div data-cal-bcs-message class="rounded-xl p-4 text-sm leading-relaxed font-medium"></div>

                        <div class="rounded-xl bg-surface-section p-4">
                            <p class="text-xs font-bold tracking-wider text-ink uppercase">RER (Resting Energy)</p>
                            <p class="mt-1 font-heading text-lg font-bold text-ink"><span data-cal-rer>0</span> kcal/day</p>
                            <p class="mt-0.5 text-xs text-ink-muted">Calories your cat needs just to exist.</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold tracking-wider text-ink uppercase">Feeding breakdown</p>
                            <p class="mt-1.5 text-sm leading-relaxed text-ink-muted" data-cal-food></p>
                        </div>

                        <div class="rounded-xl border border-line bg-surface-soft p-4">
                            <p class="text-xs font-bold tracking-wider text-ink uppercase">Per meal</p>
                            <p class="mt-1.5 text-sm leading-relaxed text-ink-muted" data-cal-meal></p>
                        </div>

                        <p class="text-xs leading-relaxed text-ink-muted">
                            Formula based on the National Research Council (NRC) guidelines and AAFCO standards.
                        </p>

                        <div class="space-y-2.5">
                            <button type="button" data-cal-pdf
                                    data-logo="{{ \App\Support\Images::largest('purrquerylogo') }}"
                                    data-module="{{ Vite::asset('resources/js/cat-calorie-pdf.js') }}"
                                    class="group flex w-full items-center justify-center gap-2 rounded-full bg-primary-vivid px-5 py-3 text-sm font-bold text-ink shadow-md transition duration-200 hover:brightness-95 hover:shadow-lg active:scale-[0.99] disabled:cursor-default disabled:opacity-70">
                                <svg data-cal-pdf-icon class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 3v12m0 0 4.5-4.5M12 15l-4.5-4.5"/>
                                    <path d="M4 17v2.5A1.5 1.5 0 0 0 5.5 21h13a1.5 1.5 0 0 0 1.5-1.5V17"/>
                                </svg>
                                <span data-cal-pdf-label>Download PDF report</span>
                            </button>

                            <button type="button" data-cal-reset
                                    class="flex w-full items-center justify-center gap-2 rounded-full border border-line-strong bg-surface px-5 py-3 text-sm font-semibold text-ink-muted transition duration-200 hover:border-primary hover:text-primary">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 11.5A8 8 0 1 1 17.5 6"/><path d="M20 4v4h-4"/>
                                </svg>
                                Recalculate
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ 3. SEO CONTENT ════════════════════════════════════════════════════ --}}
<section class="bg-surface-section py-10 lg:py-12">
    <div class="container-page grid max-w-6xl gap-8 lg:grid-cols-[minmax(0,1fr)_16rem] lg:gap-12">

        {{-- Mobile contents, collapsed by default so it does not push the
             first heading off screen on a phone. --}}
        <details class="rounded-xl border border-line bg-surface px-5 lg:hidden">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-3.5 font-heading text-sm font-bold tracking-wider text-ink uppercase marker:content-['']">
                On this page
                <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-primary-light text-primary">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </span>
            </summary>
            <nav data-cal-toc-mobile aria-label="On this page" class="pb-4"></nav>
        </details>

        <div data-article class="article min-w-0 !max-w-none [&>*]:max-w-none">

            <h2 id="how-many-calories">How Many Calories Does a Cat Need Per Day?</h2>
            <p>
                Every calorie estimate for a cat starts from the same place: resting
                energy requirement, or RER, which is roughly what a cat burns doing
                nothing at all, lying still for 24 hours. The formula is 70 times
                body weight in kilograms, raised to the power of 0.75.
            </p>
            <p>
                RER alone is not what to feed, though. A cat that walks, plays,
                digests food and regulates its temperature burns more than that, so
                RER is multiplied by a life-stage factor to get maintenance energy
                requirement, or MER, which is the number that actually belongs in a
                bowl. That factor ranges from about 1.2 for a cat that needs to lose
                weight up to 3.0 for a very young kitten, which is why "how many
                calories does my cat need" never has one universal answer.
            </p>
            <p>Roughly, for an average-activity cat at ideal weight:</p>

            <div class="my-7 overflow-hidden rounded-2xl border border-line">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-4 py-3 font-semibold">Weight (lbs)</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Neutered adult</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Intact adult</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Kitten (4–12mo)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($weightTable as $row)
                            <tr>
                                <td class="px-4 py-3 text-ink">{{ $row['weight'] }} lb</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $row['neutered'] }} kcal</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $row['intact'] }} kcal</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $row['kitten'] }} kcal</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-sm text-ink-muted">
                Figures are kcal per day at average activity and ideal body condition.
                Sources: <a href="https://nap.nationalacademies.org/catalog/10668/nutrient-requirements-of-dogs-and-cats" target="_blank" rel="noopener">NRC, Nutrient Requirements of Dogs and Cats (2006)</a>
                and the <a href="https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center/health-information/feeding" target="_blank" rel="noopener">Cornell Feline Health Center</a>.
            </p>

            <h2 id="how-to-use">How to Use This Cat Calorie Calculator</h2>
            <p>
                Start with your cat's <strong>weight</strong>, in pounds or
                kilograms, using the toggle. This is the number the whole
                calculation is built from, so a scale reading beats a guess. Next,
                pick the <strong>life stage</strong> that fits, from a young kitten
                through senior, pregnant or nursing. Each stage carries a different
                multiplier because a growing kitten and a sedentary 12-year-old
                simply do not run on the same fuel.
            </p>
            <p>
                <strong>Spay or neuter status</strong> matters because neutering
                measurably lowers a cat's metabolic rate, typically by 10 to 15%.
                <strong>Activity level</strong> and <strong>living situation</strong>
                both adjust for how much a cat actually moves: an outdoor cat that
                hunts and patrols burns meaningfully more than an indoor cat that
                naps most of the day, even at the same weight.
            </p>
            <p>
                <strong>Body Condition Score is the input that matters most.</strong>
                Two cats can be the same breed, age and weight and still need
                different amounts of food, because weight alone does not say
                whether that weight is muscle or excess fat. BCS is a hands-on
                read of actual body condition, and it is the one input in this
                calculator that directly corrects the final number up or down, by
                as much as 25%. The section below walks through how to score it.
            </p>
            <p>
                Finally, choose your <strong>food type</strong> and <strong>feeding
                frequency</strong> so the result can convert calories into an actual
                portion, in cups, cans or grams, split across the meals you
                actually feed. Add your cat's name if you like; it just makes the
                result read as though it is about your cat, because it is.
            </p>

            <h2 id="bcs">Understanding Body Condition Score (BCS) in Cats</h2>
            <p>
                Body Condition Score is a 5-point hands-on scale, built from the
                more detailed 9-point scale vets use, and it is the standard
                published by the American Association of Feline Practitioners
                (AAFP) and the American Animal Hospital Association (AAHA). It
                asks one practical question: can you feel the ribs, and how much
                fat is covering them?
            </p>
            <ul>
                <li><strong>1, Underweight:</strong> ribs, spine and hip bones are visible from across the room, with no fat cover and an obvious waist and abdominal tuck.</li>
                <li><strong>2, Lean:</strong> ribs are easily felt with minimal fat, a visible waist from above, and a slight abdominal tuck.</li>
                <li><strong>3, Ideal:</strong> ribs are easily felt with a slight fat cover, a visible waist behind the ribs, and a minimal abdominal tuck.</li>
                <li><strong>4, Overweight:</strong> ribs are difficult to feel under noticeable fat, the waist is barely visible, and the belly is starting to round out.</li>
                <li><strong>5, Obese:</strong> ribs are very hard or impossible to feel under a heavy fat layer, there is no waist, and the abdomen sags or bulges visibly.</li>
            </ul>
            <p>
                To check it yourself: run your flat hand gently along your cat's
                side, over the ribs, using about the same pressure you would use
                to feel the back of your own hand through a thin glove. Then look
                from above for a waist behind the ribs, and from the side for a
                tucked or hanging abdomen. This takes about ten seconds and is far
                more accurate than eyeballing weight alone.
            </p>
            <p>
                BCS changes the calculator's result directly: a lean or
                underweight score adds calories back in, and an overweight or
                obese score subtracts them, on the reasoning that a cat carrying
                extra fat needs to eat for the weight it should be, not the
                weight it currently is.
            </p>

            <h2 id="indoor-outdoor">Indoor vs Outdoor Cats: Why Calories Differ</h2>
            <p>
                Cats with regular outdoor access typically burn 20 to 40% more
                calories than a strictly indoor cat of the same size. Two things
                drive most of that gap. The first is temperature regulation: a cat
                outside in cold or variable weather spends real energy just
                maintaining body temperature, something an indoor cat in a
                climate-controlled home almost never has to do. The second is
                activity itself, patrolling territory, climbing, and hunting are
                sustained physical work in a way that indoor play sessions,
                however enthusiastic, rarely match in volume.
            </p>
            <p>
                This is also why "indoor formula" cat foods generally run lower in
                calories per cup than standard formulas, and why an indoor cat fed
                a bag measured out for an average cat is one of the more common,
                slow causes of feline weight gain.
            </p>

            <h2 id="life-stage">Calorie Needs by Life Stage</h2>
            <div class="my-7 overflow-hidden rounded-2xl border border-line">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-4 py-3 font-semibold">Life stage</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Multiplier</th>
                            <th scope="col" class="px-4 py-3 font-semibold">10 lb cat</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Key note</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($lifeStageTable as $row)
                            <tr>
                                <td class="px-4 py-3 text-ink">{{ $row['label'] }}</td>
                                <td class="px-4 py-3 text-ink-muted">{{ rtrim(rtrim(number_format($row['multiplier'], 2), '0'), '.') }}&times;</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $row['kcal'] }} kcal</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $row['note'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-sm text-ink-muted">
                Sources: AAFCO life-stage nutrient profiles and NRC, Nutrient
                Requirements of Dogs and Cats (2006). Pregnant and nursing values
                are averaged across the range NRC publishes; the calculator above
                narrows pregnancy down to early or late stage.
            </p>

            <h2 id="convert-to-food">How to Convert Calories Into Food Portions</h2>
            <p>
                A calorie target is not something you can pour into a bowl, so the
                last step is converting it using the calorie density of the actual
                food you feed, which is on the label as kcal per cup, per can, or
                per 100 grams.
            </p>
            <p>
                <strong>Dry food worked example:</strong> if your cat needs 250
                kcal a day and your dry food provides 380 kcal per cup, divide 250
                by 380, which comes to about 0.66 cups a day, or a little under
                two-thirds of a standard 8 oz measuring cup.
            </p>
            <p>
                <strong>Wet food worked example:</strong> using a typical 95 kcal
                per 5.5 oz can, that same 250 kcal target works out to about 2.6
                cans a day, most owners round that to two full cans plus a small
                topper, or split it across three smaller meals.
            </p>
            <p>
                <strong>Mixed feeding example:</strong> splitting 250 kcal evenly
                gives 125 kcal from each. At 380 kcal per cup that is about a
                third of a cup of dry food, plus roughly 1.3 cans of wet food, for
                the other half.
            </p>
            <p>
                The calculator above does this arithmetic automatically once you
                pick a food type, using 350 kcal per cup for dry food and 95 kcal
                per 5.5 oz can for wet food as working averages. Always check your
                specific bag or can, since real products vary by 100 kcal or more
                either side of that average.
            </p>

            <h2 id="signs">Signs You're Feeding Your Cat Too Much or Too Little</h2>
            <p>
                <strong>Overfeeding</strong> tends to show up gradually: steady
                weight gain, lower energy or less interest in play, and ribs that
                get progressively harder to feel under a thickening layer of fat.
                A widening waist when viewed from above, and a belly that sways or
                sags when your cat walks, are both later, more obvious signs.
            </p>
            <p>
                <strong>Underfeeding</strong> shows differently: visible ribs and
                spine, a pronounced waist tuck, low energy, and sometimes a coat
                that turns dull or thin, since a body running short on calories
                stops prioritizing coat quality. Unlike gradual weight gain,
                weight loss in a cat should never just be watched: a cat that eats
                too little for even a few days is at real risk of a serious liver
                condition called hepatic lipidosis.
            </p>
            <p>
                Either direction, the right response is the same: recheck body
                condition every few weeks, adjust the daily amount by about 10% up
                or down, and see a vet if the trend does not correct within a
                month, or if it is happening despite no change in how much is
                being fed.
            </p>

            <h2 id="faq">Frequently Asked Questions</h2>
            <div class="not-prose">
                @foreach ($faq as $item)
                    <details class="group mb-3 rounded-xl border border-line bg-surface px-5 shadow-sm transition hover:border-line-strong open:shadow-md" style="max-width: var(--measure)">
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

        {{-- Sticky contents, desktop only. Built from the h2 ids already in
             the article, so it cannot drift out of sync with the headings. --}}
        <aside class="hidden lg:sticky lg:top-24 lg:block lg:self-start">
            <nav aria-labelledby="cal-toc-heading" class="rounded-2xl border border-line bg-surface p-5">
                <h2 id="cal-toc-heading" class="font-heading text-sm font-bold tracking-wider text-ink uppercase">On this page</h2>
                <div data-cal-toc class="mt-3"></div>
            </nav>

            <div class="mt-5 rounded-2xl border border-line bg-surface p-5">
                <p class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Quick reference</p>
                <dl class="mt-3 space-y-2.5 text-sm">
                    @foreach ([
                        ['RER formula', '70 x kg^0.75'],
                        ['Neutered adult', '1.6 x RER'],
                        ['Intact adult', '1.8 x RER'],
                        ['Dry food', '~350 kcal/cup'],
                        ['Wet food', '~95 kcal/can'],
                        ['Treats cap', 'Under 10% of daily'],
                    ] as [$term, $value])
                        <div class="flex items-baseline justify-between gap-3">
                            <dt class="text-ink-muted">{{ $term }}</dt>
                            <dd class="shrink-0 font-semibold text-ink tabular-nums">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </aside>
    </div>
</section>

{{-- ══ 4. RELATED TOOLS ══════════════════════════════════════════════════ --}}
<section class="bg-surface pt-2 pb-8 lg:pb-10">
    <div class="container-page max-w-6xl">
        <h2 class="font-heading text-lg font-extrabold text-ink">Keep going</h2>
        <div class="mt-4 grid gap-4 sm:grid-cols-3">
            @foreach ([
                ['href' => route('tools.cat-age-calculator'), 'image' => 'cat-age-calculator-senior-tabby-cat', 'alt' => 'Cats at five life stages from kitten to senior', 'title' => 'Cat Age Calculator', 'blurb' => 'Human years and the life stage behind them.'],
                ['href' => route('tools.cat-pregnancy-calculator'), 'image' => 'cat-pregnancy-calculator-kitten', 'alt' => 'Newborn kitten curled up asleep', 'title' => 'Cat Pregnancy Calculator', 'blurb' => 'Due date and week-by-week milestones.'],
                ['href' => route('blog.show', 'how-much-should-i-feed-my-cat'), 'image' => 'how-much-to-feed-cat-hero', 'alt' => 'Cat sitting beside a bowl being filled with a measured scoop of kibble', 'title' => 'Feeding Guide', 'blurb' => 'Wet vs dry, meals per day, treats and more.'],
            ] as $item)
                <a href="{{ $item['href'] }}" class="card group">
                    <div class="card-media aspect-[4/3]">
                        <x-img :name="$item['image']" :alt="$item['alt']" sizes="(max-width: 639px) 92vw, (max-width: 1023px) 46vw, 30vw"/>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title text-base">{{ $item['title'] }}</h3>
                        <p class="card-text flex-1 text-xs">{{ $item['blurb'] }}</p>
                        <span class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-primary">
                            Open
                            <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 5. SOURCES AND BYLINE ═════════════════════════════════════════════ --}}
<section class="bg-surface-section py-8 lg:py-10">
    <div class="container-page max-w-6xl">
        <div class="rounded-2xl border border-line bg-surface p-6 sm:p-8">
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
                    examine your cat. If your cat's weight or appetite has
                    changed, or if they are pregnant, nursing, a kitten or
                    unwell, talk to a vet rather than relying on a calculator.
                </span>
            </p>
        </div>
    </div>
</section>

<script type="application/json" data-cal-model>{!! json_encode($model, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

@push('scripts')
    <script>
        (() => {
            const form = document.querySelector('[data-cal-form]');
            const modelTag = document.querySelector('[data-cal-model]');
            if (!form || !modelTag) return;

            const MODEL = JSON.parse(modelTag.textContent);
            const $ = (sel, root = document) => root.querySelector(sel);

            const errorBox = $('[data-cal-error]');
            const placeholder = $('[data-cal-placeholder]');
            const panel = $('[data-cal-panel]');
            const spinner = $('[data-cal-spinner]');
            const submit = $('[data-cal-submit]');
            const pregnancyPanel = $('[data-pregnancy-panel]');
            const neuterNote = $('[data-neuter-note]');
            const stageNote = $('[data-stage-note]');

            const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;

            let unit = 'lb';
            const unitButtons = form.querySelectorAll('[data-unit]');
            unitButtons.forEach(btn => btn.addEventListener('click', () => {
                if (btn.dataset.unit === unit) return;
                const weightInput = $('#weight');
                const value = parseFloat(weightInput.value);
                if (!Number.isNaN(value) && value > 0) {
                    weightInput.value = btn.dataset.unit === 'kg'
                        ? (value * 0.45359237).toFixed(1)
                        : (value / 0.45359237).toFixed(1);
                }
                unit = btn.dataset.unit;
                unitButtons.forEach(b => {
                    const active = b === btn;
                    b.setAttribute('aria-pressed', String(active));
                    b.classList.toggle('bg-primary-vivid', active);
                    b.classList.toggle('text-ink', active);
                    b.classList.toggle('text-ink-muted', !active);
                });
            }));

            const stageSelect = $('#life-stage');
            const updateStageUI = () => {
                const stage = MODEL.stages.find(s => s.id === stageSelect.value);
                if (!stage) return;

                stageNote.textContent = stage.note;

                const isPregnancy = stage.kind === 'pregnancy';
                pregnancyPanel.toggleAttribute('hidden', !isPregnancy);

                const neuterIrrelevant = ['fixed', 'pregnancy', 'nursing'].includes(stage.kind);
                form.querySelectorAll('input[name="neuter-status"]').forEach(r => { r.disabled = neuterIrrelevant; });
                neuterNote.toggleAttribute('hidden', !neuterIrrelevant);
            };
            stageSelect.addEventListener('change', updateStageUI);

            const hideError = () => errorBox.toggleAttribute('hidden', true);
            const showError = (message) => {
                errorBox.textContent = message;
                errorBox.removeAttribute('hidden');
            };

            /* Resting energy requirement: 70 x weight_kg^0.75. */
            const rer = (weightKg) => 70 * Math.pow(weightKg, 0.75);

            const multiplierFor = (stage, neuterStatus, pregnancyStage, bcsScore) => {
                switch (stage.kind) {
                    case 'fixed':
                        return stage.multiplier;
                    case 'neuter':
                        return neuterStatus === 'intact' ? stage.multiplier_intact : stage.multiplier_neutered;
                    case 'pregnancy':
                        return pregnancyStage === 'late' ? stage.multiplier_late : stage.multiplier_early;
                    case 'nursing': {
                        // Leans toward the high end for a thinner nursing queen,
                        // the low end for one already in good condition.
                        const t = Math.max(0, Math.min(1, (3 - bcsScore) / 2));
                        return stage.multiplier_min + t * (stage.multiplier_max - stage.multiplier_min);
                    }
                    default:
                        return 1.6;
                }
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

            const gatherInput = () => {
                const weightRaw = parseFloat($('#weight').value);
                if (!weightRaw || weightRaw <= 0) return { error: "Enter your cat's weight." };
                if (unit === 'lb' && (weightRaw < 0.5 || weightRaw > 60)) return { error: 'That weight looks off. Check the number.' };
                if (unit === 'kg' && (weightRaw < 0.2 || weightRaw > 27)) return { error: 'That weight looks off. Check the number.' };

                const weightKg = unit === 'kg' ? weightRaw : weightRaw * 0.45359237;
                const stage = MODEL.stages.find(s => s.id === stageSelect.value);
                const neuterStatus = (form.querySelector('input[name="neuter-status"]:checked') || {}).value || 'neutered';
                const pregnancyStage = $('#pregnancy-stage').value;
                const activityKey = (form.querySelector('input[name="activity"]:checked') || {}).value || 'moderate';
                const bcsScore = parseInt((form.querySelector('input[name="bcs"]:checked') || {}).value || '3', 10);
                const livingKey = (form.querySelector('input[name="living"]:checked') || {}).value || 'indoor';
                const foodType = (form.querySelector('input[name="food-type"]:checked') || {}).value || 'dry';
                const frequency = parseInt($('#frequency').value, 10);
                const name = $('#cat-name').value.trim();

                return {
                    weightKg, weightRaw, unit, stage, neuterStatus, pregnancyStage, activityKey,
                    bcsScore, livingKey, foodType, frequency, name,
                };
            };

            // The last full calculation, kept so the PDF report can be built
            // from exactly what is on screen rather than recomputed.
            let lastResult = null;

            const BCS_TONE = {
                1: 'bg-warning-light text-warning', 2: 'bg-warning-light text-warning',
                3: 'bg-accent-light text-accent-dark',
                4: 'bg-warning-light text-warning', 5: 'bg-warning-light text-warning',
            };
            const BCS_MESSAGE = {
                1: 'may be underweight. Consider a vet checkup.',
                2: 'is on the lean side, which is fine if that is normal for them.',
                3: 'is at an ideal body condition. Keep it up!',
                4: 'may be overweight. A vet-guided diet can help.',
                5: 'may be significantly overweight. A vet-guided diet can help.',
            };

            const render = (input) => {
                const { weightKg, weightRaw, unit: weightUnit, stage, neuterStatus, pregnancyStage, activityKey, bcsScore, livingKey, foodType, frequency, name } = input;

                const restingEnergy = rer(weightKg);
                const stageMultiplier = multiplierFor(stage, neuterStatus, pregnancyStage, bcsScore);
                const activityMultiplier = MODEL.activity[activityKey].value;
                const livingMultiplier = MODEL.living[livingKey].value;
                const bcsPercent = MODEL.bcs[bcsScore].percent;

                let daily = restingEnergy * stageMultiplier * activityMultiplier * livingMultiplier;
                daily *= (1 + bcsPercent);

                const dailyRounded = Math.round(daily);
                const low = Math.round(daily * 0.9);
                const high = Math.round(daily * 1.1);

                placeholder.setAttribute('hidden', '');
                panel.removeAttribute('hidden');

                countUp($('[data-cal-daily]'), dailyRounded);
                $('[data-cal-range]').textContent = `${low}-${high}`;
                countUp($('[data-cal-rer]'), Math.round(restingEnergy));

                // Food breakdown.
                const dryCups = daily / MODEL.food.dry_kcal_per_cup;
                const wetCans = daily / MODEL.food.wet_kcal_per_can;
                const foodEl = $('[data-cal-food]');
                let foodText;
                if (foodType === 'dry') {
                    const grams = Math.round(dryCups * 120); // ~120g per US cup of kibble, a working average
                    foodText = `Approximately ${dryCups.toFixed(2)} cups (about ${grams}g) of dry food per day.`;
                } else if (foodType === 'wet') {
                    foodText = `Approximately ${wetCans.toFixed(1)} cans (5.5oz) of wet food per day.`;
                } else {
                    const halfDry = (daily / 2) / MODEL.food.dry_kcal_per_cup;
                    const halfWet = (daily / 2) / MODEL.food.wet_kcal_per_can;
                    foodText = `Split evenly: about ${halfDry.toFixed(2)} cups of dry food plus ${halfWet.toFixed(1)} cans of wet food per day.`;
                }
                foodEl.textContent = foodText;

                // Per meal.
                const mealEl = $('[data-cal-meal]');
                let mealText;
                if (frequency === 0) {
                    mealText = 'Free-fed: leave the full daily amount out, split across clean bowls, and measure the total rather than refilling by eye.';
                } else {
                    const perMealKcal = Math.round(daily / frequency);
                    const times = frequency === 1 ? 'once a day' : `${frequency} times a day`;
                    let portionText;
                    if (foodType === 'dry') {
                        portionText = `${(dryCups / frequency).toFixed(2)} cups`;
                    } else if (foodType === 'wet') {
                        portionText = `${(wetCans / frequency).toFixed(2)} cans`;
                    } else {
                        portionText = `${((dryCups / 2) / frequency).toFixed(2)} cups dry + ${((wetCans / 2) / frequency).toFixed(2)} cans wet`;
                    }
                    mealText = `If feeding ${times}: ${perMealKcal} kcal per meal, about ${portionText} per meal.`;
                }
                mealEl.textContent = mealText;

                // Personalised note.
                const catLabel = name ? name : 'Your cat';
                const neuterLabel = ['fixed', 'pregnancy', 'nursing'].includes(stage.kind)
                    ? ''
                    : (neuterStatus === 'intact' ? 'intact' : 'spayed/neutered');
                const bcsLabel = MODEL.bcs[bcsScore].label.toLowerCase();
                const stageLabel = stage.label.toLowerCase();
                const article = (word) => /^[aeiou]/.test(word) ? 'an' : 'a';
                $('[data-cal-note]').textContent =
                    `${catLabel} is ${article(stageLabel)} ${stageLabel}${neuterLabel ? ' ' + neuterLabel : ''} cat with ${article(bcsLabel)} ${bcsLabel} body condition. `
                    + `At ${dailyRounded} kcal/day, aim for the portions above. Adjust by plus or minus 10% based on weight changes.`;

                // BCS message.
                const bcsMsg = $('[data-cal-bcs-message]');
                bcsMsg.className = `rounded-xl p-4 text-sm leading-relaxed font-medium ${BCS_TONE[bcsScore]}`;
                bcsMsg.textContent = `${catLabel} ${BCS_MESSAGE[bcsScore]}`;

                // Everything the PDF report needs, including the multipliers,
                // so it can show the working rather than repeat the answer.
                const FREQUENCY_LABELS = { 0: 'Free-fed', 1: 'Once a day', 2: 'Twice a day', 3: 'Three times a day' };
                const FOOD_LABELS = { dry: 'Dry food', wet: 'Wet food', mixed: 'Mixed (dry and wet)' };
                const pct = Math.round(bcsPercent * 100);

                // Trailing zeros stripped, but never below one decimal, so a
                // column of multipliers lines up as 2.0 / 1.6 / 2.25.
                const multiplier = (n) => {
                    const s = n.toFixed(2).replace(/0+$/, '');
                    return s.endsWith('.') ? s + '0' : s;
                };

                lastResult = {
                    name,
                    daily: dailyRounded,
                    rangeLow: low,
                    rangeHigh: high,
                    rer: Math.round(restingEnergy),
                    weightKg: weightKg.toFixed(2),
                    weightLabel: weightUnit === 'kg'
                        ? `${weightRaw} kg`
                        : `${weightRaw} lb (${weightKg.toFixed(1)} kg)`,
                    stageLabel: stage.label,
                    stageMultiplier: multiplier(stageMultiplier),
                    neuterLabel: ['fixed', 'pregnancy', 'nursing'].includes(stage.kind)
                        ? 'Not applicable at this life stage'
                        : (neuterStatus === 'intact' ? 'Intact' : 'Spayed / neutered'),
                    activityLabel: `${MODEL.activity[activityKey].label}, ${MODEL.activity[activityKey].description.toLowerCase()}`,
                    activityMultiplier: multiplier(activityMultiplier),
                    livingLabel: MODEL.living[livingKey].label,
                    livingMultiplier: multiplier(livingMultiplier),
                    bcsScore,
                    bcsLabel: MODEL.bcs[bcsScore].label,
                    bcsAdjustment: pct === 0 ? 'No adjustment' : `${pct > 0 ? '+' : ''}${pct}%`,
                    bcsMessage: bcsMsg.textContent,
                    foodLabel: FOOD_LABELS[foodType],
                    foodPortion: foodText.replace(/^Approximately /, '').replace(/\.$/, ''),
                    frequencyLabel: FREQUENCY_LABELS[frequency],
                    mealPortion: frequency === 0
                        ? 'Full daily amount available, measured rather than topped up by eye'
                        : mealText.replace(/^If feeding [^:]+: /, '').replace(/\.$/, ''),
                };
            };

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                hideError();

                const input = gatherInput();
                if (input.error) { showError(input.error); return; }

                spinner.removeAttribute('hidden');
                submit.disabled = true;

                setTimeout(() => {
                    spinner.setAttribute('hidden', '');
                    submit.disabled = false;
                    render(input);

                    if (!reduceMotion && window.innerWidth < 1024) {
                        panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, reduceMotion ? 0 : 400);
            });

            $('[data-cal-reset]').addEventListener('click', () => {
                form.reset();
                unit = 'lb';
                unitButtons.forEach(b => {
                    const active = b.dataset.unit === 'lb';
                    b.setAttribute('aria-pressed', String(active));
                    b.classList.toggle('bg-primary-vivid', active);
                    b.classList.toggle('text-ink', active);
                    b.classList.toggle('text-ink-muted', !active);
                });
                panel.setAttribute('hidden', '');
                placeholder.removeAttribute('hidden');
                lastResult = null;
                hideError();
                updateStageUI();
                $('#cat-name').focus();
            });

            /* ── PDF report ──────────────────────────────────────────────
               The module is fetched on the first click rather than up front:
               it is several kilobytes of PDF-format writing that most
               visitors never trigger. */
            const pdfButton = $('[data-cal-pdf]');
            const pdfLabel = $('[data-cal-pdf-label]');

            pdfButton.addEventListener('click', async () => {
                if (!lastResult) return;

                const original = pdfLabel.textContent;
                pdfButton.disabled = true;
                pdfLabel.textContent = 'Preparing report...';

                try {
                    const { downloadCalorieReport } = await import(pdfButton.dataset.module);
                    await downloadCalorieReport(lastResult, pdfButton.dataset.logo);
                    pdfLabel.textContent = 'Report downloaded';
                } catch {
                    pdfLabel.textContent = 'Could not build the PDF';
                }

                setTimeout(() => {
                    pdfLabel.textContent = original;
                    pdfButton.disabled = false;
                }, 2200);
            });

            updateStageUI();
        })();

        /* Contents, built from the article's own h2 ids so the list and the
           headings cannot drift apart. */
        (() => {
            const article = document.querySelector('[data-article]');
            if (!article) return;

            const entries = [...article.querySelectorAll('h2[id]')]
                .map(h => ({ id: h.id, text: h.textContent.trim() }));
            if (!entries.length) return;

            const build = (target) => {
                if (!target) return;

                const ol = document.createElement('ol');
                ol.className = 'space-y-0.5 text-sm';

                entries.forEach((entry, i) => {
                    const a = document.createElement('a');
                    a.href = '#' + entry.id;
                    a.className = 'flex gap-2 rounded-md py-1.5 leading-snug text-ink-muted transition-colors hover:text-primary';

                    const number = document.createElement('span');
                    number.className = 'shrink-0 tabular-nums text-primary';
                    number.textContent = (i + 1) + '.';

                    a.append(number, document.createTextNode(entry.text));

                    const li = document.createElement('li');
                    li.append(a);
                    ol.append(li);
                });

                target.append(ol);
            };

            build(document.querySelector('[data-cal-toc]'));
            build(document.querySelector('[data-cal-toc-mobile]'));
        })();
    </script>
@endpush

</x-layouts.app>

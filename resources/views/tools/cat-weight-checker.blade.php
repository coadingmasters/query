<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    $bcsTone = [1 => 'info', 2 => 'accent', 3 => 'accent', 4 => 'warning', 5 => 'danger'];
    $toneClasses = [
        'info' => 'bg-info-light text-info',
        'accent' => 'bg-accent-light text-accent-dark',
        'warning' => 'bg-warning-light text-warning',
        'danger' => 'bg-danger-light text-danger',
    ];
@endphp

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-24 -left-20 size-96 rounded-full bg-primary-vivid opacity-[0.08] blur-3xl"></div>
        <div class="absolute -right-16 bottom-0 size-80 rounded-full bg-accent-vivid opacity-[0.1] blur-3xl"></div>
        <x-paw-print class="paw absolute top-[18%] left-[4%] hidden size-10 text-primary-vivid/30 lg:block [animation-duration:24s]"/>
        <x-paw-print class="paw absolute bottom-[22%] left-[9%] hidden size-7 text-primary-vivid/25 lg:block [animation-delay:-7s] [animation-duration:21s]"/>
    </div>

    <div class="container-page relative grid items-center gap-8 pt-8 pb-16 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.05fr)] lg:pt-6 lg:pb-20">
        <div class="relative z-10">
            <nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
                <ol class="flex flex-wrap items-center gap-1.5">
                    <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="{{ route('tools.index') }}" class="transition-colors hover:text-primary">Tools</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="font-medium text-ink">Cat Weight Checker</li>
                </ol>
            </nav>

            <h1 class="mt-4 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
                Cat Weight Checker
            </h1>

            <p class="mt-4 max-w-lg text-base leading-relaxed text-ink-muted sm:text-lg">
                Score your cat's body condition, see an estimated ideal weight range, and
                get a safe food adjustment if they need one. Log weight over time and
                watch the trend, right here in your browser.
            </p>

            <ul class="mt-6 flex flex-wrap gap-x-6 gap-y-3">
                @foreach ([
                    ['Vet-based BCS scale', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z'],
                    ['Free weight log & chart', 'M3 3v18h18M8 17V10M13 17V6M18 17v-4'],
                    ['Nothing leaves your device', 'M5.5 5h13A1.5 1.5 0 0 1 20 6.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 15.5v-9A1.5 1.5 0 0 1 5.5 5Z'],
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

        <div class="relative lg:self-center">
            <div class="relative overflow-hidden rounded-[4rem_1.75rem_4rem_1.75rem] border-4 border-primary/15 bg-surface shadow-lg">
                <div class="aspect-[3/2]">
                    <x-img name="cat-weight-checker-cat-on-scale"
                           alt="Fluffy cat sitting on a digital pet scale"
                           sizes="(max-width: 1023px) 92vw, 640px" :priority="true"/>
                </div>
            </div>

            <div aria-hidden="true"
                 class="absolute -bottom-5 left-4 hidden items-center gap-3 rounded-2xl border border-line bg-surface px-4 py-3 shadow-lg sm:flex">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/></svg>
                </span>
                <span>
                    <span class="block font-heading text-sm font-extrabold text-ink">Results in seconds</span>
                    <span class="mt-0.5 block text-xs text-ink-muted">No sign-up, ever</span>
                </span>
            </div>
        </div>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-10 w-full text-surface sm:h-16" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ 2. HOW TO WEIGH YOUR CAT ══════════════════════════════════════════ --}}
<section class="bg-surface pt-4 pb-10 lg:pb-12">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">Start here</p>
            <h2 class="section-title">Don't know your cat's weight yet?</h2>
            <p class="section-intro">Three ways to get a real number before you use the checker below.</p>
        </div>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            @foreach ($weighMethods as $i => $method)
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm" style="--reveal-delay: {{ $i * 90 }}ms">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-primary-light font-heading text-base font-extrabold text-primary">
                        {{ $i + 1 }}
                    </span>
                    <h3 class="mt-4 font-heading text-base font-bold text-ink">{{ $method['title'] }}</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">{{ $method['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 3. CHECKER ════════════════════════════════════════════════════════ --}}
<section class="bg-surface-soft py-10 lg:py-14">
    <div class="container-page">
        <div class="grid gap-6 lg:grid-cols-12 lg:items-start lg:gap-7">

            {{-- ── Inputs ──────────────────────────────────────────────── --}}
            <form data-weight-form class="rounded-2xl border border-line bg-surface p-6 shadow-md sm:p-7 lg:col-span-7" novalidate>
                <h2 class="font-heading text-xl font-extrabold text-ink">Check your cat's weight</h2>

                <div class="mt-5">
                    <label for="cat-name" class="block text-sm font-semibold text-ink">Cat's name <span class="font-normal text-ink-muted">(optional)</span></label>
                    <input type="text" id="cat-name" name="cat-name" maxlength="40" placeholder="e.g. Biscuit"
                           class="mt-1.5 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                </div>

                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="cat-weight" class="block text-sm font-semibold text-ink">Current weight</label>
                        <div class="mt-1.5 flex items-center gap-2">
                            <input type="number" id="cat-weight" name="cat-weight" step="0.1" min="0.5" max="60" required
                                   placeholder="9.5"
                                   class="w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            <div class="flex shrink-0 overflow-hidden rounded-lg border border-line-strong text-xs font-bold">
                                <button type="button" data-weight-unit="lb" aria-pressed="true" class="bg-primary-vivid px-2.5 py-2 text-ink">lb</button>
                                <button type="button" data-weight-unit="kg" aria-pressed="false" class="bg-surface px-2.5 py-2 text-ink-muted">kg</button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="cat-age" class="block text-sm font-semibold text-ink">Age <span class="font-normal text-ink-muted">(years)</span></label>
                        <input type="number" id="cat-age" name="cat-age" step="1" min="0" max="30" placeholder="4"
                               class="mt-1.5 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>
                </div>

                <p data-weight-error hidden class="mt-4 rounded-xl bg-danger-light px-4 py-3 text-sm font-semibold text-danger"></p>

                {{-- ── BCS selector ────────────────────────────────────── --}}
                <fieldset class="mt-5">
                    <legend class="text-sm font-semibold text-ink">
                        Body condition: which best describes your cat?
                    </legend>
                    <p class="mt-1 text-xs text-ink-muted">
                        Run a hand along their ribs and look down at them from above.
                        <a href="#bcs-guide" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">See the full guide</a>.
                    </p>

                    <div class="mt-3 grid gap-2.5">
                        @foreach ($bcs as $score => $level)
                            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-line-strong p-3.5 transition has-checked:border-primary has-checked:bg-primary-light/40">
                                <input type="radio" name="bcs" value="{{ $score }}" class="sr-only" @checked($score === 3)>
                                <span @class([
                                    'mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg text-xs font-extrabold',
                                    $toneClasses[$bcsTone[$score]],
                                ])>{{ $score }}</span>
                                <span class="min-w-0">
                                    <span class="block text-sm font-bold text-ink">{{ $level['label'] }}</span>
                                    <span class="mt-0.5 line-clamp-2 block text-xs leading-relaxed text-ink-muted">{{ $level['short'] }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary flex-1 rounded-full sm:flex-initial sm:px-8">
                        Check weight
                    </button>
                    <button type="button" data-weight-reset class="btn-outline rounded-full">
                        Reset
                    </button>
                </div>
            </form>

            {{-- ── Results ─────────────────────────────────────────────── --}}
            <div class="lg:col-span-5 lg:sticky lg:top-24">
                <div data-weight-placeholder class="flex h-full min-h-[22rem] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-line-strong p-8 text-center">
                    <span class="flex size-14 items-center justify-center rounded-full bg-primary-light text-primary">
                        <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/></svg>
                    </span>
                    <p class="mt-4 text-sm font-semibold text-ink">Your cat's results will appear here</p>
                    <p class="mt-1 text-sm text-ink-muted">Fill in the weight and body condition, then check.</p>
                </div>

                <div data-weight-panel hidden class="post-card-pop rounded-2xl border border-line bg-surface p-6 shadow-md sm:p-7">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Body condition</p>
                            <p data-weight-bcs-label class="mt-1 font-heading text-2xl font-extrabold text-ink"></p>
                        </div>
                        <span data-weight-bcs-badge class="rounded-full px-3 py-1 text-xs font-bold"></span>
                    </div>

                    <div class="mt-5 rounded-xl bg-surface-soft p-4">
                        <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Estimated ideal weight</p>
                        <p class="mt-1 font-heading text-xl font-extrabold text-ink">
                            <span data-weight-ideal-low></span>&ndash;<span data-weight-ideal-high></span> <span data-weight-ideal-unit class="text-sm font-semibold text-ink-muted"></span>
                        </p>
                        <p class="mt-1 text-xs text-ink-muted">An estimate from weight and body condition together, not a diagnosis. A vet visit gives the precise number.</p>
                    </div>

                    <div data-weight-food-panel class="mt-4 rounded-xl p-4 text-sm leading-relaxed font-medium"></div>

                    <div data-weight-senior-panel hidden class="mt-4 flex items-start gap-3 rounded-xl border border-warning-light bg-warning-light/60 p-4">
                        <svg class="mt-0.5 size-5 shrink-0 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 9v4M12 17h.01"/><path d="m10.3 3.9-8 14A1.5 1.5 0 0 0 3.6 20h16.8a1.5 1.5 0 0 0 1.3-2.2l-8-14a1.5 1.5 0 0 0-2.6 0Z"/></svg>
                        <p class="text-sm leading-relaxed text-ink">
                            Unexplained weight change in a senior cat is worth a vet visit before a diet change. It's as often a symptom as it is overfeeding. See our
                            <a href="{{ route('blog.show', 'senior-cat-care') }}" class="font-semibold underline decoration-line-strong underline-offset-4">senior cat care guide</a>.
                        </p>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <button type="button" data-weight-log-btn class="btn-outline flex-1 rounded-full text-sm">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                            Log this weight
                        </button>
                        <button type="button" data-weight-pdf
                                data-logo="{{ \App\Support\Images::largest('purrquerylogo') }}"
                                data-module="{{ Vite::asset('resources/js/cat-weight-pdf.js') }}"
                                class="btn-outline flex-1 rounded-full text-sm">
                            <svg data-weight-pdf-icon class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                            <span data-weight-pdf-label>Download PDF</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ 4. WEIGHT LOG ═════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface">
    <div class="container-page">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="eyebrow">Track over time</p>
                <h2 class="section-title !mb-0 !text-left">Weight log</h2>
            </div>
            <button type="button" data-log-clear class="hidden text-sm font-semibold text-ink-muted underline decoration-line-strong underline-offset-4 hover:text-danger">
                Clear log
            </button>
        </div>
        <p class="mt-2 max-w-xl text-sm text-ink-muted">
            Saved only in this browser, nothing is sent anywhere. Log a weight above, or add one directly below.
        </p>

        <div class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(0,1fr)]">
            <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
                <div data-log-chart-empty class="flex h-52 flex-col items-center justify-center text-center">
                    <p class="text-sm font-semibold text-ink">No entries yet</p>
                    <p class="mt-1 text-sm text-ink-muted">Log at least two weights to see a trend line here.</p>
                </div>
                <div data-log-chart-wrap hidden></div>
            </div>

            <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
                <form data-log-add class="flex flex-wrap items-end gap-3">
                    <div class="flex-1">
                        <label for="log-date" class="block text-xs font-semibold text-ink-muted uppercase">Date</label>
                        <input type="date" id="log-date" name="log-date" required
                               class="mt-1 w-full rounded-lg border border-line-strong bg-surface px-3 py-2 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>
                    <div class="w-24">
                        <label for="log-weight" class="block text-xs font-semibold text-ink-muted uppercase">Weight</label>
                        <input type="number" id="log-weight" name="log-weight" step="0.1" min="0.5" max="60" required
                               class="mt-1 w-full rounded-lg border border-line-strong bg-surface px-3 py-2 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>
                    <button type="submit" class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary-vivid text-ink shadow-sm transition hover:brightness-95">
                        <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    </button>
                </form>

                <ul data-log-list class="mt-4 max-h-64 space-y-1.5 overflow-y-auto"></ul>
                <p data-log-list-empty class="mt-4 text-sm text-ink-muted">Nothing logged yet.</p>
            </div>
        </div>
    </div>
</section>

{{-- ══ 5. BCS GUIDE ══════════════════════════════════════════════════════ --}}
<section id="bcs-guide" class="section-tight scroll-mt-20 bg-surface-soft">
    <div class="container-page max-w-3xl">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">
            Body Condition Score, explained
        </h2>
        <p class="mt-3 text-base leading-relaxed text-ink-muted">
            BCS matters more than weight on its own: a healthy weight for one cat's frame
            can be underweight or overweight for another. Vets score it from 1 to 5 by
            feel and by eye, not with a scale.
        </p>

        <div class="mt-6 space-y-3">
            @foreach ($bcs as $score => $level)
                <div class="flex items-start gap-4 rounded-xl border border-line bg-surface p-4">
                    <span @class(['flex size-9 shrink-0 items-center justify-center rounded-lg text-sm font-extrabold', $toneClasses[$bcsTone[$score]]])>
                        {{ $score }}
                    </span>
                    <div>
                        <p class="font-heading text-sm font-bold text-ink">{{ $level['label'] }}</p>
                        <p class="mt-1 text-sm leading-relaxed text-ink-muted">{{ $level['short'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="mt-6 text-sm leading-relaxed text-ink-muted">
            If your cat sits between two descriptions, it is fine to choose the closer one.
            This tool works from the description you pick and your cat's current weight to
            estimate an ideal weight range. It is not a substitute for a vet's hands-on exam,
            and any significant change in weight or condition is worth a visit. For a deeper
            look at feeding amounts once you know where your cat stands, our
            <a href="{{ route('tools.cat-calorie-calculator') }}" class="font-semibold text-primary underline decoration-line-strong underline-offset-4">cat calorie calculator</a>
            works out a full daily target.
        </p>
    </div>
</section>

{{-- ══ 6. FAQ ════════════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface">
    <div class="container-page max-w-3xl">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Frequently asked questions</h2>
        <div class="mt-6 space-y-3">
            @foreach ($faq as $item)
                <details class="group rounded-xl border border-line bg-surface-soft px-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-4 text-left font-heading font-bold text-ink marker:content-['']">
                        {{ $item['q'] }}
                        <svg class="size-4 shrink-0 text-ink-muted transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <p class="pb-4 text-sm leading-relaxed text-ink-muted">{{ $item['a'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 7. SOURCES AND BYLINE ═════════════════════════════════════════════ --}}
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
        </div>
    </div>
</section>

<script type="application/json" data-weight-model>{!! json_encode($model) !!}</script>

@push('scripts')
<script>
(() => {
    const modelTag = document.querySelector('[data-weight-model]');
    const form = document.querySelector('[data-weight-form]');
    if (!modelTag || !form) return;

    const MODEL = JSON.parse(modelTag.textContent);
    const $ = (sel, root = document) => root.querySelector(sel);
    const LB_TO_KG = 0.45359237;
    const STORAGE_KEY = 'purrquery-weight-log-v1';

    const placeholder = $('[data-weight-placeholder]');
    const panel = $('[data-weight-panel]');
    const errorBox = $('[data-weight-error]');

    const TONE = {
        1: 'bg-info-light text-info', 2: 'bg-accent-light text-accent-dark', 3: 'bg-accent-light text-accent-dark',
        4: 'bg-warning-light text-warning', 5: 'bg-danger-light text-danger',
    };
    const FOOD_TONE = {
        under: 'bg-info-light text-info', ideal: 'bg-accent-light text-accent-dark', over: 'bg-warning-light text-warning',
    };

    let unit = 'lb';
    form.querySelectorAll('[data-weight-unit]').forEach(btn => btn.addEventListener('click', () => {
        if (btn.dataset.weightUnit === unit) return;
        const input = $('#cat-weight');
        const value = parseFloat(input.value);
        if (!Number.isNaN(value) && value > 0) {
            input.value = btn.dataset.weightUnit === 'kg' ? (value * LB_TO_KG).toFixed(1) : (value / LB_TO_KG).toFixed(1);
        }
        unit = btn.dataset.weightUnit;
        form.querySelectorAll('[data-weight-unit]').forEach(b => {
            const active = b === btn;
            b.setAttribute('aria-pressed', String(active));
            b.classList.toggle('bg-primary-vivid', active);
            b.classList.toggle('text-ink', active);
            b.classList.toggle('bg-surface', !active);
            b.classList.toggle('text-ink-muted', !active);
        });
    }));

    let lastResult = null;

    function idealRange(weightLb, bcsScore) {
        const dev = MODEL.bcs[bcsScore].deviation;
        if (dev === 0) return { low: weightLb, high: weightLb };
        const sign = bcsScore > 3 ? 1 : -1;
        const center = weightLb / (1 + sign * dev);
        return { low: center * 0.95, high: center * 1.05 };
    }

    function foodAdjustment(weightLb, bcsScore) {
        if (bcsScore === 3) return null;
        const overweight = bcsScore > 3;
        const kg = weightLb * LB_TO_KG;
        const rer = 70 * Math.pow(kg, 0.75);
        const maintenance = rer * 1.4;
        const percent = overweight ? 20 : 20;
        const adjusted = maintenance * (overweight ? 0.8 : 1.2);
        return { overweight, percent, maintenance: Math.round(maintenance), adjusted: Math.round(adjusted) };
    }

    function render(input) {
        const { weightLb, name, age, bcsScore } = input;
        const level = MODEL.bcs[bcsScore];
        const range = idealRange(weightLb, bcsScore);
        const isSenior = age !== null && age >= MODEL.seniorAge;

        placeholder.setAttribute('hidden', '');
        panel.removeAttribute('hidden');

        $('[data-weight-bcs-label]').textContent = level.label;
        const badge = $('[data-weight-bcs-badge]');
        badge.className = `rounded-full px-3 py-1 text-xs font-bold ${TONE[bcsScore]}`;
        badge.textContent = `BCS ${bcsScore}/5`;

        const displayUnit = unit;
        const toDisplay = (lb) => displayUnit === 'kg' ? (lb * LB_TO_KG) : lb;
        $('[data-weight-ideal-low]').textContent = toDisplay(range.low).toFixed(1);
        $('[data-weight-ideal-high]').textContent = toDisplay(range.high).toFixed(1);
        $('[data-weight-ideal-unit]').textContent = displayUnit;

        const foodPanel = $('[data-weight-food-panel]');
        const catLabel = name || 'Your cat';
        const adj = foodAdjustment(weightLb, bcsScore);
        if (!adj) {
            foodPanel.className = `mt-4 rounded-xl p-4 text-sm leading-relaxed font-medium ${FOOD_TONE.ideal}`;
            foodPanel.textContent = `${catLabel} is at an ideal body condition. Keep feeding what's working, and re-check every few weeks.`;
        } else if (adj.overweight) {
            foodPanel.className = `mt-4 rounded-xl p-4 text-sm leading-relaxed font-medium ${FOOD_TONE.over}`;
            foodPanel.textContent = `${catLabel} would likely benefit from gradual weight loss: aim for about ${adj.percent}% less food than current maintenance (roughly ${adj.adjusted} kcal/day instead of ${adj.maintenance}), losing no more than 1-1.5% of body weight per week. Faster loss risks a serious liver condition in cats, so go slow.`;
        } else {
            foodPanel.className = `mt-4 rounded-xl p-4 text-sm leading-relaxed font-medium ${FOOD_TONE.under}`;
            foodPanel.textContent = `${catLabel} could use some gentle weight gain: try around ${adj.percent}% more food than current maintenance (roughly ${adj.adjusted} kcal/day). If they aren't gaining within a few weeks, a vet check is worth ruling out an underlying cause.`;
        }

        $('[data-weight-senior-panel]').toggleAttribute('hidden', !isSenior);

        lastResult = {
            name: catLabel, weightLb, unit: displayUnit, age, isSenior,
            bcsScore, bcsLabel: level.label,
            idealLow: toDisplay(range.low), idealHigh: toDisplay(range.high),
            adjustment: adj,
        };

        $('#log-weight').value = displayUnit === 'kg' ? weightLb.toFixed(1) : weightLb.toFixed(1);
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        errorBox.setAttribute('hidden', '');

        const weightRaw = parseFloat($('#cat-weight').value);
        if (Number.isNaN(weightRaw) || weightRaw <= 0) {
            errorBox.textContent = "Enter your cat's weight to continue.";
            errorBox.removeAttribute('hidden');
            return;
        }
        const bcsInput = form.querySelector('input[name="bcs"]:checked');
        if (!bcsInput) {
            errorBox.textContent = 'Choose the description closest to your cat.';
            errorBox.removeAttribute('hidden');
            return;
        }

        const weightLb = unit === 'kg' ? weightRaw / LB_TO_KG : weightRaw;
        const ageRaw = $('#cat-age').value;
        render({
            weightLb,
            name: $('#cat-name').value.trim(),
            age: ageRaw ? parseInt(ageRaw, 10) : null,
            bcsScore: parseInt(bcsInput.value, 10),
        });
    });

    $('[data-weight-reset]').addEventListener('click', () => {
        form.reset();
        form.querySelector('input[value="3"]').checked = true;
        panel.setAttribute('hidden', '');
        placeholder.removeAttribute('hidden');
        errorBox.setAttribute('hidden', '');
        lastResult = null;
    });

    /* ── Weight log ─────────────────────────────────────────────────── */
    const loadLog = () => {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            const parsed = raw ? JSON.parse(raw) : [];
            return Array.isArray(parsed) ? parsed : [];
        } catch { return []; }
    };
    const saveLog = (entries) => {
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(entries)); } catch {}
    };

    let log = loadLog();

    const chartWrap = $('[data-log-chart-wrap]');
    const chartEmpty = $('[data-log-chart-empty]');
    const listEl = $('[data-log-list]');
    const listEmpty = $('[data-log-list-empty]');
    const clearBtn = $('[data-log-clear]');

    function fmtDate(iso) {
        const d = new Date(iso + 'T00:00:00');
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    function renderChart() {
        const sorted = [...log].sort((a, b) => a.date.localeCompare(b.date));
        if (sorted.length < 2) {
            chartWrap.setAttribute('hidden', '');
            chartEmpty.removeAttribute('hidden');
            return;
        }
        chartEmpty.setAttribute('hidden', '');
        chartWrap.removeAttribute('hidden');

        const width = 520, height = 190, padX = 20, padY = 24;
        const weights = sorted.map(e => e.weightLb);
        const min = Math.min(...weights), max = Math.max(...weights);
        const span = Math.max(0.5, max - min);
        const step = sorted.length > 1 ? (width - padX * 2) / (sorted.length - 1) : 0;

        const points = sorted.map((e, i) => ({
            x: padX + i * step,
            y: height - padY - ((e.weightLb - min) / span) * (height - padY * 2),
            entry: e,
        }));

        const path = points.map((p, i) => (i === 0 ? 'M' : 'L') + p.x.toFixed(2) + ',' + p.y.toFixed(2)).join(' ');
        const areaPath = path + ` L ${points[points.length - 1].x.toFixed(2)},${height - padY + 4} L ${points[0].x.toFixed(2)},${height - padY + 4} Z`;

        let pathLength = 0;
        for (let i = 1; i < points.length; i++) {
            pathLength += Math.hypot(points[i].x - points[i - 1].x, points[i].y - points[i - 1].y);
        }

        chartWrap.innerHTML = `
            <svg viewBox="0 0 ${width} ${height}" class="w-full" preserveAspectRatio="none" aria-hidden="true">
                <defs>
                    <linearGradient id="weight-fill" x1="0" x2="0" y1="0" y2="1">
                        <stop offset="0%" stop-color="var(--color-primary-vivid)" stop-opacity="0.32"/>
                        <stop offset="100%" stop-color="var(--color-primary-vivid)" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="${areaPath}" fill="url(#weight-fill)" class="admin-wave-fill" style="animation-delay:250ms"/>
                <path d="${path}" fill="none" stroke="var(--color-primary-vivid)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                      class="admin-line-draw" style="stroke-dasharray:${pathLength || 1};stroke-dashoffset:${pathLength || 1}"/>
                ${points.map((p, i) => `<circle cx="${p.x.toFixed(2)}" cy="${p.y.toFixed(2)}" r="5" fill="var(--color-primary-vivid)" stroke="var(--color-surface)" stroke-width="2.5" class="admin-dot stagger-delay" style="--stagger-delay:${550 + i * 70}ms"/>`).join('')}
            </svg>
            <div class="mt-3 flex justify-between text-xs text-ink-muted">
                <span>${fmtDate(sorted[0].date)} &middot; ${sorted[0].weightLb.toFixed(1)}lb</span>
                <span>${fmtDate(sorted[sorted.length - 1].date)} &middot; ${sorted[sorted.length - 1].weightLb.toFixed(1)}lb</span>
            </div>
        `;
    }

    function renderList() {
        const sorted = [...log].sort((a, b) => b.date.localeCompare(a.date));
        listEl.innerHTML = '';
        listEmpty.toggleAttribute('hidden', sorted.length > 0);
        clearBtn.classList.toggle('hidden', sorted.length === 0);

        sorted.forEach((entry) => {
            const li = document.createElement('li');
            li.className = 'flex items-center justify-between gap-2 rounded-lg px-2.5 py-2 text-sm hover:bg-surface-soft';
            li.innerHTML = `
                <span class="text-ink-muted">${fmtDate(entry.date)}</span>
                <span class="font-semibold text-ink">${entry.weightLb.toFixed(1)}lb</span>
                <button type="button" aria-label="Delete entry" class="flex size-6 shrink-0 items-center justify-center rounded-full text-ink-muted transition hover:bg-danger-light hover:text-danger">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            `;
            li.querySelector('button').addEventListener('click', () => {
                log = log.filter(e => e !== entry);
                saveLog(log);
                renderList();
                renderChart();
            });
            listEl.append(li);
        });
    }

    function addEntry(date, weightLb) {
        log = log.filter(e => e.date !== date);
        log.push({ date, weightLb });
        saveLog(log);
        renderList();
        renderChart();
    }

    $('#log-date').valueAsDate = new Date();

    $('[data-log-add]').addEventListener('submit', (e) => {
        e.preventDefault();
        const date = $('#log-date').value;
        const raw = parseFloat($('#log-weight').value);
        if (!date || Number.isNaN(raw) || raw <= 0) return;
        const weightLb = unit === 'kg' ? raw / LB_TO_KG : raw;
        addEntry(date, weightLb);
    });

    clearBtn.addEventListener('click', () => {
        if (!confirm('Clear your whole weight log? This cannot be undone.')) return;
        log = [];
        saveLog(log);
        renderList();
        renderChart();
    });

    $('[data-weight-log-btn]').addEventListener('click', () => {
        if (!lastResult) return;
        addEntry(new Date().toISOString().slice(0, 10), lastResult.weightLb);
    });

    renderList();
    renderChart();

    /* ── PDF report ──────────────────────────────────────────────────── */
    const pdfButton = $('[data-weight-pdf]');
    const pdfLabel = $('[data-weight-pdf-label]');
    pdfButton.addEventListener('click', async () => {
        if (!lastResult) { alert('Check your cat\'s weight first.'); return; }
        const original = pdfLabel.textContent;
        pdfButton.disabled = true;
        pdfLabel.textContent = 'Preparing report...';
        try {
            const { downloadWeightReport } = await import(pdfButton.dataset.module);
            await downloadWeightReport({ ...lastResult, log: [...log].sort((a, b) => a.date.localeCompare(b.date)) }, pdfButton.dataset.logo);
            pdfLabel.textContent = 'Report downloaded';
        } catch {
            pdfLabel.textContent = 'Could not build the PDF';
        }
        setTimeout(() => { pdfLabel.textContent = original; pdfButton.disabled = false; }, 2200);
    });
})();
</script>
@endpush

</x-layouts.app>

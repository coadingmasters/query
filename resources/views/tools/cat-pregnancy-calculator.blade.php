{{--
    Cat Pregnancy Calculator — UI shell only.

    There is deliberately no calculation here yet. The Calculate button reveals
    the results section with the dummy values written into the markup, and the
    week timeline is static. Each section below is commented so the next pass
    can target one without reading the whole file.
--}}
<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ 1. HEADER ═════════════════════════════════════════════════════════
     Tool title and one line saying what it does. The site header above it
     carries the logo and navigation.
     ═══════════════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft pb-12 lg:pb-14">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-24 size-96 rounded-full bg-primary-vivid opacity-[0.07] blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 size-80 rounded-full bg-accent-vivid opacity-[0.12] blur-3xl"></div>
        <x-paw-print class="paw absolute top-[22%] left-[6%] hidden size-10 text-primary sm:block" style="animation-duration: 23s"/>
        <x-paw-print class="paw absolute top-[28%] right-[7%] hidden size-8 text-accent-vivid sm:block" style="animation-delay: -8s; animation-duration: 20s"/>
    </div>

    <div class="container-page relative py-10 text-center lg:py-12">
        <nav aria-label="Breadcrumb" class="flex justify-center">
            <ol class="flex items-center gap-2 text-sm">
                <li><a href="/" class="font-medium text-primary transition-colors hover:text-primary-hover">Home</a></li>
                <li aria-hidden="true" class="text-ink-muted">/</li>
                <li><a href="/#tools" class="font-medium text-primary transition-colors hover:text-primary-hover">Tools</a></li>
                <li aria-hidden="true" class="text-ink-muted">/</li>
                <li><span aria-current="page" class="font-medium text-ink">Pregnancy Calculator</span></li>
            </ol>
        </nav>

        <h1 class="mt-5 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
            Cat Pregnancy Calculator
        </h1>
        <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-ink-muted sm:text-lg">
            Enter the mating date to estimate when your cat is due, the likely
            birth window, and which week of the pregnancy she is in now.
        </p>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-12 w-full text-surface sm:h-20" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

<section class="section-tight bg-surface">
    <div class="container-page max-w-3xl">

        {{-- ══ 2. INPUT ══════════════════════════════════════════════════════
             Cat name, breed, mating date, an unknown-date toggle that reveals a
             secondary input, and the Calculate button. The button reveals the
             results section; it does no arithmetic yet.
             ═══════════════════════════════════════════════════════════════ --}}
        <form id="calculator-form" class="reveal rounded-2xl border border-line bg-surface p-6 shadow-md sm:p-8"
              onsubmit="return false">
            <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">
                Your cat’s details
            </h2>
            <p class="mt-2 text-sm text-ink-muted">
                Nothing is sent anywhere — this runs entirely in your browser.
            </p>

            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                {{-- Cat name --}}
                <div>
                    <label for="cat-name" class="block text-sm font-semibold text-ink">Cat’s name</label>
                    <input id="cat-name" name="cat-name" type="text" autocomplete="off" placeholder="Luna"
                           class="mt-2 w-full rounded-xl border border-line bg-surface px-4 py-3 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                </div>

                {{-- Breed — five placeholder options for now --}}
                <div>
                    <label for="cat-breed" class="block text-sm font-semibold text-ink">Breed</label>
                    <select id="cat-breed" name="cat-breed"
                            class="mt-2 w-full rounded-xl border border-line bg-surface px-4 py-3 text-base text-ink shadow-sm transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        {{-- Values match the gestation table in the script. A
                             breed listed here without an entry there would
                             silently fall back to the 65-day default. --}}
                        <option value="mixed">Mixed breed / unknown</option>
                        <option value="abyssinian">Abyssinian</option>
                        <option value="bengal">Bengal</option>
                        <option value="british-shorthair">British Shorthair</option>
                        <option value="burmese">Burmese</option>
                        <option value="devon-rex">Devon Rex</option>
                        <option value="maine-coon">Maine Coon</option>
                        <option value="oriental-shorthair">Oriental Shorthair</option>
                        <option value="persian">Persian</option>
                        <option value="ragdoll">Ragdoll</option>
                        <option value="siamese">Siamese</option>
                    </select>
                </div>
            </div>

            {{-- Mating date. Hidden when the symptom mode is on, since the two
                 are alternatives rather than both being filled in. --}}
            <div class="mt-5" data-date-field>
                <label for="mating-date" class="block text-sm font-semibold text-ink">Mating date</label>
                <input id="mating-date" name="mating-date" type="date"
                       class="mt-2 w-full rounded-xl border border-line bg-surface px-4 py-3 text-base text-ink shadow-sm transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
            </div>

            {{-- Unknown-date toggle. Checked state reveals the block below it. --}}
            <div class="mt-5 rounded-xl border border-line bg-surface-soft p-4">
                <label for="unknown-date" class="flex cursor-pointer items-start gap-3">
                    <input id="unknown-date" name="unknown-date" type="checkbox" data-unknown-toggle
                           class="mt-0.5 size-5 shrink-0 rounded border-line-strong text-primary focus:ring-2 focus:ring-primary/20">
                    <span>
                        <span class="block text-sm font-semibold text-ink">I don’t know the exact date</span>
                        <span class="mt-0.5 block text-sm text-ink-muted">
                            Estimate from what you have noticed instead.
                        </span>
                    </span>
                </label>

                {{-- ══ SYMPTOM MODE ═════════════════════════════════════════
                     Shown when the mating date is unknown. Each card is a real
                     checkbox with its label styled as a toggle — it keeps the
                     keyboard behaviour and the screen-reader semantics that a
                     div dressed up as a button would throw away.

                     min_day rides on the markup as data-min-day, so the script
                     and the page can never disagree about a symptom's timing.
                     ═══════════════════════════════════════════════════════ --}}
                <div id="unknown-date-panel" hidden class="mt-4 border-t border-line pt-4">
                    <fieldset>
                        <legend class="text-sm font-semibold text-ink">
                            Tick everything you have noticed
                        </legend>
                        <p class="mt-1 text-sm text-ink-muted">
                            The latest sign she is showing tells us roughly how far
                            along she is. More ticks means a more confident estimate.
                        </p>

                        <div class="mt-4 grid gap-3">
                            @foreach (config('pregnancy-symptoms') as $symptom)
                                <label for="symptom-{{ $symptom['id'] }}"
                                       class="group flex cursor-pointer items-start gap-3 rounded-xl border border-line bg-surface p-4 shadow-sm transition duration-150 hover:border-line-strong has-checked:border-primary has-checked:bg-primary-light has-checked:shadow-md">
                                    <input id="symptom-{{ $symptom['id'] }}" type="checkbox"
                                           data-symptom data-min-day="{{ $symptom['min_day'] }}"
                                           value="{{ $symptom['id'] }}" class="peer sr-only">

                                    {{-- Stands in for the hidden checkbox. The
                                         real one still owns focus and state. --}}
                                    <span aria-hidden="true"
                                          class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-md border-2 border-line-strong bg-surface transition peer-checked:border-primary peer-checked:bg-primary peer-focus-visible:ring-2 peer-focus-visible:ring-primary/30 peer-focus-visible:ring-offset-2">
                                        <svg class="size-3 text-ink-inverse opacity-0 transition-opacity group-has-checked:opacity-100"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 6 9 17l-5-5"/>
                                        </svg>
                                    </span>

                                    <span class="min-w-0">
                                        <span class="block text-sm font-semibold text-ink">{{ $symptom['question'] }}</span>
                                        <span class="mt-0.5 block text-sm text-ink-muted">{{ $symptom['detail'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Confidence, filled in by the script as boxes are ticked --}}
                        <div data-confidence hidden class="mt-4 rounded-xl border border-line bg-surface p-4">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-sm font-semibold text-ink">Confidence</span>
                                <span data-confidence-label class="text-sm font-bold"></span>
                            </div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-line">
                                <div data-confidence-bar class="h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <p data-confidence-note class="mt-2 text-xs text-ink-muted"></p>
                        </div>
                    </fieldset>
                </div>
            </div>

            {{-- Validation messages land here. role=alert so a screen reader
                 hears them without the focus being moved. --}}
            <p data-error role="alert" hidden
               class="mt-5 rounded-xl border border-danger/30 bg-danger-light px-4 py-3 text-sm font-medium text-danger"></p>

            <button type="submit" data-calculate
                    class="btn-primary mt-6 w-full rounded-full disabled:cursor-not-allowed disabled:opacity-70 sm:w-auto sm:px-8">
                <span data-calculate-label>Calculate due date</span>

                <svg data-calculate-arrow class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>

                {{-- Shown while the result is being prepared. aria-hidden
                     because the button's own label already announces it. --}}
                <svg data-calculate-spinner hidden class="size-4 animate-spin" viewBox="0 0 24 24"
                     fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" opacity="0.25"/>
                    <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </button>
        </form>

        {{-- ══ 3. RESULTS ════════════════════════════════════════════════════
             Hidden until Calculate is pressed. Every figure below is dummy data
             held in the markup — nothing here is computed yet.
             ═══════════════════════════════════════════════════════════════ --}}
        <section id="results" hidden aria-live="polite" class="mt-8">
            <div class="overflow-hidden rounded-2xl border border-line bg-surface shadow-md">

                {{-- Due date, prominent --}}
                <div class="bg-primary-vivid px-6 py-8 text-center sm:px-8">
                    <p class="text-sm font-semibold tracking-wide text-ink/70 uppercase">
                        Estimated due date
                    </p>
                    <p data-result-due-date class="mt-2 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
                        12 October 2026
                    </p>
                    <p data-result-window class="mt-3 text-base text-ink/80">
                        Likely birth window: 9 – 15 October 2026
                    </p>

                    {{-- Copying a date is the thing people do next: into a
                         calendar, or a message to whoever else is watching. --}}
                    <button type="button" data-copy
                            class="mt-5 inline-flex items-center gap-2 rounded-full border border-ink/25 px-4 py-2 text-sm font-semibold text-ink transition hover:bg-ink/5 focus-visible:ring-2 focus-visible:ring-ink/40 focus-visible:outline-none">
                        <svg data-copy-icon class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="9" y="9" width="12" height="12" rx="2"/>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                        </svg>
                        <span data-copy-label>Copy the dates</span>
                    </button>
                    <span data-copy-status role="status" aria-live="polite" class="sr-only"></span>
                </div>

                {{-- An overdue or past-term pregnancy says so here, above the
                     figures, because it is the only thing that matters then. --}}
                <p data-result-warning hidden role="alert"
                   class="mx-6 mt-6 flex items-start gap-3 rounded-xl border px-4 py-3 text-sm font-medium sm:mx-8"></p>

                {{-- Week, days remaining, trimester, pinking up --}}
                <div class="grid gap-5 p-6 sm:grid-cols-2 sm:p-8">
                    <div class="rounded-xl border border-line bg-surface-soft p-5 text-center">
                        <p class="text-sm font-semibold text-ink-muted">Current stage</p>
                        <p data-result-week class="mt-2 inline-flex items-center rounded-full bg-primary-light px-4 py-1.5 font-heading text-lg font-bold text-primary-dark">
                            Week 5 of 9
                        </p>
                    </div>

                    <div class="rounded-xl border border-line bg-surface-soft p-5 text-center">
                        <p class="text-sm font-semibold text-ink-muted">Days remaining</p>
                        <p data-result-days class="mt-2 font-heading text-3xl font-extrabold tracking-tight text-ink">
                            28 days
                        </p>
                    </div>

                    <div class="rounded-xl border border-line bg-surface-soft p-5 text-center">
                        <p class="text-sm font-semibold text-ink-muted">Trimester</p>
                        <p data-result-trimester class="mt-2 font-heading text-lg font-bold text-ink">
                            Second
                        </p>
                    </div>

                    <div class="rounded-xl border border-line bg-surface-soft p-5 text-center">
                        <p class="text-sm font-semibold text-ink-muted">Pinking up</p>
                        <p data-result-pinking class="mt-2 font-heading text-lg font-bold text-ink">
                            1 September 2026
                        </p>
                        <p class="mt-1 text-xs text-ink-muted">Nipples redden, around day 21</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>

{{-- ══ 4. TIMELINE ═══════════════════════════════════════════════════════
     The nine weeks, rendered from config/pregnancy-weeks.php.

     All of it is in the markup, open or shut, rather than injected from a
     script — it is the substantial writing on this page, and content that
     only exists inside JavaScript is content a crawler may never read. The
     script's only job here is deciding which card is marked current, which
     are behind, and which are still ahead.

     Each card is a details/summary, so it expands with a keyboard and works
     with JavaScript switched off.
     ═══════════════════════════════════════════════════════════════════════ --}}
<section id="timeline" class="section-tight scroll-mt-24 bg-surface-soft">
    <div class="container-page max-w-3xl">
        <div class="text-center">
            <p class="eyebrow">Week by week</p>
            <h2 class="section-title">What happens, and when</h2>
            <p class="section-intro">
                A cat pregnancy runs about nine weeks. Run the calculator above
                and the week she is in now is marked here.
            </p>
        </div>

        {{-- The rail runs behind the cards; the dots sit on it. --}}
        <ol class="relative mt-10 space-y-4 sm:pl-8">
            <span aria-hidden="true"
                  class="absolute top-4 bottom-4 left-[15px] hidden w-px bg-line-strong sm:block"></span>

            @foreach (config('pregnancy-weeks') as $entry)
                <li data-week-card="{{ $entry['week'] }}" class="reveal relative">
                    {{-- Dot on the rail --}}
                    <span aria-hidden="true" data-week-dot
                          class="absolute top-6 -left-8 hidden size-[9px] rounded-full bg-line-strong ring-4 ring-surface-soft transition-colors sm:block"></span>

                    <details data-week-details
                             class="group rounded-2xl border border-line bg-surface shadow-sm transition duration-200 hover:border-line-strong open:shadow-md">
                        <summary class="flex cursor-pointer list-none items-center gap-4 p-5 marker:content-['']">
                            <span data-week-number
                                  class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary-light font-heading text-lg font-extrabold text-primary-dark transition-colors">
                                {{ $entry['week'] }}
                            </span>

                            <span class="min-w-0 flex-1">
                                <span class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <span class="font-heading text-lg font-bold text-ink">
                                        Week {{ $entry['week'] }} — {{ $entry['title'] }}
                                    </span>

                                    {{-- Filled in by the script once a week is known. --}}
                                    <span data-week-badge hidden
                                          class="rounded-full px-2.5 py-0.5 text-xs font-bold"></span>
                                </span>
                                <span class="mt-1 block text-sm text-ink-muted">
                                    {{ $entry['visible_signs'] }}
                                </span>
                            </span>

                            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-primary-light text-primary transition-transform duration-200 group-open:rotate-45">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                            </span>
                        </summary>

                        <div class="space-y-5 border-t border-line px-5 py-5">
                            <div>
                                <h3 class="font-heading text-sm font-bold tracking-wide text-ink uppercase">
                                    What happens
                                </h3>
                                <p class="mt-2 text-sm leading-relaxed text-ink-muted">
                                    {{ $entry['what_happens'] }}
                                </p>
                            </div>

                            <div>
                                <h3 class="font-heading text-sm font-bold tracking-wide text-ink uppercase">
                                    What you might see
                                </h3>
                                <p class="mt-2 text-sm leading-relaxed text-ink-muted">
                                    {{ $entry['visible_signs'] }}
                                </p>
                            </div>

                            <div>
                                <h3 class="font-heading text-sm font-bold tracking-wide text-ink uppercase">
                                    Care this week
                                </h3>
                                <ul class="mt-2 space-y-2">
                                    @foreach ($entry['care_tips'] as $tip)
                                        <li class="flex items-start gap-2.5 text-sm leading-relaxed text-ink-muted">
                                            <svg class="mt-1 size-3.5 shrink-0 text-accent" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="3"
                                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5"/>
                                            </svg>
                                            {{ $tip }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            @if ($entry['vet_action'])
                                <div class="flex items-start gap-3 rounded-xl border border-info-light bg-info-light p-4">
                                    <svg class="mt-0.5 size-4 shrink-0 text-info" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" aria-hidden="true">
                                        <path d="M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z"/>
                                        <path d="m9.4 12.2 1.9 1.9 3.6-3.7"/>
                                    </svg>
                                    <span class="text-sm leading-relaxed">
                                        <span class="font-bold text-ink">Vet:</span>
                                        <span class="text-ink-muted">{{ $entry['vet_action'] }}</span>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </details>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ══ 5. FAQ ══════════════════════════════════════════════════════════
     Rendered into the markup, which is what lets the FAQPage data on this
     page describe content a visitor can actually reach. details/summary, so
     it opens with a keyboard and works with scripting off.
     ═══════════════════════════════════════════════════════════════════════ --}}
<section id="faq" class="section-tight scroll-mt-24 bg-surface">
    <div class="container-page max-w-3xl">
        <div class="text-center">
            <p class="eyebrow">Common questions</p>
            <h2 class="section-title">Cat pregnancy, answered</h2>
            <p class="section-intro">
                The questions owners ask most once the calculator has given them
                a date.
            </p>
        </div>

        <div class="mt-10 space-y-3">
            @foreach (config('pregnancy-faq') as $item)
                <details id="{{ $item['id'] }}"
                         class="reveal group scroll-mt-24 rounded-xl border border-line bg-surface px-5 shadow-sm transition hover:border-line-strong open:shadow-md">
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

{{-- ══ 6. FOOTER NOTE ════════════════════════════════════════════════════ --}}
<section class="bg-surface pt-8 pb-14">
    <div class="container-page max-w-3xl">
        <p class="flex items-start gap-3 rounded-xl border border-line bg-surface-soft px-5 py-4 text-sm leading-relaxed text-ink-muted">
            <svg class="mt-0.5 size-4 shrink-0 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 9v4.5M12 17h.01"/>
                <path d="M10.3 3.9 2.4 17.5A2 2 0 0 0 4.1 20.5h15.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
            </svg>
            <span>This tool is for informational purposes only. Consult your vet.</span>
        </p>
    </div>
</section>

@push('scripts')
    {{-- The block below is left unparsed on purpose: a double brace anywhere
         in the script — a JSDoc type, a nested object literal — would be read
         as an echo and take the page down.

         Note the directive name is not written inside this comment. Blade
         pulls unparsed blocks out before it strips comments, so naming it here
         would open a block early and swallow this comment's own closing tag,
         which is exactly how it leaked onto the page once. --}}
    @verbatim
    <script>
        (() => {
            'use strict';

            // ══ GESTATION TABLE ════════════════════════════════════════════
            // Days from mating to birth, per breed. Keys match the option
            // values in the breed select; anything missing falls back to the
            // mixed-breed figure.
            const GESTATION_DAYS = {
                'abyssinian':         66,
                'bengal':             65,
                'british-shorthair':  65,
                'burmese':            64,
                'devon-rex':          65,
                'maine-coon':         66,
                'oriental-shorthair': 66,
                'persian':            65,
                'ragdoll':            65,
                'siamese':            63,
                'mixed':              65,
            };

            const DEFAULT_GESTATION = 65;   // mixed breed
            const BIRTH_WINDOW_DAYS = 4;    // due date give or take
            const PINKING_UP_DAY   = 21;    // nipples redden around here
            const TOTAL_WEEKS      = 9;
            const OVERDUE_DAY      = 72;    // past this, it is a vet call

            // How far the symptom estimate can be out, either way. Stated on
            // screen rather than buried, because an estimate presented as a
            // date is an estimate someone will plan around.
            const ESTIMATE_ACCURACY_DAYS = 5;

            // More corroborating signs means more confidence in the floor the
            // estimate rests on — never certainty.
            const CONFIDENCE_LEVELS = [
                { min: 4, label: 'Good',     width: 100, tone: 'accent',  note: 'Several signs agree, so the estimate rests on more than one observation.' },
                { min: 2, label: 'Moderate', width: 66,  tone: 'warning', note: 'A couple of signs to go on. Ticking more will sharpen the estimate.' },
                { min: 1, label: 'Low',      width: 33,  tone: 'danger',  note: 'One sign only. This is a rough floor, not a due date to plan around.' },
            ];

            const MS_PER_DAY = 86400000;

            // A deliberate pause before the result appears. The arithmetic is
            // instant; this is presentation, so it is skipped for anyone who
            // has asked for reduced motion rather than making them wait for a
            // spinner they cannot see the point of.
            const CALCULATING_MS = 800;
            const COPIED_FEEDBACK_MS = 2000;

            // ══ ELEMENTS ═══════════════════════════════════════════════════
            const form        = document.getElementById('calculator-form');
            const results     = document.getElementById('results');
            const errorBox    = document.querySelector('[data-error]');
            const warningBox  = document.querySelector('[data-result-warning]');
            const toggle      = document.querySelector('[data-unknown-toggle]');
            const panel       = document.getElementById('unknown-date-panel');

            const matingInput = document.getElementById('mating-date');
            const breedInput  = document.getElementById('cat-breed');
            const nameInput   = document.getElementById('cat-name');
            const symptomInputs = document.querySelectorAll('[data-symptom]');
            const dateField     = document.querySelector('[data-date-field]');
            const confidence    = {
                box:   document.querySelector('[data-confidence]'),
                label: document.querySelector('[data-confidence-label]'),
                bar:   document.querySelector('[data-confidence-bar]'),
                note:  document.querySelector('[data-confidence-note]'),
            };
            const dueBanner = document.querySelector('[data-result-due-date]').closest('div');

            const button = {
                el:      document.querySelector('[data-calculate]'),
                label:   document.querySelector('[data-calculate-label]'),
                arrow:   document.querySelector('[data-calculate-arrow]'),
                spinner: document.querySelector('[data-calculate-spinner]'),
            };

            const copy = {
                el:     document.querySelector('[data-copy]'),
                label:  document.querySelector('[data-copy-label]'),
                status: document.querySelector('[data-copy-status]'),
            };

            const prefersReducedMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;

            const weekCards = document.querySelectorAll('[data-week-card]');

            const out = {
                dueDate:   document.querySelector('[data-result-due-date]'),
                window:    document.querySelector('[data-result-window]'),
                week:      document.querySelector('[data-result-week]'),
                days:      document.querySelector('[data-result-days]'),
                trimester: document.querySelector('[data-result-trimester]'),
                pinking:   document.querySelector('[data-result-pinking]'),
            };

            if (!form) return;

            // ══ DATE HELPERS ═══════════════════════════════════════════════

            /**
             * Parse a YYYY-MM-DD value as a LOCAL date.
             *
             * new Date('2026-08-18') is parsed as UTC midnight, which lands on
             * the 17th for anyone west of Greenwich — a whole day of error in
             * a tool whose entire job is counting days.
             */
            const parseLocalDate = (value) => {
                const [year, month, day] = value.split('-').map(Number);
                return new Date(year, month - 1, day);
            };

            /** Today at local midnight, so day arithmetic is not skewed by the clock. */
            const startOfToday = () => {
                const now = new Date();
                return new Date(now.getFullYear(), now.getMonth(), now.getDate());
            };

            const addDays = (date, days) => {
                const copy = new Date(date);
                copy.setDate(copy.getDate() + days);
                return copy;
            };

            /** Whole days between two local midnights. */
            const daysBetween = (from, to) => Math.round((to - from) / MS_PER_DAY);

            const formatDate = (date) => date.toLocaleDateString('en-GB', {
                day: 'numeric', month: 'long', year: 'numeric',
            });

            const formatShort = (date) => date.toLocaleDateString('en-GB', {
                day: 'numeric', month: 'short',
            });

            // ══ CALCULATION ════════════════════════════════════════════════

            /** Gestation length for the chosen breed. */
            const gestationFor = (breed) => GESTATION_DAYS[breed] ?? DEFAULT_GESTATION;

            /** Which of the nine weeks a given day count falls in, clamped. */
            const weekFor = (daysElapsed) =>
                Math.min(Math.max(Math.floor(daysElapsed / 7) + 1, 1), TOTAL_WEEKS);

            /** Weeks 1-3 first, 4-6 second, 7-9 third. */
            const trimesterFor = (week) => {
                if (week <= 3) return 'First';
                if (week <= 6) return 'Second';
                return 'Third';
            };

            /** Every ticked symptom, as its earliest possible day. */
            const tickedDays = () =>
                [...symptomInputs].filter((box) => box.checked)
                                  .map((box) => Number(box.dataset.minDay));

            /**
             * Work out the mating date from the form.
             *
             * Two modes. With a mating date it is arithmetic. Without one, the
             * latest sign she is showing sets a floor for how far along she is
             * — a cat with a day-55 sign is at least day 55 — and mating is
             * placed that many days back from today.
             *
             * The floor is the honest reading: she may be further along than
             * the signs prove, never less far.
             *
             * @returns an object with either date and approximate, or error
             */
            const resolveMatingDate = () => {
                const today = startOfToday();

                if (toggle && toggle.checked) {
                    const days = tickedDays();

                    if (days.length === 0) {
                        return { error: 'Tick at least one sign you have noticed, or untick the box and give the mating date.' };
                    }

                    const estimatedDay = Math.max(...days);

                    return {
                        date: addDays(today, -estimatedDay),
                        approximate: true,
                        estimatedDay,
                        signCount: days.length,
                    };
                }

                if (!matingInput.value) {
                    return { error: 'Please enter the mating date, or tick the box if you do not know it.' };
                }

                const mating = parseLocalDate(matingInput.value);

                if (Number.isNaN(mating.getTime())) {
                    return { error: 'That date could not be read. Please pick one from the calendar.' };
                }

                if (mating > today) {
                    return { error: 'The mating date is in the future. Please check it and try again.' };
                }

                return { date: mating, approximate: false };
            };

            // ══ CONFIDENCE ═════════════════════════════════════════════════
            const TONE_CLASSES = {
                accent:  { text: 'text-accent',  bar: 'bg-accent' },
                warning: { text: 'text-warning', bar: 'bg-warning' },
                danger:  { text: 'text-danger',  bar: 'bg-danger' },
            };

            /** Redraws the confidence meter from however many boxes are ticked. */
            const paintConfidence = () => {
                const count = tickedDays().length;

                if (count === 0) {
                    confidence.box.hidden = true;
                    return;
                }

                const level = CONFIDENCE_LEVELS.find((l) => count >= l.min);
                const tone = TONE_CLASSES[level.tone];

                confidence.box.hidden = false;
                confidence.label.textContent = `${level.label} — ${count} ${count === 1 ? 'sign' : 'signs'}`;
                confidence.label.className = `text-sm font-bold ${tone.text}`;
                confidence.bar.className = `h-full rounded-full transition-all duration-300 ${tone.bar}`;
                confidence.bar.style.width = `${level.width}%`;
                confidence.note.textContent = level.note;
            };

            const showError = (message) => {
                errorBox.textContent = message;
                errorBox.hidden = false;
                results.hidden = true;

                // Back to neutral: leaving last run's highlight up beside an
                // error would be pointing at a week we no longer stand behind.
                paintTimeline(null);
            };

            const clearError = () => {
                errorBox.hidden = true;
                errorBox.textContent = '';
            };

            // ══ TIMELINE ═══════════════════════════════════════════════════

            /**
             * Marks each week card as behind, current or ahead.
             *
             * The cards and their text are already in the page; this only
             * changes how they are presented. Called with null to put them
             * back to neutral, which is the state before anything is
             * calculated.
             */
            const paintTimeline = (currentWeek) => {
                weekCards.forEach((card) => {
                    const week = Number(card.dataset.weekCard);
                    const dot = card.querySelector('[data-week-dot]');
                    const number = card.querySelector('[data-week-number]');
                    const badge = card.querySelector('[data-week-badge]');
                    const details = card.querySelector('[data-week-details]');

                    // Reset everything this function is allowed to touch, so
                    // recalculating never leaves marks from the last run.
                    card.classList.remove('opacity-60');
                    dot.className = dot.className.replace(/bg-\S+/, 'bg-line-strong');
                    number.className = number.className
                        .replace(/bg-\S+/, 'bg-primary-light')
                        .replace(/text-primary\b|text-primary-dark|text-ink-inverse/, 'text-primary-dark');
                    details.classList.remove('border-primary', 'ring-2', 'ring-primary/20');
                    badge.hidden = true;
                    badge.textContent = '';
                    badge.className = 'rounded-full px-2.5 py-0.5 text-xs font-bold';

                    if (currentWeek === null) return;

                    if (week < currentWeek) {
                        // Behind: faded, and closed if the script opened it.
                        card.classList.add('opacity-60');
                        badge.hidden = false;
                        badge.textContent = 'Done';
                        badge.className += ' bg-surface-soft text-ink-muted';
                        details.open = false;
                    } else if (week === currentWeek) {
                        dot.className = dot.className.replace(/bg-\S+/, 'bg-primary-vivid');
                        number.className = number.className
                            .replace(/bg-\S+/, 'bg-primary-vivid')
                            .replace(/text-primary-dark/, 'text-ink');
                        details.classList.add('border-primary', 'ring-2', 'ring-primary/20');
                        badge.hidden = false;
                        badge.textContent = 'You are here';
                        badge.className += ' bg-primary-vivid text-ink';

                        // Open the week she is actually in — it is the one
                        // thing the visitor came for.
                        details.open = true;
                    } else {
                        badge.hidden = false;
                        badge.textContent = 'Ahead';
                        badge.className += ' bg-primary-light text-primary-dark';
                        details.open = false;
                    }
                });
            };

            // ══ RENDER ═════════════════════════════════════════════════════
            const render = () => {
                const resolved = resolveMatingDate();

                // calculate() has already rejected the error cases; this is a
                // guard, not the validation path.
                if (resolved.error) {
                    showError(resolved.error);
                    return;
                }

                clearError();

                const mating      = resolved.date;
                const today       = startOfToday();
                const gestation   = gestationFor(breedInput.value);

                const dueDate     = addDays(mating, gestation);
                const windowStart = addDays(dueDate, -BIRTH_WINDOW_DAYS);
                const windowEnd   = addDays(dueDate, BIRTH_WINDOW_DAYS);
                const pinkingDate = addDays(mating, PINKING_UP_DAY);

                const daysElapsed   = daysBetween(mating, today);
                const daysRemaining = daysBetween(today, dueDate);
                const week          = weekFor(daysElapsed);

                // --- due date and window ---
                // Amber rather than the usual purple when the date came from
                // symptoms, so an estimate never looks like a measurement.
                dueBanner.classList.toggle('bg-primary-vivid', !resolved.approximate);
                dueBanner.classList.toggle('bg-warning', resolved.approximate);

                out.dueDate.textContent = formatDate(dueDate);
                out.window.textContent  = resolved.approximate
                    ? `Estimated window: ${formatShort(windowStart)} – ${formatDate(windowEnd)}`
                    : `Likely birth window: ${formatShort(windowStart)} – ${formatDate(windowEnd)}`;

                // --- stage ---
                out.week.textContent      = `Week ${week} of ${TOTAL_WEEKS}`;
                out.trimester.textContent = `${trimesterFor(week)} trimester`;

                // --- days remaining, which can be negative ---
                if (daysRemaining > 0) {
                    out.days.textContent = daysRemaining === 1 ? '1 day' : `${daysRemaining} days`;
                } else if (daysRemaining === 0) {
                    out.days.textContent = 'Due today';
                } else {
                    const over = Math.abs(daysRemaining);
                    out.days.textContent = `${over} ${over === 1 ? 'day' : 'days'} overdue`;
                }

                // --- pinking up: only ahead of us if it has not passed ---
                out.pinking.textContent = formatDate(pinkingDate);

                // --- warnings ---
                // Past day 72 a cat is beyond any normal gestation, and that
                // is a call to the vet rather than a number on a screen.
                // Red is kept for the one case that needs acting on. An
                // estimate is a caveat, not a problem, and colouring it like an
                // error teaches people to ignore the colour that matters.
                const WARNING_BASE = 'mx-6 mt-6 flex items-start gap-3 rounded-xl border px-4 py-3 text-sm font-medium sm:mx-8';

                if (daysElapsed > OVERDUE_DAY) {
                    warningBox.className = `${WARNING_BASE} border-danger/30 bg-danger-light text-danger`;
                    warningBox.textContent = `It has been ${daysElapsed} days since mating, which is past the normal range for a cat. Please contact your vet.`;
                    warningBox.hidden = false;
                } else if (resolved.approximate) {
                    warningBox.className = `${WARNING_BASE} border-warning/30 bg-warning-light text-warning`;
                    warningBox.textContent = `This is a symptom-based estimate, from ${resolved.signCount} ${resolved.signCount === 1 ? 'sign' : 'signs'} putting her at around day ${resolved.estimatedDay}. Accuracy ±${ESTIMATE_ACCURACY_DAYS} days. Consult your vet for confirmation.`;
                    warningBox.hidden = false;
                } else {
                    warningBox.hidden = true;
                }

                paintTimeline(week);

                // What the copy button will put on the clipboard. Built here,
                // where the figures are, rather than scraped back out of the
                // DOM afterwards.
                copyText = [
                    nameInput.value.trim()
                        ? `${nameInput.value.trim()} — cat pregnancy`
                        : 'Cat pregnancy',
                    `Due date: ${formatDate(dueDate)}`,
                    `Birth window: ${formatDate(windowStart)} to ${formatDate(windowEnd)}`,
                    `Currently: week ${week} of ${TOTAL_WEEKS}, ${trimesterFor(week).toLowerCase()} trimester`,
                    resolved.approximate ? 'Symptom-based estimate, accuracy ±5 days.' : '',
                ].filter(Boolean).join('\n');

                results.hidden = false;
                results.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            };

            // ══ SUBMIT ═════════════════════════════════════════════════════
            let copyText = '';
            let busy = false;

            const setBusy = (state) => {
                busy = state;
                button.el.disabled = state;
                button.label.textContent = state ? 'Calculating…' : 'Calculate due date';

                // toggleAttribute, not .hidden. SVGElement has no hidden IDL
                // property — assigning to it creates a meaningless expando
                // while the attribute stays exactly where it was, so the
                // spinner would never have appeared and the arrow never left.
                button.arrow.toggleAttribute('hidden', state);
                button.spinner.toggleAttribute('hidden', !state);
            };

            const calculate = () => {
                if (busy) return;

                // Validation runs immediately — there is nothing premium about
                // waiting eight hundred milliseconds to be told a field is empty.
                const resolved = resolveMatingDate();

                if (resolved.error) {
                    showError(resolved.error);
                    return;
                }

                if (prefersReducedMotion) {
                    render();
                    return;
                }

                setBusy(true);
                setTimeout(() => {
                    setBusy(false);
                    render();
                }, CALCULATING_MS);
            };

            // ══ COPY ═══════════════════════════════════════════════════════
            const showCopied = (message) => {
                copy.label.textContent = message;
                copy.status.textContent = message;
                setTimeout(() => { copy.label.textContent = 'Copy the dates'; }, COPIED_FEEDBACK_MS);
            };

            const copyToClipboard = async () => {
                if (!copyText) return;

                try {
                    await navigator.clipboard.writeText(copyText);
                    showCopied('Copied');
                } catch {
                    // Clipboard access is refused on insecure origins and in
                    // some embedded browsers. Say so plainly instead of
                    // silently doing nothing.
                    showCopied('Press Ctrl+C to copy');
                    const range = document.createRange();
                    range.selectNodeContents(out.dueDate);
                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    selection.addRange(range);
                }
            };

            // ══ EVENTS ═════════════════════════════════════════════════════
            if (toggle && panel) {
                toggle.addEventListener('change', () => {
                    panel.hidden = !toggle.checked;
                    // The two inputs are alternatives, so only one is offered
                    // at a time rather than leaving a dead field on screen.
                    dateField.hidden = toggle.checked;
                    clearError();
                    paintConfidence();
                });
            }

            symptomInputs.forEach((box) => box.addEventListener('change', () => {
                clearError();
                paintConfidence();
            }));

            form.addEventListener('submit', calculate);
            copy.el.addEventListener('click', copyToClipboard);
        })();
    </script>
    @endverbatim
@endpush

</x-layouts.app>

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
        <div class="absolute -top-32 -left-24 size-96 rounded-full bg-primary opacity-10 blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 size-80 rounded-full bg-accent-vivid opacity-10 blur-3xl"></div>
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

            {{-- Mating date --}}
            <div class="mt-5">
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

                {{-- Secondary input, hidden until the toggle is checked --}}
                <div id="unknown-date-panel" hidden class="mt-4 border-t border-line pt-4">
                    <label for="signs-noticed" class="block text-sm font-semibold text-ink">
                        When did you first notice signs?
                    </label>
                    <select id="signs-noticed" name="signs-noticed"
                            class="mt-2 w-full rounded-xl border border-line bg-surface px-4 py-3 text-base text-ink shadow-sm transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        <option value="">Select a rough time</option>
                        <option value="1">Within the last week</option>
                        <option value="2">One to two weeks ago</option>
                        <option value="3">Two to four weeks ago</option>
                        <option value="4">Four to six weeks ago</option>
                        <option value="5">More than six weeks ago</option>
                    </select>
                </div>
            </div>

            {{-- Validation messages land here. role=alert so a screen reader
                 hears them without the focus being moved. --}}
            <p data-error role="alert" hidden
               class="mt-5 rounded-xl border border-danger/30 bg-danger-light px-4 py-3 text-sm font-medium text-danger"></p>

            <button type="submit" data-calculate class="btn-primary mt-6 w-full rounded-full sm:w-auto sm:px-8">
                Calculate due date
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
            </button>
        </form>

        {{-- ══ 3. RESULTS ════════════════════════════════════════════════════
             Hidden until Calculate is pressed. Every figure below is dummy data
             held in the markup — nothing here is computed yet.
             ═══════════════════════════════════════════════════════════════ --}}
        <section id="results" hidden aria-live="polite" class="mt-8">
            <div class="overflow-hidden rounded-2xl border border-line bg-surface shadow-md">

                {{-- Due date, prominent --}}
                <div class="bg-primary px-6 py-8 text-center sm:px-8">
                    <p class="text-sm font-semibold tracking-wide text-ink-inverse/80 uppercase">
                        Estimated due date
                    </p>
                    <p data-result-due-date class="mt-2 font-heading text-4xl font-extrabold tracking-tight text-ink-inverse sm:text-5xl">
                        12 October 2026
                    </p>
                    <p data-result-window class="mt-3 text-base text-ink-inverse/85">
                        Likely birth window: 9 – 15 October 2026
                    </p>
                </div>

                {{-- An overdue or past-term pregnancy says so here, above the
                     figures, because it is the only thing that matters then. --}}
                <p data-result-warning hidden role="alert"
                   class="mx-6 mt-6 flex items-start gap-3 rounded-xl border border-danger/30 bg-danger-light px-4 py-3 text-sm font-medium text-danger sm:mx-8"></p>

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
     Week 1 to Week 9, static placeholders. No week is marked as current yet —
     that arrives with the calculation.
     ═══════════════════════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface-soft">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">Week by week</p>
            <h2 class="section-title">What happens, and when</h2>
            <p class="section-intro">
                A cat pregnancy runs about nine weeks. Here is the shape of it.
            </p>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @for ($week = 1; $week <= 9; $week++)
                <article class="reveal rounded-2xl border border-line bg-surface p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md"
                         data-week="{{ $week }}">
                    <div class="flex items-center gap-3">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary-light font-heading text-lg font-extrabold text-primary-dark">
                            {{ $week }}
                        </span>
                        <h3 class="font-heading text-lg font-bold text-ink">Week {{ $week }}</h3>
                    </div>

                    <p class="mt-4 text-sm leading-relaxed text-ink-muted">
                        Placeholder text for week {{ $week }}. What to expect, what to
                        watch for, and what your cat needs at this stage.
                    </p>
                </article>
            @endfor
        </div>
    </div>
</section>

{{-- ══ 5. FOOTER NOTE ════════════════════════════════════════════════════ --}}
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
    {{-- @verbatim so Blade leaves the JavaScript alone. Without it, any "{{"
         in the script — a JSDoc type, a nested object literal — is compiled as
         a Blade echo and the page dies with a parse error. --}}
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

            // Roughly how long ago mating was, if the owner only knows when
            // signs appeared. Signs show around the pinking-up mark, so the
            // midpoint of each range is pushed back by that much.
            const DAYS_SINCE_SIGNS = { '1': 3, '2': 10, '3': 21, '4': 35, '5': 49 };

            const MS_PER_DAY = 86400000;

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
            const signsInput  = document.getElementById('signs-noticed');

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

            /**
             * Work out the mating date from the form.
             *
             * The unknown-date path was not specified, so it is treated as an
             * estimate rather than dropped: the ranges ask when signs were
             * first noticed, and signs appear around the pinking-up mark, so
             * mating is estimated as that many days ago plus 21.
             *
             * @returns {{date: Date, approximate: boolean}|{error: string}}
             */
            const resolveMatingDate = () => {
                const today = startOfToday();

                if (toggle && toggle.checked) {
                    const choice = signsInput ? signsInput.value : '';

                    if (!choice) {
                        return { error: 'Choose roughly when you first noticed signs, or untick the box and give the mating date.' };
                    }

                    const daysAgo = DAYS_SINCE_SIGNS[choice] + PINKING_UP_DAY;
                    return { date: addDays(today, -daysAgo), approximate: true };
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

            const showError = (message) => {
                errorBox.textContent = message;
                errorBox.hidden = false;
                results.hidden = true;
            };

            const clearError = () => {
                errorBox.hidden = true;
                errorBox.textContent = '';
            };

            // ══ RENDER ═════════════════════════════════════════════════════
            const calculate = () => {
                const resolved = resolveMatingDate();

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
                out.dueDate.textContent = formatDate(dueDate);
                out.window.textContent  = resolved.approximate
                    ? `Roughly ${formatShort(windowStart)} – ${formatDate(windowEnd)}`
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
                if (daysElapsed > OVERDUE_DAY) {
                    warningBox.textContent = `It has been ${daysElapsed} days since mating, which is past the normal range for a cat. Please contact your vet.`;
                    warningBox.hidden = false;
                } else if (resolved.approximate) {
                    warningBox.textContent = 'This is estimated from when you noticed signs, so treat the dates as approximate.';
                    warningBox.hidden = false;
                } else {
                    warningBox.hidden = true;
                }

                results.hidden = false;
                results.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            };

            // ══ EVENTS ═════════════════════════════════════════════════════
            if (toggle && panel) {
                toggle.addEventListener('change', () => {
                    panel.hidden = !toggle.checked;
                    clearError();
                });
            }

            form.addEventListener('submit', calculate);
        })();
    </script>
    @endverbatim
@endpush

</x-layouts.app>

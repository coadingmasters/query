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
                        <option value="">Select a breed</option>
                        <option value="domestic-shorthair">Domestic Shorthair</option>
                        <option value="persian">Persian</option>
                        <option value="siamese">Siamese</option>
                        <option value="maine-coon">Maine Coon</option>
                        <option value="bengal">Bengal</option>
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

                {{-- Current week and days remaining --}}
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
    <script>
        // UI only. The toggle shows and hides its panel; the button reveals the
        // results section with the dummy values already in the markup. No
        // arithmetic happens here yet.
        (() => {
            const toggle = document.querySelector('[data-unknown-toggle]');
            const panel = document.getElementById('unknown-date-panel');
            const form = document.getElementById('calculator-form');
            const results = document.getElementById('results');

            if (toggle && panel) {
                toggle.addEventListener('change', () => {
                    panel.hidden = !toggle.checked;
                });
            }

            if (form && results) {
                form.addEventListener('submit', () => {
                    results.hidden = false;
                    // Bring the result into view rather than leaving it to be
                    // found — on a phone it opens below the fold.
                    results.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                });
            }
        })();
    </script>
@endpush

</x-layouts.app>

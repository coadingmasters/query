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
                    <li><a href="/#tools" class="transition-colors hover:text-primary">Tools</a></li>
                    <li aria-hidden="true">/</li>
                    <li class="font-medium text-ink">Cat Vaccination Tracker</li>
                </ol>
            </nav>

            <h1 class="mt-4 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
                Cat Vaccination Tracker
            </h1>

            <p class="mt-4 max-w-lg text-base leading-relaxed text-ink-muted sm:text-lg">
                Enter your cat's birth date and lifestyle, tell us what they have
                already had, and get a complete FVRCP, rabies and FeLV schedule
                with next due dates, plus a record you can print or save.
            </p>

            <ul class="mt-6 flex flex-wrap gap-x-6 gap-y-3">
                @foreach ([
                    ['AAFP & WSAVA guidelines', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z'],
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
                <x-img name="cat-vaccination-tracker-hero"
                       alt="Veterinarian examining a calm tabby cat"
                       sizes="(min-width: 1024px) 46vw, 90vw" :priority="true"/>
            </div>
        </div>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-10 w-full text-surface sm:h-16" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ 2. TRACKER ════════════════════════════════════════════════════════ --}}
<section class="bg-surface pt-4 pb-10 lg:pb-14 print:hidden">
    <div class="container-page grid max-w-6xl gap-8 lg:grid-cols-[minmax(0,1fr)_19rem] lg:items-start lg:gap-10">
    <div>

        {{-- Progress bar --}}
        <div data-vax-progress class="mb-6">
            <div class="flex items-center justify-between text-sm font-semibold text-ink-muted">
                <span data-vax-step-label>Step 1 of 3</span>
                <span data-vax-step-name>Your cat's profile</span>
            </div>
            <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-surface-section">
                <div data-vax-progress-bar class="h-full rounded-full bg-primary-vivid transition-[width] duration-300" style="width: 33%"></div>
            </div>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-6 shadow-md sm:p-7">

            {{-- ── STEP 1: PROFILE ─────────────────────────────────────── --}}
            <div data-vax-panel="1">
                <div class="flex items-center gap-4">
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink">Your cat's profile</h2>
                        <p class="mt-1 text-sm text-ink-muted">Nothing is sent anywhere. This runs entirely in your browser.</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="vax-name" class="block text-sm font-semibold text-ink">Cat's name <span class="font-normal text-ink-muted">(optional)</span></label>
                    <input id="vax-name" type="text" maxlength="40" placeholder="e.g. Biscuit"
                           class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                </div>

                <div class="mt-5">
                    <span class="block text-sm font-semibold text-ink">Cat type</span>
                    <div class="mt-2 grid grid-cols-2 gap-3">
                        @foreach ([['kitten', 'Kitten', 'Under 1 year'], ['adult', 'Adult Cat', '1 year or older']] as $i => [$value, $label, $hint])
                            <label class="flex cursor-pointer flex-col items-center gap-1 rounded-xl border border-line-strong bg-surface px-4 py-5 text-center transition has-checked:border-primary has-checked:bg-primary-light has-checked:ring-1 has-checked:ring-primary">
                                <input type="radio" name="vax-cat-type" value="{{ $value }}" class="sr-only" @checked($i === 0)>
                                <span class="font-heading text-base font-bold text-ink">{{ $label }}</span>
                                <span class="text-xs text-ink-muted">{{ $hint }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-5">
                    <label for="vax-dob" class="block text-sm font-semibold text-ink">Date of birth</label>
                    <input id="vax-dob" type="date"
                           class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                    <label class="mt-2.5 flex cursor-pointer items-center gap-2 text-sm text-ink-muted">
                        <input type="checkbox" id="vax-dob-unknown" class="size-4 rounded border-line-strong text-primary focus:ring-primary/30">
                        I don't know the exact date
                    </label>

                    <div data-vax-approx-panel hidden class="mt-3">
                        <label for="vax-approx-age" class="block text-sm font-semibold text-ink">Approximate age</label>
                        <select id="vax-approx-age"
                                class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            <option value="21">Under 6 weeks</option>
                            <option value="49">6 to 8 weeks</option>
                            <option value="70">8 to 12 weeks</option>
                            <option value="98">12 to 16 weeks</option>
                            <option value="150">4 to 6 months</option>
                            <option value="270">6 to 12 months</option>
                            <option value="730">1 to 3 years</option>
                            <option value="1825">3 to 7 years</option>
                            <option value="3650">7+ years (senior)</option>
                        </select>
                        <p class="mt-1.5 text-xs text-ink-muted">Used to estimate a birth date for scheduling. It is a starting point, not a diagnosis.</p>
                    </div>
                </div>

                <div class="mt-5">
                    <span class="block text-sm font-semibold text-ink">Living situation</span>
                    <div class="mt-2 grid gap-2 sm:grid-cols-3">
                        @foreach ([
                            ['indoor', 'Indoor Only', 'M3 10.5 12 3l9 7.5M5 9.5V21h5v-6h4v6h5V9.5'],
                            ['mixed', 'Indoor + Some Outdoor', 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
                            ['outdoor', 'Outdoor / Free-Roaming', 'M12 3v3m6.4.6-2.1 2.1M21 12h-3M17.4 17.4l-2.1-2.1M12 18v3M8.7 15.3l-2.1 2.1M6 12H3M8.7 8.7 6.6 6.6'],
                        ] as $i => [$value, $label, $d])
                            <label class="flex cursor-pointer flex-col items-center gap-1.5 rounded-xl border border-line-strong bg-surface px-3 py-3 text-center transition has-checked:border-primary has-checked:bg-primary-light has-checked:ring-1 has-checked:ring-primary">
                                <input type="radio" name="vax-living" value="{{ $value }}" class="sr-only" @checked($i === 0)>
                                <svg class="size-5 text-ink-muted transition group-has-checked:text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $d }}"/></svg>
                                <span class="text-xs font-semibold text-ink">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-5">
                    <span class="block text-sm font-semibold text-ink">Household</span>
                    <div class="mt-2 grid gap-2 sm:grid-cols-2">
                        @foreach ([
                            ['vax-multicat', 'Multi-cat household'],
                            ['vax-felv-household', 'Lives with an FeLV-positive cat'],
                            ['vax-boarding', 'Goes to boarding/grooming regularly'],
                            ['vax-shows', 'Attends cat shows'],
                        ] as [$id, $label])
                            <label for="{{ $id }}" class="flex cursor-pointer items-start gap-2.5 rounded-xl border border-line-strong bg-surface px-3.5 py-3 text-sm text-ink transition has-checked:border-primary has-checked:bg-primary-light">
                                <input type="checkbox" id="{{ $id }}" class="mt-0.5 size-4 shrink-0 rounded border-line-strong text-primary focus:ring-primary/30">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <p data-vax-error-1 hidden role="alert" class="mt-4 rounded-xl border border-danger/30 bg-danger-light px-4 py-3 text-sm font-medium text-danger"></p>

                <div class="mt-6 flex justify-end">
                    <button type="button" data-vax-next="1" class="btn-primary rounded-full px-8">
                        Next: vaccination history
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                    </button>
                </div>
            </div>

            {{-- ── STEP 2: HISTORY ─────────────────────────────────────── --}}
            <div data-vax-panel="2" hidden>
                <div class="flex items-center gap-4">
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 12.5 11 15l4.5-5.5M5 19V6.5A2.5 2.5 0 0 1 7.5 4h9A2.5 2.5 0 0 1 19 6.5V19l-3.5-2-3.5 2-3.5-2L5 19Z"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink">Vaccination history</h2>
                        <p class="mt-1 text-sm text-ink-muted">Mark what your cat has already had. Not sure? Choose "Due / unknown".</p>
                    </div>
                </div>

                <div data-vax-checklist class="mt-6 space-y-5"></div>

                <p data-vax-error-2 hidden role="alert" class="mt-4 rounded-xl border border-danger/30 bg-danger-light px-4 py-3 text-sm font-medium text-danger"></p>

                <div class="mt-6 flex items-center justify-between">
                    <button type="button" data-vax-back="2" class="btn-outline rounded-full px-6">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m15 6-6 6 6 6"/></svg>
                        Back
                    </button>
                    <button type="button" data-vax-next="2" class="btn-primary rounded-full px-8">
                        Build the record
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                    </button>
                </div>
            </div>

            {{-- ── STEP 3: RESULTS ─────────────────────────────────────── --}}
            <div data-vax-panel="3" hidden>
                <div data-vax-results></div>

                <div class="mt-6 flex justify-start">
                    <button type="button" data-vax-back="3" class="btn-outline rounded-full px-6">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m15 6-6 6 6 6"/></svg>
                        Back to history
                    </button>
                </div>
            </div>

        </div>

    </div>

    {{-- ── Sidebar: what to expect, why it matters, trust ─────────────── --}}
    <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">
        <div class="rounded-2xl border border-line bg-surface-section p-5">
            <h2 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">How this works</h2>
            <ol class="mt-3.5 space-y-4">
                @foreach ([
                    ['1', 'Your cat\'s profile', 'Age, living situation and household, so we know which vaccines actually apply.'],
                    ['2', 'Vaccination history', 'Mark what has already been given. Not sure about one? Leave it as due.'],
                    ['3', 'Full record', 'A color-coded schedule, suggested visits, and a card you can print or save.'],
                ] as [$n, $title, $body])
                    <li class="flex gap-3">
                        <span class="flex size-6 shrink-0 items-center justify-center rounded-full bg-primary-light text-xs font-bold text-primary">{{ $n }}</span>
                        <div>
                            <p class="text-sm font-bold text-ink">{{ $title }}</p>
                            <p class="mt-0.5 text-xs leading-relaxed text-ink-muted">{{ $body }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <h2 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Why FeLV and Bordetella appear or don't</h2>
            <p class="mt-2 text-xs leading-relaxed text-ink-muted">
                FVRCP and rabies show for every cat: they are core vaccines under
                AAFP guidance. FeLV, Bordetella and Chlamydophila only show up
                when they apply to your cat, based on age, whether they go
                outside, and the household you described in step 1.
            </p>
        </div>

        <div class="rounded-2xl border border-line bg-surface-soft p-5 text-center shadow-sm">
            <span class="mx-auto flex size-11 items-center justify-center rounded-2xl bg-surface shadow-sm">
                <svg class="size-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9 12.5 11 15l4.5-5.5M5 19V6.5A2.5 2.5 0 0 1 7.5 4h9A2.5 2.5 0 0 1 19 6.5V19l-3.5-2-3.5 2-3.5-2L5 19Z"/>
                </svg>
            </span>
            <p class="mt-3 text-sm font-bold text-ink">AAFP 2020 &amp; WSAVA 2024</p>
            <p class="mt-1 text-xs leading-relaxed text-ink-muted">
                Every schedule and interval on this page traces back to a
                named source, listed in full below the tool.
            </p>
        </div>
    </aside>

    </div>
</section>

{{-- ══ 2.5 PRINT-ONLY RECORD (populated by JS, visible only when printing) ══ --}}
<div data-vax-print-card class="hidden print:block"></div>

{{-- ══ 3. SEO CONTENT ════════════════════════════════════════════════════ --}}
<section class="bg-surface-section py-10 lg:py-14 print:hidden">
    <div class="container-page grid max-w-6xl gap-8 lg:grid-cols-[minmax(0,1fr)_19rem] lg:gap-10">
    <div>
        <div data-article class="article">

            <h2 id="schedule-guide">Cat Vaccination Schedule: Complete Guide</h2>
            <p>
                Feline vaccines split into two groups. <strong>Core vaccines</strong>,
                FVRCP and rabies, are recommended for every cat regardless of
                lifestyle, because the diseases they prevent are common, severe,
                or a legal requirement. <strong>Lifestyle (non-core) vaccines</strong>,
                like FeLV, Bordetella and Chlamydophila, are recommended based on
                a cat's actual risk: whether they go outside, live with other
                cats, or are boarded regularly.
            </p>
            <p>
                That split, and the schedules below, follow the
                <a href="https://catvets.com/guidelines/practice-guidelines/feline-vaccination-guidelines" target="_blank" rel="noopener">2020 AAFP Feline Vaccination Advisory Panel report</a>,
                the most recent comprehensive US guideline on feline vaccination.
            </p>

            <h2 id="kitten-schedule">Kitten Vaccination Schedule (Week by Week)</h2>
            <div class="my-7 overflow-hidden rounded-2xl border border-line">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-4 py-3 font-semibold">Age</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Vaccine</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ([
                            ['6 to 8 weeks', 'FVRCP (dose 1)', 'First core vaccine'],
                            ['10 to 12 weeks', 'FVRCP (dose 2), FeLV (dose 1)', 'FeLV if outdoor, multi-cat, or simply as a kitten-core vaccine'],
                            ['14 to 16 weeks', 'FVRCP (dose 3), FeLV (dose 2)', 'Third FVRCP dose is standard for kittens'],
                            ['12 to 16 weeks', 'Rabies', 'Legally required in most US states'],
                            ['1 year', 'FVRCP booster, rabies booster, FeLV booster', 'Closes out the kitten series'],
                            ['Ongoing', 'Per adult schedule', 'FVRCP every 3 years, rabies per local law, FeLV annually if at risk'],
                        ] as [$age, $vaccine, $notes])
                            <tr>
                                <td class="px-4 py-3 text-ink">{{ $age }}</td>
                                <td class="px-4 py-3 font-medium text-ink">{{ $vaccine }}</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $notes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p>
                Kittens get a series rather than one shot because of maternal
                antibody interference. A nursing kitten carries antibodies from
                their mother that fade at an unpredictable rate, anywhere from 6
                to 16 weeks old. While those antibodies are still present, they
                can neutralize a vaccine before it has a chance to work, without
                actually protecting the kitten for long. Spacing doses every
                three to four weeks through that window means at least one dose
                lands after maternal antibodies have faded enough to let it work.
            </p>
            <p class="text-sm text-ink-muted">
                Sources: 2020 AAFP Feline Vaccination Advisory Panel Report,
                <a href="https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center/health-information/feline-health-topics/vaccinations" target="_blank" rel="noopener">Cornell Feline Health Center</a>.
            </p>

            <h2 id="adult-schedule">Adult Cat Vaccination Schedule</h2>
            <div class="my-7 overflow-hidden rounded-2xl border border-line">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-4 py-3 font-semibold">Vaccine</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Frequency</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ([
                            ['FVRCP', 'Every 3 years', 'After the kitten series and a 1-year booster'],
                            ['Rabies', 'Every 1 to 3 years', 'Set by the product used and by state law, not by preference'],
                            ['FeLV', 'Annually if at risk', 'Outdoor access, multi-cat household, or an FeLV-positive housemate'],
                            ['Bordetella', 'Annually', 'Cats boarded or groomed regularly'],
                            ['Chlamydophila', 'Annually', 'Multi-cat households with a history of outbreaks'],
                        ] as [$vaccine, $frequency, $notes])
                            <tr>
                                <td class="px-4 py-3 font-medium text-ink">{{ $vaccine }}</td>
                                <td class="px-4 py-3 text-ink">{{ $frequency }}</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $notes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p>
                <strong>Cats with an unknown vaccination history</strong> are
                treated as unprotected. The standard approach under AAHA/AAFP
                guidance is two FVRCP doses three to four weeks apart, plus a
                rabies dose, then the normal adult cycle from there, rather than
                assuming an unknown past means a cat is already covered.
            </p>

            <h2 id="indoor-vs-outdoor">Indoor vs Outdoor Cats: Which Vaccines Do They Need?</h2>
            <div class="my-7 overflow-hidden rounded-2xl border border-line">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-4 py-3 font-semibold">Vaccine</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Indoor only</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Outdoor / indoor+outdoor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        <tr><td class="px-4 py-3 font-medium text-ink">FVRCP</td><td class="px-4 py-3 text-ink">Required</td><td class="px-4 py-3 text-ink">Required</td></tr>
                        <tr><td class="px-4 py-3 font-medium text-ink">Rabies</td><td class="px-4 py-3 text-ink">Required (law)</td><td class="px-4 py-3 text-ink">Required (law)</td></tr>
                        <tr><td class="px-4 py-3 font-medium text-ink">FeLV</td><td class="px-4 py-3 text-ink">Optional after age 1</td><td class="px-4 py-3 text-ink">Strongly recommended, annually</td></tr>
                        <tr><td class="px-4 py-3 font-medium text-ink">Bordetella</td><td class="px-4 py-3 text-ink">Boarding only</td><td class="px-4 py-3 text-ink">Recommended</td></tr>
                    </tbody>
                </table>
            </div>
            <p>
                Indoor cats still need the core vaccines because the diseases
                they prevent do not require a trip outdoors. FVRCP-related
                viruses spread through airborne droplets and shared surfaces, and
                a carrier walking in on shoes or clothing is a real if uncommon
                route. Rabies exposure most often comes from a bat getting into
                a home, and an indoor cat that slips out an open door or window
                even once is exposed to the same risk as one that lives outside.
            </p>
            <p class="text-sm text-ink-muted">
                Sources: 2020 AAFP Feline Vaccination Advisory Panel Report, WSAVA Vaccination Guidelines.
            </p>

            <h2 id="core-vaccines">Core Vaccines Explained: FVRCP, Rabies, FeLV</h2>

            <h3 id="fvrcp">FVRCP: What It Covers</h3>
            <p>
                FVRCP is a combination vaccine against three separate pathogens.
                <strong>FHV-1</strong> (feline herpesvirus, rhinotracheitis)
                causes upper respiratory infection and can lead to lifelong
                intermittent flare-ups. <strong>FCV</strong> (calicivirus) causes
                oral ulcers and respiratory signs, and some strains are severe.
                <strong>FPV</strong> (panleukopenia, feline distemper) is the most
                dangerous of the three: unvaccinated kittens that catch it have a
                reported mortality rate above 90%. The kitten series plus a
                booster at one year, then every three years in adulthood, is the
                standard protection against all three. Common non-adjuvanted
                options include Nobivac and Purevax.
            </p>

            <h3 id="rabies-explained">Rabies</h3>
            <p>
                Rabies vaccination is a legal requirement in most US states, even
                for cats that never go outdoors, because rabies is fatal once
                symptoms appear and is transmissible to people. Products come in
                1-year and 3-year formulations, and which one your vet uses, plus
                what your state or county requires, decides the actual interval;
                the product label does not automatically override local law.
            </p>

            <h3 id="felv-explained">FeLV (Feline Leukemia Virus)</h3>
            <p>
                FeLV suppresses the immune system and is spread through close,
                sustained contact: mutual grooming, shared food and water bowls,
                or bite wounds. The 2020 AAFP guidelines classify it as core for
                every kitten under a year old, since a kitten's future lifestyle
                and exposure risk are hard to predict early on. For adults, it
                becomes a lifestyle vaccine, recommended for cats with outdoor
                access or multi-cat households, and it is often skipped for
                cats confirmed strictly indoor and single-cat. A cat should be
                tested with a combination FeLV/FIV test (commonly the SNAP test)
                before their first dose.
            </p>

            <h2 id="non-core">Non-Core (Lifestyle) Vaccines</h2>
            <div class="my-7 overflow-hidden rounded-2xl border border-line">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-4 py-3 font-semibold">Vaccine</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Who needs it</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Schedule</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        <tr><td class="px-4 py-3 font-medium text-ink">Bordetella</td><td class="px-4 py-3 text-ink-muted">Boarding, multi-cat households</td><td class="px-4 py-3 text-ink-muted">Annual, intranasal</td></tr>
                        <tr><td class="px-4 py-3 font-medium text-ink">Chlamydophila</td><td class="px-4 py-3 text-ink-muted">Multi-cat households with outbreak history</td><td class="px-4 py-3 text-ink-muted">Annual</td></tr>
                        <tr><td class="px-4 py-3 font-medium text-ink">FIV</td><td class="px-4 py-3 text-ink-muted">High-risk outdoor, unneutered males</td><td class="px-4 py-3 text-ink-muted">3-dose series, less commonly used now</td></tr>
                        <tr><td class="px-4 py-3 font-medium text-ink">FIP</td><td class="px-4 py-3 text-ink-muted">Generally not recommended</td><td class="px-4 py-3 text-ink-muted">Low demonstrated efficacy</td></tr>
                    </tbody>
                </table>
            </div>

            <h2 id="injection-site-safety">Injection Site Safety: The 3-2-3 Rule</h2>
            <p>
                Feline injection-site sarcoma (FISS) is a rare but serious tumor
                that can develop at a vaccination site, historically linked to
                inflammation from older adjuvanted vaccines. Modern
                non-adjuvanted vaccines have measurably lowered the risk, and the
                risk that remains is still far smaller than the risk of the
                diseases these vaccines prevent, which is why the guidance below
                is about monitoring, not skipping vaccination.
            </p>
            <p>AAFP-recommended injection sites, standardized so a reaction can be traced to the vaccine that caused it:</p>
            <ul>
                @foreach ($injectionSites as $site)
                    <li><strong>{{ $site['vaccine'] }}</strong>: {{ $site['site'] }}.</li>
                @endforeach
            </ul>
            <p>
                <strong>The 3-2-3 rule</strong>, from the Vaccine-Associated
                Feline Sarcoma Task Force, flags a lump at an injection site for
                biopsy if any of these are true: it is still there <strong>3
                months</strong> after the injection, it is <strong>2
                centimeters</strong> or larger, or it is still growing <strong>3
                months</strong> after the injection. A lump that shrinks and
                disappears within a few weeks is a normal, common reaction and
                not a 3-2-3 concern.
            </p>
            <p class="text-sm text-ink-muted">
                Source: AAFP/VAFSTF Vaccine-Associated Feline Sarcoma Guidelines.
            </p>

            <h2 id="records">How to Keep Cat Vaccination Records</h2>
            <p>
                A complete record notes the vaccine name, manufacturer, lot
                number, injection site, date given, and the vet or clinic that
                gave it. That level of detail matters more than it seems: a
                boarding facility or groomer will often ask for proof, a border
                crossing or airline can require it, cat shows have their own
                entry requirements, and a new vet meeting your cat for the first
                time works faster and safer with real history instead of "I
                think they're up to date."
            </p>
            <p>
                The tracker above builds this record as you go, and its printable
                card includes blank fields for the lot number and vet name your
                own vet will fill in at each visit. Keeping a paper copy in
                addition to whatever your vet's system stores is worth doing:
                clinic records do not always follow a cat that changes vets or
                moves.
            </p>

            <h2 id="faq">Frequently Asked Questions</h2>
            <div class="not-prose">
                @foreach ($faq as $item)
                    <details class="group mb-3 rounded-xl border border-line bg-surface px-5 shadow-sm transition hover:border-line-strong open:shadow-md" style="max-width: var(--measure)">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-4 font-heading font-bold text-ink marker:content-['']">
                            {{ $item['q'] }}
                            <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-primary-light text-primary transition-transform duration-200 group-open:rotate-45">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                            </span>
                        </summary>
                        <p class="pb-5 text-base leading-relaxed text-ink-muted">{{ $item['a'] }}</p>
                    </details>
                @endforeach
            </div>

        </div>
    </div>

    {{-- ── Sidebar: contents, quick reference, related ─────────────────── --}}
    <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">
        <nav aria-labelledby="vax-toc-heading" class="hidden rounded-2xl border border-line bg-surface p-5 lg:block">
            <h2 id="vax-toc-heading" class="font-heading text-sm font-bold tracking-wider text-ink uppercase">On this page</h2>
            <div data-vax-toc class="mt-3"></div>
        </nav>

        <div class="rounded-2xl border border-primary-light bg-primary-light p-5">
            <h2 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Injection sites at a glance</h2>
            <ul class="mt-3 space-y-2 text-sm text-ink">
                @foreach ($injectionSites as $site)
                    <li><strong>{{ $site['vaccine'] }}</strong>: {{ $site['site'] }}</li>
                @endforeach
            </ul>
            <p class="mt-3 text-xs leading-relaxed text-ink">
                A lump present after 3 months, 2cm or larger, or still growing at 3 months: call your vet.
            </p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <h2 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Related reading</h2>
            <ul class="mt-3 divide-y divide-line">
                @foreach ([
                    ['href' => route('tools.cat-age-calculator'), 'label' => 'Cat Age Calculator'],
                    ['href' => route('tools.cat-pregnancy-calculator'), 'label' => 'Cat Pregnancy Calculator'],
                    ['href' => route('blog.show', 'signs-your-cat-is-sick'), 'label' => 'Signs Your Cat Is Sick'],
                ] as $link)
                    <li>
                        <a href="{{ $link['href'] }}" class="flex items-center justify-between gap-2 py-2.5 text-sm font-semibold text-ink transition-colors hover:text-primary">
                            {{ $link['label'] }}
                            <svg class="size-4 shrink-0 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </aside>

    </div>
</section>

{{-- ══ 4. RELATED TOOLS ══════════════════════════════════════════════════ --}}
<section class="bg-surface pt-2 pb-8 lg:pb-10 print:hidden">
    <div class="container-page max-w-6xl">
        <h2 class="font-heading text-lg font-extrabold text-ink">Keep going</h2>
        <div class="mt-4 grid gap-4 sm:grid-cols-3">
            @foreach ([
                ['href' => route('tools.cat-age-calculator'), 'image' => 'cat-age-calculator-senior-tabby-cat', 'alt' => 'Cats at five life stages from kitten to senior', 'title' => 'Cat Age Calculator', 'blurb' => 'Human years and the life stage behind them.'],
                ['href' => route('tools.cat-pregnancy-calculator'), 'image' => 'cat-pregnancy-calculator-kitten', 'alt' => 'Newborn kitten curled up asleep', 'title' => 'Cat Pregnancy Calculator', 'blurb' => 'Due date and week-by-week milestones.'],
                ['href' => route('blog.show', 'signs-your-cat-is-sick'), 'image' => 'signs-cat-is-sick-hero', 'alt' => 'Tabby cat resting quietly, looking subdued', 'title' => 'Signs Your Cat Is Sick', 'blurb' => 'The early signs worth a call to the vet.'],
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
                            <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 5. SOURCES AND BYLINE ═════════════════════════════════════════════ --}}
<section class="bg-surface-section py-8 lg:py-10 print:hidden">
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
                <svg class="mt-0.5 size-4 shrink-0 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M10.3 3.9 2.5 17.5A2 2 0 0 0 4.2 20.5h15.6a2 2 0 0 0 1.7-3l-7.8-13.6a2 2 0 0 0-3.4 0Z"/>
                    <path d="M12 9.5v4M12 17h.01"/>
                </svg>
                <span>
                    This schedule is for reference only. Always confirm with your
                    veterinarian: local laws, especially for rabies, vary by
                    state and country, and your vet can account for your cat's
                    actual health and history.
                </span>
            </p>
        </div>
    </div>
</section>

<script type="application/json" data-vax-model>{!! json_encode($model, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

@push('scripts')
    <script>
        (() => {
            const modelTag = document.querySelector('[data-vax-model]');
            if (!modelTag) return;

            const MODEL = JSON.parse(modelTag.textContent);
            const $ = (sel, root = document) => root.querySelector(sel);
            const $$ = (sel, root = document) => [...root.querySelectorAll(sel)];
            const STORAGE_KEY = 'purrquery_vax_tracker';

            const STEP_NAMES = { 1: "Your cat's profile", 2: 'Vaccination history', 3: 'Vaccination record' };

            /* ── State ───────────────────────────────────────────────────
               doses: { [doseId]: { status: 'done'|'due'|'na', date: 'YYYY-MM-DD'|null } } */
            let state = {
                step: 1,
                name: '',
                catType: 'kitten',
                dob: '',
                dobUnknown: false,
                approxAgeDays: '49',
                living: 'indoor',
                multicat: false,
                felvHousehold: false,
                boarding: false,
                shows: false,
                doses: {},
            };

            const loadState = () => {
                try {
                    const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || 'null');
                    if (saved && typeof saved === 'object') state = { ...state, ...saved, step: 1 };
                } catch { /* ignore a corrupt or blocked store */ }
            };

            const saveState = () => {
                try { localStorage.setItem(STORAGE_KEY, JSON.stringify(state)); } catch { /* private mode, quota, etc. */ }
            };

            /* ── Date helpers ────────────────────────────────────────── */
            const addDays = (date, days) => {
                const d = new Date(date.getTime());
                d.setDate(d.getDate() + days);
                return d;
            };
            const parseDate = (value) => {
                if (!value) return null;
                const [y, m, d] = value.split('-').map(Number);
                const date = new Date(y, m - 1, d);
                return Number.isNaN(date.getTime()) ? null : date;
            };
            const startOfToday = () => { const t = new Date(); t.setHours(0, 0, 0, 0); return t; };
            const diffDays = (a, b) => Math.round((a.getTime() - b.getTime()) / 86400000);
            const formatDate = (date) => date
                ? date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
                : '-';
            const toInputValue = (date) => {
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const d = String(date.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
            };

            const getDob = () => {
                if (!state.dobUnknown && state.dob) return parseDate(state.dob);
                if (state.dobUnknown) return addDays(startOfToday(), -parseInt(state.approxAgeDays, 10));
                return null;
            };

            /* ── Which vaccines and doses apply ─────────────────────── */
            const vaccineApplies = (vaccine) => {
                switch (vaccine.condition) {
                    case 'always': return true;
                    case 'kitten_or_outdoor_or_multicat':
                        return state.catType === 'kitten' || state.living !== 'indoor' || state.multicat || state.felvHousehold;
                    case 'boarding': return state.boarding;
                    case 'multicat': return state.multicat;
                    default: return true;
                }
            };

            const applicableDoses = (vaccine) =>
                vaccine.doses.filter(dose => !dose.kitten_only || state.catType === 'kitten');

            const applicableVaccines = () =>
                Object.entries(MODEL.vaccines)
                    .filter(([, vaccine]) => vaccineApplies(vaccine))
                    .map(([key, vaccine]) => ({ key, ...vaccine, doses: applicableDoses(vaccine) }));

            /* ── Step 1: profile form <-> state ─────────────────────── */
            const form1 = {
                name: $('#vax-name'),
                dob: $('#vax-dob'),
                dobUnknown: $('#vax-dob-unknown'),
                approxPanel: $('[data-vax-approx-panel]'),
                approxAge: $('#vax-approx-age'),
                multicat: $('#vax-multicat'),
                felvHousehold: $('#vax-felv-household'),
                boarding: $('#vax-boarding'),
                shows: $('#vax-shows'),
            };

            const applyStateToStep1 = () => {
                form1.name.value = state.name;
                $$('input[name="vax-cat-type"]').forEach(r => { r.checked = r.value === state.catType; });
                form1.dob.value = state.dob;
                form1.dobUnknown.checked = state.dobUnknown;
                form1.approxPanel.toggleAttribute('hidden', !state.dobUnknown);
                form1.approxAge.value = state.approxAgeDays;
                $$('input[name="vax-living"]').forEach(r => { r.checked = r.value === state.living; });
                form1.multicat.checked = state.multicat;
                form1.felvHousehold.checked = state.felvHousehold;
                form1.boarding.checked = state.boarding;
                form1.shows.checked = state.shows;
            };

            const readStep1IntoState = () => {
                state.name = form1.name.value.trim();
                state.catType = ($('input[name="vax-cat-type"]:checked') || {}).value || 'kitten';
                state.dob = form1.dob.value;
                state.dobUnknown = form1.dobUnknown.checked;
                state.approxAgeDays = form1.approxAge.value;
                state.living = ($('input[name="vax-living"]:checked') || {}).value || 'indoor';
                state.multicat = form1.multicat.checked;
                state.felvHousehold = form1.felvHousehold.checked;
                state.boarding = form1.boarding.checked;
                state.shows = form1.shows.checked;
            };

            form1.dobUnknown.addEventListener('change', () => {
                form1.approxPanel.toggleAttribute('hidden', !form1.dobUnknown.checked);
            });

            /* ── Step 2: checklist ───────────────────────────────────── */
            const checklist = $('[data-vax-checklist]');

            const renderChecklist = () => {
                checklist.textContent = '';

                applicableVaccines().forEach(vaccine => {
                    const group = document.createElement('div');
                    group.className = 'rounded-xl border border-line p-4';

                    const heading = document.createElement('h3');
                    heading.className = 'font-heading text-base font-bold text-ink';
                    heading.textContent = vaccine.label;
                    group.append(heading);

                    if (vaccine.note) {
                        const note = document.createElement('p');
                        note.className = 'mt-1 text-xs leading-relaxed text-ink-muted';
                        note.textContent = vaccine.note;
                        group.append(note);
                    }

                    const rows = document.createElement('div');
                    rows.className = 'mt-3 space-y-3';

                    vaccine.doses.forEach(dose => {
                        const saved = state.doses[dose.id] || { status: 'due', date: '' };

                        const row = document.createElement('div');
                        row.className = 'rounded-lg bg-surface-section p-3';

                        const top = document.createElement('div');
                        top.className = 'flex flex-wrap items-center justify-between gap-2';

                        const label = document.createElement('span');
                        label.className = 'text-sm font-semibold text-ink';
                        label.textContent = dose.label;
                        top.append(label);

                        const controls = document.createElement('div');
                        controls.className = 'flex gap-1.5';

                        [['done', 'Done'], ['due', 'Due / Unknown'], ['na', 'Not applicable']].forEach(([value, text]) => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.dataset.doseId = dose.id;
                            btn.dataset.status = value;
                            btn.textContent = text;
                            btn.className = statusButtonClass(value === saved.status);
                            controls.append(btn);
                        });
                        top.append(controls);
                        row.append(top);

                        const dateWrap = document.createElement('div');
                        dateWrap.className = 'mt-2';
                        dateWrap.hidden = saved.status !== 'done';
                        dateWrap.dataset.dateWrapFor = dose.id;

                        const dateLabel = document.createElement('label');
                        dateLabel.className = 'block text-xs font-semibold text-ink-muted';
                        dateLabel.textContent = 'Date given';
                        const dateInput = document.createElement('input');
                        dateInput.type = 'date';
                        dateInput.max = toInputValue(startOfToday());
                        dateInput.value = saved.date || '';
                        dateInput.dataset.dateFor = dose.id;
                        dateInput.className = 'mt-1 w-full max-w-[12rem] rounded-lg border border-line-strong bg-surface px-3 py-2 text-sm text-ink focus:border-primary focus:ring-1 focus:ring-primary/30 focus:outline-none';

                        dateWrap.append(dateLabel, dateInput);
                        row.append(dateWrap);
                        rows.append(row);
                    });

                    group.append(rows);
                    checklist.append(group);
                });
            };

            function statusButtonClass(active) {
                return active
                    ? 'rounded-full bg-primary-vivid px-3 py-1.5 text-xs font-bold text-ink'
                    : 'rounded-full border border-line-strong bg-surface px-3 py-1.5 text-xs font-semibold text-ink-muted transition hover:border-primary hover:text-primary';
            }

            checklist.addEventListener('click', (event) => {
                const btn = event.target.closest('button[data-dose-id]');
                if (!btn) return;

                const { doseId, status } = btn.dataset;
                state.doses[doseId] = { status, date: state.doses[doseId]?.date || '' };

                $$(`button[data-dose-id="${doseId}"]`).forEach(b => {
                    b.className = statusButtonClass(b.dataset.status === status);
                });
                const wrap = $(`[data-date-wrap-for="${doseId}"]`);
                if (wrap) wrap.hidden = status !== 'done';

                saveState();
            });

            checklist.addEventListener('change', (event) => {
                const input = event.target.closest('input[data-date-for]');
                if (!input) return;
                const doseId = input.dataset.dateFor;
                state.doses[doseId] = { status: 'done', date: input.value };
                saveState();
            });

            /* ── The schedule engine ─────────────────────────────────── */
            const buildSchedule = () => {
                const dob = getDob();
                const today = startOfToday();
                const rows = [];

                applicableVaccines().forEach(vaccine => {
                    let prevDate = null;

                    vaccine.doses.forEach(dose => {
                        const saved = state.doses[dose.id] || { status: 'due', date: '' };
                        let dueDate = null;
                        let dateGiven = null;

                        if (saved.status === 'done' && saved.date) {
                            dateGiven = parseDate(saved.date);
                            prevDate = dateGiven;
                        } else if (saved.status !== 'na') {
                            if (dose.from_prev_days != null && prevDate) {
                                dueDate = addDays(prevDate, dose.from_prev_days);
                            } else if (dose.from_dob_days != null && dob) {
                                dueDate = addDays(dob, dose.from_dob_days);
                            }
                            // Not given, but the chain still has to move forward:
                            // the next dose is timed from when this one is due,
                            // not from whichever earlier dose was last actually
                            // given. Without this every later dose in an
                            // unfinished series collapsed onto the same date.
                            if (dueDate) prevDate = dueDate;
                        }

                        let tone = 'gray';
                        let statusLabel = 'Not applicable';

                        if (saved.status === 'na') {
                            tone = 'gray';
                            statusLabel = 'Not applicable';
                        } else if (saved.status === 'done') {
                            tone = 'green';
                            statusLabel = 'Given';
                        } else if (!dueDate) {
                            tone = 'red';
                            statusLabel = 'Not started';
                        } else if (diffDays(dueDate, today) < 0) {
                            tone = 'red';
                            statusLabel = 'Overdue';
                        } else if (diffDays(dueDate, today) <= 30) {
                            tone = 'yellow';
                            statusLabel = 'Due soon';
                        } else {
                            tone = 'green';
                            statusLabel = 'Scheduled';
                        }

                        rows.push({
                            vaccine: vaccine.label,
                            dose: dose.label,
                            status: saved.status,
                            statusLabel,
                            tone,
                            dateGiven,
                            dueDate,
                            daysUntil: dueDate ? diffDays(dueDate, today) : null,
                            note: vaccine.note || null,
                        });
                    });
                });

                return rows;
            };

            const TONE_CLASSES = {
                green: 'bg-accent-light text-accent-dark',
                yellow: 'bg-warning-light text-warning',
                red: 'bg-danger-light text-danger',
                gray: 'bg-surface-section text-ink-muted',
            };
            const ROW_BG = {
                green: 'bg-accent-light/40',
                yellow: 'bg-warning-light/50',
                red: 'bg-danger-light/40',
                gray: 'bg-surface-section/60',
            };

            const escapeHtml = (s) => String(s).replace(/[&<>"']/g, c => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
            }[c]));

            const renderResults = () => {
                const rows = buildSchedule();
                const active = rows.filter(r => r.status !== 'na');

                const upToDate = active.filter(r => r.tone === 'green').length;
                const dueSoon = active.filter(r => r.tone === 'yellow').length;
                const overdue = active.filter(r => r.tone === 'red').length;

                const catLabel = state.name || 'Your cat';
                const dob = getDob();

                /* Upcoming visits: not-done rows, grouped by identical due date. */
                const pending = active.filter(r => r.status !== 'done' && r.dueDate);
                const noDatePending = active.filter(r => r.status !== 'done' && !r.dueDate);
                const visitMap = new Map();
                pending.sort((a, b) => a.dueDate - b.dueDate).forEach(r => {
                    const key = toInputValue(r.dueDate);
                    if (!visitMap.has(key)) visitMap.set(key, []);
                    visitMap.get(key).push(r);
                });

                const results = $('[data-vax-results]');
                results.innerHTML = `
                    <div class="text-center">
                        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">${escapeHtml(catLabel)}'s Vaccination Record</h2>
                        <p class="mt-1 text-sm text-ink-muted">Based on AAFP 2020 and WSAVA 2024 guidelines</p>
                    </div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-xl ${TONE_CLASSES.green} px-4 py-3 text-center">
                            <p class="text-2xl font-extrabold">${upToDate}</p>
                            <p class="text-xs font-semibold">Up to date</p>
                        </div>
                        <div class="rounded-xl ${TONE_CLASSES.yellow} px-4 py-3 text-center">
                            <p class="text-2xl font-extrabold">${dueSoon}</p>
                            <p class="text-xs font-semibold">Due soon (30 days)</p>
                        </div>
                        <div class="rounded-xl ${TONE_CLASSES.red} px-4 py-3 text-center">
                            <p class="text-2xl font-extrabold">${overdue}</p>
                            <p class="text-xs font-semibold">Overdue / not started</p>
                        </div>
                    </div>

                    <div class="mt-6 overflow-x-auto rounded-2xl border border-line">
                        <table class="w-full min-w-[640px] text-left text-sm">
                            <thead>
                                <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                                    <th scope="col" class="px-4 py-3 font-semibold">Vaccine</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Status</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Date given</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Next due</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Days until due</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                ${rows.map(r => `
                                    <tr class="${ROW_BG[r.tone]}">
                                        <td class="px-4 py-3 font-medium text-ink">${escapeHtml(r.dose)}</td>
                                        <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-xs font-bold ${TONE_CLASSES[r.tone]}">${r.statusLabel}</span></td>
                                        <td class="px-4 py-3 text-ink-muted">${r.dateGiven ? formatDate(r.dateGiven) : '-'}</td>
                                        <td class="px-4 py-3 text-ink-muted">${r.status === 'done' ? '-' : formatDate(r.dueDate)}</td>
                                        <td class="px-4 py-3 text-ink-muted">${r.status !== 'done' && r.daysUntil !== null ? r.daysUntil : '-'}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <h3 class="font-heading text-base font-bold text-ink">Upcoming visits</h3>
                        ${visitMap.size === 0 && noDatePending.length === 0
                            ? '<p class="mt-2 text-sm text-ink-muted">Nothing scheduled. Everything is either given or not applicable.</p>'
                            : ''}
                        <div class="mt-2 space-y-2">
                            ${noDatePending.length > 0 ? `
                                <div class="rounded-xl border border-danger/30 bg-danger-light px-4 py-3">
                                    <p class="text-sm font-bold text-danger">As soon as possible</p>
                                    <p class="mt-0.5 text-sm text-ink">${noDatePending.map(r => escapeHtml(r.dose)).join(', ')}</p>
                                </div>
                            ` : ''}
                            ${[...visitMap.entries()].map(([key, group], i) => `
                                <div class="rounded-xl border border-line bg-surface-section px-4 py-3">
                                    <p class="text-sm font-bold text-ink">Visit ${i + 1} &mdash; suggested ${formatDate(parseDate(key))}</p>
                                    <p class="mt-0.5 text-sm text-ink-muted">${group.map(r => escapeHtml(r.dose)).join(', ')}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <div class="mt-6 rounded-xl border border-primary-light bg-primary-light p-4">
                        <p class="text-sm font-bold text-ink">AAFP injection site guidelines</p>
                        <ul class="mt-2 space-y-1 text-sm text-ink">
                            ${MODEL.injectionSites.map(s => `<li>&bull; ${escapeHtml(s.vaccine)} &rarr; ${escapeHtml(s.site)}</li>`).join('')}
                        </ul>
                        <p class="mt-2 text-sm text-ink">Record the site and lot number at every visit.</p>
                        <p class="mt-2 text-xs leading-relaxed text-ink-muted">
                            Any lump at an injection site still present after 3 months, 2cm or larger, or still growing at 3 months, is worth a call to your vet (the "3-2-3" rule).
                        </p>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <button type="button" data-vax-print class="btn-primary rounded-full px-6">
                            Print vaccination record
                        </button>
                        <button type="button" data-vax-copy class="btn-outline rounded-full px-6">
                            <span data-vax-copy-label>Copy summary to clipboard</span>
                        </button>
                    </div>

                    <p class="mt-5 text-sm leading-relaxed text-ink-muted">
                        This schedule is for reference only. Always confirm with your veterinarian, local laws (especially rabies) vary by state and country.
                    </p>

                    <div class="mt-5 grid gap-2 sm:grid-cols-2">
                        <a href="{{ route('tools.cat-age-calculator') }}" class="btn-outline w-full rounded-full text-center">
                            Cat's age in human years &rarr;
                        </a>
                        <a href="{{ route('tools.cat-pregnancy-calculator') }}" class="btn-outline w-full rounded-full text-center">
                            Is your cat pregnant? &rarr;
                        </a>
                    </div>
                `;

                buildPrintCard(rows, catLabel, dob);
                wireResultButtons(rows, catLabel);
            };

            /* ── Print card ──────────────────────────────────────────── */
            const buildPrintCard = (rows, catLabel, dob) => {
                const card = $('[data-vax-print-card]');
                card.innerHTML = `
                    <div style="padding:32px;font-family:sans-serif;color:#12383B;">
                        <div style="display:flex;justify-content:space-between;align-items:baseline;border-bottom:2px solid #F47C6B;padding-bottom:12px;">
                            <p style="margin:0;font-size:22px;font-weight:700;">${escapeHtml('{{ config('app.name') }}')}: Vaccination Record</p>
                            <span style="font-size:12px;color:#526568;">Printed ${formatDate(startOfToday())}</span>
                        </div>
                        <table style="width:100%;margin-top:16px;font-size:13px;">
                            <tr>
                                <td style="padding:4px 0;"><strong>Cat's name:</strong> ${escapeHtml(catLabel)}</td>
                                <td style="padding:4px 0;"><strong>Date of birth:</strong> ${dob ? formatDate(dob) : '____________'}</td>
                                <td style="padding:4px 0;"><strong>Breed:</strong> ____________</td>
                            </tr>
                        </table>
                        <table style="width:100%;margin-top:16px;border-collapse:collapse;font-size:12px;">
                            <thead>
                                <tr style="background:#FFF1EC;text-align:left;">
                                    <th style="border:1px solid #EDE7E1;padding:6px;">Vaccine</th>
                                    <th style="border:1px solid #EDE7E1;padding:6px;">Date given</th>
                                    <th style="border:1px solid #EDE7E1;padding:6px;">Lot #</th>
                                    <th style="border:1px solid #EDE7E1;padding:6px;">Vet</th>
                                    <th style="border:1px solid #EDE7E1;padding:6px;">Next due</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows.filter(r => r.status !== 'na').map(r => `
                                    <tr>
                                        <td style="border:1px solid #EDE7E1;padding:6px;">${escapeHtml(r.dose)}</td>
                                        <td style="border:1px solid #EDE7E1;padding:6px;">${r.dateGiven ? formatDate(r.dateGiven) : ''}</td>
                                        <td style="border:1px solid #EDE7E1;padding:6px;"></td>
                                        <td style="border:1px solid #EDE7E1;padding:6px;"></td>
                                        <td style="border:1px solid #EDE7E1;padding:6px;">${r.status === 'done' ? '' : (r.dueDate ? formatDate(r.dueDate) : '')}</td>
                                    </tr>
                                `).join('')}
                                ${Array.from({ length: 4 }).map(() => `
                                    <tr>
                                        <td style="border:1px solid #EDE7E1;padding:6px;height:22px;"></td>
                                        <td style="border:1px solid #EDE7E1;padding:6px;"></td>
                                        <td style="border:1px solid #EDE7E1;padding:6px;"></td>
                                        <td style="border:1px solid #EDE7E1;padding:6px;"></td>
                                        <td style="border:1px solid #EDE7E1;padding:6px;"></td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                        <p style="margin-top:16px;font-size:11px;color:#526568;">
                            This schedule is for reference only. Always confirm with your veterinarian; local laws (especially rabies) vary by state and country.
                            {{ config('app.url') }}/tools/cat-vaccination-tracker
                        </p>
                    </div>
                `;
            };

            const wireResultButtons = (rows, catLabel) => {
                $('[data-vax-print]')?.addEventListener('click', () => window.print());

                const copyBtn = $('[data-vax-copy]');
                copyBtn?.addEventListener('click', async () => {
                    const label = $('[data-vax-copy-label]');
                    const lines = [`${catLabel}'s vaccination record (purrquery.com)`, ''];
                    rows.filter(r => r.status !== 'na').forEach(r => {
                        const given = r.dateGiven ? `given ${formatDate(r.dateGiven)}` : 'not given';
                        const due = r.status === 'done' ? '' : `, next due ${r.dueDate ? formatDate(r.dueDate) : 'unknown'}`;
                        lines.push(`- ${r.dose}: ${given}${due}`);
                    });
                    try {
                        await navigator.clipboard.writeText(lines.join('\n'));
                        label.textContent = 'Copied';
                    } catch {
                        label.textContent = 'Press Ctrl+C to copy';
                    }
                    setTimeout(() => { label.textContent = 'Copy summary to clipboard'; }, 2000);
                });
            };

            /* ── Step navigation ─────────────────────────────────────── */
            const progressBar = $('[data-vax-progress-bar]');
            const stepLabel = $('[data-vax-step-label]');
            const stepName = $('[data-vax-step-name]');

            const showStep = (step) => {
                state.step = step;
                $$('[data-vax-panel]').forEach(panel => panel.toggleAttribute('hidden', panel.dataset.vaxPanel !== String(step)));
                progressBar.style.width = `${(step / 3) * 100}%`;
                stepLabel.textContent = `Step ${step} of 3`;
                stepName.textContent = STEP_NAMES[step];
                window.scrollTo({ top: $('[data-vax-progress]').getBoundingClientRect().top + window.scrollY - 90, behavior: 'smooth' });
            };

            $('[data-vax-next="1"]').addEventListener('click', () => {
                readStep1IntoState();
                const error = $('[data-vax-error-1]');

                if (!state.dobUnknown && !state.dob) {
                    error.textContent = "Enter your cat's date of birth, or check that you don't know it.";
                    error.removeAttribute('hidden');
                    return;
                }
                if (!state.dobUnknown && parseDate(state.dob) > startOfToday()) {
                    error.textContent = 'That date is in the future.';
                    error.removeAttribute('hidden');
                    return;
                }
                error.setAttribute('hidden', '');

                saveState();
                renderChecklist();
                showStep(2);
            });

            $('[data-vax-back="2"]').addEventListener('click', () => showStep(1));
            $('[data-vax-back="3"]').addEventListener('click', () => showStep(2));

            $('[data-vax-next="2"]').addEventListener('click', () => {
                saveState();
                renderResults();
                showStep(3);
            });

            /* ── Boot ────────────────────────────────────────────────── */
            loadState();
            applyStateToStep1();
            showStep(1);
        })();
    </script>

    <script>
        (() => {
            /* Contents sidebar, built from the headings actually in the
               article, same pattern as the blog post template. */
            const article = document.querySelector('[data-article]');
            const toc = document.querySelector('[data-vax-toc]');
            if (!article || !toc) return;

            const entries = [...article.querySelectorAll('h2[id]')]
                .map(h => ({ id: h.id, text: h.textContent.trim() }));
            if (!entries.length) return;

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

            toc.append(ol);
        })();
    </script>
@endpush

</x-layouts.app>

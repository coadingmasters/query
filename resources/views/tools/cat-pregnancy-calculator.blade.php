{{--
    Cat Pregnancy Calculator.

    The hero runs full width, then everything else sits in one two-column
    section: the tool and its writing on the left, a sticky sidebar on the
    right. Each panel below is commented so the next pass can target one
    without reading the whole file.
--}}
<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    // One heading style for every panel in the main column, so the whole page
    // reads as a single design rather than a tool with an article bolted on.
    $panelHeading = 'flex items-center gap-2.5 border-b-2 border-primary-vivid/25 pb-3 font-heading text-xl font-extrabold tracking-tight text-ink';

    // No controller-supplied list on this page, so the bottom grid builds its
    // own from the catalogue with this tool taken out of it.
    $moreTools = collect(config('catalog.tools'))
        ->reject(fn ($t) => $t['slug'] === 'cat-pregnancy-calculator')
        ->values();
@endphp

{{-- ══ 1. HEADER ═════════════════════════════════════════════════════════
     Tool title and one line saying what it does. The site header above it
     carries the logo and navigation.
     ═══════════════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft pb-12 lg:pb-14">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-24 size-96 rounded-full bg-primary-vivid opacity-[0.07] blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 size-80 rounded-full bg-accent-vivid opacity-[0.12] blur-3xl"></div>
        <x-paw-print class="paw absolute top-[22%] left-[6%] hidden size-10 text-primary sm:block [animation-duration:23s]"/>
        <x-paw-print class="paw absolute top-[28%] right-[7%] hidden size-8 text-accent-vivid sm:block [animation-delay:-8s] [animation-duration:20s]"/>
    </div>

    <div class="container-page relative py-10 text-center lg:py-12">
        <nav aria-label="Breadcrumb" class="flex justify-center">
            <ol class="flex items-center gap-2 text-sm">
                <li><a href="/" class="font-medium text-primary transition-colors hover:text-primary-hover">Home</a></li>
                <li aria-hidden="true" class="text-ink-muted">/</li>
                <li><a href="{{ route('tools.index') }}" class="font-medium text-primary transition-colors hover:text-primary-hover">Tools</a></li>
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

{{-- ══ 2. TOOL + SIDEBAR ═════════════════════════════════════════════════ --}}
<section class="bg-surface-section py-8 lg:py-12">
    <div class="mx-auto w-full max-w-[1600px] px-5 sm:px-8 lg:px-[50px]">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">

            {{-- ── Main column ──────────────────────────────────────────── --}}
            <div class="space-y-6">

                {{-- ══ INPUT ═════════════════════════════════════════════════
                     Cat name, breed, mating date, an unknown-date toggle that
                     reveals a secondary input, and the Calculate button.
                     ═══════════════════════════════════════════════════════ --}}
                <div id="calculator" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        Your cat’s details
                    </h2>

                    <p class="mt-4 text-sm text-ink-muted">
                        Nothing is sent anywhere. This runs entirely in your browser.
                    </p>

                    <form id="calculator-form" class="mt-5" onsubmit="return false">
                        <div class="grid gap-5 sm:grid-cols-2">
                            {{-- Cat name --}}
                            <div>
                                <label for="cat-name" class="block text-sm font-semibold text-ink">Cat’s name</label>
                                <input id="cat-name" name="cat-name" type="text" autocomplete="off" placeholder="Luna"
                                       class="mt-2 w-full rounded-xl border border-line bg-surface px-4 py-3 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            </div>

                            {{-- Breed: values match the gestation table in the script --}}
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
                        <div class="mt-5 rounded-xl border border-line p-4">
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
                                 checkbox with its label styled as a toggle. It keeps the
                                 keyboard behavior and the screen-reader semantics that a
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
                                                   class="group flex cursor-pointer items-start gap-3 rounded-xl border border-line bg-surface p-4 transition duration-150 hover:border-line-strong has-checked:border-primary has-checked:shadow-sm">
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
                           class="mt-5 border-l-2 border-danger py-1 pl-4 text-sm leading-relaxed font-medium text-danger"></p>

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
                </div>

                {{-- ══ RESULTS ═══════════════════════════════════════════════
                     Hidden until Calculate is pressed. The figures below are
                     placeholders in the markup; the script writes the real
                     ones in over them.
                     ═══════════════════════════════════════════════════════ --}}
                <section id="results" hidden aria-live="polite">
                    <div class="overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">

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
                             figures, because it is the only thing that matters then.
                             The script rewrites this class list, so it has to keep
                             matching WARNING_BASE below. --}}
                        <p data-result-warning hidden role="alert"
                           class="mx-6 mt-6 flex items-start gap-3 rounded-xl border px-4 py-3 text-sm font-medium sm:mx-8"></p>

                        {{-- Week, days remaining, trimester, pinking up --}}
                        <div class="grid gap-5 p-6 sm:grid-cols-2 sm:p-8">
                            <div class="rounded-xl border border-line p-5 text-center">
                                <p class="text-sm font-semibold text-ink-muted">Current stage</p>
                                <p data-result-week class="mt-2 inline-flex items-center rounded-full bg-primary-light px-4 py-1.5 font-heading text-lg font-bold text-primary-dark">
                                    Week 5 of 9
                                </p>
                            </div>

                            <div class="rounded-xl border border-line p-5 text-center">
                                <p class="text-sm font-semibold text-ink-muted">Days remaining</p>
                                <p data-result-days class="mt-2 font-heading text-3xl font-extrabold tracking-tight text-ink">
                                    28 days
                                </p>
                            </div>

                            <div class="rounded-xl border border-line p-5 text-center">
                                <p class="text-sm font-semibold text-ink-muted">Trimester</p>
                                <p data-result-trimester class="mt-2 font-heading text-lg font-bold text-ink">
                                    Second
                                </p>
                            </div>

                            <div class="rounded-xl border border-line p-5 text-center">
                                <p class="text-sm font-semibold text-ink-muted">Pinking up</p>
                                <p data-result-pinking class="mt-2 font-heading text-lg font-bold text-ink">
                                    1 September 2026
                                </p>
                                <p class="mt-1 text-xs text-ink-muted">Nipples redden, around day 21</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ══ TIMELINE ══════════════════════════════════════════════
                     The nine weeks, rendered from config/pregnancy-weeks.php.

                     All of it is in the markup, open or shut, rather than
                     injected from a script. It is the substantial writing on
                     this page, and content that only exists inside JavaScript
                     is content a crawler may never read. The script's only job
                     here is deciding which card is marked current, which are
                     behind, and which are still ahead.

                     Each card is a details/summary, so it expands with a
                     keyboard and works with JavaScript switched off.
                     ═══════════════════════════════════════════════════════ --}}
                <div id="timeline" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        What happens, and when
                    </h2>

                    <p class="mt-4 text-base leading-relaxed text-ink-muted">
                        A cat pregnancy runs about nine weeks. Run the calculator above
                        and the week she is in now is marked here.
                    </p>

                    {{-- The rail runs behind the cards; the dots sit on it. --}}
                    <ol class="relative mt-5 space-y-4 sm:pl-8">
                        <span aria-hidden="true"
                              class="absolute top-4 bottom-4 left-[15px] hidden w-px bg-line-strong sm:block"></span>

                        @foreach (config('pregnancy-weeks') as $entry)
                            <li data-week-card="{{ $entry['week'] }}" class="relative">
                                {{-- Dot on the rail --}}
                                <span aria-hidden="true" data-week-dot
                                      class="absolute top-6 -left-8 hidden size-[9px] rounded-full bg-line-strong ring-4 ring-surface transition-colors sm:block"></span>

                                <details data-week-details
                                         class="group rounded-xl border border-line bg-surface transition duration-200 hover:border-line-strong open:shadow-sm">
                                    <summary class="flex cursor-pointer list-none items-center gap-4 p-5 marker:content-['']">
                                        <span data-week-number
                                              class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary-light font-heading text-lg font-extrabold text-primary-dark transition-colors">
                                            {{ $entry['week'] }}
                                        </span>

                                        <span class="min-w-0 flex-1">
                                            <span class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                                <span class="font-heading text-lg font-bold text-ink">
                                                    Week {{ $entry['week'] }}: {{ $entry['title'] }}
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
                                            <ul class="mt-2 list-disc space-y-2 pl-5 text-sm leading-relaxed text-ink-muted marker:text-primary">
                                                @foreach ($entry['care_tips'] as $tip)
                                                    <li>{{ $tip }}</li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        @if ($entry['vet_action'])
                                            <p class="border-l-2 border-info py-1 pl-4 text-sm leading-relaxed">
                                                <span class="font-bold text-ink">Vet:</span>
                                                <span class="text-ink-muted">{{ $entry['vet_action'] }}</span>
                                            </p>
                                        @endif
                                    </div>
                                </details>
                            </li>
                        @endforeach
                    </ol>
                </div>

                {{-- ══ FAQ ═══════════════════════════════════════════════════
                     Rendered into the markup, which is what lets the FAQPage
                     data on this page describe content a visitor can actually
                     reach. details/summary, so it opens with a keyboard and
                     works with scripting off.
                     ═══════════════════════════════════════════════════════ --}}
                <div id="faq" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        Cat pregnancy, answered
                    </h2>

                    <p class="mt-4 text-base leading-relaxed text-ink-muted">
                        The questions owners ask most once the calculator has given them
                        a date.
                    </p>

                    <div class="mt-5 space-y-2.5">
                        @foreach (config('pregnancy-faq') as $item)
                            <details id="{{ $item['id'] }}"
                                     name="faq"
                                     class="group scroll-mt-24 border-b border-line last:border-b-0">
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
                </div>

                {{-- ══ FOOTER NOTE ═══════════════════════════════════════════ --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <p class="border-l-2 border-warning py-1 pl-4 text-sm leading-relaxed text-ink-muted">
                        This tool is for informational purposes only. Consult your vet.
                    </p>
                </div>
            </div>

            {{-- ── Sidebar ──────────────────────────────────────────────── --}}
            <x-tool-sidebar slug="cat-pregnancy-calculator" :toc="[
                ['id' => 'calculator', 'label' => 'Calculator'],
                ['id' => 'timeline', 'label' => 'Week by week'],
                ['id' => 'faq', 'label' => 'Common questions'],
            ]"/>
        </div>
    </div>
</section>

{{-- ══ 3. MORE TOOLS ═════════════════════════════════════════════════════ --}}
<section class="border-t border-line bg-surface py-10 lg:py-12">
    <div class="mx-auto w-full max-w-[1600px] px-5 sm:px-8 lg:px-[50px]">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">
                More Tools You Will Love
            </h2>
            <a href="{{ route('tools.index') }}" class="text-sm font-semibold text-primary transition hover:underline">
                View all tools
            </a>
        </div>

        {{-- A plain grid, not a scroller: five tools do not need paging, and a
             horizontal scroll container shows a scrollbar on most desktops. --}}
        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($moreTools->take(3) as $tool)
                <a href="{{ $tool['url'] }}"
                   class="card reveal group"
                   style="--reveal-delay: {{ $loop->index * 70 }}ms">
                    <div class="card-media aspect-[16/10]">
                        <x-img :name="$tool['image']" :alt="$tool['alt']"
                               class="transition-transform duration-500 group-hover:scale-105"
                               sizes="(max-width: 639px) 92vw, (max-width: 1023px) 46vw, 30vw"/>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title transition-colors group-hover:text-primary">{{ $tool['title'] }}</h3>
                        <p class="card-text line-clamp-2 flex-1">{{ $tool['blurb'] }}</p>
                        <span class="mt-auto inline-flex items-center gap-1.5 pt-4 text-sm font-semibold text-primary">
                            Open tool
                            <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- The remaining tools still get a real link from this page, just as
             text rather than another row of cards. --}}
        <ul class="mt-5 flex flex-wrap gap-x-5 gap-y-2 text-sm">
            @foreach ($moreTools->skip(3) as $tool)
                <li>
                    <a href="{{ $tool['url'] }}" class="font-semibold text-ink-muted transition hover:text-primary">
                        {{ $tool['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>

@push('scripts')
    {{-- The block below is left unparsed on purpose: a double brace anywhere
         in the script, whether a JSDoc type or a nested object literal, would be read
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
            // estimate rests on, never certainty.
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
             * the 17th for anyone west of Greenwich, a whole day of error in
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
             * a cat with a day-55 sign is at least day 55, and mating is
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
                confidence.label.textContent = `${level.label}: ${count} ${count === 1 ? 'sign' : 'signs'}`;
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

                        // Open the week she is actually in. It is the one
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
                // estimate is a caveat, not a problem, and coloring it like an
                // error teaches people to ignore the color that matters.
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
                        ? `${nameInput.value.trim()}: cat pregnancy`
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
                // property, so assigning to it creates a meaningless expando
                // while the attribute stays exactly where it was, so the
                // spinner would never have appeared and the arrow never left.
                button.arrow.toggleAttribute('hidden', state);
                button.spinner.toggleAttribute('hidden', !state);
            };

            const calculate = () => {
                if (busy) return;

                // Validation runs immediately. There is nothing premium about
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

<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    // One heading style for every panel in the main column, so the whole page
    // reads as a single design rather than a tool with an article bolted on.
    $panelHeading = 'flex items-center gap-2.5 border-b-2 border-primary-vivid/25 pb-3 font-heading text-xl font-extrabold tracking-tight text-ink';
@endphp

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-28 -left-20 size-80 rounded-full bg-primary-vivid opacity-[0.08] blur-3xl"></div>
        <div class="absolute -right-20 bottom-0 size-72 rounded-full bg-accent-vivid opacity-[0.12] blur-3xl"></div>
        <x-paw-print class="paw absolute top-[14%] left-[5%] hidden size-10 text-primary-vivid/25 lg:block [animation-duration:23s]"/>
        <x-paw-print class="paw absolute right-[46%] bottom-[10%] hidden size-7 text-accent-vivid/30 lg:block [animation-delay:-9s] [animation-duration:27s]"/>
    </div>

    <div class="relative mx-auto w-full max-w-[1600px] px-5 sm:px-8 lg:px-[50px]">
        <div class="grid items-center gap-8 pt-6 pb-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.85fr)]">
            <div class="reveal relative z-10">
                <nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
                    <ol class="flex flex-wrap items-center gap-1.5">
                        <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ route('tools.index') }}" class="transition-colors hover:text-primary">Tools</a></li>
                        <li aria-hidden="true">/</li>
                        <li class="font-medium text-ink">Cat Name Generator</li>
                    </ol>
                </nav>

                <h1 class="mt-4 flex flex-wrap items-center gap-3 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
                    Cat Name Generator
                    <x-paw-print class="size-8 shrink-0 text-primary-vivid"/>
                </h1>

                <p class="mt-3 font-heading text-lg font-bold text-primary sm:text-xl">
                    Find the perfect name for your new best friend.
                </p>

                <p class="mt-3 max-w-lg text-base leading-relaxed text-ink-muted">
                    150+ names, filtered by style, personality and even breed. Every
                    one comes with a real meaning behind it, not a made-up one.
                </p>

                <ul class="mt-6 flex flex-wrap gap-x-6 gap-y-3">
                    @foreach ([
                        ['Real name meanings', 'primary', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z'],
                        ['Free, no sign-up', 'accent', 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
                        ['Nothing leaves your browser', 'info', 'M5.5 5h13A1.5 1.5 0 0 1 20 6.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 15.5v-9A1.5 1.5 0 0 1 5.5 5Z'],
                    ] as [$label, $tone, $d])
                        <li class="group flex items-center gap-2 text-sm font-medium text-ink-muted">
                            <span @class([
                                'flex size-8 shrink-0 items-center justify-center rounded-full transition-transform duration-300 group-hover:scale-110',
                                'bg-primary-light text-primary' => $tone === 'primary',
                                'bg-accent-light text-accent-dark' => $tone === 'accent',
                                'bg-info-light text-info' => $tone === 'info',
                            ])>
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="{{ $d }}"/>
                                </svg>
                            </span>
                            {{ $label }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="reveal relative" style="--reveal-delay: 150ms">
                <div class="relative overflow-hidden rounded-[4rem_1.75rem_4rem_1.75rem] border-4 border-primary/15 bg-surface shadow-lg transition-transform duration-500 hover:-translate-y-1.5 sm:rounded-[5rem_2rem_5rem_2rem]">
                    <x-img name="cat-name-generator-cute-kitten" alt="Fluffy kitten beside a board of name ideas"
                           sizes="(max-width: 1023px) 92vw, 620px" :priority="true"/>
                </div>

                <div aria-hidden="true"
                     class="absolute -bottom-5 left-4 hidden items-center gap-3 rounded-2xl border border-line bg-surface px-4 py-3 shadow-lg sm:flex">
                    <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-accent-light text-accent-dark">
                        <x-paw-print class="size-5"/>
                    </span>
                    <span>
                        <span class="block font-heading text-sm font-extrabold text-ink">{{ count($inToolNames) }}+ names</span>
                        <span class="mt-0.5 block text-xs text-ink-muted">Every one with a real meaning</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ 2. TOOL + SIDEBAR ═════════════════════════════════════════════════ --}}
<section id="generator" class="scroll-mt-24 bg-surface-section py-8 lg:py-12"
         x-data="catNameGenerator({
             names: @js($names),
             breeds: @js($breeds),
             trending: @js($trendingNames),
             styleLabels: @js(collect($styles)->pluck('label', 'slug')),
             personalityLabels: @js(collect($personalities)->pluck('label', 'slug')),
             styleSlugs: @js(collect($styles)->pluck('slug')),
             personalitySlugs: @js(collect($personalities)->pluck('slug')),
             saveUrl: @js(route('tools.cat-name-generator.save')),
             csrfToken: @js(csrf_token()),
         })">
    <div class="mx-auto w-full max-w-[1600px] px-5 sm:px-8 lg:px-[50px]">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">

            {{-- ── Main column ──────────────────────────────────────────── --}}
            <div class="space-y-6">

                {{-- Generate a Name --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <div class="flex flex-wrap items-center justify-between gap-4 border-b-2 border-primary-vivid/25 pb-3">
                        <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink">Generate a Name</h2>

                        <label class="flex items-center gap-2.5 text-sm font-semibold text-ink">
                            <span>Two cats</span>
                            <button type="button" role="switch" x-on:click="twoCats = !twoCats"
                                    :aria-checked="twoCats ? 'true' : 'false'"
                                    :class="twoCats ? 'bg-primary-vivid' : 'bg-line-strong'"
                                    class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors duration-200">
                                <span :class="twoCats ? 'translate-x-5' : 'translate-x-1'"
                                      class="inline-block size-4 transform rounded-full bg-surface shadow transition-transform duration-200"></span>
                            </button>
                        </label>
                    </div>

                    {{-- Gender --}}
                    <fieldset class="mt-5">
                        <legend class="text-sm font-bold text-ink">
                            <span class="text-primary">1.</span> Gender
                        </legend>
                        <div class="mt-2.5 flex flex-wrap gap-2">
                            @foreach (['any' => 'Any', 'male' => 'Male', 'female' => 'Female', 'neutral' => 'Either'] as $value => $label)
                                <button type="button" x-on:click="gender = '{{ $value }}'"
                                        :class="gender === '{{ $value }}' ? 'bg-primary-vivid text-ink border-primary-vivid shadow-sm' : 'bg-surface text-ink-muted border-line hover:border-primary/40 hover:text-primary'"
                                        class="rounded-full border px-4 py-1.5 text-sm font-semibold transition">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </fieldset>

                    {{-- Style --}}
                    <fieldset class="mt-5">
                        <legend class="text-sm font-bold text-ink">
                            <span class="text-primary">2.</span> Style
                            <span class="font-medium text-ink-muted">(pick any number)</span>
                        </legend>
                        <div class="mt-2.5 flex flex-wrap gap-2">
                            @foreach ($styles as $style)
                                <button type="button" x-on:click="toggleStyle('{{ $style['slug'] }}')"
                                        :class="styles.includes('{{ $style['slug'] }}') ? 'bg-accent text-ink-inverse border-accent shadow-sm' : 'bg-surface text-ink-muted border-line hover:border-accent/40 hover:text-accent-dark'"
                                        class="rounded-full border px-4 py-1.5 text-sm font-semibold transition">
                                    {{ $style['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </fieldset>

                    {{-- Selects --}}
                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ([
                            ['3', 'personality', 'Personality', null],
                            ['4', 'breed', 'Breed', 'unique'],
                            ['5', 'letter', 'Starts with', null],
                            ['6', 'length', 'Length', null],
                        ] as [$num, $id, $label, $flag])
                            <div>
                                <label for="{{ $id }}" class="text-sm font-bold text-ink">
                                    <span class="text-primary">{{ $num }}.</span> {{ $label }}
                                    @if ($flag)
                                        <span class="text-xs font-semibold text-primary">({{ $flag }})</span>
                                    @endif
                                </label>

                                @if ($id === 'personality')
                                    <select id="personality" x-model="personality"
                                            class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                        <option value="">Any</option>
                                        @foreach ($personalities as $p)
                                            <option value="{{ $p['slug'] }}">{{ $p['label'] }}</option>
                                        @endforeach
                                    </select>
                                @elseif ($id === 'breed')
                                    <select id="breed" x-model="breedSlug"
                                            class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                        <option value="">Any / not sure</option>
                                        <template x-for="breed in breeds" :key="breed.slug">
                                            <option :value="breed.slug" x-text="breed.name"></option>
                                        </template>
                                    </select>
                                @elseif ($id === 'letter')
                                    <select id="letter" x-model="letter"
                                            class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                        <option value="">Any letter</option>
                                        <template x-for="l in 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('')" :key="l">
                                            <option :value="l" x-text="l"></option>
                                        </template>
                                    </select>
                                @else
                                    <select id="length" x-model="length"
                                            class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                        <option value="any">Any</option>
                                        <option value="short">Short</option>
                                        <option value="medium">Medium</option>
                                        <option value="long">Long</option>
                                    </select>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <p class="mt-4 text-xs text-ink-muted" x-show="poolCount() === 0" x-cloak>
                        No exact match for that combination. Generating will pick from the full list instead.
                    </p>

                    <div class="mt-6 flex justify-center">
                        <button type="button" x-on:click="generate()"
                                class="btn-primary w-full justify-center rounded-full py-3 text-base transition-transform duration-150 hover:scale-[1.02] active:scale-[0.98] sm:w-auto sm:px-14">
                            <x-paw-print class="size-5"/>
                            <span x-text="twoCats ? 'Generate a Pair' : 'Generate a Name'"></span>
                        </button>
                    </div>
                </div>

                {{-- Suggested names --}}
                <div id="results" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="flex items-center gap-2 font-heading text-xl font-extrabold tracking-tight text-ink">
                            <span x-text="twoCats ? 'Name Pairs For Your Cats' : 'Suggested Names For Your Cat'"></span>
                            <svg class="size-5 text-primary-vivid" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 2.5l1.9 5.6 5.6 1.9-5.6 1.9L12 17.5l-1.9-5.6L4.5 10l5.6-1.9L12 2.5Z"/>
                            </svg>
                        </h2>
                        <button type="button" x-on:click="generate()"
                                class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-1.5 text-sm font-semibold text-ink-muted transition hover:border-primary/40 hover:text-primary">
                            <svg class="size-4 transition-transform duration-500 group-hover:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 11a8 8 0 1 0-2.3 5.6M20 4v7h-7"/>
                            </svg>
                            Shuffle
                        </button>
                    </div>

                    {{-- Pairs --}}
                    <template x-if="twoCats && pairs.length">
                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <template x-for="pair in pairs" :key="pair.cats[0].name + pair.cats[1].name">
                                <div class="rounded-xl border border-line p-4 transition hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md">
                                    <p class="font-heading text-lg font-extrabold text-ink">
                                        <span x-text="pair.cats[0].name"></span>
                                        <span class="text-primary">&amp;</span>
                                        <span x-text="pair.cats[1].name"></span>
                                    </p>
                                    <p class="mt-1.5 text-xs leading-relaxed text-ink-muted" x-text="pair.why"></p>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Single names --}}
                    <template x-if="!twoCats">
                        <div>
                            <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                                <template x-for="pick in visibleResults()" :key="pick.name">
                                    <div class="group relative flex flex-col rounded-xl border border-line bg-surface p-4 transition duration-200 hover:-translate-y-1 hover:border-primary/40 hover:shadow-lg">
                                        <button type="button" x-on:click="toggleFavorite(pick)"
                                                :aria-label="isFavorite(pick.name) ? 'Remove ' + pick.name + ' from favorites' : 'Save ' + pick.name + ' to favorites'"
                                                class="absolute top-3 right-3 flex size-7 items-center justify-center rounded-full transition hover:bg-primary-light">
                                            <svg class="size-4 text-primary-vivid transition-transform duration-200 hover:scale-125"
                                                 viewBox="0 0 24 24" :fill="isFavorite(pick.name) ? 'currentColor' : 'none'"
                                                 stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path d="M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z"/>
                                            </svg>
                                        </button>

                                        <button type="button" x-on:click="openDetail(pick)" class="text-left">
                                            <span class="block pr-8 font-heading text-lg font-extrabold text-ink transition-colors group-hover:text-primary" x-text="pick.name"></span>

                                            <span class="mt-1.5 inline-block rounded-full bg-primary-light px-2.5 py-0.5 text-[11px] font-bold text-primary"
                                                  x-text="styleLabels[pick.styles[0]] || pick.styles[0]"></span>

                                            <span class="mt-2 block text-xs leading-relaxed text-ink-muted" x-text="pick.meaning"></span>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <div class="mt-5 text-center" x-show="results.length > 8" x-cloak>
                                <button type="button" x-on:click="showAll = !showAll"
                                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary transition hover:gap-2.5">
                                    <span x-text="showAll ? 'Show Fewer Names' : 'View More Names'"></span>
                                    <svg class="size-4 transition-transform duration-200" :class="showAll && 'rotate-180'"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Favorites --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7" x-show="favorites.length" x-cloak>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="flex items-center gap-2 font-heading text-xl font-extrabold tracking-tight text-ink">
                            <svg class="size-5 text-primary-vivid" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z"/>
                            </svg>
                            Your favorites
                            <span class="text-ink-muted" x-text="'(' + favorites.length + ')'"></span>
                        </h2>
                        <div class="flex flex-wrap items-center gap-1.5">
                            <button type="button" x-on:click="copyAllFavorites()"
                                    class="rounded-full border border-line bg-surface px-3 py-1.5 text-xs font-semibold text-ink-muted transition hover:border-line-strong hover:text-ink"
                                    x-text="favoritesCopied ? 'Copied!' : 'Copy all'"></button>
                            <button type="button" x-on:click="downloadFavorites()"
                                    class="rounded-full border border-line bg-surface px-3 py-1.5 text-xs font-semibold text-ink-muted transition hover:border-line-strong hover:text-ink">Download</button>
                            <button type="button" x-on:click="clearFavorites()"
                                    class="rounded-full border border-line bg-surface px-3 py-1.5 text-xs font-semibold text-danger transition hover:border-danger/40 hover:bg-danger-light">Clear all</button>
                        </div>
                    </div>
                    <ul class="mt-4 flex flex-wrap gap-2">
                        <template x-for="fav in favorites" :key="fav.name">
                            <li class="flex items-center gap-2 rounded-full border border-line py-1.5 pr-2 pl-4 text-sm font-semibold text-ink">
                                <span x-text="fav.name"></span>
                                <button type="button" x-on:click="removeFavorite(fav.name)" aria-label="Remove from favorites"
                                        class="flex size-5 items-center justify-center rounded-full text-ink-muted transition hover:bg-danger-light hover:text-danger">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>

                {{-- How to choose --}}
                <div class="reveal overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">
                    <div class="grid gap-0 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.8fr)]">
                        <div class="p-5 sm:p-7">
                            <h2 class="border-b-2 border-primary-vivid/25 pb-3 font-heading text-xl font-extrabold tracking-tight text-ink">
                                How to Choose the Perfect Name
                            </h2>

                            <ol class="mt-5 space-y-4">
                                @foreach ($howToChoose as $i => $step)
                                    <li class="group flex gap-3.5">
                                        <span @class([
                                            'flex size-9 shrink-0 items-center justify-center rounded-xl font-heading text-sm font-extrabold transition-transform duration-300 group-hover:scale-110',
                                            'bg-primary-light text-primary' => $i % 4 === 0,
                                            'bg-accent-light text-accent-dark' => $i % 4 === 1,
                                            'bg-warning-light text-warning' => $i % 4 === 2,
                                            'bg-info-light text-info' => $i % 4 === 3,
                                        ])>{{ $i + 1 }}</span>
                                        <span>
                                            <span class="block font-heading text-sm font-bold text-ink">{{ $step['title'] }}</span>
                                            <span class="mt-1 block text-sm leading-relaxed text-ink-muted">{{ $step['text'] }}</span>
                                        </span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>

                        <div class="relative min-h-56 lg:min-h-full">
                            <x-img name="purrquery-happy-tabby-cat-relaxing" alt="Relaxed tabby cat resting on a soft blanket"
                                   sizes="(max-width: 1023px) 92vw, 420px"/>
                        </div>
                    </div>
                </div>

                {{-- How naming works --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        How cat naming actually works
                    </h2>
                    <div class="mt-5 space-y-4 text-base leading-relaxed text-ink-muted">
                        <p>
                            Most name generators are a random word picker with a cat theme
                            bolted on. This one starts from a smaller, curated list on
                            purpose: every name here has a real reason attached, whether
                            that is a genuine translation, a mythology figure, or a
                            well-known character, rather than a string pulled from a
                            dictionary with no context.
                        </p>
                        <p>
                            The breed filter is the part worth using if you already know
                            what you are bringing home. Pick a breed and the generator
                            leans toward names that fit its actual origin country when
                            there is a real match (Japanese names for a Japanese
                            Bobtail, French names for a Chartreux), and shows that
                            breed's real temperament alongside the result, pulled
                            straight from our breed data rather than invented for this
                            tool.
                        </p>
                        <p>
                            A short name a cat can actually learn tends to work better
                            than a long, elaborate one: most trainers suggest one or two
                            syllables with a clear, consistent sound, which is exactly
                            what the length filter is for. The nicknames included with
                            some names are there for the same reason: a formal name for
                            the vet's paperwork and a shorter one for calling them in for
                            dinner are not a contradiction.
                        </p>
                    </div>
                </div>

                {{-- Popular names --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        Popular cat names
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-ink-muted">
                        No organization publishes a single verified real-time ranking of
                        cat names, so treat this as what turns up again and again in
                        cat-owner communities, vet clinic intake forms and pet insurance
                        sign-ups, not an official chart.
                    </p>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        @foreach (['female' => 'Popular female cat names', 'male' => 'Popular male cat names'] as $key => $heading)
                            <div class="rounded-xl border border-line p-4">
                                <h3 class="font-heading text-sm font-bold text-ink">{{ $heading }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ implode(', ', $popularNames[$key]) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- By style --}}
                <div id="by-style" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        Cat names by style
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-ink-muted">
                        The style filter above covers ten long-tail directions, from
                        mythology to food-inspired names. Here is a sample from each.
                    </p>
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        @foreach ($styleExamples as $slug => $data)
                            <button type="button" x-on:click="pickCategory('{{ $slug }}')"
                                    class="group rounded-xl border border-line p-4 text-left transition hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md">
                                <h3 class="font-heading text-sm font-bold text-ink transition-colors group-hover:text-primary">{{ $data['label'] }} cat names</h3>
                                <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $data['names']->pluck('name')->implode(', ') }}</p>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- By personality --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        Cat names by personality
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-ink-muted">
                        A name that matches how a cat actually acts tends to feel right
                        longer than one picked from looks alone.
                    </p>
                    <div class="mt-5 overflow-hidden rounded-xl border border-line">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                                    <th scope="col" class="px-4 py-3 font-semibold">Personality</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Example names</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @foreach ($personalityExamples as $data)
                                    <tr class="transition-colors hover:bg-surface-soft">
                                        <td class="px-4 py-3 font-semibold text-ink">{{ $data['label'] }}</td>
                                        <td class="px-4 py-3 text-ink-muted">{{ $data['names']->pluck('name')->implode(', ') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- By breed --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        Cat names by breed
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-ink-muted">
                        Maine Coon, Siamese, Persian, Bengal, Russian Blue and more,
                        matched to a theme that actually fits the breed rather than a
                        generic list reused for all of them.
                    </p>
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        @foreach ($breedGuide as $breed)
                            <div class="rounded-xl border border-line p-4 transition hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md">
                                <h3 class="font-heading text-sm font-bold text-ink">{{ $breed['breed'] }} cat names</h3>
                                <p class="mt-0.5 text-xs font-semibold text-primary">{{ $breed['theme'] }}</p>
                                <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ implode(', ', $breed['names']) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kitten vs adult --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        Kitten names vs. adult cat names
                    </h2>
                    <div class="mt-5 space-y-4 text-base leading-relaxed text-ink-muted">
                        <p>
                            A kitten grows into a name the way it grows into everything
                            else, so a cute or diminutive name like Peanut or Biscuit
                            rarely feels wrong even years later. A nine-pound cat still
                            named Peanut is its own kind of joke that keeps working.
                        </p>
                        <p>
                            Adult cats and shelter rescues carry more nuance: many
                            already answer to a name, and changing it is fine, but a
                            switch tends to go smoother when the new name shares a
                            similar sound or syllable count with the old one, since
                            that overlap shortens the relearning period. A name chosen
                            for a kitten is usually the name that cat keeps for the
                            next fifteen-plus years, so it is worth picking one that
                            still suits a full-grown, dignified adult cat, not just a
                            fluffy kitten.
                        </p>
                    </div>
                </div>

                {{-- Rules --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        Seven rules for choosing the right cat name
                    </h2>
                    <ol class="mt-5 space-y-3">
                        @foreach ([
                            ['Keep it short.', 'One or two syllables is easiest for a cat to learn and for you to call out consistently.'],
                            ['End on a clear sound.', 'Names like Luna, Milo or Jax cut through background noise better than soft, trailing ones, which is part of why vets and trainers so often repeat this advice.'],
                            ['Say it out loud, not just on paper.', 'A name that reads well can still sound awkward called across a yard.'],
                            ['Avoid names that sound like commands.', '"Kit" sounds close to "sit," and overlap with a command word slows down training.'],
                            ['Pick something the whole household agrees on.', 'A name everyone shortens differently just confuses a cat.'],
                            ['Let personality guide it, not just looks.', 'A name chosen after living with a cat for a few days often fits better than one picked from a photo alone.'],
                            ['Make sure it still works in five years.', 'Cats live well into their teens, so a name should suit a full-grown adult cat, not just a kitten.'],
                        ] as $i => [$rule, $detail])
                            <li class="group flex gap-3.5">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-primary-light font-heading text-sm font-extrabold text-primary transition-transform duration-300 group-hover:scale-110">
                                    {{ $i + 1 }}
                                </span>
                                <span class="text-base leading-relaxed text-ink-muted">
                                    <strong class="text-ink">{{ $rule }}</strong> {{ $detail }}
                                </span>
                            </li>
                        @endforeach
                    </ol>
                </div>

                {{-- Two cats --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        Cat names for two cats
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-ink-muted">
                        Naming two cats at once opens up pairs. Two Cats mode in the
                        generator above builds a matched pair automatically and
                        explains what actually connects them. A few tested pair ideas
                        to start from:
                    </p>
                    <div class="mt-5 overflow-hidden rounded-xl border border-line">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                                    <th scope="col" class="px-4 py-3 font-semibold">Theme</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Pair</th>
                                    <th scope="col" class="hidden px-4 py-3 font-semibold sm:table-cell">Why it works</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @foreach ($pairExamples as $pair)
                                    <tr class="align-top transition-colors hover:bg-surface-soft">
                                        <td class="px-4 py-3 font-semibold text-ink">{{ $pair['theme'] }}</td>
                                        <td class="px-4 py-3 text-ink-muted">
                                            {{ $pair['name1'] }} &amp; {{ $pair['name2'] }}
                                            <span class="mt-1 block text-xs sm:hidden">{{ $pair['why'] }}</span>
                                        </td>
                                        <td class="hidden px-4 py-3 text-ink-muted sm:table-cell">{{ $pair['why'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- FAQ --}}
                <div id="faq" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        Common Questions
                    </h2>

                    <div class="mt-5 space-y-2.5">
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
                </div>

                {{-- Still can't decide --}}
                <div class="reveal relative overflow-hidden rounded-2xl border border-line bg-surface-soft p-5 shadow-sm sm:p-7">
                    <div class="grid items-center gap-5 sm:grid-cols-[minmax(0,1fr)_auto]">
                        <div>
                            <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink">Still cannot decide?</h2>
                            <p class="mt-2 max-w-md text-sm leading-relaxed text-ink-muted">
                                Shuffle a fresh set, or scroll down for full lists by style, personality and breed.
                            </p>
                            <div class="mt-4 flex flex-wrap gap-2.5">
                                <button type="button" x-on:click="generate()" class="btn-primary rounded-full px-6 py-2.5 text-sm">
                                    <x-paw-print class="size-4"/>
                                    Generate more
                                </button>
                                <a href="#name-ideas" class="btn-outline rounded-full px-6 py-2.5 text-sm">
                                    Browse by category
                                </a>
                            </div>
                        </div>

                        <div class="hidden w-40 shrink-0 sm:block">
                            <x-img name="purrquery-cat-saying-hi" alt="Ginger kitten raising a paw" sizes="160px"/>
                        </div>
                    </div>
                </div>

                {{-- Keep exploring --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <h2 class="{{ $panelHeading }}">
                        Keep exploring
                    </h2>
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        @foreach ($internalLinks as $link)
                            <a href="{{ $link['url'] }}"
                               class="group flex items-center justify-between gap-2 rounded-xl border border-line px-4 py-3.5 text-sm font-semibold text-ink transition hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md">
                                {{ $link['label'] }}
                                <svg class="size-4 shrink-0 text-ink-muted transition-transform group-hover:translate-x-0.5 group-hover:text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m9 6 6 6-6 6"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ── Sidebar ──────────────────────────────────────────────── --}}
            <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">

                {{-- Popular right now --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
                    <h2 class="flex items-center gap-2 font-heading text-base font-extrabold text-ink">
                        <svg class="size-4 text-warning" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M13 2 4.5 13H11l-1 9 8.5-11H12l1-9Z"/>
                        </svg>
                        Popular Right Now
                    </h2>

                    <ol class="mt-4 space-y-1">
                        @foreach ($trendingNames->take(5) as $i => $pick)
                            <li>
                                <button type="button" x-on:click="openDetail(@js($pick))"
                                        class="group flex w-full items-center gap-3 rounded-lg px-2 py-2 text-left transition hover:bg-surface-soft">
                                    <span class="font-heading text-xs font-extrabold text-primary/50">{{ $i + 1 }}</span>
                                    <span class="flex-1 text-sm font-semibold text-ink transition-colors group-hover:text-primary">{{ $pick['name'] }}</span>
                                    <svg class="size-3.5 shrink-0 text-accent-dark transition-transform duration-200 group-hover:-translate-y-0.5"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M7 17 17 7M9 7h8v8"/>
                                    </svg>
                                </button>
                            </li>
                        @endforeach
                    </ol>

                    <button type="button" x-on:click="generate()"
                            class="btn-outline mt-4 w-full justify-center rounded-full py-2 text-sm">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20 11a8 8 0 1 0-2.3 5.6M20 4v7h-7"/>
                        </svg>
                        Generate More
                    </button>
                </div>

                {{-- Name ideas by category --}}
                <div id="name-ideas" class="reveal scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm">
                    <h2 class="flex items-center gap-2 font-heading text-base font-extrabold text-ink">
                        <svg class="size-4 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 7.5A1.5 1.5 0 0 1 4.5 6h4l2 2.5h9A1.5 1.5 0 0 1 21 10v7.5a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 17.5Z"/>
                        </svg>
                        Name Ideas by Category
                    </h2>

                    <ul class="mt-4 space-y-1">
                        @foreach ($categoryCounts->take(7) as $category)
                            <li>
                                <button type="button" x-on:click="pickCategory('{{ $category['slug'] }}')"
                                        class="group flex w-full items-center gap-2.5 rounded-lg px-2 py-2 text-left transition hover:bg-surface-soft">
                                    <svg class="size-3 shrink-0 text-primary-vivid transition-transform duration-200 group-hover:translate-x-0.5"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
                                        <path d="m9 6 6 6-6 6"/>
                                    </svg>
                                    <span class="flex-1 text-sm font-medium text-ink transition-colors group-hover:text-primary">{{ $category['label'] }}</span>
                                    <span class="text-xs font-bold text-ink-muted">{{ $category['count'] }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <a href="#by-style" class="btn-outline mt-4 w-full justify-center rounded-full py-2 text-sm">
                        View All Categories
                    </a>
                </div>

                {{-- Naming tips --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm">
                    <h2 class="flex items-center gap-2 font-heading text-base font-extrabold text-ink">
                        <svg class="size-4 text-warning" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 18h6M10 21h4M12 3a6 6 0 0 1 3.6 10.8c-.4.3-.6.8-.6 1.2H9c0-.4-.2-.9-.6-1.2A6 6 0 0 1 12 3Z"/>
                        </svg>
                        Naming Tips
                    </h2>

                    <ul class="mt-4 space-y-3.5">
                        @foreach ($namingTips as $tip)
                            <li class="border-l-2 border-line pl-3">
                                <span class="text-sm leading-relaxed text-ink-muted">{{ $tip['tip'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Image --}}
                <div class="reveal overflow-hidden rounded-2xl border border-line shadow-sm">
                    <div class="aspect-[4/3]">
                        <x-img name="purrquery-cat-cozy-blanket" alt="Kitten peeking out from under a cozy blanket" sizes="340px"/>
                    </div>
                </div>

                {{-- Fun fact --}}
                <div class="reveal rounded-2xl border border-line bg-surface-soft p-5 shadow-sm">
                    <h2 class="flex items-center gap-2 font-heading text-base font-extrabold text-ink">
                        <svg class="size-4 text-primary-vivid" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z"/>
                        </svg>
                        Fun Fact
                    </h2>
                    <p class="mt-3 text-sm leading-relaxed text-ink-muted">{{ $funFact }}</p>
                </div>
            </aside>
        </div>
    </div>

    {{-- Result dialog: reuses the site's cat-face reveal animation, driven by
         Alpine instead of a server round trip since there is nothing here to
         send to a server in the first place. --}}
    <dialog x-ref="resultDialog" aria-labelledby="name-result-heading"
            class="result-card m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl border border-line bg-surface p-0 shadow-2xl backdrop:bg-ink/50 backdrop:backdrop-blur-sm"
            x-on:click="if ($event.target === $refs.resultDialog) $refs.resultDialog.close()">
        <template x-if="result">
            <div class="p-6 text-center sm:p-8">
                <div class="relative mx-auto flex size-32 items-center justify-center rounded-full bg-primary-light">
                    <x-cat-face-icon size="size-24"/>
                </div>

                <h2 id="name-result-heading" class="mt-5 font-heading text-3xl font-extrabold tracking-tight text-ink" x-text="result.name"></h2>
                <p class="mt-1 text-sm font-semibold text-ink-muted" x-show="result.nickname" x-cloak>
                    Nickname: <span x-text="result.nickname"></span>
                </p>

                <p class="mt-3 text-base leading-relaxed text-ink-muted" x-text="result.meaning"></p>

                <div class="mt-3 flex flex-wrap justify-center gap-1.5">
                    <span class="inline-flex items-center gap-1 rounded-full bg-surface-soft px-3 py-1 text-xs font-semibold text-ink-muted" x-show="result.origin_country" x-cloak>
                        <span x-text="result.origin_flag"></span>
                        <span x-text="result.origin_country"></span>
                    </span>
                    <template x-for="p in (result.personalities || [])" :key="p">
                        <span class="rounded-full bg-accent-light px-3 py-1 text-xs font-semibold text-accent-dark" x-text="personalityLabels[p] || p"></span>
                    </template>
                </div>

                <div class="mt-4 rounded-xl border border-line bg-surface-soft p-3.5 text-left text-sm leading-relaxed text-ink-muted" x-show="selectedBreed()" x-cloak>
                    <span class="font-bold text-ink" x-text="selectedBreed() ? selectedBreed().name + ' note:' : ''"></span>
                    <span x-text="selectedBreed() ? selectedBreed().temperament : ''"></span>
                </div>

                <div class="mt-6 flex flex-wrap justify-center gap-2.5">
                    <button type="button" x-on:click="generate(); $refs.resultDialog.close()" class="btn-outline rounded-full px-5 py-2.5 text-sm">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 11a8 8 0 1 0-2.3 5.6M20 4v7h-7"/></svg>
                        More names
                    </button>
                    <button type="button" x-on:click="toggleFavorite(result)"
                            :class="isFavorite(result.name) ? 'bg-primary-light text-primary border-primary-light' : 'bg-surface text-ink-muted border-line'"
                            class="inline-flex items-center gap-1.5 rounded-full border px-5 py-2.5 text-sm font-semibold transition">
                        <svg class="size-4" viewBox="0 0 24 24" :fill="isFavorite(result.name) ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path d="M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z"/>
                        </svg>
                        <span x-text="isFavorite(result.name) ? 'Saved' : 'Save'"></span>
                    </button>
                </div>

                <div class="mt-5 border-t border-line pt-5">
                    <p class="text-xs font-bold tracking-wide text-ink-muted uppercase">Share this pick</p>
                    <div class="mt-2.5 flex flex-wrap justify-center gap-2">
                        <button type="button" x-on:click="shareTo('facebook')" aria-label="Share on Facebook"
                                class="flex size-9 items-center justify-center rounded-full border border-line text-sm font-black text-ink-muted transition hover:border-primary/40 hover:bg-primary-light hover:text-primary">f</button>
                        <button type="button" x-on:click="shareTo('twitter')" aria-label="Share on X"
                                class="flex size-9 items-center justify-center rounded-full border border-line text-sm font-black text-ink-muted transition hover:border-primary/40 hover:bg-primary-light hover:text-primary">X</button>
                        <button type="button" x-on:click="shareTo('whatsapp')" aria-label="Share on WhatsApp"
                                class="flex size-9 items-center justify-center rounded-full border border-line text-ink-muted transition hover:border-primary/40 hover:bg-primary-light hover:text-primary">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 20l1.4-4.2A8 8 0 1 1 8.8 19L4 20Z"/>
                                <path d="M9 10c0 2.5 2.5 5 5 5"/>
                            </svg>
                        </button>
                        <button type="button" x-on:click="copyLink()" aria-label="Copy link"
                                class="flex size-9 items-center justify-center rounded-full border border-line text-ink-muted transition hover:border-primary/40 hover:bg-primary-light hover:text-primary">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 9V5.5A1.5 1.5 0 0 1 10.5 4h8A1.5 1.5 0 0 1 20 5.5v8a1.5 1.5 0 0 1-1.5 1.5H15"/>
                                <path d="M5.5 9h8A1.5 1.5 0 0 1 15 10.5v8a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 4 18.5v-8A1.5 1.5 0 0 1 5.5 9Z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-2 text-xs font-semibold text-primary" x-show="shareCopied" x-cloak>Link copied!</p>
                </div>

                <button type="button" x-on:click="$refs.resultDialog.close()" class="mt-4 text-sm font-semibold text-ink-muted hover:text-ink">
                    Close
                </button>
            </div>
        </template>
    </dialog>
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
    </div>
</section>

@push('scripts')
    <script>
        function catNameGenerator(config) {
            return {
                names: config.names,
                breeds: config.breeds,
                trending: config.trending,
                styleLabels: config.styleLabels,
                personalityLabels: config.personalityLabels,
                styleSlugs: config.styleSlugs,
                personalitySlugs: config.personalitySlugs,
                saveUrl: config.saveUrl,
                csrfToken: config.csrfToken,

                gender: 'any',
                styles: [],
                personality: '',
                breedSlug: '',
                letter: '',
                length: 'any',
                twoCats: false,
                result: null,
                results: [],
                pairs: [],
                showAll: false,
                favorites: [],
                favoritesCopied: false,
                shareCopied: false,

                init() {
                    try {
                        this.favorites = JSON.parse(localStorage.getItem('purrquery-name-favorites') || '[]');
                    } catch (e) {
                        this.favorites = [];
                    }

                    // Seed the grid so the section is never an empty box on
                    // arrival, the way it reads in the design.
                    this.fill();
                },

                toggleStyle(slug) {
                    const i = this.styles.indexOf(slug);
                    if (i === -1) this.styles.push(slug); else this.styles.splice(i, 1);
                },

                // Sidebar category click: applies that style as the only filter
                // and regenerates, so the list is the category, not a jump link.
                pickCategory(slug) {
                    this.styles = [slug];
                    this.twoCats = false;
                    this.generate();
                },

                lengthBucket(name) {
                    const len = name.replace(/[^a-zA-Z]/g, '').length;
                    if (len <= 4) return 'short';
                    if (len <= 7) return 'medium';
                    return 'long';
                },

                selectedBreed() {
                    return this.breeds.find((b) => b.slug === this.breedSlug) || null;
                },

                pool() {
                    let pool = this.names.filter((n) => {
                        if (this.gender !== 'any' && n.gender !== this.gender && n.gender !== 'neutral') return false;
                        if (this.styles.length && !n.styles.some((s) => this.styles.includes(s))) return false;
                        if (this.personality && !(n.personalities || []).includes(this.personality)) return false;
                        if (this.letter && n.name[0].toUpperCase() !== this.letter) return false;
                        if (this.length !== 'any' && this.lengthBucket(n.name) !== this.length) return false;
                        return true;
                    });

                    // A boost, not a hard filter: a name that doesn't share
                    // the breed's origin is still a perfectly good name for
                    // that cat, so this only narrows the pool when doing so
                    // still leaves something to pick from.
                    const breed = this.selectedBreed();
                    if (breed && breed.originTag) {
                        const matched = pool.filter((n) => (n.origin_tags || []).includes(breed.originTag));
                        if (matched.length) pool = matched;
                    }

                    return pool;
                },

                poolCount() {
                    return this.pool().length;
                },

                pickOne(source) {
                    return source[Math.floor(Math.random() * source.length)];
                },

                pairWhy(a, b) {
                    const sharedStyle = a.styles.find((s) => b.styles.includes(s));
                    if (sharedStyle) {
                        const label = (this.styleLabels[sharedStyle] || sharedStyle).toLowerCase();
                        return a.name + ' and ' + b.name + ' both carry a ' + label + ' feel, so they sound natural called out together.';
                    }
                    const sharedPersonality = (a.personalities || []).find((p) => (b.personalities || []).includes(p));
                    if (sharedPersonality) {
                        const label = (this.personalityLabels[sharedPersonality] || sharedPersonality).toLowerCase();
                        return a.name + ' and ' + b.name + ' are both classic ' + label + ' names, a matched theme for two cats.';
                    }
                    if (a.origin_country && a.origin_country === b.origin_country) {
                        return a.name + ' and ' + b.name + ' share the same origin, ' + a.origin_country + ', a subtle but real connection.';
                    }
                    return a.name + ' and ' + b.name + ' have a similar rhythm and length, which is the main thing that makes two cat names sound like a matched set.';
                },

                // Builds the grid without moving the page, so init() can use it too.
                fill() {
                    const pool = this.pool();
                    const source = pool.length ? pool : this.names;
                    const shuffled = [...source].sort(() => Math.random() - 0.5);

                    if (this.twoCats) {
                        this.pairs = [];
                        for (let i = 0; i + 1 < shuffled.length && this.pairs.length < 4; i += 2) {
                            const a = shuffled[i];
                            const b = shuffled[i + 1];
                            this.pairs.push({ cats: [a, b], why: this.pairWhy(a, b) });
                        }
                    } else {
                        this.results = shuffled.slice(0, 16);
                        this.showAll = false;
                    }
                },

                generate() {
                    this.fill();
                    document.getElementById('results')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                },

                visibleResults() {
                    return this.showAll ? this.results : this.results.slice(0, 8);
                },

                openDetail(pick) {
                    this.result = pick;
                    this.$nextTick(() => this.$refs.resultDialog?.showModal());
                },

                isFavorite(name) {
                    return this.favorites.some((f) => f.name === name);
                },

                toggleFavorite(pick) {
                    if (this.isFavorite(pick.name)) {
                        this.favorites = this.favorites.filter((f) => f.name !== pick.name);
                    } else {
                        this.favorites.push({ name: pick.name, meaning: pick.meaning });
                        this.saveName(pick.name);
                    }
                    try {
                        localStorage.setItem('purrquery-name-favorites', JSON.stringify(this.favorites));
                    } catch (e) {}
                },

                removeFavorite(name) {
                    this.favorites = this.favorites.filter((f) => f.name !== name);
                    try {
                        localStorage.setItem('purrquery-name-favorites', JSON.stringify(this.favorites));
                    } catch (e) {}
                },

                copyAllFavorites() {
                    const text = this.favorites.map((f) => f.name + ': ' + f.meaning).join('\n');
                    navigator.clipboard.writeText(text).then(() => {
                        this.favoritesCopied = true;
                        setTimeout(() => { this.favoritesCopied = false; }, 2000);
                    }).catch(() => {});
                },

                downloadFavorites() {
                    const text = this.favorites.map((f) => f.name + ': ' + f.meaning).join('\n');
                    const blob = new Blob([text], { type: 'text/plain' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'cat-name-favorites.txt';
                    a.click();
                    URL.revokeObjectURL(url);
                },

                clearFavorites() {
                    if (!confirm('Remove all saved favorites?')) return;
                    this.favorites = [];
                    try {
                        localStorage.setItem('purrquery-name-favorites', JSON.stringify(this.favorites));
                    } catch (e) {}
                },

                saveName(name) {
                    fetch(this.saveUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ name }),
                    }).catch(() => {});
                },

                shareText() {
                    return this.result.name + ': ' + this.result.meaning;
                },

                shareTo(network) {
                    const text = encodeURIComponent(this.shareText());
                    const url = encodeURIComponent(location.href);
                    const urls = {
                        facebook: 'https://www.facebook.com/sharer/sharer.php?u=' + url,
                        twitter: 'https://twitter.com/intent/tweet?text=' + text + '&url=' + url,
                        whatsapp: 'https://wa.me/?text=' + text + '%20' + url,
                    };
                    window.open(urls[network], '_blank', 'noopener,width=600,height=500');
                },

                async copyLink() {
                    try {
                        await navigator.clipboard.writeText(location.href);
                        this.shareCopied = true;
                        setTimeout(() => { this.shareCopied = false; }, 2000);
                    } catch (e) {}
                },
            };
        }

    </script>
@endpush

</x-layouts.app>

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
                        <div class="relative mt-2.5 grid grid-cols-4 divide-x divide-line overflow-hidden rounded-xl border border-line bg-surface-section p-1 shadow-inner">
                            <div class="pointer-events-none absolute inset-y-1 left-1 w-[calc(25%-2px)] rounded-lg bg-primary-vivid shadow-sm transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]"
                                 :style="{ transform: 'translateX(' + (genderIndex() * 100) + '%)' }"></div>
                            @foreach (['any' => 'Any', 'male' => 'Male', 'female' => 'Female', 'neutral' => 'Either'] as $value => $label)
                                <button type="button" x-on:click="gender = '{{ $value }}'"
                                        :class="gender === '{{ $value }}' ? 'text-ink' : 'text-ink-muted hover:scale-[1.03] hover:text-primary'"
                                        class="relative z-10 rounded-lg py-2.5 text-sm font-bold transition-all duration-200">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </fieldset>

                    {{-- Style --}}
                    <div class="mt-5" x-data="{ open: false }" @click.outside="open = false">
                        <label class="text-sm font-bold text-ink">
                            <span class="text-primary">2.</span> Style
                        </label>
                        <div class="relative mt-2">
                            <button type="button" x-on:click="open = !open"
                                    :class="open && 'border-primary/40 ring-2 ring-primary/20'"
                                    class="flex w-full items-center justify-between gap-2 rounded-lg border border-line bg-surface px-3 py-2.5 text-left text-sm text-ink transition hover:border-primary/40 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                <span x-text="styles.length ? styles.length + ' style' + (styles.length > 1 ? 's' : '') + ' selected' : 'Any style'"></span>
                                <svg class="size-4 shrink-0 text-ink-muted transition-transform duration-200" :class="open && 'rotate-180'"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak
                                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute z-20 mt-2 max-h-72 w-full origin-top overflow-y-auto rounded-xl border border-line bg-surface p-2 shadow-lg">
                                @foreach ($styles as $style)
                                    <label class="flex cursor-pointer items-center gap-2.5 rounded-lg px-2.5 py-2 text-sm text-ink transition-colors hover:bg-primary-light">
                                        <input type="checkbox" x-on:change="toggleStyle('{{ $style['slug'] }}')" :checked="styles.includes('{{ $style['slug'] }}')"
                                               class="size-4 rounded border-line-strong accent-primary-vivid transition-transform focus:ring-2 focus:ring-primary/30">
                                        {{ $style['label'] }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Selects --}}
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="breed" class="text-sm font-bold text-ink">
                                <span class="text-primary">3.</span> Breed
                            </label>
                            <select id="breed" x-model="breedSlug"
                                    class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink transition hover:border-primary/40 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                <option value="">Any / not sure</option>
                                <template x-for="breed in breeds" :key="breed.slug">
                                    <option :value="breed.slug" x-text="breed.name"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-bold text-ink">
                                <span class="text-primary">4.</span> Size
                                <span class="text-xs font-semibold text-ink-muted" x-show="selectedBreed()" x-cloak>(from breed)</span>
                            </label>
                            <template x-if="selectedBreed()">
                                <p class="mt-2 flex h-[42px] items-center rounded-lg border border-line bg-surface-section px-3 text-sm font-semibold text-ink capitalize" x-text="selectedBreed().sizeCategory"></p>
                            </template>
                            <template x-if="!selectedBreed()">
                                <select x-model="sizePreference"
                                        class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink transition hover:border-primary/40 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                    <option value="any">Any</option>
                                    <option value="small">Small</option>
                                    <option value="medium">Medium</option>
                                    <option value="large">Large</option>
                                </select>
                            </template>
                        </div>

                        <div>
                            <label for="personality" class="text-sm font-bold text-ink">
                                <span class="text-primary">5.</span> Personality
                            </label>
                            <select id="personality" x-model="personality"
                                    class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink transition hover:border-primary/40 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                <option value="">Any</option>
                                @foreach ($personalities as $p)
                                    <option value="{{ $p['slug'] }}">{{ $p['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="length" class="text-sm font-bold text-ink">
                                <span class="text-primary">6.</span> Length
                            </label>
                            <select id="length" x-model="length"
                                    class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink transition hover:border-primary/40 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                <option value="any">Any</option>
                                <option value="short">Short</option>
                                <option value="medium">Medium</option>
                                <option value="long">Long</option>
                            </select>
                        </div>
                    </div>

                    {{-- Starts with --}}
                    <div class="mt-5">
                        <label class="text-sm font-bold text-ink">
                            <span class="text-primary">7.</span> Starts With
                        </label>
                        <div class="mt-2.5 flex gap-1.5 overflow-x-auto pb-1.5 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                            <button type="button" x-on:click="letter = ''"
                                    :class="letter === '' ? 'border-primary-vivid bg-primary-vivid text-ink' : 'border-line bg-surface text-ink-muted hover:-translate-y-0.5 hover:border-primary/40 hover:text-primary hover:shadow-sm'"
                                    class="shrink-0 rounded-lg border px-3 py-2 text-sm font-bold transition-all duration-150">
                                Any
                            </button>
                            <template x-for="l in 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('')" :key="l">
                                <button type="button" x-on:click="letter = l"
                                        :class="letter === l ? 'border-primary-vivid bg-primary-vivid text-ink' : 'border-line bg-surface text-ink-muted hover:-translate-y-0.5 hover:border-primary/40 hover:text-primary hover:shadow-sm'"
                                        class="shrink-0 rounded-lg border px-3 py-2 text-sm font-bold transition-all duration-150"
                                        x-text="l"></button>
                            </template>
                        </div>
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

                {{-- Favorites --}}
                <div class="reveal overflow-hidden rounded-2xl border border-line shadow-sm" x-show="favorites.length" x-cloak>
                    <div class="flex flex-wrap items-center justify-between gap-3 bg-primary-dark px-5 py-4 text-white sm:px-7">
                        <p class="flex items-center gap-2 font-heading text-base font-extrabold">
                            <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z"/>
                            </svg>
                            <span x-text="favorites.length"></span>
                            <span>favorite<span x-show="favorites.length !== 1">s</span></span>
                        </p>

                        <div class="flex flex-wrap items-center gap-2">
                            <label class="flex cursor-pointer items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold transition hover:bg-white/20">
                                <input type="checkbox" :checked="allFavoritesSelected()" x-on:change="toggleSelectAllFavorites()"
                                       class="size-4 rounded border-white/40 accent-primary-vivid">
                                Select All
                            </label>

                            <button type="button" x-on:click="copyAllFavorites()"
                                    class="rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold transition hover:bg-white/20"
                                    x-text="favoritesCopied ? 'Copied!' : 'Copy'"></button>

                            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <button type="button" x-on:click="open = !open"
                                        class="flex items-center gap-1.5 rounded-full bg-primary-vivid px-4 py-1.5 text-xs font-bold text-ink transition hover:brightness-95">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 3v12M7 10l5 5 5-5M5 21h14"/>
                                    </svg>
                                    Download as
                                    <svg class="size-3 transition-transform duration-200" :class="open && 'rotate-180'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </button>
                                <div x-show="open" x-cloak
                                     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                     class="absolute right-0 z-20 mt-2 w-32 overflow-hidden rounded-xl border border-line bg-surface shadow-lg">
                                    <button type="button" x-on:click="downloadFavorites('csv'); open = false"
                                            class="block w-full px-4 py-2.5 text-left text-sm font-semibold text-ink transition hover:bg-primary-light">CSV</button>
                                    <button type="button" x-on:click="downloadFavorites('txt'); open = false"
                                            class="block w-full px-4 py-2.5 text-left text-sm font-semibold text-ink transition hover:bg-primary-light">TXT</button>
                                </div>
                            </div>

                            <button type="button" x-on:click="clearFavorites()"
                                    class="rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold transition hover:bg-danger/80">Clear all</button>
                        </div>
                    </div>

                    <ul class="flex flex-wrap gap-2 bg-surface p-5 sm:p-7">
                        <template x-for="fav in favorites" :key="fav.name">
                            <li class="flex items-center gap-2 rounded-full border border-line py-1.5 pr-2 pl-3 text-sm font-semibold text-ink">
                                <input type="checkbox" :checked="fav.selected" x-on:change="toggleFavoriteSelected(fav.name)"
                                       class="size-4 rounded border-line-strong accent-primary-vivid">
                                <span x-text="fav.name"></span>
                                <button type="button" x-on:click="removeFavorite(fav.name)" aria-label="Remove from favorites"
                                        class="flex size-5 items-center justify-center rounded-full text-ink-muted transition hover:bg-danger-light hover:text-danger">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>

                {{-- Suggested names --}}
                <div id="results" x-show="hasGenerated" x-cloak
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                     class="scroll-mt-24 rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
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
                                    <div class="group flex flex-col items-center gap-3 rounded-xl border border-line bg-surface p-5 text-center transition duration-200 hover:-translate-y-1 hover:border-primary/40 hover:shadow-lg">
                                        <button type="button" x-on:click="openDetail(pick)"
                                                class="font-heading text-xl font-extrabold text-ink transition-colors hover:text-primary" x-text="pick.name"></button>

                                        <div class="flex items-center gap-2">
                                            <button type="button" x-on:click="toggleFavorite(pick)"
                                                    :aria-label="isFavorite(pick.name) ? 'Remove ' + pick.name + ' from favorites' : 'Save ' + pick.name + ' to favorites'"
                                                    class="flex size-9 items-center justify-center rounded-full bg-surface-section transition-transform duration-150 hover:scale-110 hover:bg-primary-light">
                                                <svg class="size-4 text-primary-vivid" viewBox="0 0 24 24" :fill="isFavorite(pick.name) ? 'currentColor' : 'none'"
                                                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                    <path d="M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z"/>
                                                </svg>
                                            </button>

                                            <button type="button" x-on:click="openTagMaker(pick)" aria-label="Make a tag for this name"
                                                    class="flex size-9 items-center justify-center rounded-full bg-surface-section transition-transform duration-150 hover:scale-110 hover:bg-primary-light">
                                                <svg class="size-4 text-ink-muted transition-colors group-hover:text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M20.6 12.7 12.7 20.6a2 2 0 0 1-2.8 0l-7-7a2 2 0 0 1 0-2.8l7.9-7.9A2 2 0 0 1 12.2 2H18a2.6 2.6 0 0 1 2.6 2.6v5.8a2 2 0 0 1-.6 1.4Z"/>
                                                    <path d="M15.5 8.5h.01"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="mt-6 text-center" x-show="results.length" x-cloak>
                                <p class="text-xs font-semibold text-ink-muted">
                                    Showing <span x-text="visibleResults().length"></span> of <span x-text="results.length"></span> names
                                </p>

                                <button type="button" x-on:click="loadMore()" x-show="visibleCount < results.length" x-cloak
                                        class="btn-primary group mt-4 rounded-full px-8 py-2.5 text-sm transition-transform duration-150 hover:scale-[1.02] active:scale-[0.98]">
                                    Load More
                                    <svg class="size-4 animate-bounce" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M12 5v14M6 13l6 6 6-6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Quick-start shortcuts: sits right under the button when
                     there are no results yet, and after them once there are,
                     since it always lives right after the results panel. --}}
                <div class="reveal rounded-2xl border border-line bg-surface p-5 shadow-sm sm:p-7">
                    <p class="text-center text-xs font-bold tracking-wide text-ink-muted uppercase">Or try a shortcut</p>
                    <div class="mt-3 grid gap-3 sm:grid-cols-3">
                        <button type="button" x-on:click="surpriseMe()"
                                class="group flex flex-col items-center gap-2 rounded-2xl bg-primary-vivid px-4 py-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                            <span class="flex size-11 items-center justify-center rounded-full bg-ink/10 transition-transform duration-200 group-hover:scale-110">
                                <svg class="size-5 text-ink" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M12 2.5l1.9 5.6 5.6 1.9-5.6 1.9L12 17.5l-1.9-5.6L4.5 10l5.6-1.9L12 2.5Z"/>
                                </svg>
                            </span>
                            <span class="font-heading text-sm font-extrabold text-ink">Surprise Me</span>
                            <span class="text-xs font-semibold text-ink/70">Random filters</span>
                        </button>

                        <button type="button" x-on:click="randomNames()"
                                class="group flex flex-col items-center gap-2 rounded-2xl bg-primary-dark px-4 py-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                            <span class="flex size-11 items-center justify-center rounded-full bg-white/15 transition-transform duration-200 group-hover:scale-110">
                                <svg class="size-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 11a8 8 0 1 0-2.3 5.6M20 4v7h-7"/>
                                </svg>
                            </span>
                            <span class="font-heading text-sm font-extrabold text-white">Random Names</span>
                            <span class="text-xs font-semibold text-white/70">Quick generate</span>
                        </button>

                        <button type="button" x-on:click="showTrending()"
                                class="group flex flex-col items-center gap-2 rounded-2xl bg-accent-dark px-4 py-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                            <span class="flex size-11 items-center justify-center rounded-full bg-white/15 transition-transform duration-200 group-hover:scale-110">
                                <svg class="size-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 17l6-6 4 4 8-8"/>
                                    <path d="M15 7h6v6"/>
                                </svg>
                            </span>
                            <span class="font-heading text-sm font-extrabold text-white">Trending Names</span>
                            <span class="text-xs font-semibold text-white/70">Popular picks</span>
                        </button>
                    </div>
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
                    <button type="button" x-on:click="openTagMaker(result)"
                            class="inline-flex items-center gap-1.5 rounded-full border border-line bg-surface px-5 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary/40 hover:text-primary">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20.6 12.7 12.7 20.6a2 2 0 0 1-2.8 0l-7-7a2 2 0 0 1 0-2.8l7.9-7.9A2 2 0 0 1 12.2 2H18a2.6 2.6 0 0 1 2.6 2.6v5.8a2 2 0 0 1-.6 1.4Z"/>
                            <path d="M15.5 8.5h.01"/>
                        </svg>
                        Make a tag
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

    {{-- Tag maker: a live canvas, redrawn on every change, so Download and
         Share always save exactly what's on screen rather than a separate
         re-render that could drift from the preview. --}}
    <dialog x-ref="tagDialog"
            class="tag-maker m-auto w-[calc(100%-2rem)] max-w-2xl rounded-2xl border border-line bg-surface p-0 shadow-2xl backdrop:bg-ink/50 backdrop:backdrop-blur-sm"
            x-on:click="if ($event.target === $refs.tagDialog) $refs.tagDialog.close()">
        <div class="flex items-center justify-between border-b border-line px-6 py-4">
            <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink">Cat Tag Preview</h2>
            <button type="button" x-on:click="$refs.tagDialog.close()" aria-label="Close"
                    class="flex size-8 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-soft hover:text-ink">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
            </button>
        </div>

        <div class="grid gap-6 p-6 sm:grid-cols-2 sm:p-8">
            {{-- Preview --}}
            <div>
                <div class="flex aspect-square items-center justify-center rounded-2xl bg-surface-section">
                    <canvas x-ref="tagCanvas" width="320" height="320" class="tag-swing size-full max-h-80 max-w-80"></canvas>
                </div>

                <div class="mt-4 flex items-center justify-between rounded-xl border border-line p-3.5">
                    <label for="tag-phone-toggle" class="text-sm font-bold text-ink">Phone Number</label>
                    <button type="button" role="switch" id="tag-phone-toggle" x-on:click="toggleTagPhone()"
                            :aria-checked="tagShowPhone ? 'true' : 'false'"
                            :class="tagShowPhone ? 'bg-primary-vivid' : 'bg-line-strong'"
                            class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors duration-200">
                        <span :class="tagShowPhone ? 'translate-x-5' : 'translate-x-1'"
                              class="inline-block size-4 transform rounded-full bg-surface shadow transition-transform duration-200"></span>
                    </button>
                </div>
                <input type="tel" x-show="tagShowPhone" x-cloak x-model="tagPhone" x-on:input="redrawTag()"
                       placeholder="+1 123 456 7890"
                       class="mt-2.5 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink transition hover:border-primary/40 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
            </div>

            {{-- Controls --}}
            <div>
                <p class="text-sm font-bold text-ink">Tag Style</p>
                <div class="mt-2 grid grid-cols-3 gap-2">
                    @foreach (['circle' => 'Circle', 'heart' => 'Heart', 'fish' => 'Fish'] as $shape => $label)
                        <button type="button" x-on:click="setTagShape('{{ $shape }}')"
                                :class="tagShape === '{{ $shape }}' ? 'border-primary-vivid bg-primary-light text-primary' : 'border-line bg-surface text-ink-muted hover:border-primary/40'"
                                class="rounded-lg border py-2 text-xs font-bold transition">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                @php
                    $tagPresets = ['#F47C6B' => 'Coral', '#123F42' => 'Deep teal', '#4F6C49' => 'Sage'];
                    $textPresets = ['#FFFFFF' => 'White', '#123F42' => 'Dark'];
                    $swatchClass = 'size-9 shrink-0 rounded-full border border-line transition-transform duration-150 hover:scale-110';
                @endphp

                <p class="mt-5 text-sm font-bold text-ink">Tag Color</p>
                <div class="mt-2 flex items-center gap-2.5">
                    @foreach ($tagPresets as $hex => $label)
                        <button type="button" x-on:click="setTagColor('{{ $hex }}')" aria-label="{{ $label }}"
                                :class="tagColor === '{{ $hex }}' && 'ring-2 ring-offset-2 ring-primary-vivid'"
                                class="{{ $swatchClass }}" style="background-color: {{ $hex }}"></button>
                    @endforeach
                    <label aria-label="Custom tag color"
                           class="relative flex {{ $swatchClass }} cursor-pointer items-center justify-center overflow-hidden"
                           :class="! @js(array_keys($tagPresets)).includes(tagColor.toUpperCase()) && 'ring-2 ring-offset-2 ring-primary-vivid'"
                           style="background: conic-gradient(from 180deg, #F47C6B, #F4D06B, #6BF47C, #6BC8F4, #8B6BF4, #F46BD0, #F47C6B)">
                        <input type="color" x-model="tagColor" x-on:input="redrawTag()" aria-label="Custom tag color picker"
                               class="absolute inset-0 size-full cursor-pointer opacity-0">
                    </label>
                </div>

                <p class="mt-5 text-sm font-bold text-ink">Text Color</p>
                <div class="mt-2 flex items-center gap-2.5">
                    @foreach ($textPresets as $hex => $label)
                        <button type="button" x-on:click="setTagTextColor('{{ $hex }}')" aria-label="{{ $label }}"
                                :class="tagTextColor === '{{ $hex }}' && 'ring-2 ring-offset-2 ring-primary-vivid'"
                                class="{{ $swatchClass }}" style="background-color: {{ $hex }}"></button>
                    @endforeach
                    <label aria-label="Custom text color"
                           class="relative flex {{ $swatchClass }} cursor-pointer items-center justify-center overflow-hidden"
                           :class="! @js(array_keys($textPresets)).includes(tagTextColor.toUpperCase()) && 'ring-2 ring-offset-2 ring-primary-vivid'"
                           style="background: conic-gradient(from 180deg, #F47C6B, #F4D06B, #6BF47C, #6BC8F4, #8B6BF4, #F46BD0, #F47C6B)">
                        <input type="color" x-model="tagTextColor" x-on:input="redrawTag()" aria-label="Custom text color picker"
                               class="absolute inset-0 size-full cursor-pointer opacity-0">
                    </label>
                </div>

                <p class="mt-5 text-sm font-bold text-ink">Font</p>
                <select x-model="tagFont" x-on:change="redrawTag()"
                        class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink transition hover:border-primary/40 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    <option value="Inter, sans-serif">Inter</option>
                    <option value="'Plus Jakarta Sans', sans-serif">Plus Jakarta Sans</option>
                    <option value="Georgia, serif">Georgia</option>
                    <option value="'Courier New', monospace">Courier New</option>
                </select>

                <div class="mt-6 flex gap-2.5">
                    <button type="button" x-on:click="downloadTag()"
                            class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-full bg-primary-dark px-4 py-2.5 text-sm font-bold text-white transition hover:brightness-110">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12M7 10l5 5 5-5M5 21h14"/></svg>
                        Download
                    </button>
                    <button type="button" x-on:click="shareTag()"
                            class="btn-primary flex-1 justify-center rounded-full px-4 py-2.5 text-sm">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M18 8a3 3 0 1 0-2.8-4.1M18 20a3 3 0 1 0-2.8-4.1M8.7 13.5l6.6 3.8M15.3 6.7 8.7 10.5M8 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                        </svg>
                        Share
                    </button>
                </div>
            </div>
        </div>

        {{-- On-cat mockup: a live snapshot of the same canvas, so it never
             shows a design that doesn't match what Download actually saves. --}}
        <div class="border-t border-line p-6 sm:p-8">
            <p class="text-sm font-bold text-ink">Preview on your cat</p>
            <div class="relative mt-3 overflow-hidden rounded-2xl bg-surface-section">
                <div class="aspect-[16/10]">
                    <x-img name="purrquery-orange-tabby-cat-hero" alt="Cat wearing a preview of the tag around its neck" sizes="600px"/>
                </div>
                <img x-ref="tagOnCat" alt="" aria-hidden="true"
                     class="tag-swing pointer-events-none absolute w-[15%]"
                     style="top: 56%; left: 50%; margin-left: -7.5%;">
            </div>
        </div>
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
                genderOptions: ['any', 'male', 'female', 'neutral'],
                styles: [],
                personality: '',
                breedSlug: '',
                sizePreference: 'any',
                letter: '',
                length: 'any',
                twoCats: false,
                result: null,
                results: [],
                pairs: [],
                visibleCount: 8,
                hasGenerated: false,
                favorites: [],
                favoritesCopied: false,
                shareCopied: false,

                tagName: '',
                tagShape: 'circle',
                tagColor: '#F47C6B',
                tagTextColor: '#123F42',
                tagFont: 'Inter, sans-serif',
                tagShowPhone: false,
                tagPhone: '',

                init() {
                    try {
                        // Older saved favorites predate the selected flag, so
                        // anything missing it defaults to selected, not lost.
                        this.favorites = JSON.parse(localStorage.getItem('purrquery-name-favorites') || '[]')
                            .map((f) => ({ ...f, selected: f.selected !== false }));
                    } catch (e) {
                        this.favorites = [];
                    }

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

                genderIndex() {
                    return this.genderOptions.indexOf(this.gender);
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
                        this.results = shuffled;
                        this.visibleCount = 8;
                    }
                },

                generate() {
                    this.fill();
                    this.revealResults();
                },

                // Shared by every action that puts names on screen, so the
                // reveal and scroll behave identically no matter which
                // button triggered it.
                revealResults() {
                    this.hasGenerated = true;
                    this.$nextTick(() => {
                        document.getElementById('results')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    });
                },

                // Randomizes the filters themselves, then generates from
                // that combination, so the result (and the form) both
                // reflect a genuinely different search each time.
                surpriseMe() {
                    this.twoCats = false;
                    this.gender = this.pickOne(this.genderOptions);
                    this.styles = Math.random() < 0.5 ? [this.pickOne(this.styleSlugs)] : [];
                    this.personality = Math.random() < 0.5 ? this.pickOne(this.personalitySlugs) : '';
                    this.breedSlug = (Math.random() < 0.3 && this.breeds.length) ? this.pickOne(this.breeds).slug : '';
                    this.length = this.pickOne(['any', 'short', 'medium', 'long']);
                    this.letter = '';
                    this.generate();
                },

                // Leaves whatever filters are set alone and just pulls a
                // fresh random set from the full list, the plain "give me
                // something" option next to the filter-driven ones.
                randomNames() {
                    this.twoCats = false;
                    this.results = [...this.names].sort(() => Math.random() - 0.5);
                    this.visibleCount = 8;
                    this.revealResults();
                },

                // The trending list is real (save counts from actual
                // visitors, topped up from the curated list), so this shows
                // it as-is rather than running it back through fill().
                showTrending() {
                    this.twoCats = false;
                    this.results = this.trending;
                    this.visibleCount = 8;
                    this.revealResults();
                },

                visibleResults() {
                    return this.results.slice(0, this.visibleCount);
                },

                loadMore() {
                    this.visibleCount = Math.min(this.visibleCount + 8, this.results.length);
                },

                openDetail(pick) {
                    this.result = pick;
                    this.$nextTick(() => this.$refs.resultDialog?.showModal());
                },

                isFavorite(name) {
                    return this.favorites.some((f) => f.name === name);
                },

                // Every mutation ends by calling this rather than each
                // repeating the same try/catch, since localStorage can throw
                // in private-browsing contexts and a favorite that fails to
                // persist should still work for the rest of the session.
                persistFavorites() {
                    try {
                        localStorage.setItem('purrquery-name-favorites', JSON.stringify(this.favorites));
                    } catch (e) {}
                },

                toggleFavorite(pick) {
                    if (this.isFavorite(pick.name)) {
                        this.favorites = this.favorites.filter((f) => f.name !== pick.name);
                    } else {
                        this.favorites.push({ name: pick.name, meaning: pick.meaning, selected: true });
                        this.saveName(pick.name);
                    }
                    this.persistFavorites();
                },

                removeFavorite(name) {
                    this.favorites = this.favorites.filter((f) => f.name !== name);
                    this.persistFavorites();
                },

                allFavoritesSelected() {
                    return this.favorites.length > 0 && this.favorites.every((f) => f.selected);
                },

                toggleSelectAllFavorites() {
                    const next = !this.allFavoritesSelected();
                    this.favorites = this.favorites.map((f) => ({ ...f, selected: next }));
                    this.persistFavorites();
                },

                toggleFavoriteSelected(name) {
                    this.favorites = this.favorites.map((f) => f.name === name ? { ...f, selected: !f.selected } : f);
                    this.persistFavorites();
                },

                // Bulk actions act on whatever's checked, or everything if
                // nothing is, so Copy/Download still make sense on their own
                // without forcing a selection step first.
                selectedOrAllFavorites() {
                    const selected = this.favorites.filter((f) => f.selected);
                    return selected.length ? selected : this.favorites;
                },

                copyAllFavorites() {
                    const text = this.selectedOrAllFavorites().map((f) => f.name + ': ' + f.meaning).join('\n');
                    navigator.clipboard.writeText(text).then(() => {
                        this.favoritesCopied = true;
                        setTimeout(() => { this.favoritesCopied = false; }, 2000);
                    }).catch(() => {});
                },

                downloadFavorites(format) {
                    const rows = this.selectedOrAllFavorites();
                    if (!rows.length) return;

                    let text, mime, filename;
                    if (format === 'csv') {
                        const escape = (v) => '"' + String(v).replace(/"/g, '""') + '"';
                        text = 'Name,Meaning\r\n' + rows.map((f) => escape(f.name) + ',' + escape(f.meaning)).join('\r\n');
                        mime = 'text/csv';
                        filename = 'cat-name-favorites.csv';
                    } else {
                        text = rows.map((f) => f.name + ': ' + f.meaning).join('\n');
                        mime = 'text/plain';
                        filename = 'cat-name-favorites.txt';
                    }

                    const blob = new Blob([text], { type: mime });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    a.click();
                    URL.revokeObjectURL(url);
                },

                clearFavorites() {
                    if (!confirm('Remove all saved favorites?')) return;
                    this.favorites = [];
                    this.persistFavorites();
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

                openTagMaker(pick) {
                    this.tagName = pick.name;
                    this.tagShape = 'circle';
                    this.tagColor = '#F47C6B';
                    this.tagTextColor = '#123F42';
                    this.tagFont = 'Inter, sans-serif';
                    this.tagShowPhone = false;
                    this.tagPhone = '';
                    this.$nextTick(() => {
                        this.$refs.tagDialog?.showModal();
                        this.redrawTag();
                    });
                },

                setTagShape(shape) {
                    this.tagShape = shape;
                    this.redrawTag();
                },

                setTagColor(hex) {
                    this.tagColor = hex;
                    this.redrawTag();
                },

                setTagTextColor(hex) {
                    this.tagTextColor = hex;
                    this.redrawTag();
                },

                toggleTagPhone() {
                    this.tagShowPhone = !this.tagShowPhone;
                    this.redrawTag();
                },

                // A pet tag always has a hole for the ring, so every shape
                // punches the same small notch near the top after filling.
                drawTagHole(ctx, cx, topY) {
                    ctx.beginPath();
                    ctx.arc(cx, topY, 10, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(0,0,0,0.18)';
                    ctx.fill();
                    ctx.beginPath();
                    ctx.arc(cx, topY, 6, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(255,255,255,0.35)';
                    ctx.fill();
                },

                drawHeart(ctx, cx, cy, size) {
                    const top = cy - size * 0.35;
                    ctx.beginPath();
                    ctx.moveTo(cx, top + size * 0.3);
                    ctx.bezierCurveTo(cx, top, cx - size / 2, top, cx - size / 2, top + size * 0.3);
                    ctx.bezierCurveTo(cx - size / 2, top + size * 0.55, cx, top + size * 0.75, cx, top + size);
                    ctx.bezierCurveTo(cx, top + size * 0.75, cx + size / 2, top + size * 0.55, cx + size / 2, top + size * 0.3);
                    ctx.bezierCurveTo(cx + size / 2, top, cx, top, cx, top + size * 0.3);
                    ctx.closePath();
                    ctx.fill();
                },

                drawFish(ctx, cx, cy, size) {
                    ctx.beginPath();
                    ctx.ellipse(cx - size * 0.06, cy, size * 0.42, size * 0.3, 0, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.beginPath();
                    ctx.moveTo(cx + size * 0.34, cy - size * 0.22);
                    ctx.lineTo(cx + size * 0.62, cy);
                    ctx.lineTo(cx + size * 0.34, cy + size * 0.22);
                    ctx.closePath();
                    ctx.fill();
                },

                hexToRgb(hex) {
                    const clean = hex.replace('#', '');
                    const full = clean.length === 3 ? clean.split('').map((c) => c + c).join('') : clean;
                    const n = parseInt(full, 16);
                    return { r: (n >> 16) & 255, g: (n >> 8) & 255, b: n & 255 };
                },

                // A real tag reads as metal or enamel, not a flat fill, so
                // this shifts a color toward white (positive) or black
                // (negative) to build the gradient and rim tones below.
                shadeColor(hex, percent) {
                    const { r, g, b } = this.hexToRgb(hex);
                    const t = percent < 0 ? 0 : 255;
                    const p = Math.abs(percent);
                    const mix = (c) => Math.round((t - c) * p) + c;
                    return 'rgb(' + mix(r) + ',' + mix(g) + ',' + mix(b) + ')';
                },

                // The preview canvas is what Download and Share both save, so
                // redrawing it on every change keeps all three in sync with
                // no separate render path to drift out of step.
                redrawTag() {
                    const canvas = this.$refs.tagCanvas;
                    if (!canvas) return;
                    const ctx = canvas.getContext('2d');
                    const w = canvas.width, h = canvas.height;
                    const cx = w / 2, cy = h / 2 + 8;

                    ctx.clearRect(0, 0, w, h);

                    // A glossy radial gradient plus a drop shadow under the
                    // silhouette is what actually sells "real metal tag"
                    // instead of a flat colored sticker.
                    const highlight = this.shadeColor(this.tagColor, 0.45);
                    const rim = this.shadeColor(this.tagColor, -0.3);
                    const gradient = ctx.createRadialGradient(cx - 55, cy - 65, 15, cx, cy, 210);
                    gradient.addColorStop(0, highlight);
                    gradient.addColorStop(0.55, this.tagColor);
                    gradient.addColorStop(1, rim);

                    ctx.save();
                    ctx.shadowColor = 'rgba(0,0,0,0.35)';
                    ctx.shadowBlur = 22;
                    ctx.shadowOffsetY = 12;
                    ctx.fillStyle = gradient;
                    ctx.strokeStyle = rim;
                    ctx.lineWidth = 3;

                    let holeX = cx, holeY = cy - 130;
                    if (this.tagShape === 'heart') {
                        this.drawHeart(ctx, cx, cy, 220);
                        holeY = cy - 100;
                    } else if (this.tagShape === 'fish') {
                        this.drawFish(ctx, cx, cy, 260);
                        holeX = cx - 96;
                        holeY = cy - 30;
                    } else {
                        ctx.beginPath();
                        ctx.arc(cx, cy, 130, 0, Math.PI * 2);
                        ctx.fill();
                        ctx.stroke();
                    }
                    ctx.restore();

                    // A soft highlight ellipse over the upper-left reads as
                    // a light reflection, the detail that makes enamel or
                    // brushed metal look glossy rather than painted flat.
                    ctx.save();
                    ctx.globalAlpha = 0.22;
                    ctx.fillStyle = '#FFFFFF';
                    ctx.beginPath();
                    ctx.ellipse(cx - 55, cy - 60, 70, 38, -0.5, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.restore();

                    this.drawTagHole(ctx, holeX, holeY);

                    ctx.fillStyle = this.tagTextColor;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.font = 'bold 38px ' + this.tagFont;
                    const hasPhone = this.tagShowPhone && this.tagPhone.trim();
                    ctx.fillText(this.tagName || 'Name', cx, cy + (hasPhone ? -10 : 10));

                    if (hasPhone) {
                        ctx.font = '17px ' + this.tagFont;
                        ctx.fillText(this.tagPhone, cx, cy + 28);
                    }

                    // Keeps the "on your cat" mockup showing exactly what's
                    // on the main canvas, without a second drawing routine
                    // that could fall out of sync with this one.
                    this.$nextTick(() => {
                        if (this.$refs.tagOnCat) this.$refs.tagOnCat.src = canvas.toDataURL('image/png');
                    });
                },

                downloadTag() {
                    const canvas = this.$refs.tagCanvas;
                    if (!canvas) return;
                    const link = document.createElement('a');
                    link.download = (this.tagName || 'cat-tag').toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-tag.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                },

                shareTag() {
                    const canvas = this.$refs.tagCanvas;
                    if (!canvas) return;
                    canvas.toBlob(async (blob) => {
                        if (!blob) return;
                        const file = new File([blob], 'cat-tag.png', { type: 'image/png' });
                        if (navigator.canShare && navigator.canShare({ files: [file] })) {
                            try {
                                await navigator.share({ files: [file], title: this.tagName + ' cat tag' });
                                return;
                            } catch (e) {
                                return;
                            }
                        }
                        this.downloadTag();
                    }, 'image/png');
                },
            };
        }

    </script>
@endpush

</x-layouts.app>

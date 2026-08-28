<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-28 -left-20 size-80 rounded-full bg-primary-vivid opacity-[0.08] blur-3xl"></div>
        <div class="absolute -right-20 bottom-0 size-72 rounded-full bg-accent-vivid opacity-[0.12] blur-3xl"></div>
        <x-paw-print class="paw absolute top-[14%] left-[5%] hidden size-10 text-primary-vivid/30 lg:block [animation-duration:23s]"/>
        <x-paw-print class="paw absolute right-[42%] bottom-[10%] hidden size-7 text-accent-vivid/30 lg:block [animation-delay:-9s] [animation-duration:27s]"/>
    </div>

    <div class="container-page relative grid items-center gap-8 pt-8 pb-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.05fr)] lg:pt-6">
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

            <h1 class="mt-4 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
                Cat Name Generator
            </h1>
            <p class="mt-4 max-w-lg text-base leading-relaxed text-ink-muted sm:text-lg">
                150+ names, filtered by style, personality and even breed. Every
                one comes with a real meaning behind it, not a made-up one.
            </p>

            <ul class="mt-6 flex flex-wrap gap-x-6 gap-y-3">
                @foreach ([
                    ['Real name meanings', 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z'],
                    ['Free, no sign-up', 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
                    ['Nothing leaves your browser', 'M5.5 5h13A1.5 1.5 0 0 1 20 6.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 15.5v-9A1.5 1.5 0 0 1 5.5 5Z'],
                ] as [$label, $d])
                    <li class="group flex items-center gap-2 text-sm font-medium text-ink-muted">
                        <svg class="size-4 shrink-0 text-accent-dark transition-transform duration-300 group-hover:scale-125" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $d }}"/>
                        </svg>
                        {{ $label }}
                    </li>
                @endforeach
            </ul>

            <a href="#generator" class="btn-primary mt-7 transition-transform duration-200 hover:scale-105">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v3.5M12 17.5V21M3 12h3.5M17.5 12H21M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/>
                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                </svg>
                Start Generating
            </a>
        </div>

        <div class="reveal relative" style="--reveal-delay: 150ms">
            <div class="relative overflow-hidden rounded-[4.5rem_2rem_4.5rem_2rem] sm:rounded-[6rem_2.5rem_6rem_2.5rem] border-4 border-primary/15 bg-surface shadow-lg transition-transform duration-500 hover:-translate-y-1.5">
                <x-img name="cat-name-generator-cute-kitten" alt="Fluffy kitten beside a board of name ideas"
                       sizes="(max-width: 1023px) 92vw, 780px" :priority="true"/>
            </div>

            <div aria-hidden="true"
                 class="absolute -bottom-5 left-4 hidden items-center gap-3 rounded-2xl border border-line bg-surface px-4 py-3 shadow-lg sm:flex">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-accent-light text-accent-dark">
                    <x-paw-print class="size-5"/>
                </span>
                <span>
                    <span class="block font-heading text-sm font-extrabold text-ink">150+ names</span>
                    <span class="mt-0.5 block text-xs text-ink-muted">Every one with a real meaning</span>
                </span>
            </div>
        </div>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-10 w-full text-surface sm:h-16" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ 2. GENERATOR ══════════════════════════════════════════════════════ --}}
<section id="generator" class="scroll-mt-24 bg-surface py-10 lg:py-14"
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
    <div class="container-page max-w-4xl">

        {{-- Quick start --}}
        <div class="reveal mb-6 flex flex-wrap justify-center gap-2.5 sm:justify-start">
            <button type="button" x-on:click="surpriseMe()"
                    class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2 text-sm font-semibold text-ink shadow-sm transition hover:-translate-y-0.5 hover:border-primary/40 hover:text-primary hover:shadow-md">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="4" y="4" width="16" height="16" rx="4"/>
                    <circle cx="8.5" cy="8.5" r="1.1" fill="currentColor" stroke="none"/>
                    <circle cx="15.5" cy="8.5" r="1.1" fill="currentColor" stroke="none"/>
                    <circle cx="12" cy="12" r="1.1" fill="currentColor" stroke="none"/>
                    <circle cx="8.5" cy="15.5" r="1.1" fill="currentColor" stroke="none"/>
                    <circle cx="15.5" cy="15.5" r="1.1" fill="currentColor" stroke="none"/>
                </svg>
                Surprise Me
            </button>
            <button type="button" x-on:click="showRandomGrid()"
                    class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2 text-sm font-semibold text-ink shadow-sm transition hover:-translate-y-0.5 hover:border-primary/40 hover:text-primary hover:shadow-md">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="4" y="4" width="6" height="6" rx="1.4"/>
                    <rect x="14" y="4" width="6" height="6" rx="1.4"/>
                    <rect x="4" y="14" width="6" height="6" rx="1.4"/>
                    <rect x="14" y="14" width="6" height="6" rx="1.4"/>
                </svg>
                Random Names
            </button>
            <button type="button" x-on:click="showTrendingGrid()"
                    class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-2 text-sm font-semibold text-ink shadow-sm transition hover:-translate-y-0.5 hover:border-primary/40 hover:text-primary hover:shadow-md">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 17l6-6 4 4 8-8"/>
                    <path d="M15 7h6v6"/>
                </svg>
                Trending Names
            </button>
        </div>

        {{-- Random / trending grid --}}
        <div class="reveal mb-6" x-show="gridMode" x-cloak x-transition>
            <div class="flex items-center justify-between">
                <h2 class="font-heading text-lg font-bold text-ink" x-text="gridMode === 'trending' ? 'Trending right now' : '12 random picks'"></h2>
                <button type="button" x-on:click="gridMode = null" aria-label="Close"
                        class="flex size-8 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-soft hover:text-ink">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-2.5 sm:grid-cols-3 lg:grid-cols-4">
                <template x-for="pick in gridResults" :key="pick.name">
                    <button type="button" x-on:click="openFromGrid(pick)"
                            class="group flex flex-col items-start rounded-xl border border-line bg-surface p-3.5 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md">
                        <span class="flex w-full items-center justify-between gap-1">
                            <span class="font-heading text-base font-bold text-ink" x-text="pick.name"></span>
                            <svg class="size-4 shrink-0 text-primary-vivid transition-transform group-hover:scale-110" viewBox="0 0 24 24" :fill="isFavorite(pick.name) ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z"/>
                            </svg>
                        </span>
                        <span class="mt-1 line-clamp-2 text-xs text-ink-muted" x-text="pick.meaning"></span>
                    </button>
                </template>
            </div>
        </div>

        <div class="reveal rounded-2xl border border-line bg-surface-soft p-5 shadow-sm sm:p-8">

            {{-- One cat / two cats --}}
            <div class="mb-6 flex w-fit gap-1 rounded-full border border-line bg-surface p-1">
                <button type="button" x-on:click="twoCats = false"
                        :class="!twoCats ? 'bg-primary-vivid text-ink' : 'text-ink-muted'"
                        class="rounded-full px-4 py-1.5 text-sm font-semibold transition">One Cat</button>
                <button type="button" x-on:click="twoCats = true"
                        :class="twoCats ? 'bg-primary-vivid text-ink' : 'text-ink-muted'"
                        class="rounded-full px-4 py-1.5 text-sm font-semibold transition">Two Cats</button>
            </div>

            {{-- Gender --}}
            <div>
                <p class="text-xs font-bold tracking-wide text-ink-muted uppercase">Gender</p>
                <div class="mt-2.5 flex flex-wrap gap-2">
                    @foreach (['any' => 'Any', 'male' => 'Male', 'female' => 'Female', 'neutral' => 'Either'] as $value => $label)
                        <button type="button" x-on:click="gender = '{{ $value }}'"
                                :class="gender === '{{ $value }}' ? 'bg-primary-vivid text-ink border-primary-vivid' : 'bg-surface text-ink-muted border-line hover:border-line-strong'"
                                class="rounded-full border px-4 py-1.5 text-sm font-semibold transition">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Style --}}
            <div class="mt-6">
                <p class="text-xs font-bold tracking-wide text-ink-muted uppercase">Style <span class="font-medium normal-case text-ink-muted/70">(pick any number)</span></p>
                <div class="mt-2.5 flex flex-wrap gap-2">
                    @foreach ($styles as $style)
                        <button type="button" x-on:click="toggleStyle('{{ $style['slug'] }}')"
                                :class="styles.includes('{{ $style['slug'] }}') ? 'bg-accent text-ink-inverse border-accent' : 'bg-surface text-ink-muted border-line hover:border-line-strong'"
                                class="rounded-full border px-4 py-1.5 text-sm font-semibold transition">
                            {{ $style['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Personality, breed, letter, length --}}
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="personality" class="text-xs font-bold tracking-wide text-ink-muted uppercase">Personality</label>
                    <select id="personality" x-model="personality"
                            class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        <option value="">Any</option>
                        @foreach ($personalities as $p)
                            <option value="{{ $p['slug'] }}">{{ $p['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="breed" class="text-xs font-bold tracking-wide text-ink-muted uppercase">Breed <span class="font-medium normal-case text-primary">(unique)</span></label>
                    <select id="breed" x-model="breedSlug"
                            class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        <option value="">Any / not sure</option>
                        <template x-for="breed in breeds" :key="breed.slug">
                            <option :value="breed.slug" x-text="breed.name"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label for="letter" class="text-xs font-bold tracking-wide text-ink-muted uppercase">Starts with</label>
                    <select id="letter" x-model="letter"
                            class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        <option value="">Any letter</option>
                        <template x-for="l in 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('')" :key="l">
                            <option :value="l" x-text="l"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label for="length" class="text-xs font-bold tracking-wide text-ink-muted uppercase">Length</label>
                    <select id="length" x-model="length"
                            class="mt-2 w-full rounded-lg border border-line bg-surface px-3 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        <option value="any">Any</option>
                        <option value="short">Short</option>
                        <option value="medium">Medium</option>
                        <option value="long">Long</option>
                    </select>
                </div>
            </div>

            <p class="mt-4 text-xs text-ink-muted" x-show="poolCount() === 0" x-cloak>
                No exact match for that combination. Generating will pick from the full list instead.
            </p>

            <button type="button" x-on:click="generate()"
                    class="btn-primary mt-6 w-full justify-center rounded-full py-3.5 text-base transition-transform duration-150 active:scale-[0.98] sm:w-auto sm:px-10">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v3.5M12 17.5V21M3 12h3.5M17.5 12H21M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/>
                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                </svg>
                <span x-text="twoCats ? 'Generate a Pair' : 'Generate a Name'"></span>
            </button>
        </div>

        {{-- Favorites --}}
        <div class="reveal mt-6" x-show="favorites.length" x-cloak>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="flex items-center gap-2 text-sm font-bold text-ink">
                    <svg class="size-4 text-primary-vivid" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z"/>
                    </svg>
                    Your favorites <span class="text-ink-muted" x-text="'(' + favorites.length + ')'"></span>
                </p>
                <div class="flex items-center gap-1.5">
                    <button type="button" x-on:click="copyAllFavorites()"
                            class="rounded-full border border-line bg-surface px-3 py-1.5 text-xs font-semibold text-ink-muted transition hover:border-line-strong hover:text-ink"
                            x-text="favoritesCopied ? 'Copied!' : 'Copy all'"></button>
                    <button type="button" x-on:click="downloadFavorites()"
                            class="rounded-full border border-line bg-surface px-3 py-1.5 text-xs font-semibold text-ink-muted transition hover:border-line-strong hover:text-ink">Download</button>
                    <button type="button" x-on:click="clearFavorites()"
                            class="rounded-full border border-line bg-surface px-3 py-1.5 text-xs font-semibold text-danger transition hover:border-danger/40 hover:bg-danger-light">Clear all</button>
                </div>
            </div>
            <ul class="mt-3 flex flex-wrap gap-2">
                <template x-for="fav in favorites" :key="fav.name">
                    <li class="flex items-center gap-2 rounded-full border border-line bg-surface py-1.5 pr-2 pl-4 text-sm font-semibold text-ink shadow-sm">
                        <span x-text="fav.name"></span>
                        <button type="button" x-on:click="removeFavorite(fav.name)" aria-label="Remove from favorites"
                                class="flex size-5 items-center justify-center rounded-full text-ink-muted transition hover:bg-danger-light hover:text-danger">
                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                        </button>
                    </li>
                </template>
            </ul>
        </div>
    </div>

    {{-- Result dialog: reuses the site's cat-face reveal animation (the same
         one the tool result cards use elsewhere), driven by Alpine instead
         of a server round trip since there is nothing here to send to a
         server in the first place. --}}
    <dialog x-ref="resultDialog" aria-labelledby="name-result-heading"
            class="result-card m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl border border-line bg-surface p-0 shadow-2xl backdrop:bg-ink/50 backdrop:backdrop-blur-sm"
            x-on:click="if ($event.target === $refs.resultDialog) $refs.resultDialog.close()">

        {{-- Single name --}}
        <template x-if="result && !result.pair">
            <div class="p-6 text-center sm:p-8">
                <div class="relative mx-auto flex size-32 items-center justify-center rounded-full bg-primary-light">
                    <svg class="result-cat size-24" viewBox="0 0 120 120" fill="none" aria-hidden="true">
                        <path d="M40 36 L45 13 L60 30 Z" fill="currentColor" class="text-primary-vivid"/>
                        <path d="M80 36 L75 13 L60 30 Z" fill="currentColor" class="text-primary-vivid"/>
                        <path d="M45 33 L47 21 L55 30 Z" fill="#FCE3DD"/>
                        <path d="M75 33 L73 21 L65 30 Z" fill="#FCE3DD"/>
                        <ellipse cx="60" cy="54" rx="31" ry="27" fill="currentColor" class="text-primary-vivid"/>
                        <ellipse cx="60" cy="64" rx="17" ry="12" fill="#FFF6F1"/>
                        <ellipse class="result-eye" cx="49" cy="50" rx="4.6" ry="6" fill="#12383B"/>
                        <ellipse class="result-eye" cx="71" cy="50" rx="4.6" ry="6" fill="#12383B"/>
                        <circle cx="50.6" cy="47.8" r="1.5" fill="#FFFFFF"/>
                        <circle cx="72.6" cy="47.8" r="1.5" fill="#FFFFFF"/>
                        <path d="M56 59 h8 l-4 4.5 Z" fill="#12383B"/>
                        <path d="M60 63.5 q-4 5 -8 1.5 M60 63.5 q4 5 8 1.5" stroke="#12383B" stroke-width="2.4" stroke-linecap="round"/>
                        <path d="M42 60 L26 56 M42 65 L27 66 M78 60 L94 56 M78 65 L93 66" stroke="#12383B" stroke-width="2" stroke-linecap="round" opacity="0.45"/>
                        <g class="result-paw">
                            <ellipse cx="98" cy="74" rx="9" ry="10" fill="currentColor" class="text-primary-vivid"/>
                            <circle cx="94" cy="69" r="2.1" fill="#FCE3DD"/>
                            <circle cx="98.5" cy="67.5" r="2.1" fill="#FCE3DD"/>
                            <circle cx="102.5" cy="70" r="2.1" fill="#FCE3DD"/>
                            <ellipse cx="98" cy="76" rx="4" ry="3.4" fill="#FCE3DD"/>
                        </g>
                        <path class="result-heart" d="M20 30c-2.4-1.5-4.6-3.1-4.6-5.6a2.6 2.6 0 0 1 4.6-1.5 2.6 2.6 0 0 1 4.6 1.5c0 2.5-2.2 4.1-4.6 5.6Z" fill="#F47C6B"/>
                        <path class="result-heart result-heart-2" d="M100 26c-2-1.3-3.9-2.6-3.9-4.7a2.2 2.2 0 0 1 3.9-1.3 2.2 2.2 0 0 1 3.9 1.3c0 2.1-1.9 3.4-3.9 4.7Z" fill="#F47C6B"/>
                        <path class="result-heart result-heart-3" d="M31 15c-1.7-1.1-3.3-2.2-3.3-4a1.9 1.9 0 0 1 3.3-1.1 1.9 1.9 0 0 1 3.3 1.1c0 1.8-1.6 2.9-3.3 4Z" fill="#F47C6B"/>
                    </svg>
                </div>

                <h2 id="name-result-heading" class="mt-5 font-heading text-3xl font-extrabold tracking-tight text-ink" x-text="result.name"></h2>
                <p class="mt-1 text-sm font-semibold text-ink-muted" x-show="result.nickname" x-cloak>
                    Nickname: <span x-text="result.nickname"></span>
                </p>

                <p class="mt-3 text-base leading-relaxed text-ink-muted" x-text="result.meaning"></p>

                <div class="mt-3 flex flex-wrap justify-center gap-1.5" x-show="result.origin_country || (result.personalities && result.personalities.length)" x-cloak>
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
                    <button type="button" x-on:click="generate()" class="btn-outline rounded-full px-5 py-2.5 text-sm">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 11a8 8 0 1 0-2.3 5.6M20 4v7h-7"/></svg>
                        Another
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

        {{-- Two cats --}}
        <template x-if="result && result.pair">
            <div class="p-6 text-center sm:p-8">
                <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-primary-light text-primary">
                    <svg class="size-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z"/>
                    </svg>
                </div>
                <h2 id="name-result-heading" class="mt-4 font-heading text-2xl font-extrabold tracking-tight text-ink">
                    <span x-text="result.cats[0].name"></span> &amp; <span x-text="result.cats[1].name"></span>
                </h2>

                <div class="mt-5 grid grid-cols-2 gap-3 text-left">
                    <template x-for="cat in result.cats" :key="cat.name">
                        <div class="rounded-xl border border-line bg-surface-soft p-3.5">
                            <p class="font-heading text-base font-bold text-ink" x-text="cat.name"></p>
                            <p class="mt-1 text-xs leading-relaxed text-ink-muted" x-text="cat.meaning"></p>
                        </div>
                    </template>
                </div>

                <p class="mt-4 rounded-xl border border-line bg-surface-soft p-3.5 text-left text-sm leading-relaxed text-ink-muted" x-text="result.why"></p>

                <div class="mt-6 flex flex-wrap justify-center gap-2.5">
                    <button type="button" x-on:click="generate()" class="btn-outline rounded-full px-5 py-2.5 text-sm">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 11a8 8 0 1 0-2.3 5.6M20 4v7h-7"/></svg>
                        Another Pair
                    </button>
                    <button type="button" x-on:click="toggleFavorite(result.cats[0]); toggleFavorite(result.cats[1])" class="btn-outline rounded-full px-5 py-2.5 text-sm">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z"/>
                        </svg>
                        Save Both
                    </button>
                </div>

                <div class="mt-5 border-t border-line pt-5">
                    <p class="text-xs font-bold tracking-wide text-ink-muted uppercase">Share this pair</p>
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

{{-- ══ 3. SEO CONTENT ════════════════════════════════════════════════════ --}}
<section class="border-t border-line bg-surface-section py-10 lg:py-14">
    <div class="mx-auto w-full max-w-[1600px] px-5 sm:px-8 lg:px-[50px]">

        <div class="reveal mx-auto max-w-3xl text-center">
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <x-paw-print class="size-4"/>
                The full guide
            </p>
            <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                Everything worth knowing about naming a cat
            </h2>
        </div>

        {{-- How it works --}}
        <div class="reveal mx-auto mt-10 max-w-3xl">
            <h3 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">How cat naming actually works</h3>
            <div class="mt-4 space-y-4 text-base leading-relaxed text-ink-muted">
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
        <div class="reveal mt-14">
            <h3 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">Popular cat names</h3>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-ink-muted">
                No organization publishes a single verified real-time ranking of
                cat names, so treat this as what turns up again and again in
                cat-owner communities, vet clinic intake forms and pet insurance
                sign-ups, not an official chart.
            </p>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Popular female cat names</h4>
                        <p class="card-text">{{ implode(', ', $popularNames['female']) }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Popular male cat names</h4>
                        <p class="card-text">{{ implode(', ', $popularNames['male']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- By style --}}
        <div class="reveal mt-14">
            <h3 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">Cat names by style</h3>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-ink-muted">
                The style filter above covers ten long-tail directions, from
                mythology to food-inspired names. Here is a sample from each.
            </p>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($styleExamples as $data)
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $data['label'] }} cat names</h4>
                            <p class="card-text">{{ $data['names']->pluck('name')->implode(', ') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- By personality --}}
        <div class="reveal mt-14">
            <h3 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">Cat names by personality</h3>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-ink-muted">
                A name that matches how a cat actually acts tends to feel right
                longer than one picked from looks alone.
            </p>
            <div class="mt-6 overflow-hidden rounded-2xl border border-line">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-4 py-3 font-semibold">Personality</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Example names</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line bg-surface">
                        @foreach ($personalityExamples as $data)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-ink">{{ $data['label'] }}</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $data['names']->pluck('name')->implode(', ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- By breed --}}
        <div class="reveal mt-14">
            <h3 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">Cat names by breed</h3>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-ink-muted">
                Maine Coon, Siamese, Persian, Bengal, Russian Blue and more,
                matched to a theme that actually fits the breed rather than a
                generic list reused for all of them.
            </p>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($breedGuide as $breed)
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $breed['breed'] }} cat names</h4>
                            <p class="mt-0.5 text-xs font-semibold text-primary">{{ $breed['theme'] }}</p>
                            <p class="card-text">{{ implode(', ', $breed['names']) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Kitten vs adult --}}
        <div class="reveal mx-auto mt-14 max-w-3xl">
            <h3 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">Kitten names vs. adult cat names</h3>
            <div class="mt-4 space-y-4 text-base leading-relaxed text-ink-muted">
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
        <div class="reveal mx-auto mt-14 max-w-3xl">
            <h3 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">Seven rules for choosing the right cat name</h3>
            <ol class="mt-4 space-y-3 text-base leading-relaxed text-ink-muted">
                <li><strong class="text-ink">1. Keep it short.</strong> One or two syllables is easiest for a cat to learn and for you to call out consistently.</li>
                <li><strong class="text-ink">2. End on a clear sound.</strong> Names like Luna, Milo or Jax cut through background noise better than soft, trailing ones, which is part of why vets and trainers so often repeat this advice.</li>
                <li><strong class="text-ink">3. Say it out loud, not just on paper.</strong> A name that reads well can still sound awkward called across a yard.</li>
                <li><strong class="text-ink">4. Avoid names that sound like commands.</strong> "Kit" sounds close to "sit," and overlap with a command word slows down training.</li>
                <li><strong class="text-ink">5. Pick something the whole household agrees on.</strong> A name everyone shortens differently just confuses a cat.</li>
                <li><strong class="text-ink">6. Let personality guide it, not just looks.</strong> A name chosen after living with a cat for a few days often fits better than one picked from a photo alone.</li>
                <li><strong class="text-ink">7. Make sure it still works in five years.</strong> Cats live well into their teens, so a name should suit a full-grown adult cat, not just a kitten.</li>
            </ol>
        </div>

        {{-- Two cats --}}
        <div class="reveal mt-14">
            <h3 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">Cat names for two cats</h3>
            <p class="mt-4 max-w-3xl text-base leading-relaxed text-ink-muted">
                Naming two cats at once opens up pairs. Two Cats mode in the
                generator above builds a matched pair automatically and
                explains what actually connects them. A few tested pair ideas
                to start from:
            </p>
            <div class="mt-6 overflow-hidden rounded-2xl border border-line">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-4 py-3 font-semibold">Theme</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Pair</th>
                            <th scope="col" class="px-4 py-3 font-semibold">Why it works</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line bg-surface">
                        @foreach ($pairExamples as $pair)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-ink">{{ $pair['theme'] }}</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $pair['name1'] }} &amp; {{ $pair['name2'] }}</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $pair['why'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Internal links --}}
        <div class="reveal mt-14">
            <h3 class="font-heading text-xl font-extrabold tracking-tight text-ink sm:text-2xl">Keep exploring</h3>
            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($internalLinks as $link)
                    <a href="{{ $link['url'] }}"
                       class="group flex items-center justify-between gap-2 rounded-xl border border-line bg-surface px-4 py-3.5 text-sm font-semibold text-ink shadow-sm transition hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md">
                        {{ $link['label'] }}
                        <svg class="size-4 shrink-0 text-ink-muted transition-transform group-hover:translate-x-0.5 group-hover:text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m9 6 6 6-6 6"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══ 4. FAQ ════════════════════════════════════════════════════════════ --}}
<section id="faq" class="scroll-mt-24 bg-surface-soft py-10 lg:py-14">
    <div class="container-page max-w-3xl">
        <div class="reveal text-center">
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <x-paw-print class="size-4"/>
                Common questions
            </p>
            <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                Cat names, answered
            </h2>
        </div>

        <div class="mt-8 space-y-3">
            @foreach ($faq as $item)
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
                favorites: [],
                favoritesCopied: false,
                shareCopied: false,
                gridMode: null,
                gridResults: [],

                init() {
                    try {
                        this.favorites = JSON.parse(localStorage.getItem('purrquery-name-favorites') || '[]');
                    } catch (e) {
                        this.favorites = [];
                    }
                },

                toggleStyle(slug) {
                    const i = this.styles.indexOf(slug);
                    if (i === -1) this.styles.push(slug); else this.styles.splice(i, 1);
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

                generate() {
                    const pool = this.pool();
                    const source = pool.length ? pool : this.names;

                    if (this.twoCats) {
                        const a = this.pickOne(source);
                        let b = this.pickOne(source);
                        let attempts = 0;
                        while (b.name === a.name && attempts < 10) {
                            b = this.pickOne(source);
                            attempts++;
                        }
                        this.result = { pair: true, cats: [a, b], why: this.pairWhy(a, b) };
                    } else {
                        this.result = { pair: false, ...this.pickOne(source) };
                    }

                    this.gridMode = null;
                    this.$nextTick(() => this.$refs.resultDialog?.showModal());
                },

                surpriseMe() {
                    const genders = ['any', 'male', 'female', 'neutral'];
                    this.gender = genders[Math.floor(Math.random() * genders.length)];
                    this.styles = Math.random() < 0.6 ? [this.pickOne(this.styleSlugs)] : [];
                    this.personality = Math.random() < 0.5 ? this.pickOne(this.personalitySlugs) : '';
                    this.letter = '';
                    this.length = 'any';
                    this.breedSlug = '';
                    this.twoCats = false;
                    this.generate();
                },

                showRandomGrid() {
                    this.gridMode = 'random';
                    this.gridResults = [...this.names].sort(() => Math.random() - 0.5).slice(0, 12);
                },

                showTrendingGrid() {
                    this.gridMode = 'trending';
                    this.gridResults = this.trending;
                },

                openFromGrid(pick) {
                    this.result = { pair: false, ...pick };
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
                    if (this.result.pair) {
                        return this.result.cats[0].name + ' & ' + this.result.cats[1].name + ': cat name ideas from PurrQuery';
                    }
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

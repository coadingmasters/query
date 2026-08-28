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
         x-data="catNameGenerator(@js($names), @js($breeds))">
    <div class="container-page max-w-4xl">
        <div class="reveal rounded-2xl border border-line bg-surface-soft p-5 shadow-sm sm:p-8">

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
                Generate a Name
            </button>
        </div>

        {{-- Favorites --}}
        <div class="reveal mt-6" x-show="favorites.length" x-cloak>
            <p class="flex items-center gap-2 text-sm font-bold text-ink">
                <svg class="size-4 text-primary-vivid" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 21s-6.7-4.35-9.3-8.1C1 10.2 1.6 6.9 4.2 5.4c2.2-1.3 4.9-.6 6.3 1.4l1.5 2 1.5-2c1.4-2 4.1-2.7 6.3-1.4 2.6 1.5 3.2 4.8 1.5 7.5C18.7 16.65 12 21 12 21Z"/>
                </svg>
                Your favorites
            </p>
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
        <template x-if="result">
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
                    <button type="button" x-on:click="share()" class="btn-outline rounded-full px-5 py-2.5 text-sm">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8a3 3 0 1 0-2.8-4H15a3 3 0 0 0 .1 3.2L8.9 10.7a3 3 0 1 0 0 2.6l6.2 3.5a3 3 0 1 0 .8-1.7l-6.2-3.5a3 3 0 0 0 0-1.2l6.2-3.5c.3.3.7.5 1.1.6Z"/></svg>
                        <span x-text="shareCopied ? 'Copied!' : 'Share'"></span>
                    </button>
                </div>

                <button type="button" x-on:click="$refs.resultDialog.close()" class="mt-4 text-sm font-semibold text-ink-muted hover:text-ink">
                    Close
                </button>
            </div>
        </template>
    </dialog>
</section>

{{-- ══ 3. How it works / breed integration note ═════════════════════════ --}}
<section class="border-t border-line bg-surface-section py-10 lg:py-14">
    <div class="container-page max-w-3xl">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
            How to actually choose a name
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
        function catNameGenerator(names, breeds) {
            return {
                names,
                breeds,
                gender: 'any',
                styles: [],
                personality: '',
                breedSlug: '',
                letter: '',
                length: 'any',
                result: null,
                favorites: [],
                shareCopied: false,

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

                generate() {
                    const pool = this.pool();
                    const source = pool.length ? pool : this.names;
                    this.result = source[Math.floor(Math.random() * source.length)];
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

                async share() {
                    const text = this.result.name + ': ' + this.result.meaning;
                    if (navigator.share) {
                        try {
                            await navigator.share({ title: 'Cat name idea', text, url: location.href });
                            return;
                        } catch (e) {
                            return;
                        }
                    }
                    try {
                        await navigator.clipboard.writeText(text + ' ' + location.href);
                        this.shareCopied = true;
                        setTimeout(() => { this.shareCopied = false; }, 2000);
                    } catch (e) {}
                },
            };
        }
    </script>
@endpush

</x-layouts.app>

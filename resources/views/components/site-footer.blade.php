@props(['wave' => 'text-surface'])

@php
    // The span is written out in full: Tailwind only generates classes it can
    // find as literal strings, so "lg:col-span-{$n}" would produce nothing.
    $columns = [
        [
            'heading' => 'Explore',
            'span' => 'lg:col-span-2',
            'items' => [
                ['All tools', '/#tools'],
                ['Food guides', '/#food-guides'],
                ['Blog', '/#blog'],
                ['How it works', '/#how-it-works'],
                ['About Us', route('about')],
                ['Contact Us', route('contact')],
            ],
        ],
        [
            'heading' => 'Popular tools',
            'span' => 'lg:col-span-2',
            'items' => collect(config('catalog.tools'))->take(6)
                ->map(fn ($t) => [$t['title'], '/#tools'])->all(),
        ],
        [
            // Short labels here, not the full questions: a footer column is
            // for scanning, and the questions are already on the cards.
            // Hidden on phones: two link columns is enough to scan there, and
            // the third only makes the footer longer to scroll past.
            'heading' => 'Resources',
            'span' => 'hidden sm:block lg:col-span-2',
            'items' => collect(config('catalog.foods'))->take(5)
                ->map(fn ($f) => [$f['title'], '/#food-guides'])
                ->prepend(['FAQ', route('faq')])->all(),
        ],
    ];
@endphp

{{-- Deep teal rather than another pale band: it closes the page instead of
     letting it fade out, and white on this reads at 12.6:1. --}}
<footer class="relative overflow-hidden bg-primary-dark text-ink-inverse">

    {{-- The same curve as the wave under the hero, rotated a half turn and
         painted in the color of the section above, so it reads as that band
         dipping into this one. It sits inside the footer on purpose: the
         overflow-hidden here would clip anything placed above it. --}}
    <svg class="absolute inset-x-0 top-0 h-10 w-full {{ $wave }} sm:h-14"
         viewBox="0 0 1440 80" preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path transform="rotate(180 720 40)"
              d="M0 80c180-30 360-30 540-8s360 36 540 13 300-36 360-40V80Z"/>
    </svg>

    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <div class="absolute -top-24 -left-20 size-72 rounded-full bg-primary opacity-30 blur-3xl"></div>
        <div class="absolute -right-16 bottom-0 size-64 rounded-full bg-accent-vivid opacity-20 blur-3xl"></div>
        <x-paw-print class="paw absolute top-[30%] right-[3%] hidden size-20 text-ink-inverse opacity-[0.07] sm:block [animation-duration:26s]"/>
        <x-paw-print class="paw absolute bottom-[8%] left-[2%] hidden size-12 text-ink-inverse opacity-[0.07] sm:block [animation-delay:-9s] [animation-duration:22s]"/>
    </div>

    <div class="container-page relative pt-12 pb-8 sm:pt-14">
        <div class="grid grid-cols-2 gap-x-8 gap-y-8 sm:grid-cols-3 lg:grid-cols-12 lg:gap-8">

            {{-- Brand --}}
            <div class="col-span-2 sm:col-span-3 lg:col-span-3">
                <div class="flex items-center gap-3">
                    {{-- The badge is dark on transparent, so it needs a light
                         chip behind it to stay visible on this ground. --}}
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-surface p-1.5 shadow-md">
                        <x-img name="purrquerylogo" alt="{{ config('app.name') }}" sizes="40px" fit="contain"/>
                    </span>
                    <span class="font-heading text-xl font-extrabold tracking-tight">
                        {{ config('app.name') }}
                    </span>
                </div>

                <p class="mt-4 max-w-xs text-sm leading-relaxed text-ink-inverse/75">
                    {{ config('brand.tagline') }}. Free to use, no account required,
                    and nothing about your cat leaves your device.
                </p>

                <a href="mailto:{{ config('brand.email') }}"
                   class="mt-4 inline-flex items-center gap-2 rounded-full bg-surface/10 px-4 py-2 text-sm font-semibold ring-1 ring-surface/20 transition hover:bg-surface/20">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Z"/>
                        <path d="m3.5 8 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5"/>
                    </svg>
                    {{ config('brand.email') }}
                </a>
            </div>

            {{-- Link columns --}}
            @foreach ($columns as $column)
                <nav class="{{ $column['span'] }}" aria-label="{{ $column['heading'] }}">
                    <h2 class="font-heading text-sm font-bold tracking-wider text-ink-inverse uppercase">{{ $column['heading'] }}</h2>
                    <ul class="mt-4 space-y-2.5">
                        @foreach ($column['items'] as [$label, $href])
                            <li>
                                <a href="{{ $href }}"
                                   class="text-sm text-ink-inverse/75 transition hover:text-ink-inverse">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endforeach

            {{-- Newsletter. Posts to the same endpoint as the one on the home
                 page; the ids differ because both render on that page and a
                 repeated id would break the label association. --}}
            <div class="col-span-2 sm:col-span-3 lg:col-span-3">
                <h2 class="font-heading text-sm font-bold tracking-wider text-ink-inverse uppercase">Stay in the loop</h2>
                <p class="mt-3 text-sm leading-relaxed text-ink-inverse/75">
                    One email a month with new tools and guides. No selling your
                    address, and one click to leave.
                </p>

                {{-- The form is never replaced. It stays on screen and comes
                     back empty, and what happened is announced in the dialog
                     below, the same way the contact form does it. --}}
                <form method="POST" action="{{ route('subscribe') }}" class="mt-4 space-y-2.5">
                    @csrf
                    <label for="footer-email" class="sr-only">Email address</label>
                    <input id="footer-email" name="email" type="email" required
                           placeholder="Enter your email" autocomplete="email"
                           class="w-full rounded-lg border border-surface/25 bg-surface px-4 py-2.5 text-sm text-ink transition placeholder:text-ink-muted focus:border-primary-vivid focus:ring-2 focus:ring-primary-vivid/40 focus:outline-none">

                    {{-- Honeypot: a real person never sees this, so anything
                         that fills it in is a bot. Cheaper and less hostile
                         than a captcha. --}}
                    <div class="absolute left-[-9999px]" aria-hidden="true">
                        <label for="footer-website">Leave this empty</label>
                        <input id="footer-website" name="website" type="text" tabindex="-1" autocomplete="off">
                    </div>

                    <button type="submit"
                            class="w-full rounded-lg bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-md transition hover:brightness-95">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-10 flex flex-col gap-3 border-t border-surface/15 pt-5 sm:flex-row sm:items-center sm:justify-between">
            <p class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-ink-inverse/80">
                <span>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
                <span class="inline-flex items-center gap-1.5">
                    Made with
                    {{-- Not aria-hidden: the heart is a word in this sentence,
                         and hiding it leaves "Made with for cat owners". --}}
                    <svg class="size-4 text-primary-vivid" viewBox="0 0 24 24" fill="currentColor" role="img">
                        <title>love</title>
                        <path d="M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z"/>
                    </svg>
                    for cat owners
                </span>
            </p>
            <ul class="flex flex-wrap items-center gap-x-6 gap-y-2">
                @foreach ([['Privacy Policy', route('privacy')], ['Terms & Conditions', route('terms')], ['Sitemap', route('sitemap')]] as [$label, $href])
                    <li>
                        <a href="{{ $href }}" class="text-sm text-ink-inverse/80 transition hover:text-ink-inverse">{{ $label }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>

{{-- ══ Subscribe result ══════════════════════════════════════════════════ --}}
{{-- Outside the footer element: the footer clips its overflow and paints its
     own background, and a modal inside it inherits both. --}}
@if (session('subscribed'))
    <x-result-dialog tone="success" heading="You are on the list!" focus="#footer-email">
        <p>
            Thank you. One email a month, nothing else, and every one of them
            carries a one-click unsubscribe link.
        </p>
    </x-result-dialog>
@elseif ($errors->has('email'))
    <x-result-dialog tone="error" heading="That address did not look right" focus="#footer-email">
        <p>{{ $errors->first('email') }}</p>
    </x-result-dialog>
@endif

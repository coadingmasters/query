@php
    // Every item links to its own real page, not a same-page anchor: a
    // fragment like "#tools" is invisible to Google and does nothing at all
    // from a page that is not "/". A tool with no page of its own yet points
    // at the tools index, where it is honestly listed as "coming soon".
    //
    // The bar carries five things. Blog, How It Works and the FAQ moved to the
    // footer: a navigation that lists everything ranks nothing, and these are
    // the five a cat owner arrives wanting.
    $tools = collect(config('catalog.tools'))->map(fn (array $t): array => [
        'label' => $t['title'],
        'href' => $t['url'] ?? route('tools.index'),
        'live' => isset($t['url']),
        'blurb' => $t['blurb'],
    ]);

    $foods = collect(config('catalog.foods'))->map(fn (array $f): array => [
        'label' => $f['title'],
        'href' => route('food-guides.show', $f['slug']),
    ]);
@endphp

<header class="sticky top-0 z-50 border-b border-line/70 bg-surface/85 backdrop-blur-md">
    <div class="mx-auto flex h-24 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

        {{-- The badge alone, with no wordmark beside it. It carries the name
             around its rim, so it is sized to let that read, and the alt text
             has to spell the name out, because it is now the only thing naming
             this link to a screen reader or to Google. --}}
        <a href="/" class="group flex shrink-0 items-center">
            <span class="block size-18 shrink-0 transition-transform duration-300 group-hover:scale-105 sm:size-20">
                <x-img name="purrquerylogo" alt="{{ config('app.name') }}" sizes="80px" fit="contain"/>
            </span>
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="Main">
            <a href="/" class="rounded-lg px-3 py-2 text-sm font-semibold text-ink-muted transition-colors hover:bg-surface-soft hover:text-primary">
                Home
            </a>

            {{-- Each menu is a button and a panel rather than a hover-only
                 popup: hover alone cannot be reached from a keyboard and
                 cannot be opened on a touchscreen at all. --}}
            <div class="relative" data-menu>
                <button type="button" data-menu-button aria-expanded="false" aria-haspopup="true"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold text-ink-muted transition-colors hover:bg-surface-soft hover:text-primary aria-expanded:bg-surface-soft aria-expanded:text-primary">
                    Cat Foods
                    <svg class="size-4 transition-transform duration-200" data-menu-chevron viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </button>

                <div data-menu-panel hidden
                     class="absolute top-full left-0 z-50 mt-3 w-[30rem] origin-top scale-95 rounded-2xl border border-line bg-surface p-3 opacity-0 shadow-2xl transition duration-200 ease-out">
                    <p class="px-3 pt-1 pb-2 text-xs font-bold tracking-wider text-ink-muted uppercase">
                        What is safe to feed
                    </p>
                    <ul class="grid grid-cols-2 gap-1">
                        @foreach ($foods as $food)
                            <li>
                                <a href="{{ $food['href'] }}"
                                   class="group/item flex items-center justify-between gap-2 rounded-xl px-3 py-2.5 transition-colors hover:bg-surface-soft">
                                    <span class="text-sm font-medium text-ink">{{ $food['label'] }}</span>
                                    <svg class="size-3.5 shrink-0 text-ink-muted opacity-0 transition-all duration-150 group-hover/item:translate-x-0.5 group-hover/item:text-primary group-hover/item:opacity-100"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                                        <path d="m9 6 6 6-6 6"/>
                                    </svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('food-guides.index') }}" class="mt-1 flex items-center gap-1.5 rounded-xl px-3 py-2.5 text-sm font-semibold text-primary transition-colors hover:bg-surface-soft">
                        All food guides
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                    </a>
                </div>
            </div>

            <div class="relative" data-menu>
                <button type="button" data-menu-button aria-expanded="false" aria-haspopup="true"
                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold text-ink-muted transition-colors hover:bg-surface-soft hover:text-primary aria-expanded:bg-surface-soft aria-expanded:text-primary">
                    Tools
                    <svg class="size-4 transition-transform duration-200" data-menu-chevron viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </button>

                <div data-menu-panel hidden
                     class="absolute top-full left-0 z-50 mt-3 w-[32rem] origin-top scale-95 rounded-2xl border border-line bg-surface p-3 opacity-0 shadow-2xl transition duration-200 ease-out">
                    <p class="px-3 pt-1 pb-2 text-xs font-bold tracking-wider text-ink-muted uppercase">
                        Free calculators and checkers
                    </p>
                    <ul class="grid gap-1 sm:grid-cols-2">
                        @foreach ($tools as $tool)
                            <li>
                                <a href="{{ $tool['href'] }}"
                                   class="group/item flex items-start gap-2.5 rounded-xl px-3 py-2.5 transition-colors hover:bg-surface-soft">
                                    <span class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary-light text-primary transition-transform duration-200 group-hover/item:scale-110">
                                        <x-paw-print class="size-3.5"/>
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block text-sm font-medium text-ink">{{ $tool['label'] }}</span>
                                        @unless ($tool['live'])
                                            <span class="mt-0.5 block text-[11px] font-semibold text-ink-muted">Coming soon</span>
                                        @endunless
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('tools.index') }}" class="mt-1 flex items-center gap-1.5 rounded-xl px-3 py-2.5 text-sm font-semibold text-primary transition-colors hover:bg-surface-soft">
                        All tools
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                    </a>
                </div>
            </div>

            <a href="{{ route('blog.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-ink-muted transition-colors hover:bg-surface-soft hover:text-primary">
                Blog
            </a>
            <a href="{{ route('shop.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-ink-muted transition-colors hover:bg-surface-soft hover:text-primary">
                Shop
            </a>
            <a href="{{ route('about') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-ink-muted transition-colors hover:bg-surface-soft hover:text-primary">
                About Us
            </a>
            <a href="{{ route('contact') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-ink-muted transition-colors hover:bg-surface-soft hover:text-primary">
                Contact Us
            </a>
        </nav>

        <div class="flex items-center gap-2 sm:gap-3">
            <a href="{{ route('tools.index') }}"
               class="group hidden items-center gap-2 rounded-full bg-primary-vivid px-5 py-2.5 text-sm font-semibold text-ink shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:brightness-95 sm:inline-flex">
                <svg class="size-4 transition-transform duration-300 group-hover:rotate-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v3.5M12 17.5V21M3 12h3.5M17.5 12H21M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/>
                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                </svg>
                Try Free Tools
            </a>

            {{-- Three bars that become an X. The middle bar fades and the outer
                 two rotate onto it, so the button says which state it is in
                 rather than swapping one glyph for another. --}}
            <button type="button" data-drawer-toggle aria-expanded="false" aria-controls="mobile-drawer"
                    class="group relative inline-flex size-12 items-center justify-center rounded-full text-ink transition-transform duration-200 hover:bg-surface-soft active:scale-90 lg:hidden">
                <span class="sr-only">Open menu</span>
                <span aria-hidden="true" class="relative block h-4 w-5">
                    <span class="absolute inset-x-0 top-0 h-0.5 rounded-full bg-ink transition-transform duration-300 group-aria-expanded:translate-y-[7px] group-aria-expanded:rotate-45"></span>
                    <span class="absolute inset-x-0 top-[7px] h-0.5 rounded-full bg-ink transition-all duration-200 group-aria-expanded:scale-x-0 group-aria-expanded:opacity-0"></span>
                    <span class="absolute inset-x-0 top-[14px] h-0.5 rounded-full bg-ink transition-transform duration-300 group-aria-expanded:-translate-y-[7px] group-aria-expanded:-rotate-45"></span>
                </span>
            </button>
        </div>
    </div>
</header>

{{-- ══ MOBILE DRAWER ═════════════════════════════════════════════════════ --}}
{{-- Outside the header, because the header is sticky and creates a stacking
     context that a fixed overlay inside it cannot escape. --}}
<div data-drawer-backdrop hidden
     class="fixed inset-x-0 top-20 bottom-0 z-40 bg-ink/50 opacity-0 backdrop-blur-sm transition-opacity duration-300 lg:hidden"></div>

<aside id="mobile-drawer" data-drawer hidden aria-label="Main, mobile"
       class="fixed top-20 bottom-0 left-0 z-45 flex w-[19rem] max-w-[85vw] -translate-x-full flex-col border-r border-line bg-surface shadow-2xl transition-transform duration-300 ease-out lg:hidden">

    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <a href="/" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-base font-semibold text-ink transition-colors hover:bg-surface-soft">
            <span class="flex size-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m3 10.5 9-7 9 7V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1Z"/>
                </svg>
            </span>
            Home
        </a>

        {{-- details/summary for the two groups: it opens with a keyboard and
             works before the script runs. --}}
        <details class="group mt-1">
            <summary class="flex cursor-pointer list-none items-center gap-3 rounded-xl px-3 py-2.5 text-base font-semibold text-ink transition-colors hover:bg-surface-soft marker:content-['']">
                <span class="flex size-8 items-center justify-center rounded-lg bg-accent-light text-accent-dark">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3.5 12.5h17a8.5 8.5 0 0 1-17 0Z"/><path d="M6 9.2c0-1.6 1.4-2.2 1.4-3.4M10.5 9.2c0-1.6 1.4-2.2 1.4-3.4M15 9.2c0-1.6 1.4-2.2 1.4-3.4"/>
                    </svg>
                </span>
                Cat Foods
                <svg class="ml-auto size-4 text-ink-muted transition-transform duration-200 group-open:rotate-180"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </summary>
            <ul class="mt-1 space-y-0.5 pb-1 pl-11">
                @foreach ($foods as $food)
                    <li>
                        <a href="{{ $food['href'] }}" class="block rounded-lg px-3 py-2 text-sm text-ink-muted transition-colors hover:bg-surface-soft hover:text-primary">
                            {{ $food['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </details>

        <details class="group mt-1">
            <summary class="flex cursor-pointer list-none items-center gap-3 rounded-xl px-3 py-2.5 text-base font-semibold text-ink transition-colors hover:bg-surface-soft marker:content-['']">
                <span class="flex size-8 items-center justify-center rounded-lg bg-primary-light text-primary">
                    <x-paw-print class="size-4"/>
                </span>
                Tools
                <svg class="ml-auto size-4 text-ink-muted transition-transform duration-200 group-open:rotate-180"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </summary>
            <ul class="mt-1 space-y-0.5 pb-1 pl-11">
                @foreach ($tools as $tool)
                    <li>
                        <a href="{{ $tool['href'] }}" class="flex items-center justify-between gap-2 rounded-lg px-3 py-2 text-sm text-ink-muted transition-colors hover:bg-surface-soft hover:text-primary">
                            {{ $tool['label'] }}
                            @unless ($tool['live'])
                                <span class="rounded-full bg-surface-section px-1.5 py-0.5 text-[10px] font-bold text-ink-muted">Soon</span>
                            @endunless
                        </a>
                    </li>
                @endforeach
            </ul>
        </details>

        @foreach ([
            [route('blog.index'), 'Blog', 'M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8Z|M14 3v5h5|M9 13h6M9 17h4'],
            [route('shop.index'), 'Shop', 'M3.5 12.5h17a8.5 8.5 0 0 1-17 0Z|M6 9.2c0-1.6 1.4-2.2 1.4-3.4M17.4 9.2c0-1.6-1.4-2.2-1.4-3.4'],
            [route('about'), 'About Us', 'M12 21.5a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19Z|M12 10.5v6M12 7.5h.01'],
            [route('contact'), 'Contact Us', 'M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Z|m3.5 8 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5'],
        ] as [$href, $label, $paths])
            <a href="{{ $href }}" class="mt-1 flex items-center gap-3 rounded-xl px-3 py-2.5 text-base font-semibold text-ink transition-colors hover:bg-surface-soft">
                <span class="flex size-8 items-center justify-center rounded-lg bg-info-light text-info">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        @foreach (explode('|', $paths) as $d)<path d="{{ $d }}"/>@endforeach
                    </svg>
                </span>
                {{ $label }}
            </a>
        @endforeach

        <div class="mt-4 border-t border-line pt-3">
            <p class="px-3 pb-1 text-xs font-bold tracking-wider text-ink-muted uppercase">More</p>
            @foreach ([[route('faq'), 'FAQ'], [route('how-it-works'), 'How it works']] as [$href, $label])
                <a href="{{ $href }}" class="block rounded-lg px-3 py-2 text-sm text-ink-muted transition-colors hover:bg-surface-soft hover:text-primary">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </nav>

    <div class="border-t border-line p-4">
        <a href="{{ route('tools.index') }}" class="block rounded-xl bg-primary-vivid px-4 py-3 text-center text-base font-bold text-ink shadow-md transition hover:brightness-95">
            Try Free Tools
        </a>
    </div>
</aside>

@push('scripts')
    <script>
        (() => {
            /* ── Desktop dropdowns ────────────────────────────────────── */
            const menus = [...document.querySelectorAll('[data-menu]')].map(root => ({
                root,
                button: root.querySelector('[data-menu-button]'),
                panel: root.querySelector('[data-menu-panel]'),
                chevron: root.querySelector('[data-menu-chevron]'),
            }));

            // Fades and scales the panel in/out rather than an instant show,
            // the same enter/leave timing the mobile drawer uses below.
            // `hidden` is only added back once the leave transition finishes,
            // so the panel doesn't just vanish mid-fade.
            const openPanel = (panel) => {
                panel.removeAttribute('hidden');
                requestAnimationFrame(() => {
                    panel.classList.remove('opacity-0', 'scale-95');
                });
            };

            const closePanel = (panel) => {
                if (panel.hasAttribute('hidden')) return;
                panel.classList.add('opacity-0', 'scale-95');
                setTimeout(() => panel.setAttribute('hidden', ''), 200);
            };

            const closeMenus = (except = null) => menus.forEach(m => {
                if (m === except) return;
                closePanel(m.panel);
                m.button.setAttribute('aria-expanded', 'false');
                m.chevron.classList.remove('rotate-180');
            });

            menus.forEach(m => {
                m.button.addEventListener('click', () => {
                    const willOpen = m.panel.hasAttribute('hidden');
                    closeMenus(m);
                    willOpen ? openPanel(m.panel) : closePanel(m.panel);
                    m.button.setAttribute('aria-expanded', String(willOpen));
                    m.chevron.classList.toggle('rotate-180', willOpen);
                });

                // Following a link should not leave the panel open behind it.
                m.panel.addEventListener('click', e => e.target.closest('a') && closeMenus());

                // Tab out of the group and it closes, which is what a mouse
                // user gets for free by moving away.
                m.root.addEventListener('focusout', (e) => {
                    if (!m.root.contains(e.relatedTarget)) closeMenus(m.panel.hasAttribute('hidden') ? null : undefined);
                });
            });

            document.addEventListener('click', (e) => {
                if (!e.target.closest('[data-menu]')) closeMenus();
            });

            /* ── Mobile drawer ────────────────────────────────────────── */
            const toggle = document.querySelector('[data-drawer-toggle]');
            const drawer = document.querySelector('[data-drawer]');
            const backdrop = document.querySelector('[data-drawer-backdrop]');
            if (!toggle || !drawer) return;

            let open = false;

            const setDrawer = (next) => {
                open = next;
                toggle.setAttribute('aria-expanded', String(open));

                if (open) {
                    drawer.removeAttribute('hidden');
                    backdrop.removeAttribute('hidden');
                    // A frame between removing hidden and starting the slide,
                    // or the browser has nothing to transition from.
                    requestAnimationFrame(() => {
                        drawer.classList.remove('-translate-x-full');
                        backdrop.classList.remove('opacity-0');
                    });
                    document.body.style.overflow = 'hidden';
                    drawer.querySelector('a, button')?.focus();
                } else {
                    drawer.classList.add('-translate-x-full');
                    backdrop.classList.add('opacity-0');
                    document.body.style.overflow = '';
                    setTimeout(() => {
                        if (open) return;
                        drawer.setAttribute('hidden', '');
                        backdrop.setAttribute('hidden', '');
                    }, 300);
                    toggle.focus();
                }
            };

            toggle.addEventListener('click', () => setDrawer(!open));
            backdrop.addEventListener('click', () => setDrawer(false));
            drawer.addEventListener('click', e => e.target.closest('a') && setDrawer(false));

            document.addEventListener('keydown', (e) => {
                if (e.key !== 'Escape') return;
                closeMenus();
                if (open) setDrawer(false);
            });

            // Resizing past the breakpoint leaves a drawer nobody can close,
            // because the button that closes it is hidden at that width.
            matchMedia('(min-width: 1024px)').addEventListener('change', (e) => {
                if (e.matches && open) setDrawer(false);
            });
        })();
    </script>
@endpush

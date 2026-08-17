@php
    // Rooted at "/" rather than bare fragments: a bare "#tools" only scrolls
    // on the home page, and does nothing from /about.
    $links = [
        ['/#tools', 'Tools'],
        ['/#food-guides', 'Food Guides'],
        ['/#blog', 'Blog'],
        ['/#how-it-works', 'How It Works'],
        [route('about'), 'About Us'],
        [route('contact'), 'Contact Us'],
    ];
@endphp

<header class="sticky top-0 z-50 border-b border-line/70 bg-surface/85 backdrop-blur-md">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

        {{-- The badge alone, with no wordmark beside it. It carries the name
             around its rim, so it is sized to let that read — and the alt text
             has to spell the name out, because it is now the only thing naming
             this link to a screen reader or to Google. --}}
        <a href="/" class="flex shrink-0 items-center">
            <span class="block size-14 shrink-0 sm:size-16">
                <x-img name="purrquerylogo" alt="{{ config('app.name') }}" sizes="64px" fit="contain"/>
            </span>
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="Main">
            @foreach ($links as [$href, $label])
                <a href="{{ $href }}"
                   class="rounded-md px-3 py-2 text-sm font-medium text-ink-muted transition-colors hover:bg-surface-soft hover:text-primary">
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-2">
            <a href="/#tools"
               class="hidden rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-ink-inverse shadow-sm transition-colors hover:bg-primary-hover sm:inline-flex">
                Try Free Tools
            </a>

            {{-- Toggling a class beats toggling `hidden` inline: the closed
                 state stays in the stylesheet, so the menu cannot flash open
                 before the script runs. --}}
            <button type="button" data-menu-toggle
                    class="inline-flex items-center justify-center rounded-md p-2 text-ink transition-colors hover:bg-surface-soft lg:hidden"
                    aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu">
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden border-t border-line bg-surface lg:hidden">
        <nav class="mx-auto max-w-7xl px-4 py-3 sm:px-6" aria-label="Main, mobile">
            @foreach ($links as [$href, $label])
                <a href="{{ $href }}"
                   class="block rounded-md px-3 py-2.5 text-base font-medium text-ink transition-colors hover:bg-surface-soft hover:text-primary">
                    {{ $label }}
                </a>
            @endforeach
            <a href="/#tools"
               class="mt-2 block rounded-lg bg-primary px-4 py-2.5 text-center text-base font-semibold text-ink-inverse">
                Try Free Tools
            </a>
        </nav>
    </div>
</header>

@push('scripts')
    <script>
        (() => {
            const button = document.querySelector('[data-menu-toggle]');
            const menu = document.getElementById('mobile-menu');

            const close = () => {
                menu.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
            };

            button.addEventListener('click', () => {
                const open = menu.classList.toggle('hidden') === false;
                button.setAttribute('aria-expanded', String(open));
            });

            // Tapping a link should close the menu, or the destination is
            // hidden behind it on a phone.
            menu.addEventListener('click', e => e.target.closest('a') && close());
            document.addEventListener('keydown', e => e.key === 'Escape' && close());
        })();
    </script>
@endpush

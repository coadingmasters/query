@php
    $navItems = [
        ['label' => 'Dashboard', 'active' => true, 'icon' => 'M4 13h6V4H4v9Zm0 7h6v-5H4v5Zm10 0h6V11h-6v9Zm0-16v5h6V4h-6Z'],
        ['label' => 'Blog Posts', 'soon' => true, 'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z'],
        ['label' => 'Messages', 'soon' => true, 'icon' => 'm3 7 8.5 6L20 7M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z'],
        ['label' => 'Subscribers', 'soon' => true, 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M12.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z'],
        ['label' => 'Settings', 'soon' => true, 'icon' => 'M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z'],
    ];

    $stats = [
        ['label' => 'Blog Posts', 'value' => count(config('blog')), 'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z', 'tone' => 'primary'],
        ['label' => 'Contact Messages', 'value' => \App\Models\ContactMessage::count(), 'icon' => 'm3 7 8.5 6L20 7M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z', 'tone' => 'accent'],
        ['label' => 'Subscribers', 'value' => \App\Models\Subscriber::count(), 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M12.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z', 'tone' => 'info'],
        ['label' => 'Article Reactions', 'value' => \App\Models\ArticleFeedback::count(), 'icon' => 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z', 'tone' => 'warning'],
    ];

    $toneClasses = [
        'primary' => 'bg-primary-light text-primary',
        'accent' => 'bg-accent-light text-accent',
        'info' => 'bg-info-light text-info',
        'warning' => 'bg-warning-light text-warning',
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ config('brand.lang') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard — {{ config('app.name') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-section" x-data="{ sidebarOpen: false }">

    {{-- Mobile sidebar backdrop --}}
    <div x-show="sidebarOpen" x-cloak
         class="fixed inset-0 z-40 bg-ink/40 lg:hidden"
         x-on:click="sidebarOpen = false"
         x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    {{-- ═══ Sidebar ═══════════════════════════════════════════════════ --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-line bg-surface transition-transform duration-200 lg:translate-x-0"
        :class="sidebarOpen && '!translate-x-0'">

        <div class="flex items-center gap-2.5 border-b border-line px-6 py-5">
            <span class="flex size-9 items-center justify-center overflow-hidden rounded-full bg-surface-soft">
                <x-img name="purrquerylogo" alt="" sizes="36px" fit="contain"/>
            </span>
            <div class="leading-tight">
                <p class="font-heading text-sm font-extrabold text-ink">{{ config('app.name') }}</p>
                <p class="text-[11px] font-semibold tracking-wide text-ink-muted uppercase">Admin</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5">
            @foreach ($navItems as $item)
                <span
                    @class([
                        'flex items-center justify-between gap-2 rounded-xl px-3 py-2.5 text-sm font-semibold transition',
                        'bg-primary-light text-primary' => $item['active'] ?? false,
                        'text-ink-muted' => ! ($item['active'] ?? false),
                        'cursor-default opacity-70' => $item['soon'] ?? false,
                        'hover:bg-surface-soft hover:text-ink' => ! ($item['active'] ?? false) && ! ($item['soon'] ?? false),
                    ])
                >
                    <span class="flex items-center gap-3">
                        <svg class="size-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </span>
                    @if ($item['soon'] ?? false)
                        <span class="rounded-full bg-surface-soft px-2 py-0.5 text-[10px] font-bold tracking-wide text-ink-muted uppercase">Soon</span>
                    @endif
                </span>
            @endforeach
        </nav>

        <div class="border-t border-line p-4">
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ink-muted transition hover:bg-surface-soft hover:text-ink">
                <svg class="size-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 10.5 12 3l9 7.5M5 9.5V21h5v-6h4v6h5V9.5"/>
                </svg>
                View site
            </a>
        </div>
    </aside>

    {{-- ═══ Main column ═══════════════════════════════════════════════ --}}
    <div class="flex min-h-screen flex-col lg:pl-64">

        {{-- Topbar --}}
        <header class="sticky top-0 z-30 flex items-center justify-between gap-4 border-b border-line bg-surface/90 px-4 py-3.5 backdrop-blur sm:px-6">
            <div class="flex items-center gap-3">
                <button type="button" x-on:click="sidebarOpen = true"
                        class="-ml-1 flex size-9 items-center justify-center rounded-lg text-ink-muted hover:bg-surface-soft lg:hidden">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="hidden sm:block">
                    <h1 class="font-heading text-lg font-bold text-ink">Dashboard</h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden text-right sm:block">
                    <p class="text-sm font-semibold text-ink">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-ink-muted">{{ auth()->user()->email }}</p>
                </div>
                <span class="flex size-9 items-center justify-center rounded-full bg-primary-vivid text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            title="Log out"
                            class="flex size-9 items-center justify-center rounded-lg text-ink-muted transition hover:bg-danger-light hover:text-danger">
                        <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 px-4 py-8 sm:px-6 lg:py-10">

            <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">
                    Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋
                </h2>
                <p class="mt-1 text-sm text-ink-muted">
                    Here's a snapshot of {{ config('app.name') }} right now.
                </p>
            </div>

            {{-- Stat cards --}}
            <div class="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($stats as $i => $stat)
                    <div
                        class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md"
                        style="animation-delay: {{ $i * 70 }}ms"
                    >
                        <div class="flex items-center justify-between">
                            <span class="flex size-10 items-center justify-center rounded-xl {{ $toneClasses[$stat['tone']] }}">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="{{ $stat['icon'] }}"/>
                                </svg>
                            </span>
                        </div>
                        <p class="mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $stat['label'] }}</p>
                        <p class="mt-1 font-heading text-3xl font-extrabold text-ink"
                           x-data="{ n: 0 }" x-init="let t = setInterval(() => { n < {{ $stat['value'] }} ? n++ : clearInterval(t) }, Math.max(600 / Math.max({{ $stat['value'] }}, 1), 12))"
                           x-text="n">0</p>
                    </div>
                @endforeach
            </div>

            {{-- Content grid --}}
            <div class="mt-6 grid gap-6 lg:grid-cols-3">

                <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm lg:col-span-2">
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading text-base font-bold text-ink">Recent activity</h3>
                    </div>

                    <div class="mt-8 flex flex-col items-center justify-center py-10 text-center">
                        <span class="flex size-14 items-center justify-center rounded-full bg-surface-soft text-primary">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 8v4l3 3M12 21.5a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19Z"/>
                            </svg>
                        </span>
                        <p class="mt-4 text-sm font-semibold text-ink">Nothing to show yet</p>
                        <p class="mt-1 max-w-xs text-sm text-ink-muted">
                            Activity like new messages and subscribers will appear here as the site grows.
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
                    <h3 class="font-heading text-base font-bold text-ink">Quick links</h3>
                    <ul class="mt-4 space-y-1">
                        @foreach ([
                            ['label' => 'View blog', 'href' => route('blog.index')],
                            ['label' => 'View live site', 'href' => route('home')],
                            ['label' => 'Sitemap', 'href' => route('sitemap')],
                        ] as $link)
                            <li>
                                <a href="{{ $link['href'] }}"
                                   class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-semibold text-ink-muted transition hover:bg-surface-soft hover:text-ink">
                                    {{ $link['label'] }}
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10"/></svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </main>

        <footer class="border-t border-line px-4 py-5 text-center text-xs text-ink-muted sm:px-6">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Admin dashboard.
        </footer>
    </div>

</body>
</html>

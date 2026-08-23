@props([
    'code',        // "404", "500", ...
    'heading',
    'message',
    'showSearch' => true,
])

{{-- A standalone document, not x-layouts.app: an error page has to render
     even when something upstream is broken, so it stays free of anything
     that could itself fail (a DB-backed nav item, a Setting lookup). Every
     piece used here — @fonts, x-img, x-paw-print — reads from a file, never
     the database. --}}
<!DOCTYPE html>
<html lang="{{ config('brand.lang') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F47C6B">

    <title>{{ $heading }} | {{ config('app.name') }}</title>
    <meta name="description" content="{{ $message }}">

    {{-- Nothing here is a page worth ranking, and a stray 200-only crawl of
         a broken link should not compete with the real pages for it. --}}
    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48">
    <link rel="icon" href="/favicon-96.png" type="image/png" sizes="96x96">

    @fonts
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-dvh flex-col bg-surface-soft">

    <header class="py-6">
        <div class="container-page">
            <a href="/" class="inline-flex items-center">
                <span class="block size-14 shrink-0">
                    <x-img name="purrquerylogo" alt="{{ config('app.name') }}" sizes="56px" fit="contain"/>
                </span>
            </a>
        </div>
    </header>

    <main class="relative flex flex-1 items-center overflow-hidden py-10">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -left-20 size-96 rounded-full bg-primary-vivid opacity-[0.08] blur-3xl"></div>
            <div class="absolute -right-20 bottom-0 size-80 rounded-full bg-accent-vivid opacity-[0.1] blur-3xl"></div>
            <x-paw-print class="paw absolute top-[12%] right-[10%] hidden size-10 text-primary sm:block [animation-duration:22s]"/>
            <x-paw-print class="paw absolute bottom-[16%] left-[8%] hidden size-8 text-accent-vivid sm:block [animation-delay:-7s] [animation-duration:25s]"/>
        </div>

        <div class="container-page relative max-w-2xl text-center">
            <p class="font-heading text-7xl font-extrabold tracking-tight text-primary sm:text-8xl">{{ $code }}</p>

            <h1 class="mt-4 font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                {{ $heading }}
            </h1>

            <p class="mt-3 text-base leading-relaxed text-ink-muted sm:text-lg">
                {{ $message }}
            </p>

            @if ($showSearch)
                <form method="GET" action="/search" class="mx-auto mt-8 max-w-md" role="search">
                    <label for="error-search" class="sr-only">Search cat care tools and guides</label>
                    <div class="relative">
                        <svg class="pointer-events-none absolute top-1/2 left-4 size-5 -translate-y-1/2 text-ink-muted"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/>
                        </svg>
                        <input id="error-search" name="q" type="search" autocomplete="off"
                               placeholder="Search tools, guides & more…"
                               class="w-full rounded-full border border-line bg-surface py-3.5 pr-14 pl-12 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        <button type="submit" aria-label="Search"
                                class="absolute top-1/2 right-1.5 flex size-10 -translate-y-1/2 items-center justify-center rounded-full bg-primary-vivid text-ink transition hover:brightness-95">
                            <svg class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </form>
            @endif

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="/" class="btn-primary rounded-full px-7">Back to home</a>
                <a href="/tools" class="btn-outline rounded-full bg-surface px-7">Free tools</a>
                <a href="/blog" class="btn-outline rounded-full bg-surface px-7">Blog</a>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-sm text-ink-muted">
        &copy; {{ date('Y') }} {{ config('app.name') }}
    </footer>

</body>
</html>

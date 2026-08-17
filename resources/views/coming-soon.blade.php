<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#534AB7">

    {{-- The page returns a normal 200 and stays indexable on purpose: it lets
         search engines discover the domain and start building brand authority
         before the full site ships. --}}
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonical }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $url }}/og-image.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ config('app.name') }} — launching soon">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="/favicon.ico" sizes="any">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="application/ld+json">
        @json($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    </script>
</head>

<body class="relative flex min-h-dvh flex-col overflow-hidden bg-surface">
    <a href="#main" class="skip-link">Skip to content</a>

    {{-- Decorative background. Hidden from assistive tech: it carries no
         meaning, only atmosphere. --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="aurora -left-32 -top-40 size-[26rem] bg-primary"></div>
        <div class="aurora -right-32 top-10 size-[22rem] bg-accent-vivid" style="animation-delay: -7s"></div>
        <div class="aurora -bottom-64 left-1/3 size-[24rem] bg-primary-hover" style="animation-delay: -14s"></div>
        <div class="absolute inset-0 bg-surface/75"></div>
    </div>

    <main id="main"
          class="relative z-10 mx-auto flex w-full max-w-2xl flex-1 flex-col items-center justify-center px-6 py-16 text-center">

        <svg class="rise size-14 sm:size-16" style="--rise-delay: 0ms" viewBox="0 0 56 56" fill="none"
             role="img" aria-label="{{ config('app.name') }} logo">
            <circle cx="26" cy="26" r="17" stroke="var(--color-primary)" stroke-width="4"/>
            <path d="M38.5 38.5 49 49" stroke="var(--color-accent)" stroke-width="5" stroke-linecap="round"/>
            <g fill="var(--color-primary)">
                <ellipse cx="26" cy="31.8" rx="5.1" ry="3.9"/>
                <circle cx="18.4" cy="21.8" r="2.1"/>
                <circle cx="23" cy="17.8" r="2.1"/>
                <circle cx="29" cy="17.8" r="2.1"/>
                <circle cx="33.6" cy="21.8" r="2.1"/>
            </g>
        </svg>

        <p class="rise mt-8 inline-flex items-center gap-2 rounded-full border border-line bg-surface-soft px-4 py-1.5 text-sm font-medium text-primary-dark shadow-sm"
           style="--rise-delay: 120ms">
            <span class="beacon size-2 rounded-full bg-accent-vivid"></span>
            In development
        </p>

        <h1 class="rise mt-6 text-5xl font-extrabold tracking-tight text-ink sm:text-6xl"
            style="--rise-delay: 220ms">
            {{ config('app.name') }}
        </h1>

        {{-- The rotator is decorative motion, so it is hidden from screen
             readers and the full list is exposed as one static sentence. --}}
        <p class="rise mt-5 text-xl text-ink-soft sm:text-2xl" style="--rise-delay: 320ms">
            <span class="sr-only">Practical {{ implode(', ', $rotates) }} — coming soon.</span>
            <span aria-hidden="true" x-data="rotator(@js($rotates))">
                Practical
                <span class="font-semibold text-primary" x-text="current">{{ $rotates[0] }}</span>
            </span>
        </p>

        <p class="rise mt-6 max-w-lg text-base leading-relaxed text-ink-muted sm:text-lg"
           style="--rise-delay: 420ms">
            {{ $description }}
        </p>

        <div class="rise sweep relative mt-10 h-1 w-56 overflow-hidden rounded-full bg-line"
             style="--rise-delay: 520ms" role="presentation"></div>

        <p class="rise mt-10 text-sm text-ink-soft" style="--rise-delay: 620ms">
            Questions or partnerships?
            <a href="mailto:{{ $email }}"
               class="font-medium text-primary underline decoration-line-strong underline-offset-4 transition-colors hover:text-primary-hover">
                {{ $email }}
            </a>
        </p>
    </main>

    <footer class="relative z-10 pb-8 text-center text-sm text-ink-soft">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </footer>
</body>
</html>

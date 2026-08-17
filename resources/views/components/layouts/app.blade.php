<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#534AB7">

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonical }}">

    {{-- max-image-preview:large is the one that earns its place on a site
         built around photographs: without it Google is limited to a thumbnail
         in results. The snippet and video values lift the default caps too. --}}
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ rtrim(config('app.url'), '/') }}/og-image.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ config('app.name') }} — {{ config('brand.tagline') }}">
    <meta property="og:locale" content="{{ config('brand.og_locale') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:alt" content="{{ config('app.name') }} — {{ config('brand.tagline') }}">

    {{-- Small sizes carry the cat on its disc rather than the whole badge:
         the rim lettering is unreadable below about 48px. --}}
    <link rel="icon" href="/favicon.ico" sizes="16x16 32x32 48x48">
    <link rel="icon" href="/favicon-96.png" type="image/png" sizes="96x96">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    @fonts
    @vite(['resources/css/app.css'])

    {{-- Pages push their above-the-fold image preload here. Telling the
         browser about it in the head means it starts downloading alongside
         the stylesheet instead of waiting for layout. --}}
    @stack('head')

    @isset($schema)
        <script type="application/ld+json">
            @json($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        </script>
    @endisset
</head>

<body class="flex min-h-dvh flex-col bg-surface">
    <a href="#main" class="skip-link">Skip to content</a>

    <x-site-header/>

    <main id="main" class="flex-1">
        {{ $slot }}
    </main>

    <x-site-footer/>

    @stack('scripts')
</body>
</html>

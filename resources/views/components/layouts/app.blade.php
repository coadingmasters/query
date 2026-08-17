<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#534AB7">

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonical }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ rtrim(config('app.url'), '/') }}/og-image.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="/favicon.ico" sizes="any">

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

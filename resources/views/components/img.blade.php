@props([
    'name',                 // file name in resources/images, without extension
    'alt',
    'sizes' => '100vw',     // how wide the image renders, so the browser can pick a variant
    'priority' => false,    // true for the one image visible on load
    'class' => '',
])

@php
    $image = \App\Support\Images::get($name);
@endphp

@if ($image)
    {{-- width/height are always emitted: they reserve the space before the
         file arrives, which is what keeps layout shift at zero. --}}
    <img
        src="{{ $image['src'] }}"
        srcset="{{ $image['srcset'] }}"
        sizes="{{ $sizes }}"
        width="{{ $image['width'] }}"
        height="{{ $image['height'] }}"
        alt="{{ $alt }}"
        @class(['h-full w-full object-cover', $class])
        @if ($priority)
            fetchpriority="high" decoding="sync"
        @else
            loading="lazy" decoding="async"
        @endif
    >
@endif

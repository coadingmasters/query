@props([
    'name',                 // file name in resources/images, without extension
    'alt',
    'sizes' => '100vw',     // how wide the image renders, so the browser can pick a variant
    'priority' => false,    // true for the one image visible on load
    'fit' => 'cover',       // 'contain' for a logo, which must not be cropped
    'class' => '',
])

@php
    $image = \App\Support\Images::get($name);
@endphp

@if ($image)
    {{-- The image always fills its container, so the caller sizes the box
         rather than the image. Passing a size class here would fight the
         h-full/w-full below, and which one won would come down to the order
         Tailwind happened to emit them in.

         width/height are always emitted: they reserve the space before the
         file arrives, which is what keeps layout shift at zero. --}}
    <img
        src="{{ $image['src'] }}"
        srcset="{{ $image['srcset'] }}"
        sizes="{{ $sizes }}"
        width="{{ $image['width'] }}"
        height="{{ $image['height'] }}"
        alt="{{ $alt }}"
        @class([
            'h-full w-full',
            'object-cover' => $fit === 'cover',
            'object-contain' => $fit === 'contain',
            $class,
        ])
        @if ($priority)
            fetchpriority="high" decoding="sync"
        @else
            loading="lazy" decoding="async"
        @endif
    >
@endif

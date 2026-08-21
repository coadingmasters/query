@props([
    'post',
    'priority' => false,    // true for the one image visible on load
    'fit' => 'cover',       // 'contain' for a logo, which must not be cropped
    'class' => '',
])

@php
    $src = $post->featured_image_url;
@endphp

{{-- A Post's featured image is a single uploaded file, not a manifest entry
     with generated variants, so there is no srcset to offer. Width and
     height still come free: the model records them from the file itself
     when it is saved, which is what keeps this from shifting the layout
     as it loads, the same job img.blade.php does for manifest images. --}}
@if ($src)
    <img
        src="{{ $src }}"
        @if ($post->featured_image_width && $post->featured_image_height)
            width="{{ $post->featured_image_width }}"
            height="{{ $post->featured_image_height }}"
        @endif
        alt="{{ $post->featured_image_alt ?: $post->title }}"
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

@props([
    'post',
    'priority' => false,    // true for the one image visible on load
    'fit' => 'cover',       // 'contain' for a logo, which must not be cropped
    'class' => '',
])

@php
    // featured_image_url builds a URL from whatever path is on the record,
    // whether or not a file actually landed there, so a post saved with a
    // placeholder path (drafted before its photo was uploaded) would still
    // pass a truthy check here and render a broken <img>. width/height are
    // only ever set by reading the real file at save time, so their
    // presence is the actual "a file exists" signal, not just "a path is
    // recorded".
    $src = ($post->featured_image && $post->featured_image_width) ? $post->featured_image_url : null;
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
@else
    {{-- A post awaiting its photo (drafted before the image was uploaded)
         still needs to fill this box, or every card built on top of it, in
         a flex row especially, collapses to whatever height its siblings
         happen to have. Same brand mark the admin list falls back to. --}}
    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-primary-vivid to-primary-light" role="img" aria-label="{{ $post->featured_image_alt ?: $post->title }}">
        <svg class="size-10 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 20.5c-3.6-2.2-7-4.6-7-8.4A3.9 3.9 0 0 1 12 9.6a3.9 3.9 0 0 1 7 2.5c0 3.8-3.4 6.2-7 8.4Z"/>
        </svg>
    </div>
@endif

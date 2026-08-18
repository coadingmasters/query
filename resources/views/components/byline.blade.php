@props(['reviewed' => false])

@php
    $author = config('author.founder');
    $reviewer = config('author.reviewer');
@endphp

{{-- Nothing renders until a real name is configured. An unattributed byline
     is not a smaller version of a byline, it is a claim with a hole in it. --}}
@if ($author['name'])
    <div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-x-4 gap-y-2 text-sm']) }}>
        <span class="inline-flex items-center gap-2.5">
            @if ($author['image'])
                <span class="size-8 shrink-0 overflow-hidden rounded-full bg-surface-soft">
                    <x-img :name="$author['image']" :alt="$author['name']" sizes="32px"/>
                </span>
            @else
                <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-primary-light">
                    <x-paw-print class="size-4 text-primary"/>
                </span>
            @endif
            <span class="text-ink-muted">
                By <a href="{{ route('about') }}#founder" class="font-semibold text-ink underline decoration-line-strong underline-offset-4 transition-colors hover:text-primary">{{ $author['name'] }}</a>
            </span>
        </span>

        @if ($reviewed && $reviewer['name'])
            <span class="inline-flex items-center gap-2 text-ink-muted">
                <svg class="size-4 shrink-0 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z"/>
                    <path d="m9.4 12.2 1.9 1.9 3.6-3.7"/>
                </svg>
                Reviewed by {{ $reviewer['name'] }}@if ($reviewer['credentials']), {{ $reviewer['credentials'] }}@endif
                @if ($reviewer['reviewed_on'])
                    <time datetime="{{ $reviewer['reviewed_on'] }}">
                        on {{ \Illuminate\Support\Carbon::parse($reviewer['reviewed_on'])->format('F j, Y') }}
                    </time>
                @endif
            </span>
        @endif
    </div>
@endif

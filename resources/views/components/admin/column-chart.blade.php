@props(['data'])

@php
    $palette = ['var(--color-primary-vivid)', 'var(--color-info-vivid)', 'var(--color-accent-vivid)', 'var(--color-warning-vivid)', 'var(--color-danger)'];
    $max = max(1, collect($data)->max('percent'));
@endphp

@if (count($data))
    {{-- Scrolls rather than squeezing past a workable column width — a
         chart nobody can read is worse than one that needs a swipe. --}}
    <div class="flex h-52 items-end gap-3 overflow-x-auto pb-1 sm:gap-4">
        @foreach ($data as $i => $row)
            <div class="flex h-full w-14 shrink-0 flex-col items-center justify-end gap-2 sm:w-16">
                <span class="text-xs font-bold text-ink">{{ $row['count'] }}</span>

                <div class="relative w-full max-w-11 flex-1 overflow-hidden rounded-t-lg bg-surface-soft">
                    <div class="admin-column stagger-delay dot-color column-height absolute inset-x-0 bottom-0 rounded-t-lg"
                         style="--column-percent: {{ max(6, round($row['percent'] / $max * 100)) }}%; --stagger-delay: {{ $i * 90 }}ms; --dot-color: {{ $palette[$i % count($palette)] }}"></div>
                </div>

                <span class="line-clamp-2 w-full text-center text-[11px] leading-tight font-semibold text-ink-muted" title="{{ $row['label'] }}">
                    {{ $row['label'] }}
                </span>
            </div>
        @endforeach
    </div>
@else
    <p class="text-sm text-ink-muted">No data yet.</p>
@endif

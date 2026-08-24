@props(['data'])

@php
    $size = 176;
    $stroke = 30;
    $radius = ($size - $stroke) / 2;
    $circumference = 2 * M_PI * $radius;
    $palette = ['var(--color-primary-vivid)', 'var(--color-accent-vivid)', 'var(--color-info-vivid)', 'var(--color-warning-vivid)', 'var(--color-danger)'];

    $total = collect($data)->sum('count');

    // Each segment's length is its share of the ring; dashoffset is where
    // the previous segments left off, so the whole ring reads as one
    // continuous split rather than segments stacked on top of each other.
    $cursor = 0;
    $segments = collect($data)->values()->map(function ($row, $i) use ($total, $circumference, $palette, &$cursor) {
        $fraction = $total > 0 ? $row['count'] / $total : 0;
        $length = $fraction * $circumference;
        $segment = [
            'label' => $row['label'],
            'count' => $row['count'],
            'percent' => $total > 0 ? (int) round($fraction * 100) : 0,
            'length' => $length,
            'offset' => -$cursor,
            'color' => $palette[$i % count($palette)],
        ];
        $cursor += $length;

        return $segment;
    });
@endphp

{{--
    Deliberately always stacked (chart above legend), not side-by-side.
    A viewport breakpoint can't tell how wide THIS card is — a pie-chart
    sitting in a narrow third-of-a-row card would force the legend into a
    sliver too tight for its own numbers, and they'd get clipped by the
    card's overflow-hidden (used to clip the decorative blur behind it).
    Stacked always fits, at any card width.
--}}
<div class="flex flex-col items-center gap-6">
    <div class="relative size-44 shrink-0">
        <svg viewBox="0 0 {{ $size }} {{ $size }}" width="{{ $size }}" height="{{ $size }}" class="-rotate-90" aria-hidden="true">
            <circle cx="{{ $size / 2 }}" cy="{{ $size / 2 }}" r="{{ $radius }}" fill="none"
                    stroke="var(--color-surface-soft)" stroke-width="{{ $stroke }}"/>
            @foreach ($segments as $i => $s)
                @if ($s['count'] > 0)
                    <circle cx="{{ $size / 2 }}" cy="{{ $size / 2 }}" r="{{ $radius }}" fill="none"
                            stroke="{{ $s['color'] }}" stroke-width="{{ $stroke }}" stroke-linecap="butt"
                            stroke-dashoffset="{{ $s['offset'] }}"
                            class="admin-pie-segment stagger-delay"
                            style="--stagger-delay:{{ $i * 130 }}ms;--pie-circumference:{{ $circumference }};--pie-length:{{ $s['length'] }}"/>
                @endif
            @endforeach
        </svg>
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="font-heading text-2xl font-extrabold text-ink">{{ $total }}</span>
            <span class="text-[11px] font-semibold text-ink-muted">total</span>
        </div>
    </div>

    <div class="w-full space-y-2.5">
        @foreach ($segments as $s)
            <div class="flex items-center justify-between gap-3 text-sm">
                <span class="flex min-w-0 items-center gap-2 font-semibold text-ink">
                    <span class="dot-color size-2.5 shrink-0 rounded-full" style="--dot-color:{{ $s['color'] }}"></span>
                    <span class="truncate">{{ $s['label'] }}</span>
                </span>
                <span class="shrink-0 text-ink-muted">{{ $s['count'] }} <span class="text-ink-muted/70">· {{ $s['percent'] }}%</span></span>
            </div>
        @endforeach
    </div>
</div>

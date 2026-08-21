@props(['data', 'id'])

@php
    $width = 520;
    $height = 170;
    $padX = 14;
    $padY = 20;

    $palette = ['var(--color-primary-vivid)', 'var(--color-info-vivid)', 'var(--color-accent-vivid)', 'var(--color-warning-vivid)'];

    $points = collect($data)->values();
    $n = $points->count();
    $step = $n > 1 ? ($width - $padX * 2) / ($n - 1) : 0;
    $max = max(1, $points->max('percent'));

    $points = $points->map(fn ($d, $i) => [
        'x' => round($padX + $i * $step, 2),
        'y' => round($height - $padY - ($d['percent'] / $max) * ($height - $padY * 2), 2),
        'label' => $d['label'],
        'count' => $d['count'],
    ]);

    // Catmull-Rom through the points, converted to cubic Bezier segments, for
    // a smooth wave instead of the sharp joints a plain polyline would give.
    $at = fn (int $i) => $points[max(0, min($n - 1, $i))];
    $path = $n ? 'M'.$points[0]['x'].','.$points[0]['y'].' ' : '';
    for ($i = 0; $i < $n - 1; $i++) {
        $p0 = $at($i - 1);
        $p1 = $at($i);
        $p2 = $at($i + 1);
        $p3 = $at($i + 2);
        $cp1x = $p1['x'] + ($p2['x'] - $p0['x']) / 6;
        $cp1y = $p1['y'] + ($p2['y'] - $p0['y']) / 6;
        $cp2x = $p2['x'] - ($p3['x'] - $p1['x']) / 6;
        $cp2y = $p2['y'] - ($p3['y'] - $p1['y']) / 6;
        $path .= "C {$cp1x},{$cp1y} {$cp2x},{$cp2y} {$p2['x']},{$p2['y']} ";
    }

    $baseline = $height - $padY + 6;
    $areaPath = $n ? $path."L {$points[$n - 1]['x']},{$baseline} L {$points[0]['x']},{$baseline} Z" : '';

    $pathLength = 0;
    for ($i = 1; $i < $n; $i++) {
        $pathLength += sqrt(($points[$i]['x'] - $points[$i - 1]['x']) ** 2 + ($points[$i]['y'] - $points[$i - 1]['y']) ** 2);
    }
@endphp

<div>
    <svg viewBox="0 0 {{ $width }} {{ $height }}" class="w-full" preserveAspectRatio="none" aria-hidden="true">
        <defs>
            <linearGradient id="wave-{{ $id }}-line" x1="0" x2="1" y1="0" y2="0">
                @foreach ($points as $i => $p)
                    <stop offset="{{ $n > 1 ? round($i / ($n - 1) * 100) : 0 }}%" stop-color="{{ $palette[$i % count($palette)] }}"/>
                @endforeach
            </linearGradient>
            <linearGradient id="wave-{{ $id }}-fill" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="{{ $palette[0] }}" stop-opacity="0.32"/>
                <stop offset="100%" stop-color="{{ $palette[0] }}" stop-opacity="0"/>
            </linearGradient>
        </defs>

        <path d="{{ $areaPath }}" fill="url(#wave-{{ $id }}-fill)" class="admin-wave-fill" style="animation-delay:250ms"/>
        <path d="{{ $path }}" fill="none" stroke="url(#wave-{{ $id }}-line)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"
              class="admin-line-draw" style="stroke-dasharray:{{ $pathLength ?: 1 }};stroke-dashoffset:{{ $pathLength ?: 1 }}"/>

        @foreach ($points as $i => $p)
            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="5.5" fill="{{ $palette[$i % count($palette)] }}" stroke="var(--color-surface)" stroke-width="2.5"
                    class="admin-dot" style="animation-delay:{{ 550 + $i * 90 }}ms"/>
        @endforeach
    </svg>

    <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs font-semibold text-ink-muted">
        @foreach ($points as $i => $p)
            <span class="flex items-center gap-1.5">
                <span class="size-2.5 rounded-full" style="background:{{ $palette[$i % count($palette)] }}"></span>
                {{ $p['label'] }} <span class="text-ink">{{ $p['count'] }}</span>
            </span>
        @endforeach
    </div>
</div>

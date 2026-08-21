@props(['series'])

@php
    $width = 600;
    $height = 180;
    $padX = 8;
    $padY = 14;

    $max = max(1, collect($series)->flatMap(fn ($d) => [$d['messages'], $d['subscribers']])->max());
    $count = count($series);
    $step = $count > 1 ? ($width - $padX * 2) / ($count - 1) : 0;

    $point = fn (int $i, int $value) => [
        'x' => round($padX + $i * $step, 2),
        'y' => round($height - $padY - ($value / $max) * ($height - $padY * 2), 2),
    ];

    $messagePoints = collect($series)->values()->map(fn ($d, $i) => $point($i, $d['messages']));
    $subscriberPoints = collect($series)->values()->map(fn ($d, $i) => $point($i, $d['subscribers']));

    $toPath = fn ($points) => $points->map(fn ($p, $i) => ($i === 0 ? 'M' : 'L').$p['x'].','.$p['y'])->implode(' ');

    $pathLength = function ($points) {
        $points = $points->values();
        $length = 0;
        for ($i = 1; $i < $points->count(); $i++) {
            $length += sqrt(($points[$i]['x'] - $points[$i - 1]['x']) ** 2 + ($points[$i]['y'] - $points[$i - 1]['y']) ** 2);
        }

        return $length;
    };

    $hasActivity = collect($series)->sum('messages') + collect($series)->sum('subscribers') > 0;
@endphp

<div class="relative">
    <svg viewBox="0 0 {{ $width }} {{ $height }}" class="w-full" preserveAspectRatio="none" aria-hidden="true">
        <path d="{{ $toPath($subscriberPoints) }}" fill="none" stroke="var(--color-accent-vivid)" stroke-width="2.5"
              stroke-linecap="round" stroke-linejoin="round"
              class="admin-line-draw" style="stroke-dasharray:{{ $pathLength($subscriberPoints) ?: 1 }};stroke-dashoffset:{{ $pathLength($subscriberPoints) ?: 1 }};animation-delay:120ms"/>
        <path d="{{ $toPath($messagePoints) }}" fill="none" stroke="var(--color-primary-vivid)" stroke-width="2.5"
              stroke-linecap="round" stroke-linejoin="round"
              class="admin-line-draw" style="stroke-dasharray:{{ $pathLength($messagePoints) ?: 1 }};stroke-dashoffset:{{ $pathLength($messagePoints) ?: 1 }}"/>

        @foreach ($messagePoints as $p)
            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="2.5" fill="var(--color-primary-vivid)" class="admin-dot stagger-delay" style="--stagger-delay:{{ 500 + $loop->index * 20 }}ms"/>
        @endforeach
        @foreach ($subscriberPoints as $p)
            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="2.5" fill="var(--color-accent-vivid)" class="admin-dot stagger-delay" style="--stagger-delay:{{ 620 + $loop->index * 20 }}ms"/>
        @endforeach
    </svg>

    @unless ($hasActivity)
        <p class="pointer-events-none absolute inset-x-0 top-1/2 -translate-y-1/2 text-center text-sm text-ink-muted">
            No activity in the last 14 days yet
        </p>
    @endunless

    <div class="mt-2 flex justify-between text-[11px] text-ink-muted">
        @foreach ($series as $i => $day)
            @if ($i % 3 === 0 || $i === $count - 1)
                <span>{{ $day['label'] }}</span>
            @endif
        @endforeach
    </div>

    <div class="mt-4 flex items-center gap-5 text-xs font-semibold text-ink-muted">
        <span class="flex items-center gap-1.5"><span class="size-2 rounded-full" style="background:var(--color-primary-vivid)"></span> Messages</span>
        <span class="flex items-center gap-1.5"><span class="size-2 rounded-full" style="background:var(--color-accent-vivid)"></span> Subscribers</span>
    </div>
</div>

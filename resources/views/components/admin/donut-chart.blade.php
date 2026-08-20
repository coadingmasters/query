@props(['feedback'])

@php
    $size = 140;
    $stroke = 16;
    $radius = ($size - $stroke) / 2;
    $circumference = 2 * M_PI * $radius;
    $helpfulLength = $feedback['total'] > 0 ? $circumference * ($feedback['helpful'] / $feedback['total']) : 0;
@endphp

<div class="flex items-center gap-6">
    <div class="relative shrink-0" style="width:{{ $size }}px;height:{{ $size }}px">
        <svg viewBox="0 0 {{ $size }} {{ $size }}" width="{{ $size }}" height="{{ $size }}" class="-rotate-90">
            <circle cx="{{ $size / 2 }}" cy="{{ $size / 2 }}" r="{{ $radius }}" fill="none"
                    stroke="var(--color-surface-soft)" stroke-width="{{ $stroke }}"/>
            @if ($feedback['total'] > 0)
                <circle cx="{{ $size / 2 }}" cy="{{ $size / 2 }}" r="{{ $radius }}" fill="none"
                        stroke="var(--color-accent-vivid)" stroke-width="{{ $stroke }}" stroke-linecap="round"
                        class="admin-donut"
                        style="stroke-dasharray:{{ $circumference }};stroke-dashoffset:{{ $circumference }};--admin-donut-offset:{{ $circumference - $helpfulLength }}"/>
            @endif
        </svg>
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="font-heading text-2xl font-extrabold text-ink">{{ $feedback['helpful_percent'] }}%</span>
            <span class="text-[11px] font-semibold text-ink-muted">helpful</span>
        </div>
    </div>

    <div class="space-y-3 text-sm">
        <div class="flex items-center gap-2">
            <span class="size-2.5 rounded-full" style="background:var(--color-accent-vivid)"></span>
            <span class="font-semibold text-ink">{{ $feedback['helpful'] }}</span>
            <span class="text-ink-muted">found it helpful</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="size-2.5 rounded-full bg-surface-soft ring-1 ring-inset ring-line"></span>
            <span class="font-semibold text-ink">{{ $feedback['not_helpful'] }}</span>
            <span class="text-ink-muted">did not</span>
        </div>
        @if ($feedback['total'] === 0)
            <p class="text-xs text-ink-muted">No votes yet.</p>
        @endif
    </div>
</div>

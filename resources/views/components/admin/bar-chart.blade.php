@props(['data'])

<div class="space-y-4">
    @forelse ($data as $i => $row)
        <div>
            <div class="mb-1.5 flex items-center justify-between text-sm">
                <span class="font-semibold text-ink">{{ $row['label'] }}</span>
                <span class="text-ink-muted">{{ $row['count'] }}</span>
            </div>
            <div class="h-2.5 w-full overflow-hidden rounded-full bg-surface-soft">
                <div class="admin-bar stagger-delay h-full rounded-full bg-primary-vivid"
                     style="--admin-bar-percent: {{ $row['percent'] }}%; --stagger-delay: {{ $i * 90 }}ms"></div>
            </div>
        </div>
    @empty
        <p class="text-sm text-ink-muted">No posts yet.</p>
    @endforelse
</div>

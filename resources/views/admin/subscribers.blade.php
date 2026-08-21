<x-admin.shell active="subscribers" title="Subscribers">

    <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Newsletter subscribers</h2>
        <p class="mt-1 text-sm text-ink-muted">Everyone who signed up, newest first.</p>
    </div>

    @php
        $statCards = [
            ['label' => 'Total subscribers', 'value' => $counts['total'], 'tone' => 'primary', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M12.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z'],
            ['label' => 'Active', 'value' => $counts['active'], 'tone' => 'accent', 'icon' => 'M5 13l4 4L19 7'],
            ['label' => 'Unsubscribed', 'value' => $counts['unsubscribed'], 'tone' => 'warning', 'icon' => 'm6 6 12 12M18 6 6 18'],
        ];
        $toneClasses = [
            'primary' => 'bg-primary-light text-primary',
            'accent' => 'bg-accent-light text-accent-dark',
            'warning' => 'bg-warning-light text-warning',
        ];
    @endphp

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        @foreach ($statCards as $i => $stat)
            <div class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md"
                 style="--pop-delay: {{ $i * 70 }}ms">
                <span class="flex size-10 items-center justify-center rounded-xl {{ $toneClasses[$stat['tone']] }}">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="{{ $stat['icon'] }}"/>
                    </svg>
                </span>
                <p class="mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $stat['label'] }}</p>
                <p class="mt-1 font-heading text-3xl font-extrabold text-ink"
                   x-data="{ n: 0 }" x-init="let t = setInterval(() => { n < {{ $stat['value'] }} ? n++ : clearInterval(t) }, Math.max(600 / Math.max({{ $stat['value'] }}, 1), 12))"
                   x-text="n">0</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 rounded-2xl border border-line bg-surface p-6 shadow-sm">
        <h3 class="font-heading text-base font-bold text-ink">Active vs. unsubscribed</h3>
        <p class="text-sm text-ink-muted">How the {{ $counts['total'] }} subscribers on file are split right now.</p>
        <div class="mt-6 max-w-lg">
            <x-admin.wave-chart :data="$statusChart" id="subscribers-status"/>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                        <th scope="col" class="px-5 py-3 font-semibold">Email</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Subscribed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($subscribers as $subscriber)
                        <tr>
                            <td class="px-5 py-3.5 font-medium text-ink">{{ $subscriber->email }}</td>
                            <td class="px-5 py-3.5">
                                @if ($subscriber->unsubscribed_at)
                                    <span class="rounded-full bg-surface-soft px-2 py-0.5 text-xs font-semibold text-ink-muted">Unsubscribed</span>
                                @else
                                    <span class="rounded-full bg-accent-light px-2 py-0.5 text-xs font-semibold text-accent">Active</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-ink-muted">{{ $subscriber->created_at->format('M j, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-16">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <span class="flex size-14 items-center justify-center rounded-full bg-surface-soft text-primary">
                                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M12.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"/>
                                        </svg>
                                    </span>
                                    <p class="mt-4 text-sm font-semibold text-ink">No subscribers yet</p>
                                    <p class="mt-1 max-w-xs text-sm text-ink-muted">
                                        Sign-ups from the newsletter form will show up here.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($subscribers->hasPages())
        <div class="mt-6">
            {{ $subscribers->links() }}
        </div>
    @endif

</x-admin.shell>

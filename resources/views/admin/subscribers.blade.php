<x-admin.shell active="subscribers" title="Subscribers">

    <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Newsletter subscribers</h2>
        <p class="mt-1 text-sm text-ink-muted">Everyone who signed up, newest first.</p>
    </div>

    <div class="mt-7 grid gap-4 sm:grid-cols-2 sm:max-w-md">
        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Active</p>
            <p class="mt-1 font-heading text-2xl font-extrabold text-ink">{{ $activeCount }}</p>
        </div>
        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Unsubscribed</p>
            <p class="mt-1 font-heading text-2xl font-extrabold text-ink">{{ $unsubscribedCount }}</p>
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

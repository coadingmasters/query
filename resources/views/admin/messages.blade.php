<x-admin.shell active="messages" title="Messages">

    <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Contact messages</h2>
        <p class="mt-1 text-sm text-ink-muted">Everything submitted through the contact form, newest first.</p>
    </div>

    <div class="mt-7 rounded-2xl border border-line bg-surface shadow-sm">
        <div class="divide-y divide-line">
            @forelse ($messages as $message)
                <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                            <p class="font-semibold text-ink">{{ $message->name }}</p>
                            <span class="text-ink-muted">&middot;</span>
                            <a href="mailto:{{ $message->email }}" class="text-sm text-primary hover:text-primary-hover">{{ $message->email }}</a>
                            @if (! $message->handled_at)
                                <span class="rounded-full bg-primary-light px-2 py-0.5 text-[11px] font-bold text-primary uppercase">New</span>
                            @endif
                        </div>
                        <p class="mt-1.5 text-sm font-semibold text-ink">{{ $message->subject }}</p>
                        <p class="mt-1 max-w-2xl text-sm whitespace-pre-line text-ink-muted">{{ $message->message }}</p>
                        <p class="mt-2 text-xs text-ink-muted">{{ $message->created_at->format('M j, Y \a\t g:ia') }}</p>
                    </div>

                    <form method="POST" action="{{ route('admin.messages.handled', $message) }}" class="shrink-0">
                        @csrf
                        <button type="submit"
                                @class([
                                    'rounded-lg px-3 py-1.5 text-xs font-semibold transition',
                                    'bg-surface-soft text-ink-muted hover:bg-line' => $message->handled_at,
                                    'bg-accent-light text-accent hover:brightness-95' => ! $message->handled_at,
                                ])>
                            {{ $message->handled_at ? 'Mark unhandled' : 'Mark handled' }}
                        </button>
                    </form>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <span class="flex size-14 items-center justify-center rounded-full bg-surface-soft text-primary">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m3 7 8.5 6L20 7M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                        </svg>
                    </span>
                    <p class="mt-4 text-sm font-semibold text-ink">No messages yet</p>
                    <p class="mt-1 max-w-xs text-sm text-ink-muted">
                        Submissions from the contact form will show up here.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    @if ($messages->hasPages())
        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    @endif

</x-admin.shell>

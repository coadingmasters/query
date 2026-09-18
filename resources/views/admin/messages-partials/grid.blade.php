@php
    $messageItems = $messages->map(fn ($m) => [
        'id' => $m->id,
        'name' => $m->name,
        'email' => $m->email,
        'subject' => $m->subject,
        'message' => $m->message,
        'date' => $m->created_at->format('M j, Y \a\t g:ia'),
        'handled' => (bool) $m->handled_at,
        'formAction' => route('admin.messages.handled', $m),
        'replyAction' => route('admin.messages.reply', $m),
    ])->values();
@endphp

<div data-messages-grid
     x-data='{
         viewOpen: false, viewIndex: null,
         items: @json($messageItems),
         get current() { return this.viewIndex !== null ? this.items[this.viewIndex] : null; },

         replying: false, sending: false, sent: false, replyBody: "",

         open(i) { this.viewIndex = i; this.viewOpen = true; this.replying = false; this.sent = false; this.replyBody = ""; },

         async sendReply() {
             if (!this.replyBody.trim() || this.sending) return;
             this.sending = true;
             try {
                 const res = await fetch(this.current.replyAction, {
                     method: "POST",
                     headers: {
                         "Content-Type": "application/json",
                         "X-CSRF-TOKEN": document.querySelector(`meta[name="csrf-token"]`).content,
                         "Accept": "application/json",
                     },
                     body: JSON.stringify({ body: this.replyBody }),
                 });
                 if (!res.ok) throw new Error();
                 this.sent = true;
                 showToast("success", "Reply sent", `Emailed to ${this.current.email}`);
                 setTimeout(() => { this.viewOpen = false; $dispatch("messages:refresh"); }, 1100);
             } catch {
                 showToast("error", "Could not send reply", "Please try again.");
             } finally {
                 this.sending = false;
             }
         },
     }'>

    @if ($messages->isEmpty())
        <div class="flex flex-col items-center justify-center rounded-2xl border border-line bg-surface py-16 text-center shadow-sm">
            <span class="flex size-14 items-center justify-center rounded-full bg-surface-soft text-primary">
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m3 7 8.5 6L20 7M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                </svg>
            </span>
            <p class="mt-4 text-sm font-semibold text-ink">No messages found</p>
            <p class="mt-1 max-w-xs text-sm text-ink-muted">
                @if (collect($filters)->filter()->isNotEmpty())
                    Nothing matches those filters yet.
                @else
                    Submissions from the contact form will show up here.
                @endif
            </p>
        </div>
    @else
        <div class="rounded-2xl border border-line bg-surface shadow-sm">
            <div class="divide-y divide-line">
                @foreach ($messages as $i => $message)
                    <div data-message-row="{{ $message->id }}" class="flex flex-col gap-3 p-5 sm:flex-row sm:items-start sm:justify-between">
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
                            <p class="mt-1 line-clamp-2 max-w-2xl text-sm whitespace-pre-line text-ink-muted">{{ $message->message }}</p>
                            <div class="mt-2 flex items-center gap-3">
                                <p class="text-xs text-ink-muted">{{ $message->created_at->format('M j, Y \a\t g:ia') }}</p>
                                <button type="button" x-on:click="open({{ $i }})"
                                        class="flex items-center gap-1 text-xs font-semibold text-primary hover:text-primary-hover">
                                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    View full message
                                </button>
                            </div>
                        </div>

                        <div class="flex shrink-0 items-center gap-1.5">
                            <form method="POST" action="{{ route('admin.messages.handled', $message) }}">
                                @csrf
                                <button type="submit" title="{{ $message->handled_at ? 'Mark unhandled' : 'Mark handled' }}"
                                        @class([
                                            'flex size-8 items-center justify-center rounded-lg transition',
                                            'bg-surface-soft text-ink-muted hover:bg-line' => $message->handled_at,
                                            'text-accent-dark hover:bg-accent-light' => ! $message->handled_at,
                                        ])>
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                            <button type="button" title="Delete" x-on:click="$dispatch('messages:delete', { id: {{ $message->id }}, name: '{{ addslashes($message->name) }}' })"
                                    class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-danger-light hover:text-danger">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($messages->hasPages())
            <div class="mt-6" data-messages-pagination>
                {{ $messages->links() }}
            </div>
        @endif
    @endif

    {{-- Full message popup, with reply composer. --}}
    <div x-cloak x-show="viewOpen" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4"
         x-on:keydown.escape.window="viewOpen = false">
        <div x-show="viewOpen" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-on:click.outside="viewOpen = false"
             class="w-full max-w-lg rounded-2xl bg-surface p-6 shadow-xl">
            <template x-if="current">
                <div>
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="font-heading text-lg font-extrabold text-ink" x-text="current.subject"></h3>
                            <p class="mt-1 text-sm text-ink-muted">
                                <span x-text="current.name"></span> &middot;
                                <a class="text-primary hover:text-primary-hover" x-bind:href="'mailto:' + current.email" x-text="current.email"></a>
                            </p>
                        </div>
                        <button type="button" x-on:click="viewOpen = false" class="flex size-7 shrink-0 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                            <span class="sr-only">Close</span>
                        </button>
                    </div>

                    <p class="mt-4 max-h-56 overflow-y-auto text-sm leading-relaxed whitespace-pre-line text-ink" x-text="current.message"></p>

                    <div class="mt-5 flex items-center justify-between gap-3 border-t border-line pt-4">
                        <p class="text-xs text-ink-muted" x-text="current.date"></p>
                        <div class="flex items-center gap-2">
                            <button type="button" x-show="!replying && !sent" x-cloak
                                    x-on:click="replying = true"
                                    class="flex items-center gap-1.5 rounded-lg bg-primary-light px-3 py-1.5 text-xs font-semibold text-primary transition hover:brightness-95">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 17-5-5 5-5M4 12h11a5 5 0 0 1 5 5v1"/></svg>
                                Reply
                            </button>
                            <form method="POST" x-bind:action="current.formAction" x-show="!replying" x-cloak>
                                @csrf
                                <button type="submit"
                                        class="rounded-lg px-3 py-1.5 text-xs font-semibold transition"
                                        x-bind:class="current.handled ? 'bg-surface-soft text-ink-muted hover:bg-line' : 'bg-accent-light text-accent hover:brightness-95'"
                                        x-text="current.handled ? 'Mark unhandled' : 'Mark handled'">
                                </button>
                            </form>
                        </div>
                    </div>

                    <div x-show="replying" x-cloak
                         x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="mt-4 border-t border-line pt-4">
                        <template x-if="!sent">
                            <div>
                                <label class="text-xs font-semibold text-ink-muted uppercase">Reply to <span x-text="current?.email"></span></label>
                                <textarea x-model="replyBody" rows="4" x-bind:disabled="sending"
                                          placeholder="Type your reply — it's emailed straight to them."
                                          class="mt-1.5 w-full resize-none rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none disabled:opacity-60"></textarea>
                                <div class="mt-3 flex items-center justify-end gap-2">
                                    <button type="button" x-on:click="replying = false" x-bind:disabled="sending"
                                            class="rounded-lg px-3 py-1.5 text-xs font-semibold text-ink-muted transition hover:bg-surface-section disabled:opacity-50">
                                        Cancel
                                    </button>
                                    <button type="button" x-on:click="sendReply()" x-bind:disabled="sending || !replyBody.trim()"
                                            class="flex items-center gap-1.5 rounded-lg bg-primary-vivid px-3.5 py-1.5 text-xs font-bold text-ink shadow-sm transition hover:brightness-95 disabled:cursor-not-allowed disabled:opacity-50">
                                        <svg x-show="sending" x-cloak class="size-3.5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-opacity="0.25"/><path d="M21 12a9 9 0 0 0-9-9"/></svg>
                                        <span x-text="sending ? 'Sending…' : 'Send reply'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="sent" x-cloak
                             x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                             class="flex flex-col items-center gap-2 py-4 text-center">
                            <span class="flex size-10 items-center justify-center rounded-full bg-accent-light text-accent-dark">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <p class="text-sm font-semibold text-ink">Reply sent</p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

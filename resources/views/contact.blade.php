<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ Hero ══════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-soft pb-12 lg:pb-14">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-24 size-96 rounded-full bg-primary-vivid opacity-[0.07] blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 size-80 rounded-full bg-accent-vivid opacity-[0.12] blur-3xl"></div>
        <x-paw-print class="paw absolute top-[20%] left-[6%] hidden size-10 text-primary sm:block" style="animation-duration: 23s"/>
        <x-paw-print class="paw absolute top-[26%] right-[7%] hidden size-8 text-accent-vivid sm:block" style="animation-delay: -8s; animation-duration: 20s"/>
    </div>

    <div class="container-page relative py-10 text-center lg:py-12">
        <p class="eyebrow">
            <span class="size-1.5 rounded-full bg-accent-vivid"></span>
            We read everything
        </p>
        <h1 class="mt-5 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
            Get in touch
        </h1>
        <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-ink-muted sm:text-lg">
            A question about your cat, a correction to a guide, a tool that is
            misbehaving, or something else entirely — this reaches a person.
        </p>
    </div>

    <svg class="absolute inset-x-0 bottom-0 h-12 w-full text-surface sm:h-20" viewBox="0 0 1440 120"
         preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
        <path d="M0 60c180-45 360-45 540-10s360 55 540 20 300-55 360-60v110H0Z"/>
    </svg>
</section>

{{-- ══ Form and expectations ═════════════════════════════════════════════ --}}
<section class="section-tight bg-surface">
    <div class="container-page grid gap-8 lg:grid-cols-5 lg:gap-10">

        {{-- Form --}}
        <div class="reveal lg:col-span-3">
            <div class="rounded-2xl border border-line bg-surface p-6 shadow-md sm:p-8">
                @if (session('sent'))
                    {{-- role=status so a screen reader hears the result without
                         the focus being yanked away. --}}
                    <div role="status" class="flex items-start gap-4 rounded-xl bg-accent-light p-5">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-accent text-ink-inverse">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </span>
                        <span>
                            <span class="block font-heading text-lg font-bold text-ink">Message sent</span>
                            <span class="mt-1 block text-sm leading-relaxed text-ink-muted">
                                Thank you — it is with us. Replies usually take a few
                                days, and corrections get looked at first.
                            </span>
                        </span>
                    </div>
                @else
                    <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Send a message</h2>
                    <p class="mt-2 text-sm text-ink-muted">
                        Fields marked with an asterisk are required.
                    </p>

                    @if ($errors->any())
                        <div role="alert" class="mt-5 rounded-xl border border-danger/30 bg-danger-light p-4">
                            <p class="text-sm font-semibold text-danger">
                                Please check the form — {{ $errors->count() }}
                                {{ Str::plural('field', $errors->count()) }} needs attention.
                            </p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-5">
                        @csrf

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-ink">
                                    Your name <span class="text-danger" aria-hidden="true">*</span>
                                </label>
                                <input id="name" name="name" type="text" required maxlength="120"
                                       value="{{ old('name') }}" autocomplete="name"
                                       @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                                       class="mt-2 w-full rounded-xl border border-line bg-surface px-4 py-3 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                       placeholder="Alex Morgan">
                                @error('name')
                                    <p id="name-error" class="mt-1.5 text-sm font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-ink">
                                    Email <span class="text-danger" aria-hidden="true">*</span>
                                </label>
                                <input id="email" name="email" type="email" required maxlength="254"
                                       value="{{ old('email') }}" autocomplete="email"
                                       @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                                       class="mt-2 w-full rounded-xl border border-line bg-surface px-4 py-3 text-base text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                       placeholder="you@example.com">
                                @error('email')
                                    <p id="email-error" class="mt-1.5 text-sm font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-semibold text-ink">
                                What is it about? <span class="text-danger" aria-hidden="true">*</span>
                            </label>
                            <select id="subject" name="subject" required
                                    @error('subject') aria-invalid="true" @enderror
                                    class="mt-2 w-full rounded-xl border border-line bg-surface px-4 py-3 text-base text-ink shadow-sm transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                @foreach ($subjects as $option)
                                    <option value="{{ $option }}" @selected(old('subject') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                            @error('subject')
                                <p class="mt-1.5 text-sm font-medium text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-semibold text-ink">
                                Message <span class="text-danger" aria-hidden="true">*</span>
                            </label>
                            <textarea id="message" name="message" rows="6" required minlength="20" maxlength="5000"
                                      data-counter-input
                                      @error('message') aria-invalid="true" aria-describedby="message-error" @enderror
                                      class="mt-2 w-full resize-y rounded-xl border border-line bg-surface px-4 py-3 text-base leading-relaxed text-ink shadow-sm transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                      placeholder="Tell us what is on your mind. If it is a correction, a link to your source helps a lot.">{{ old('message') }}</textarea>
                            <div class="mt-1.5 flex items-start justify-between gap-4">
                                @error('message')
                                    <p id="message-error" class="text-sm font-medium text-danger">{{ $message }}</p>
                                @else
                                    <p class="text-sm text-ink-muted">Twenty characters or more, please.</p>
                                @enderror
                                <p data-counter class="shrink-0 text-sm tabular-nums text-ink-muted" aria-hidden="true">0 / 5000</p>
                            </div>
                        </div>

                        {{-- Honeypot. Off-screen rather than display:none, which
                             some bots check for. --}}
                        <div class="absolute left-[-9999px]" aria-hidden="true">
                            <label for="website">Leave this empty</label>
                            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                        </div>

                        <button type="submit" class="btn-primary w-full rounded-full sm:w-auto sm:px-8">
                            Send message
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 3 10.5 13.5M21 3l-6.8 18-3.7-7.5L3 9.8Z"/>
                            </svg>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- What to expect --}}
        <div class="reveal lg:col-span-2" style="--reveal-delay: 120ms">
            <div class="rounded-2xl border border-line bg-surface-soft p-6 sm:p-8">
                <h2 class="font-heading text-xl font-extrabold tracking-tight text-ink">What happens next</h2>

                <ul class="mt-6 space-y-6">
                    @foreach (config('contact.expectations') as $item)
                        <li class="flex items-start gap-4">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-surface text-primary shadow-sm">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    @foreach ($item['paths'] as $d)
                                        <path d="{{ $d }}"/>
                                    @endforeach
                                </svg>
                            </span>
                            <span>
                                <span class="block font-heading font-bold text-ink">{{ $item['title'] }}</span>
                                <span class="mt-1 block text-sm leading-relaxed text-ink-muted">{{ $item['body'] }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-8 border-t border-line pt-6">
                    <p class="text-sm text-ink-muted">Prefer your own mail client?</p>
                    <a href="mailto:{{ config('brand.email') }}"
                       class="mt-2 inline-flex items-center gap-2 text-sm font-semibold text-primary underline decoration-line-strong underline-offset-4 transition-colors hover:text-primary-hover">
                        {{ config('brand.email') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ FAQ ═══════════════════════════════════════════════════════════════ --}}
<section class="section-tight bg-surface-soft">
    <div class="container-page max-w-3xl">
        <div class="text-center">
            <p class="eyebrow">Before you write</p>
            <h2 class="section-title">Answers to the usual questions</h2>
            <p class="section-intro">
                If one of these covers it, you have saved yourself a wait.
            </p>
        </div>

        <div class="mt-10 space-y-3">
            @foreach (config('contact.faqs') as $faq)
                {{-- Native details/summary: keyboard accessible, works without
                     JavaScript, and the answer stays in the DOM either way,
                     which is what lets the FAQ markup describe it honestly. --}}
                <details class="reveal group rounded-xl border border-line bg-surface px-5 shadow-sm transition hover:border-line-strong open:shadow-md">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-4 font-heading font-bold text-ink marker:content-['']">
                        {{ $faq['question'] }}
                        <span class="flex size-7 shrink-0 items-center justify-center rounded-full bg-primary-light text-primary transition-transform duration-200 group-open:rotate-45">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" aria-hidden="true">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                        </span>
                    </summary>
                    <p class="pb-5 text-sm leading-relaxed text-ink-muted">{{ $faq['answer'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA ═══════════════════════════════════════════════════════════════ --}}
<section class="bg-surface pt-10 pb-14">
    <div class="container-page">
        <div class="reveal relative overflow-hidden rounded-2xl border border-line bg-accent-light px-6 py-12 text-center shadow-lg sm:px-12">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -top-16 -right-10 size-64 rounded-full bg-primary-vivid/20 blur-2xl"></div>
                <div class="absolute -bottom-20 -left-10 size-72 rounded-full bg-accent-vivid/30 blur-2xl"></div>
            </div>
            <div class="relative">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                    While you are here
                </h2>
                <p class="mx-auto mt-3 max-w-xl text-base leading-relaxed text-ink-muted">
                    The tools answer most of what people write in about — and they
                    answer it immediately.
                </p>
                <div class="mt-7 flex flex-wrap justify-center gap-3">
                    <a href="/#tools"
                       class="btn-primary rounded-full px-7">
                        Try the free tools
                    </a>
                    <a href="/#food-guides"
                       class="btn-outline rounded-full px-7">
                        Browse food guides
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        // Live character count. Purely additive: the field works without it,
        // and maxlength already does the enforcing.
        (() => {
            const field = document.querySelector('[data-counter-input]');
            const output = document.querySelector('[data-counter]');
            if (!field || !output) return;

            const limit = field.getAttribute('maxlength');
            const update = () => { output.textContent = `${field.value.length} / ${limit}`; };

            field.addEventListener('input', update);
            update();
        })();
    </script>
@endpush

</x-layouts.app>

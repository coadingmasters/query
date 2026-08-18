<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema" footer-wave="text-surface-section">

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
{{-- The artwork carries its own peach field, paw and heart doodles, so this
     section stays a flat blush band and lets the file do the decorating. --}}
<section class="relative overflow-hidden bg-surface-soft">
    <div class="container-page grid items-center gap-6 pt-10 pb-4 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)] lg:pt-6 lg:pb-0">

        <div class="relative z-10 lg:py-10">
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <svg class="size-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z"/>
                </svg>
                We read every message
            </p>

            <h1 class="mt-5 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl lg:text-[3.35rem] lg:leading-[1.06]">
                We’d love to<br>
                <span class="text-primary">hear</span> from you!
            </h1>

            <p class="mt-5 max-w-md text-base leading-relaxed text-ink-muted">
                A question, a correction, a suggestion, or just hello — it reaches
                a person, and a person answers it.
            </p>

            <ul class="mt-8 flex flex-wrap gap-x-6 gap-y-5">
                @foreach (config('contact.assurances') as $item)
                    <li class="flex items-center gap-3">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-primary-light text-primary">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                @foreach ($item['paths'] as $d)<path d="{{ $d }}"/>@endforeach
                            </svg>
                        </span>
                        <span>
                            <span class="block font-heading text-sm font-bold text-ink">{{ $item['title'] }}</span>
                            <span class="mt-0.5 block text-xs text-ink-muted">{{ $item['body'] }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Runs to the viewport edge on desktop, which is what stops the
             artwork reading as a card floating in the middle of the band. --}}
        {{-- The box matches the artwork's own 3:2, so object-contain fits it
             exactly — a fixed height would letterbox it or scale it past the
             edge depending on the width it happened to get. --}}
        {{-- -mr-8 cancels the container padding so the artwork runs to the edge of
             the band. A percentage bleed would not work here: percentage margins
             on a grid item resolve against its track, not the container. --}}
        <div class="lg:-mr-8 lg:self-end">
          <div class="aspect-[3/2]">
            <x-img name="purrquery-orange-tabby-cat-hero"
                   alt="Fluffy orange tabby cat resting on a cozy blanket"
                   sizes="(min-width: 1024px) 50vw, 100vw"
                   fit="contain"
                   :priority="true"/>
          </div>
        </div>
    </div>
</section>

{{-- ══ 2. FORM AND SIDEBAR ═══════════════════════════════════════════════ --}}
<section class="bg-surface py-10 lg:py-14">
    <div class="container-page grid gap-6 lg:grid-cols-[minmax(0,1.25fr)_minmax(0,1fr)] lg:gap-7">

        {{-- ── Form ────────────────────────────────────────────────────── --}}
        <div class="reveal">
            <div class="rounded-2xl border border-line bg-surface p-6 shadow-md sm:p-8">
                    <div class="flex items-center gap-4">
                        <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Z"/>
                                <path d="m3.5 8 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5"/>
                            </svg>
                        </span>
                        <div>
                            <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Send us a message</h2>
                            <p class="mt-1 text-sm text-ink-muted">
                                Fields marked with an asterisk
                                (<span class="font-semibold text-danger">*</span>) are required.
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-5">
                        @csrf

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-ink">
                                    Your name <span class="text-danger" aria-hidden="true">*</span>
                                </label>
                                <div class="relative mt-2">
                                    <svg class="pointer-events-none absolute top-1/2 left-3.5 size-5 -translate-y-1/2 text-ink-muted"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M19 20v-1.5a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4V20"/>
                                        <path d="M12 11a3.6 3.6 0 1 1 0-7.2 3.6 3.6 0 0 1 0 7.2Z"/>
                                    </svg>
                                    <input id="name" name="name" type="text" required maxlength="120"
                                           value="{{ old('name') }}" autocomplete="name"
                                           @error('name') aria-invalid="true" aria-describedby="name-error" @enderror
                                           class="w-full rounded-xl border border-line-strong bg-surface py-3 pr-4 pl-11 text-base text-ink transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                           placeholder="e.g. Alex Morgan">
                                </div>
                                @error('name')
                                    <p id="name-error" class="mt-1.5 text-sm font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-ink">
                                    Email address <span class="text-danger" aria-hidden="true">*</span>
                                </label>
                                <div class="relative mt-2">
                                    <svg class="pointer-events-none absolute top-1/2 left-3.5 size-5 -translate-y-1/2 text-ink-muted"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Z"/>
                                        <path d="m3.5 8 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5"/>
                                    </svg>
                                    <input id="email" name="email" type="email" required maxlength="254"
                                           value="{{ old('email') }}" autocomplete="email"
                                           @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                                           class="w-full rounded-xl border border-line-strong bg-surface py-3 pr-4 pl-11 text-base text-ink transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                           placeholder="e.g. you@example.com">
                                </div>
                                @error('email')
                                    <p id="email-error" class="mt-1.5 text-sm font-medium text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-semibold text-ink">
                                What is this about? <span class="text-danger" aria-hidden="true">*</span>
                            </label>
                            <select id="subject" name="subject" required
                                    @error('subject') aria-invalid="true" @enderror
                                    class="mt-2 w-full rounded-xl border border-line-strong bg-surface px-4 py-3 text-base text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
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
                            <textarea id="message" name="message" rows="5" required minlength="20" maxlength="5000"
                                      data-counter-input
                                      @error('message') aria-invalid="true" aria-describedby="message-error" @enderror
                                      class="mt-2 w-full resize-y rounded-xl border border-line-strong bg-surface px-4 py-3 text-base leading-relaxed text-ink transition placeholder:text-ink-muted focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"
                                      placeholder="Tell us what is on your mind. Whether it is a question, feedback, or just a friendly hello — we would love to hear it.">{{ old('message') }}</textarea>
                            <div class="mt-2 flex items-start justify-between gap-4">
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

                        <div class="flex flex-wrap items-center gap-x-6 gap-y-4 pt-1">
                            <button type="submit" class="btn-primary rounded-full px-7">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 3 10.5 13.5M21 3l-6.8 18-3.7-7.5L3 9.8Z"/>
                                </svg>
                                Send message
                            </button>
                            <p class="flex items-center gap-2.5 text-sm leading-snug text-ink-muted">
                                <svg class="size-5 shrink-0 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 6v6l4 2"/><path d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20Z"/>
                                </svg>
                                Replies take a few days.<br class="hidden sm:inline">
                                Corrections come first.
                            </p>
                        </div>
                    </form>
            </div>
        </div>

        {{-- ── Sidebar ─────────────────────────────────────────────────── --}}
        <div class="space-y-6">

            <div class="reveal relative overflow-hidden rounded-2xl border border-line bg-surface-soft p-6 pr-28 sm:pr-36"
                 style="--reveal-delay: 80ms">
                <span aria-hidden="true"
                      class="absolute top-5 right-5 z-10 flex size-9 items-center justify-center rounded-full bg-surface shadow-sm">
                    <svg class="size-4 text-primary-vivid" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z"/>
                    </svg>
                </span>

                <h2 class="font-heading text-lg font-extrabold text-ink">No question? Still say hi</h2>
                <p class="mt-2 text-sm leading-relaxed text-ink-muted">
                    We like hearing from cat owners. Drop us a message any time.
                </p>

                {{-- The file also holds a speech bubble off to its right; the
                     box crops to the cat so the bubble does not collide with
                     the heart badge above. --}}
                <div aria-hidden="true" class="pointer-events-none absolute -right-11 bottom-0 h-24 w-40 sm:h-28 sm:w-44">
                    <x-img name="purrquery-cat-saying-hi"
                           alt="Curious gray tabby cat peeking over a surface with a heart speech bubble"
                           sizes="176px" fit="contain" class="object-bottom"/>
                </div>
            </div>

            <div class="reveal rounded-2xl border border-line bg-surface p-6 shadow-sm" style="--reveal-delay: 160ms">
                <h2 class="font-heading text-lg font-extrabold text-ink">What happens next?</h2>

                <ol class="mt-5">
                    @foreach (config('contact.steps') as $step)
                        <li class="relative flex gap-4 @if (! $loop->last) pb-5 @endif">
                            @unless ($loop->last)
                                {{-- Joins one step to the next, so the three read
                                     as a sequence rather than a list. --}}
                                <span aria-hidden="true"
                                      class="absolute top-11 left-[19px] h-[calc(100%-2.75rem)] w-px border-l border-dashed border-line-strong"></span>
                            @endunless

                            <span class="relative flex size-10 shrink-0 items-center justify-center rounded-full bg-accent-light text-accent-dark">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    @foreach ($step['paths'] as $d)<path d="{{ $d }}"/>@endforeach
                                </svg>
                            </span>
                            <span class="pt-0.5">
                                <span class="block font-heading text-sm font-bold text-ink">{{ $step['title'] }}</span>
                                <span class="mt-1 block text-sm leading-relaxed text-ink-muted">{{ $step['body'] }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>

            <div class="reveal rounded-2xl border border-line bg-accent-light p-6" style="--reveal-delay: 240ms">
                <h2 class="flex items-center gap-2.5 font-heading text-lg font-extrabold text-ink">
                    <x-paw-print class="size-5 text-accent-dark"/>
                    Contact info
                </h2>

                <ul class="mt-4 space-y-3 text-sm">
                    <li class="flex items-center gap-3">
                        <svg class="size-5 shrink-0 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Z"/>
                            <path d="m3.5 8 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5"/>
                        </svg>
                        <a href="mailto:{{ config('brand.email') }}"
                           class="font-semibold text-ink underline decoration-accent-dark/40 underline-offset-4 transition-colors hover:text-primary">
                            {{ config('brand.email') }}
                        </a>
                    </li>
                    <li class="flex items-center gap-3 text-ink-muted">
                        <svg class="size-5 shrink-0 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 22s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11Z"/>
                            <path d="M12 13.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5Z"/>
                        </svg>
                        Email is the only channel — there is no phone line.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ══ 3. FAQ ════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface-section py-10 lg:py-14">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <x-paw-print class="paw absolute top-[14%] right-[6%] hidden size-9 text-primary-vivid/40 lg:block" style="animation-duration: 24s"/>
        <x-paw-print class="paw absolute bottom-[18%] right-[10%] hidden size-7 text-primary-vivid/30 lg:block" style="animation-delay: -9s; animation-duration: 21s"/>
        <x-paw-print class="paw absolute bottom-[26%] left-[4%] hidden size-8 text-primary-vivid/30 lg:block" style="animation-delay: -5s; animation-duration: 26s"/>
    </div>

    <div class="container-page relative grid items-center gap-6 lg:grid-cols-[minmax(0,0.42fr)_minmax(0,1fr)] lg:gap-8">

        <div class="reveal mx-auto w-56 lg:w-full lg:max-w-xs">
            <x-img name="purrquery-cat-waving-paw"
                   alt="Cute gray and white tabby cat waving its paw"
                   sizes="(min-width: 1024px) 320px, 224px" fit="contain"/>
        </div>

        <div>
            <div class="reveal text-center" style="--reveal-delay: 80ms">
                <p class="text-xs font-bold tracking-[0.14em] text-primary uppercase">Before you write</p>
                <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                    Answers to common questions
                </h2>
                <p class="mt-3 text-base text-ink-muted">Quick answers might already be here.</p>
            </div>

            <div class="mt-7 space-y-3">
                @foreach (config('contact.faqs') as $faq)
                    {{-- Native details/summary: keyboard accessible, works without
                         JavaScript, and the answer stays in the DOM either way,
                         which is what lets the FAQ markup describe it honestly. --}}
                    <details class="reveal group rounded-xl border border-line bg-surface px-5 shadow-sm transition hover:border-line-strong open:shadow-md">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-3.5 text-sm font-semibold text-ink marker:content-['']">
                            {{ $faq['question'] }}
                            <span class="flex size-6 shrink-0 items-center justify-center rounded-full border border-primary-vivid text-primary transition-transform duration-200 group-open:rotate-45">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                            </span>
                        </summary>
                        <p class="pb-4 text-sm leading-relaxed text-ink-muted">{{ $faq['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══ 4. CTA ════════════════════════════════════════════════════════════ --}}
<section class="bg-surface-section pb-10 lg:pb-14">
    <div class="container-page">
        <div class="reveal relative overflow-hidden rounded-2xl bg-accent-light px-6 py-10 text-center shadow-lg sm:px-12">

            {{-- Decorative, and the copy stands on its own without them, so
                 they drop out on the widths where they would crowd it. --}}
            <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-2 hidden w-44 items-center lg:flex xl:w-52">
                <x-img name="purrquery-cat-food-bowl-heart"
                       alt="Pink cat food bowl filled with kibble beside a heart-shaped toy"
                       sizes="208px" fit="contain"/>
            </div>
            <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 right-2 hidden w-48 items-center lg:flex xl:w-56">
                <x-img name="purrquery-cat-yarn-ball"
                       alt="Playful tabby cat lying beside a green yarn ball"
                       sizes="224px" fit="contain"/>
            </div>

            <div class="relative mx-auto max-w-xl">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                    While you’re here…
                </h2>
                <p class="mt-3 text-base leading-relaxed text-ink-muted">
                    The tools answer most of what people write in about — and they
                    answer it immediately.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <a href="/#tools" class="btn-primary rounded-full px-7">Try free tools</a>
                    <a href="/#food-guides" class="btn-outline rounded-full bg-surface px-7">Browse food guides</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ RESULT ════════════════════════════════════════════════════════════ --}}
{{-- The form is never replaced: it stays on screen and simply comes back
     empty, so a second message costs nothing. What happened is announced
     here instead. --}}
@if (session('sent'))
    <x-result-dialog tone="success" heading="Message sent!">
        <p>
            Thank you — it is with us. Replies usually take a few days, and
            corrections get looked at first.
        </p>
    </x-result-dialog>
@elseif ($errors->any())
    <x-result-dialog tone="error" heading="Almost there">
        <p>
            {{ $errors->count() }} {{ Str::plural('field', $errors->count()) }}
            {{ $errors->count() === 1 ? 'needs' : 'need' }} a look before this can go:
        </p>
        <ul class="mt-3 space-y-1.5 text-left text-sm">
            @foreach ($errors->all() as $message)
                <li class="flex items-start gap-2">
                    <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-warning"></span>
                    {{ $message }}
                </li>
            @endforeach
        </ul>
    </x-result-dialog>
@endif

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

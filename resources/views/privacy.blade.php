<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    // Written out in full: Tailwind only generates classes it can find as
    // literal strings, so "bg-{$tone}-light" would produce nothing.
    $tones = [
        'primary' => 'bg-primary-light text-primary',
        'accent' => 'bg-accent-light text-accent-dark',
        'info' => 'bg-info-light text-info',
    ];
@endphp

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-surface">
    <div class="container-page grid items-center gap-6 pt-8 pb-6 lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)] lg:pt-6">

        <div class="relative z-10">
            <p class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] text-primary uppercase">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z"/>
                </svg>
                Your privacy matters
            </p>

            <h1 class="mt-4 font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
                Privacy Policy
            </h1>

            <p class="mt-5 max-w-lg text-base leading-relaxed text-ink-muted">
                This policy explains what {{ config('app.name') }} collects, why we
                collect it, how long we keep it and what you can ask us to do with
                it. It is written to be read, not to be survived.
            </p>

            <p class="mt-6 inline-flex items-center gap-2 rounded-full bg-accent-light px-4 py-2 text-sm font-medium text-ink">
                <svg class="size-4 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5.5 5h13A1.5 1.5 0 0 1 20 6.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19.5v-13A1.5 1.5 0 0 1 5.5 5Z"/>
                    <path d="M4 10h16M8.5 3v4M15.5 3v4"/>
                </svg>
                Last updated
                <time datetime="{{ $effective }}" class="font-semibold">
                    {{ \Illuminate\Support\Carbon::parse($effective)->format('j F Y') }}
                </time>
            </p>
        </div>

        <div class="mx-auto w-full max-w-sm lg:max-w-none">
            <div class="aspect-[3/2] overflow-hidden rounded-3xl bg-surface-soft">
                <x-img name="purrquery-privacy-policy-cat-shield"
                       alt="Illustrated cat holding a shield with a padlock on it"
                       sizes="(min-width: 1024px) 44vw, 90vw" fit="contain" :priority="true"/>
            </div>
        </div>
    </div>
</section>

{{-- ══ 2. THE SHORT VERSION ══════════════════════════════════════════════ --}}
@if ($summary)
    <section class="bg-surface pb-8">
        <div class="container-page">
            {{-- Most people never read a policy in full. This is the honest
                 short version, placed where it will actually be seen. --}}
            <div class="reveal rounded-2xl border border-accent-light bg-accent-light p-6 sm:p-7">
                <h2 class="font-heading text-lg font-bold text-ink">The short version</h2>
                <ul class="mt-4 grid gap-2.5 sm:grid-cols-2">
                    @foreach ($summary as $point)
                        <li class="flex items-start gap-3 text-base leading-relaxed text-ink">
                            <svg class="mt-1 size-4 shrink-0 text-accent-dark" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                 stroke-linejoin="round" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
                <p class="mt-4 text-sm text-ink-muted">
                    The full detail is below. Where the two differ, the full text is what applies.
                </p>
            </div>
        </div>
    </section>
@endif

{{-- ══ 3. THE POLICY ═════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-10">
    <div class="container-page max-w-4xl">
        @foreach ($sections as $i => $section)
            {{-- scroll-mt clears the sticky header, or a jumped-to heading
                 lands underneath it. --}}
            <article id="{{ $section['id'] }}"
                     @class([
                         'reveal scroll-mt-24 flex gap-5 py-7 sm:gap-6',
                         'border-t border-line' => ! $loop->first,
                     ])>
                <span class="hidden size-12 shrink-0 items-center justify-center rounded-2xl bg-primary-light text-primary sm:flex">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        @foreach ($section['paths'] as $d)<path d="{{ $d }}"/>@endforeach
                    </svg>
                </span>

                <div class="min-w-0 flex-1">
                    <h2 class="font-heading text-lg font-extrabold tracking-tight text-ink sm:text-xl">
                        {{ $i + 1 }}. {{ $section['heading'] }}
                    </h2>

                    <div @class([
                        'mt-3 space-y-3 text-base leading-relaxed text-ink-muted',
                        // One clause carries more weight than the rest, and is
                        // set apart rather than left to blend in.
                        'rounded-2xl border border-warning-light bg-warning-light p-5' => $section['highlight'] ?? false,
                    ])>
                        @foreach ($section['body'] as $paragraph)
                            <p @class(['font-medium text-ink' => ($section['highlight'] ?? false) && $loop->first])>
                                {{ $paragraph }}
                            </p>
                        @endforeach

                        @isset($section['list'])
                            <ul class="space-y-2.5 pt-1">
                                @foreach ($section['list'] as $item)
                                    <li class="flex items-start gap-3">
                                        <span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary"></span>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endisset
                    </div>

                    @if ($section['id'] === 'contact')
                        <a href="mailto:{{ config('brand.email') }}"
                           class="mt-4 inline-flex items-center gap-2 rounded-full bg-accent-light px-4 py-2.5 text-sm font-semibold text-ink transition hover:brightness-95">
                            <svg class="size-4 text-accent-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Z"/>
                                <path d="m3.5 8 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5"/>
                            </svg>
                            {{ config('brand.email') }}
                        </a>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
</section>

{{-- ══ 4. ASSURANCES ═════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-10">
    <div class="container-page max-w-4xl">
        <ul class="reveal grid divide-y divide-line rounded-2xl border border-line bg-surface p-2 shadow-sm sm:grid-cols-3 sm:divide-x sm:divide-y-0">
            @foreach ($assurances as $item)
                <li class="flex items-start gap-3.5 p-5">
                    <span @class(['flex size-10 shrink-0 items-center justify-center rounded-xl', $tones[$item['tone']]])>
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            @foreach ($item['paths'] as $d)<path d="{{ $d }}"/>@endforeach
                        </svg>
                    </span>
                    <span>
                        <span class="block font-heading text-sm font-bold text-ink">{{ $item['title'] }}</span>
                        <span class="mt-1 block text-sm leading-relaxed text-ink-muted">{{ $item['body'] }}</span>
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ══ 5. CTA ════════════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-14">
    <div class="container-page max-w-4xl">
        <div class="reveal grid overflow-hidden rounded-2xl bg-surface-soft sm:grid-cols-[auto_minmax(0,1fr)]">
            <div aria-hidden="true" class="hidden w-52 self-end sm:block">
                <div class="aspect-[3/2]">
                    <x-img name="purrquery-cat-waving-paw"
                           alt="Cute gray and white tabby cat waving its paw"
                           sizes="208px" fit="contain"/>
                </div>
            </div>

            <div class="px-6 py-9 text-center sm:px-8 sm:text-left">
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">
                    Still have a question?
                </h2>
                <p class="mt-2 text-base leading-relaxed text-ink-muted">
                    If any of this is unclear, ask. We would rather explain a clause
                    than have you guess at what it means.
                </p>
                <div class="mt-5 flex flex-wrap justify-center gap-3 sm:justify-start">
                    <a href="{{ route('contact') }}" class="btn-primary rounded-full px-7">Contact us</a>
                    <a href="{{ route('terms') }}" class="btn-outline rounded-full bg-surface px-7">Read the terms</a>
                </div>
            </div>
        </div>
    </div>
</section>

</x-layouts.app>

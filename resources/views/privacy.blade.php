<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ 1. HEADER ═════════════════════════════════════════════════════════ --}}
<section class="bg-surface pt-10 pb-6">
    <div class="container-page">
        <h1 class="font-heading text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
            Privacy Policy
        </h1>

        <p class="mt-5 text-base leading-relaxed text-ink-muted">
            This policy explains what {{ config('app.name') }} collects, why we
            collect it, how long we keep it and what you can ask us to do with
            it. It is written to be read, not to be survived.
        </p>

        <p class="mt-4 text-sm text-ink-muted">
            Last updated
            <time datetime="{{ $effective }}" class="font-semibold text-ink">
                {{ \Illuminate\Support\Carbon::parse($effective)->format('F j, Y') }}
            </time>
        </p>
    </div>
</section>

{{-- ══ 2. THE SHORT VERSION ══════════════════════════════════════════════ --}}
@if ($summary)
    <section class="bg-surface pb-8">
        <div class="container-page">
            {{-- Most people never read a policy in full. This is the honest
                 short version, placed where it will actually be seen. --}}
            <h2 class="font-heading text-lg font-bold text-ink">The short version</h2>
            <ul class="mt-3 space-y-2">
                @foreach ($summary as $point)
                    <li class="text-base leading-relaxed text-ink">{{ $point }}</li>
                @endforeach
            </ul>
            <p class="mt-4 text-sm text-ink-muted">
                The full detail is below. Where the two differ, the full text is what applies.
            </p>
        </div>
    </section>
@endif

{{-- ══ 3. THE POLICY ═════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-10">
    <div class="container-page">
        @foreach ($sections as $i => $section)
            {{-- scroll-mt clears the sticky header, or a jumped-to heading
                 lands underneath it. --}}
            <article id="{{ $section['id'] }}"
                     @class([
                         'scroll-mt-24 py-7',
                         'border-t border-line' => ! $loop->first,
                     ])>
                <h2 class="font-heading text-lg font-extrabold tracking-tight text-ink sm:text-xl">
                    {{ $i + 1 }}. {{ $section['heading'] }}
                </h2>

                <div class="mt-3 space-y-3 text-base leading-relaxed text-ink-muted">
                    @foreach ($section['body'] as $paragraph)
                        <p @class(['font-medium text-ink' => ($section['highlight'] ?? false) && $loop->first])>
                            {{ $paragraph }}
                        </p>
                    @endforeach

                    @isset($section['list'])
                        <ul class="space-y-2 pt-1 pl-5 list-disc marker:text-line-strong">
                            @foreach ($section['list'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @endisset
                </div>

                @if ($section['id'] === 'contact')
                    <a href="mailto:{{ config('brand.email') }}"
                       class="mt-4 inline-block text-sm font-semibold text-primary underline decoration-line-strong underline-offset-4 transition-colors hover:text-primary-hover">
                        {{ config('brand.email') }}
                    </a>
                @endif
            </article>
        @endforeach
    </div>
</section>

{{-- ══ 4. ASSURANCES ═════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-10">
    <div class="container-page">
        <ul class="divide-y divide-line border-t border-b border-line">
            @foreach ($assurances as $item)
                <li class="py-5">
                    <span class="block font-heading text-sm font-bold text-ink">{{ $item['title'] }}</span>
                    <span class="mt-1 block text-sm leading-relaxed text-ink-muted">{{ $item['body'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ══ 5. CONTACT ════════════════════════════════════════════════════════ --}}
<section class="bg-surface pb-14">
    <div class="container-page">
        <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">
            Still have a question?
        </h2>
        <p class="mt-2 text-base leading-relaxed text-ink-muted">
            If any of this is unclear, ask. We would rather explain a clause
            than have you guess at what it means.
        </p>
        <div class="mt-5 flex flex-wrap gap-3">
            <a href="{{ route('contact') }}" class="btn-primary rounded-full px-7">Contact us</a>
            <a href="{{ route('terms') }}" class="btn-outline rounded-full bg-surface px-7">Read the terms</a>
        </div>
    </div>
</section>

</x-layouts.app>

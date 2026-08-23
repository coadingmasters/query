<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

{{-- ══ 1. HERO ═══════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-primary-dark">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -top-24 -right-20 size-96 rounded-full bg-white opacity-[0.06] blur-3xl"></div>
        <x-paw-print class="paw absolute top-[16%] left-[6%] hidden size-9 text-white/20 lg:block [animation-duration:23s]"/>
        <x-paw-print class="paw absolute bottom-[18%] right-[10%] hidden size-7 text-white/15 lg:block [animation-delay:-6s] [animation-duration:20s]"/>
    </div>

    <div class="container-page relative pt-8 pb-16 text-center lg:pt-6 lg:pb-20">
        <nav aria-label="Breadcrumb" class="text-sm text-white/70">
            <ol class="flex flex-wrap items-center justify-center gap-1.5">
                <li><a href="{{ route('home') }}" class="transition-colors hover:text-white">Home</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-white">How It Works</li>
            </ol>
        </nav>

        <p class="mt-4 inline-flex items-center rounded-full border border-white/25 bg-white/10 px-3.5 py-1.5 text-xs font-semibold tracking-wide text-white uppercase">
            How it works
        </p>
        <h1 class="mx-auto mt-5 max-w-2xl font-heading text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
            Three steps to a clear answer
        </h1>
        <p class="mx-auto mt-4 max-w-lg text-base leading-relaxed text-white/80 sm:text-lg">
            Every tool on PurrQuery works the same simple way, and nothing you type
            into one ever leaves your device.
        </p>
    </div>
</section>

{{-- ══ 2. THE THREE STEPS ════════════════════════════════════════════════ --}}
<section class="section bg-surface">
    <div class="container-page">
        <ol class="grid gap-10 md:grid-cols-3 md:gap-8">
            @foreach ([
                [
                    'Pick a tool',
                    'Choose the calculator or checker that matches your question, from your cat\'s age to how much they should weigh.',
                    'Every tool is free and built for one question, so you are never wading through settings meant for something else.',
                ],
                [
                    'Enter the details',
                    'Weight, age, breed, whatever that specific tool needs. Type it in and the answer updates instantly.',
                    'None of it is sent to a server. The calculation happens in your browser, on your device, and stays there.',
                ],
                [
                    'Get your answer',
                    'A clear result you can act on immediately, explained in plain language rather than left as a raw number.',
                    'Tools with a report option let you save it as a PDF, built the same way, on your device, to bring to a vet visit.',
                ],
            ] as $i => [$title, $lead, $detail])
                <li class="reveal relative text-center md:text-left" style="--reveal-delay: {{ $i * 100 }}ms">
                    <span class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-primary-light font-heading text-2xl font-extrabold text-primary md:mx-0">
                        {{ $i + 1 }}
                    </span>
                    <h2 class="mt-5 font-heading text-xl font-bold text-ink">{{ $title }}</h2>
                    <p class="mt-2.5 text-base leading-relaxed text-ink-muted">{{ $lead }}</p>
                    <p class="mt-3 text-sm leading-relaxed text-ink-muted">{{ $detail }}</p>
                </li>
            @endforeach
        </ol>

        <div class="mt-12 text-center">
            <a href="{{ route('tools.index') }}" class="btn-primary rounded-full px-8">
                Try a tool now
            </a>
        </div>
    </div>
</section>

{{-- ══ 3. WHY IT'S BUILT THIS WAY ════════════════════════════════════════ --}}
<section class="section bg-surface-soft">
    <div class="container-page">
        <div class="text-center">
            <p class="eyebrow">The reasoning</p>
            <h2 class="section-title">Why it works this way</h2>
            <p class="section-intro">
                Three deliberate choices behind every tool, not just a design preference.
            </p>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-3">
            @foreach ([
                [
                    'Nothing leaves your device',
                    'Every calculation runs in JavaScript, in your browser. Your cat\'s weight, age or medical details are never transmitted to a server, logged, or stored anywhere but your own device.',
                    'M5.5 5h13A1.5 1.5 0 0 1 20 6.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 15.5v-9A1.5 1.5 0 0 1 5.5 5Z',
                    'primary',
                ],
                [
                    'No account, ever',
                    'You will never be asked to sign up, verify an email, or create a password to use a tool. Open the page, use it, close it.',
                    'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z',
                    'accent',
                ],
                [
                    'Answers, not just numbers',
                    'A raw figure rarely means much on its own. Every result comes with the context to understand it, and a plain-language note on what it means for your cat.',
                    'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z|m9.4 12.2 1.9 1.9 3.6-3.7',
                    'warning',
                ],
            ] as [$title, $text, $paths, $tone])
                <div class="reveal rounded-2xl border border-line bg-surface p-6 shadow-sm">
                    <span @class([
                        'flex size-11 items-center justify-center rounded-xl',
                        'bg-primary-light text-primary' => $tone === 'primary',
                        'bg-accent-light text-accent-dark' => $tone === 'accent',
                        'bg-warning-light text-warning' => $tone === 'warning',
                    ])>
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            @foreach (explode('|', $paths) as $d)<path d="{{ $d }}"/>@endforeach
                        </svg>
                    </span>
                    <h3 class="mt-4 font-heading text-base font-bold text-ink">{{ $title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-muted">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 4. CTA ════════════════════════════════════════════════════════════ --}}
<section class="section bg-surface">
    <div class="container-page">
        <div class="relative overflow-hidden rounded-2xl border border-line bg-accent-light px-6 py-14 text-center shadow-lg sm:px-12">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -top-16 -right-10 size-64 rounded-full bg-primary-vivid/20 blur-2xl"></div>
                <div class="absolute -bottom-20 -left-10 size-72 rounded-full bg-accent-vivid/20 blur-2xl"></div>
            </div>
            <div class="relative mx-auto max-w-xl">
                <h2 class="font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
                    Ready to try it yourself?
                </h2>
                <p class="mx-auto mt-4 text-base leading-relaxed text-ink-muted sm:text-lg">
                    Pick a tool, answer a few questions about your cat, and get a clear
                    result in under a minute.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('tools.index') }}" class="btn-primary rounded-full px-6">
                        Browse free tools
                    </a>
                    <a href="{{ route('blog.index') }}" class="btn-outline rounded-full px-6">
                        Read the guides
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

</x-layouts.app>

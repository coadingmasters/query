<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

<section class="bg-surface-soft pt-8 pb-10">
    <div class="container-page">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink">Tools</li>
            </ol>
        </nav>

        <h1 class="mt-4 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
            Free cat care tools
        </h1>
        <p class="mt-3 max-w-2xl text-base leading-relaxed text-ink-muted">
            {{ ucfirst(\Illuminate\Support\Number::spell($tools->count())) }} calculators and checkers built for the
            questions cat owners actually ask. Nothing to install, no account, no limits.
        </p>
    </div>
</section>

<section class="section-tight bg-surface">
    <div class="container-page">
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($tools as $tool)
                @php $isLive = isset($tool['url']); @endphp
                <{{ $isLive ? 'a' : 'article' }}
                    @if ($isLive) href="{{ $tool['url'] }}" @endif
                    class="card group">
                    <div class="card-media aspect-[4/3]">
                        <x-img :name="$tool['image']" :alt="$tool['alt']"
                               sizes="(max-width: 639px) 92vw, (max-width: 1023px) 46vw, 30vw"/>

                        @unless ($isLive)
                            <span class="absolute top-3 right-3 rounded-full bg-surface/90 px-3 py-1 text-xs font-bold text-ink-muted shadow-sm">
                                Coming soon
                            </span>
                        @endunless
                    </div>
                    <div class="card-body">
                        <h2 class="card-title">{{ $tool['title'] }}</h2>
                        <p class="card-text flex-1">{{ $tool['blurb'] }}</p>
                        <span @class([
                            'mt-4 inline-flex items-center gap-1.5 text-sm font-semibold',
                            'text-primary' => $isLive,
                            'text-ink-muted' => ! $isLive,
                        ])>
                            {{ $isLive ? 'Open tool' : 'In development' }}
                            @if ($isLive)
                                <svg class="size-4 transition-transform duration-200 group-hover:translate-x-1"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                            @endif
                        </span>
                    </div>
                </{{ $isLive ? 'a' : 'article' }}>
            @endforeach
        </div>
    </div>
</section>

</x-layouts.app>

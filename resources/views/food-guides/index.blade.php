<x-layouts.app :title="$title" :description="$description" :canonical="$canonical" :schema="$schema">

@php
    $verdictLabel = ['safe' => 'Safe', 'caution' => 'In moderation', 'unsafe' => 'Never'];
@endphp

<section class="bg-surface-soft pt-8 pb-10">
    <div class="container-page">
        <nav aria-label="Breadcrumb" class="text-sm text-ink-muted">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li><a href="{{ route('home') }}" class="transition-colors hover:text-primary">Home</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-ink">Food Guides</li>
            </ol>
        </nav>

        <h1 class="mt-4 font-heading text-3xl font-extrabold tracking-tight text-ink sm:text-4xl">
            Can cats eat that?
        </h1>
        <p class="mt-3 max-w-2xl text-base leading-relaxed text-ink-muted">
            The answer, before the article. {{ $foods->count() }} categories covering what is
            safe, what needs care, and what to never let near your cat.
        </p>
    </div>
</section>

<section class="section-tight bg-surface">
    <div class="container-page">
        <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @foreach ($foods as $food)
                <a href="{{ route('food-guides.show', $food['slug']) }}" class="card">
                    <div class="card-media aspect-[3/2]">
                        <x-img :name="$food['image']" :alt="$food['alt']"
                               sizes="(max-width: 639px) 46vw, (max-width: 1023px) 30vw, 23vw"/>

                        <span @class([
                            'absolute top-3 right-3 rounded-full px-3 py-1 text-xs font-bold text-ink-inverse shadow-md',
                            'bg-accent' => $food['verdict'] === 'safe',
                            'bg-warning' => $food['verdict'] === 'caution',
                            'bg-danger' => $food['verdict'] === 'unsafe',
                        ])>{{ $verdictLabel[$food['verdict']] }}</span>
                    </div>

                    <div class="card-body">
                        <p class="text-xs font-bold tracking-wide text-primary uppercase">{{ $food['title'] }}</p>
                        <h2 class="mt-1.5 line-clamp-2 font-heading text-base leading-snug font-bold text-ink">
                            {{ $food['question'] }}
                        </h2>
                        <p class="card-text line-clamp-2 flex-1">{{ $food['answer'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

</x-layouts.app>

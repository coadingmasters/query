@if ($posts->isEmpty())
    <div class="flex flex-col items-center justify-center rounded-2xl border border-line bg-surface py-20 text-center shadow-sm">
        <svg class="size-16 text-primary" viewBox="0 0 64 64" fill="none" aria-hidden="true">
            <circle cx="32" cy="32" r="30" fill="var(--color-primary-light)"/>
            <path d="M20 40c0-8 5-14 12-14s12 6 12 14" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round"/>
            <circle cx="26" cy="26" r="2" fill="var(--color-primary)"/>
            <circle cx="38" cy="26" r="2" fill="var(--color-primary)"/>
            <path d="M42 18l6-4 2 6-6 3" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <p class="mt-4 text-sm font-semibold text-ink">No posts found</p>
        <p class="mt-1 max-w-xs text-sm text-ink-muted">
            @if (collect($filters)->filter()->isNotEmpty())
                Nothing matches those filters yet.
            @else
                Start writing your first cat care article.
            @endif
        </p>
        <a href="{{ route('admin.posts.create') }}"
           class="mt-5 flex items-center gap-2 rounded-full bg-primary-vivid px-5 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Create First Post
        </a>
    </div>
@else
    <div class="flex flex-col gap-3" data-posts-grid>
        @foreach ($posts as $i => $post)
            <div data-post-card="{{ $post->id }}"
                 class="post-card-pop stagger-delay group flex flex-col gap-4 rounded-2xl border border-line border-l-4 bg-surface p-4 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md sm:flex-row sm:items-center
                    {{ match ($post->status) {
                        'published' => 'border-l-success',
                        'scheduled' => 'border-l-warning post-card-pulse',
                        default => 'border-l-line-strong',
                    } }}"
                 style="--stagger-delay: {{ min($i, 11) * 60 }}ms">

                {{-- Thumbnail --}}
                <a href="{{ route('admin.posts.edit', $post) }}"
                   class="relative block h-20 w-full shrink-0 overflow-hidden rounded-xl bg-surface-soft sm:h-16 sm:w-24">
                    @if ($post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->featured_image_alt ?: $post->title }}"
                             class="size-full object-cover transition duration-300 group-hover:scale-105">
                    @else
                        <div class="flex size-full items-center justify-center bg-gradient-to-br from-primary-vivid to-primary-light">
                            <svg class="size-6 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z"/>
                            </svg>
                        </div>
                    @endif
                </a>

                {{-- Title + meta --}}
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-1.5 text-xs text-ink-muted">
                        @if ($post->category)
                            <span class="dot-color size-2 rounded-full" style="--dot-color:{{ $post->category->color }}"></span>
                            <span class="font-semibold">{{ $post->category->name }}</span>
                            <span>&middot;</span>
                        @endif
                        <span>{{ $post->reading_time_text }}</span>
                        @if ($post->is_featured)
                            <span class="flex items-center gap-1 rounded-full bg-primary-light px-2 py-0.5 text-[10px] font-bold text-primary">
                                <svg class="size-2.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 2.9 6.6 7.1.7-5.4 4.7 1.6 7-6.2-3.7-6.2 3.7 1.6-7-5.4-4.7 7.1-.7Z"/></svg>
                                Featured
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('admin.posts.edit', $post) }}" class="mt-1 block">
                        <h3 class="truncate font-heading text-base font-bold text-ink transition group-hover:text-primary">{{ $post->title }}</h3>
                    </a>
                    <p class="mt-0.5 line-clamp-1 text-sm text-ink-muted">{{ $post->excerpt }}</p>

                    <div class="mt-2 flex items-center gap-2">
                        @if ($post->author?->photo)
                            <img src="{{ Storage::url($post->author->photo) }}" alt="{{ $post->author->name }}" class="size-5 rounded-full object-cover">
                        @else
                            <span class="flex size-5 items-center justify-center rounded-full bg-primary-light text-[10px] font-bold text-primary">
                                {{ $post->author ? Str::of($post->author->name)->substr(0, 1) : '?' }}
                            </span>
                        @endif
                        <p class="truncate text-xs text-ink-muted">
                            <span class="font-semibold text-ink">{{ $post->author?->name ?? 'Unassigned' }}</span>
                            &middot; {{ ($post->published_at ?? $post->created_at)->format('M j, Y') }}
                            &middot; {{ number_format($post->views_count) }} views
                        </p>
                    </div>
                </div>

                {{-- Status + actions --}}
                <div class="flex shrink-0 items-center justify-between gap-2 border-t border-line pt-3 sm:flex-col sm:items-end sm:justify-center sm:gap-2.5 sm:border-t-0 sm:pt-0">
                    <div class="relative" x-data="{ open: false }" x-on:click.outside="open = false">
                        <button type="button" x-on:click="open = !open"
                                class="flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition
                                    {{ $post->status === 'published' ? 'bg-accent-light text-accent-dark' : ($post->status === 'scheduled' ? 'bg-warning-light text-warning' : 'bg-surface-soft text-ink-muted') }}"
                                data-status-pill>
                            <span class="size-1.5 rounded-full bg-current"></span>
                            {{ $post->status_badge['label'] }}
                            <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div x-cloak x-show="open" x-transition.opacity.duration.150ms
                             class="absolute right-0 z-10 mt-1.5 w-36 rounded-xl border border-line bg-surface p-1 shadow-lg sm:right-0 sm:bottom-full sm:mt-0 sm:mb-1.5">
                            @foreach (['draft' => 'Draft', 'scheduled' => 'Scheduled', 'published' => 'Published'] as $value => $label)
                                <button type="button"
                                        x-on:click="open = false; changeStatus({{ $post->id }}, '{{ $value }}', $el.closest('[data-post-card]'))"
                                        class="flex w-full items-center justify-between rounded-lg px-2.5 py-1.5 text-left text-xs font-semibold text-ink transition hover:bg-surface-section">
                                    {{ $label }}
                                    @if ($post->status === $value)
                                        <svg class="size-3.5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.posts.edit', $post) }}" title="Edit"
                           class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-primary-light hover:text-primary">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16.5 4.5a2.1 2.1 0 0 1 3 3L7.5 19.5 3 21l1.5-4.5Z"/></svg>
                        </a>
                        <button type="button" title="Duplicate"
                                x-on:click="duplicateOpen = true; duplicateId = {{ $post->id }}; duplicateTitle = '{{ addslashes($post->title) }}'; duplicateNewTitle = 'Copy of {{ addslashes($post->title) }}'"
                                class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-info-light hover:text-info">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
                        </button>
                        <button type="button" title="Delete"
                                x-on:click="deleteOpen = true; deleteId = {{ $post->id }}; deleteName = '{{ addslashes($post->title) }}'"
                                class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-danger-light hover:text-danger">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6" data-posts-pagination>
        {{ $posts->onEachSide(1)->links() }}
    </div>
@endif

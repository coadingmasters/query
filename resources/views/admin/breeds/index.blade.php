<x-admin.shell active="breeds" title="Breeds">

    <div class="flex flex-wrap items-start justify-between gap-4 animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <div>
            <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Cat breeds</h2>
            <p class="mt-1 text-sm text-ink-muted">The breed data behind the age calculator, the coming breed quiz, and anywhere else on the site that needs it.</p>
        </div>
        <a href="{{ route('admin.breeds.create') }}"
           class="flex items-center gap-2 rounded-full bg-primary-vivid px-5 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Add breed
        </a>
    </div>

    @if (session('status'))
        <p class="mt-5 rounded-xl bg-accent-light px-4 py-3 text-sm font-semibold text-accent-dark">{{ session('status') }}</p>
    @endif

    <div class="mt-6 grid gap-4 sm:grid-cols-4">
        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Total breeds</p>
            <p class="mt-1 font-heading text-2xl font-extrabold text-ink">{{ $counts['total'] }}</p>
        </div>
        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Small</p>
            <p class="mt-1 font-heading text-2xl font-extrabold text-ink">{{ $counts['small'] }}</p>
        </div>
        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Medium</p>
            <p class="mt-1 font-heading text-2xl font-extrabold text-ink">{{ $counts['medium'] }}</p>
        </div>
        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Large</p>
            <p class="mt-1 font-heading text-2xl font-extrabold text-ink">{{ $counts['large'] }}</p>
        </div>
    </div>

    <form method="GET" class="mt-6 flex flex-wrap gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search breeds..."
               class="min-w-0 flex-1 rounded-xl border border-line-strong bg-surface px-4 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
        <label for="size" class="sr-only">Filter by size</label>
        <select id="size" name="size" class="rounded-xl border border-line-strong bg-surface px-4 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
            <option value="">All sizes</option>
            @foreach (['small' => 'Small', 'medium' => 'Medium', 'large' => 'Large'] as $value => $label)
                <option value="{{ $value }}" @selected(request('size') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl border border-line-strong bg-surface px-5 py-2.5 text-sm font-semibold text-ink transition hover:border-primary hover:text-primary">
            Filter
        </button>
        @if (request('q') || request('size'))
            <a href="{{ route('admin.breeds.index') }}" class="flex items-center px-2 text-sm font-semibold text-ink-muted hover:text-ink">Clear</a>
        @endif
    </form>

    <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead>
                    <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                        <th scope="col" class="px-5 py-3 font-semibold">Breed</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Size</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Weight</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Origin</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Lifespan</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($breeds as $breed)
                        <tr class="{{ $breed->is_active ? '' : 'opacity-50' }}">
                            <td class="px-5 py-3.5 font-semibold text-ink">{{ $breed->name }}</td>
                            <td class="px-5 py-3.5">
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-xs font-bold uppercase',
                                    'bg-info-light text-info' => $breed->size_category === 'small',
                                    'bg-accent-light text-accent-dark' => $breed->size_category === 'medium',
                                    'bg-warning-light text-warning' => $breed->size_category === 'large',
                                ])>{{ $breed->size_category }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-ink-muted">
                                @if ($breed->weight_min_kg && $breed->weight_max_kg)
                                    {{ rtrim(rtrim($breed->weight_min_kg, '0'), '.') }}&ndash;{{ rtrim(rtrim($breed->weight_max_kg, '0'), '.') }} kg
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-ink-muted">{{ $breed->origin_country ?: '—' }}</td>
                            <td class="px-5 py-3.5 text-ink-muted">
                                @if ($breed->lifespan_min && $breed->lifespan_max)
                                    {{ $breed->lifespan_min }}&ndash;{{ $breed->lifespan_max }} yrs
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @if ($breed->is_active)
                                    <span class="text-xs font-semibold text-accent-dark">Active</span>
                                @else
                                    <span class="text-xs font-semibold text-ink-muted">Hidden</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.breeds.edit', $breed) }}" class="text-sm font-semibold text-primary hover:text-primary-hover">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center text-sm text-ink-muted">No breeds match that search.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($breeds->hasPages())
        <div class="mt-6">{{ $breeds->links() }}</div>
    @endif

</x-admin.shell>

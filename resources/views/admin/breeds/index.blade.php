@php
    $statCards = [
        ['label' => 'Total breeds', 'value' => $counts['total'], 'tone' => 'primary', 'icon' => 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z'],
        ['label' => 'Small', 'value' => $counts['small'], 'tone' => 'info', 'icon' => 'M12 3v3m6.4.6-2.1 2.1M21 12h-3M17.4 17.4l-2.1-2.1M12 18v3M8.7 15.3l-2.1 2.1M6 12H3M8.7 8.7 6.6 6.6'],
        ['label' => 'Medium', 'value' => $counts['medium'], 'tone' => 'accent', 'icon' => 'M4 13h6V4H4v9Zm0 7h6v-5H4v5Zm10 0h6V11h-6v9Zm0-16v5h6V4h-6Z'],
        ['label' => 'Large', 'value' => $counts['large'], 'tone' => 'warning', 'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z'],
    ];
    $toneClasses = [
        'primary' => 'bg-primary-light text-primary',
        'info' => 'bg-info-light text-info',
        'accent' => 'bg-accent-light text-accent-dark',
        'warning' => 'bg-warning-light text-warning',
    ];
@endphp

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

    <x-admin.toast :message="session('status')"/>

    {{-- Stat cards, one row --}}
    <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($statCards as $i => $stat)
            <div class="animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md"
                 style="animation-delay: {{ $i * 70 }}ms">
                <span class="flex size-10 items-center justify-center rounded-xl {{ $toneClasses[$stat['tone']] }}">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="{{ $stat['icon'] }}"/>
                    </svg>
                </span>
                <p class="mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $stat['label'] }}</p>
                <p class="mt-1 font-heading text-3xl font-extrabold text-ink"
                   x-data="{ n: 0 }" x-init="let t = setInterval(() => { n < {{ $stat['value'] }} ? n++ : clearInterval(t) }, Math.max(600 / Math.max({{ $stat['value'] }}, 1), 12))"
                   x-text="n">0</p>
            </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-base font-bold text-ink">Breeds by size</h3>
            <p class="text-sm text-ink-muted">How the {{ $counts['total'] }} seeded breeds split across small, medium and large.</p>
            <div class="mt-6">
                <x-admin.wave-chart :data="$sizeChart" id="size"/>
            </div>
        </div>
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-base font-bold text-ink">Registry recognition</h3>
            <p class="text-sm text-ink-muted">Breeds in this list recognized by each major registry.</p>
            <div class="mt-6">
                <x-admin.wave-chart :data="$registryChart" id="registry"/>
            </div>
        </div>
    </div>

    {{-- Live, client-side filtering: with 68 rows there is nothing to gain
         from a server round trip, and every keystroke updates the table and
         the count immediately instead of waiting on a Filter click. --}}
    <div data-breed-filters
         x-data="{
             q: '', size: '', registry: '', status: '',
             visible: {{ $breeds->count() }},
             get active() { return this.q || this.size || this.registry || this.status; },
             clear() { this.q = ''; this.size = ''; this.registry = ''; this.status = ''; },
         }"
         x-effect="
             void [q, size, registry, status];
             $nextTick(() => {
                 visible = [...$root.querySelectorAll('[data-breed-row]')].filter(row => row.style.display !== 'none').length;
             });
         ">

        <div class="mt-6 flex flex-wrap items-center gap-3">
            <div class="relative min-w-[220px] flex-1">
                <svg class="pointer-events-none absolute top-1/2 left-3.5 size-4 -translate-y-1/2 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>
                </svg>
                <label for="breed-search" class="sr-only">Search breeds</label>
                <input id="breed-search" type="text" x-model.debounce.100ms="q" placeholder="Search by name or origin..." autocomplete="off"
                       class="w-full rounded-xl border border-line-strong bg-surface py-2.5 pr-4 pl-10 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
            </div>

            <label for="breed-size" class="sr-only">Filter by size</label>
            <select id="breed-size" x-model="size" class="rounded-xl border border-line-strong bg-surface px-4 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                <option value="">All sizes</option>
                <option value="small">Small</option>
                <option value="medium">Medium</option>
                <option value="large">Large</option>
            </select>

            <label for="breed-registry" class="sr-only">Filter by registry</label>
            <select id="breed-registry" x-model="registry" class="rounded-xl border border-line-strong bg-surface px-4 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                <option value="">Any registry</option>
                <option value="tica">TICA</option>
                <option value="cfa">CFA</option>
                <option value="fife">FIFe</option>
            </select>

            <label for="breed-status" class="sr-only">Filter by status</label>
            <select id="breed-status" x-model="status" class="rounded-xl border border-line-strong bg-surface px-4 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                <option value="">Any status</option>
                <option value="active">Active</option>
                <option value="hidden">Hidden</option>
            </select>

            <button type="button" x-on:click="clear()" x-bind:disabled="!active"
                    class="rounded-xl border border-line-strong bg-surface px-5 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-line-strong disabled:hover:text-ink-muted">
                Clear filters
            </button>
        </div>

    {{-- Table, with a shared delete-confirmation dialog rather than a
         native confirm() popup. --}}
    <div class="mt-3 overflow-hidden rounded-2xl border border-line bg-surface shadow-sm" x-data="{ deleteOpen: false, deleteName: '', deleteFormId: '' }">
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
                        <tr data-breed-row
                            x-show="
                                (!q || '{{ Str::lower($breed->name.' '.$breed->origin_country) }}'.includes(q.toLowerCase())) &&
                                (!size || size === '{{ $breed->size_category }}') &&
                                (!registry || (registry === 'tica' && {{ $breed->registry_tica ? 'true' : 'false' }}) || (registry === 'cfa' && {{ $breed->registry_cfa ? 'true' : 'false' }}) || (registry === 'fife' && {{ $breed->registry_fife ? 'true' : 'false' }})) &&
                                (!status || (status === 'active' && {{ $breed->is_active ? 'true' : 'false' }}) || (status === 'hidden' && {{ $breed->is_active ? 'false' : 'true' }}))
                            "
                            class="transition-colors hover:bg-surface-section/60 {{ $breed->is_active ? '' : 'opacity-50' }}">
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
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.breeds.edit', $breed) }}" title="Edit {{ $breed->name }}"
                                       class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-primary-light hover:text-primary">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M16.5 4.5a2.1 2.1 0 0 1 3 3L7.5 19.5 3 21l1.5-4.5Z"/>
                                        </svg>
                                        <span class="sr-only">Edit {{ $breed->name }}</span>
                                    </a>
                                    <button type="button" title="Delete {{ $breed->name }}"
                                            x-on:click="deleteOpen = true; deleteName = '{{ addslashes($breed->name) }}'; deleteFormId = 'delete-breed-{{ $breed->id }}'"
                                            class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-danger-light hover:text-danger">
                                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/>
                                        </svg>
                                        <span class="sr-only">Delete {{ $breed->name }}</span>
                                    </button>
                                </div>

                                <form id="delete-breed-{{ $breed->id }}" method="POST" action="{{ route('admin.breeds.destroy', $breed) }}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
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

        {{-- Delete confirmation dialog, shared by every row. --}}
        <div x-cloak x-show="deleteOpen" x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4"
             x-on:keydown.escape.window="deleteOpen = false">
            <div x-show="deleteOpen" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-on:click.outside="deleteOpen = false"
                 class="w-full max-w-sm rounded-2xl bg-surface p-6 shadow-xl">
                <span class="flex size-11 items-center justify-center rounded-full bg-danger-light text-danger">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M10.3 3.9 2.5 17.5A2 2 0 0 0 4.2 20.5h15.6a2 2 0 0 0 1.7-3l-7.8-13.6a2 2 0 0 0-3.4 0Z"/><path d="M12 9.5v4M12 17h.01"/>
                    </svg>
                </span>
                <h3 class="mt-4 font-heading text-lg font-extrabold text-ink">Delete this breed?</h3>
                <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">
                    You're about to remove <strong class="text-ink" x-text="deleteName"></strong> from the database. This cannot be undone.
                </p>
                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="deleteOpen = false"
                            class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">
                        Cancel
                    </button>
                    <button type="button" x-on:click="document.getElementById(deleteFormId).submit()"
                            class="flex-1 rounded-full bg-danger px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:brightness-110">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <p class="mt-3 text-sm text-ink-muted">
        Showing <span x-text="visible">{{ $breeds->count() }}</span> of {{ $breeds->count() }} breeds
    </p>

    </div>

</x-admin.shell>

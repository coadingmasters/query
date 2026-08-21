@php
    $editing = $breed->exists;
    $input = 'mt-1.5 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none';
    $label = 'block text-sm font-semibold text-ink';
    $traits = [
        'energy_level' => 'Energy level',
        'affection_level' => 'Affection level',
        'child_friendly' => 'Child friendly',
        'grooming_needs' => 'Grooming needs',
        'shedding_level' => 'Shedding level',
        'intelligence' => 'Intelligence',
    ];
@endphp

<x-admin.shell active="breeds" :title="$editing ? 'Edit '.$breed->name : 'Add breed'">

    <div class="flex items-center gap-2 text-sm text-ink-muted animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <a href="{{ route('admin.breeds.index') }}" class="font-semibold text-primary hover:text-primary-hover">Breeds</a>
        <span>/</span>
        <span>{{ $editing ? $breed->name : 'Add breed' }}</span>
    </div>

    <h2 class="mt-2 font-heading text-2xl font-extrabold tracking-tight text-ink">
        {{ $editing ? 'Edit '.$breed->name : 'Add a breed' }}
    </h2>

    <x-admin.toast :message="session('status')"/>

    @if ($errors->any())
        <div class="mt-5 rounded-xl border border-danger/30 bg-danger-light px-4 py-3 text-sm text-danger">
            <p class="font-semibold">Please fix the following:</p>
            <ul class="mt-1 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $editing ? route('admin.breeds.update', $breed) : route('admin.breeds.store') }}" class="mt-6 max-w-4xl space-y-8">
        @csrf
        @if ($editing) @method('PUT') @endif

        {{-- ── Identity ──────────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Identity</h3>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="{{ $label }}">Name</label>
                    <input id="name" name="name" type="text" required value="{{ old('name', $breed->name) }}" class="{{ $input }}">
                </div>
                <div>
                    <label for="slug" class="{{ $label }}">Slug <span class="font-normal text-ink-muted">(auto-generated if left blank)</span></label>
                    <input id="slug" name="slug" type="text" value="{{ old('slug', $breed->slug) }}" placeholder="e.g. maine-coon" class="{{ $input }}">
                </div>
                <div>
                    <label for="origin_country" class="{{ $label }}">Origin country</label>
                    <input id="origin_country" name="origin_country" type="text" value="{{ old('origin_country', $breed->origin_country) }}" class="{{ $input }}">
                </div>
                <div>
                    <label for="popularity_rank" class="{{ $label }}">Popularity rank <span class="font-normal text-ink-muted">(1 = shown first)</span></label>
                    <input id="popularity_rank" name="popularity_rank" type="number" min="1" value="{{ old('popularity_rank', $breed->popularity_rank) }}" class="{{ $input }}">
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-4">
                @foreach (['registry_tica' => 'TICA', 'registry_cfa' => 'CFA', 'registry_fife' => 'FIFe'] as $field => $reg)
                    <label class="flex items-center gap-2 text-sm text-ink">
                        <input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $breed->$field)) class="size-4 rounded border-line-strong text-primary focus:ring-primary/30">
                        Recognized by {{ $reg }}
                    </label>
                @endforeach
                <label class="flex items-center gap-2 text-sm text-ink">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editing ? $breed->is_active : true)) class="size-4 rounded border-line-strong text-primary focus:ring-primary/30">
                    Visible on the site
                </label>
            </div>
        </div>

        {{-- ── Physical ──────────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Physical</h3>
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="size_category" class="{{ $label }}">Size category</label>
                    <select id="size_category" name="size_category" required class="{{ $input }}">
                        @foreach (['small' => 'Small', 'medium' => 'Medium', 'large' => 'Large'] as $value => $text)
                            <option value="{{ $value }}" @selected(old('size_category', $breed->size_category) === $value)>{{ $text }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="weight_min_kg" class="{{ $label }}">Weight min (kg)</label>
                    <input id="weight_min_kg" name="weight_min_kg" type="number" step="0.1" min="0" value="{{ old('weight_min_kg', $breed->weight_min_kg) }}" class="{{ $input }}">
                </div>
                <div>
                    <label for="weight_max_kg" class="{{ $label }}">Weight max (kg)</label>
                    <input id="weight_max_kg" name="weight_max_kg" type="number" step="0.1" min="0" value="{{ old('weight_max_kg', $breed->weight_max_kg) }}" class="{{ $input }}">
                </div>
            </div>

            <div class="mt-4">
                <label for="coat_length" class="{{ $label }}">Coat length</label>
                <select id="coat_length" name="coat_length" class="{{ $input }} sm:max-w-xs">
                    <option value="">Not set</option>
                    @foreach (['hairless' => 'Hairless', 'short' => 'Short', 'medium' => 'Medium', 'long' => 'Long'] as $value => $text)
                        <option value="{{ $value }}" @selected(old('coat_length', $breed->coat_length) === $value)>{{ $text }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ── Traits ────────────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Traits <span class="font-normal normal-case text-ink-muted">(1 low, 5 high)</span></h3>
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                @foreach ($traits as $field => $text)
                    <div>
                        <label for="{{ $field }}" class="{{ $label }}">{{ $text }}</label>
                        <select id="{{ $field }}" name="{{ $field }}" class="{{ $input }}">
                            <option value="">Not set</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" @selected((string) old($field, $breed->$field) === (string) $i)>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── Health ────────────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Health</h3>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="lifespan_min" class="{{ $label }}">Lifespan min (years)</label>
                    <input id="lifespan_min" name="lifespan_min" type="number" min="0" value="{{ old('lifespan_min', $breed->lifespan_min) }}" class="{{ $input }}">
                </div>
                <div>
                    <label for="lifespan_max" class="{{ $label }}">Lifespan max (years)</label>
                    <input id="lifespan_max" name="lifespan_max" type="number" min="0" value="{{ old('lifespan_max', $breed->lifespan_max) }}" class="{{ $input }}">
                </div>
            </div>
            <div class="mt-4">
                <label for="health_watch" class="{{ $label }}">Conditions to watch for <span class="font-normal text-ink-muted">(one per line)</span></label>
                <textarea id="health_watch" name="health_watch" rows="3" class="{{ $input }}">{{ old('health_watch', $breed->health_watch ? implode("\n", $breed->health_watch) : '') }}</textarea>
            </div>
            <label class="mt-4 flex items-center gap-2 text-sm text-ink">
                <input type="checkbox" name="hypoallergenic" value="1" @checked(old('hypoallergenic', $breed->hypoallergenic)) class="size-4 rounded border-line-strong text-primary focus:ring-primary/30">
                Often marketed as lower-allergen
            </label>
        </div>

        {{-- ── Lifestyle fit ─────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Lifestyle fit</h3>
            <div class="mt-4 flex flex-wrap gap-4">
                <label class="flex items-center gap-2 text-sm text-ink">
                    <input type="checkbox" name="good_for_apartments" value="1" @checked(old('good_for_apartments', $breed->good_for_apartments)) class="size-4 rounded border-line-strong text-primary focus:ring-primary/30">
                    Good for apartments
                </label>
                <label class="flex items-center gap-2 text-sm text-ink">
                    <input type="checkbox" name="good_for_beginners" value="1" @checked(old('good_for_beginners', $breed->good_for_beginners)) class="size-4 rounded border-line-strong text-primary focus:ring-primary/30">
                    Good for first-time owners
                </label>
            </div>
        </div>

        {{-- ── Content ───────────────────────────────────────────────── --}}
        <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Content</h3>
            <div class="mt-4">
                <label for="temperament_summary" class="{{ $label }}">Temperament summary <span class="font-normal text-ink-muted">(one line)</span></label>
                <input id="temperament_summary" name="temperament_summary" type="text" value="{{ old('temperament_summary', $breed->temperament_summary) }}" class="{{ $input }}">
            </div>
            <div class="mt-4">
                <label for="description" class="{{ $label }}">Description</label>
                <textarea id="description" name="description" rows="3" class="{{ $input }}">{{ old('description', $breed->description) }}</textarea>
            </div>
            <div class="mt-4">
                <label for="fun_fact" class="{{ $label }}">Fun fact</label>
                <textarea id="fun_fact" name="fun_fact" rows="2" class="{{ $input }}">{{ old('fun_fact', $breed->fun_fact) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-between" @if ($editing) x-data="{ deleteOpen: false }" @endif>
            <div>
                @if ($editing)
                    <button type="button" x-on:click="deleteOpen = true"
                            class="flex items-center gap-1.5 text-sm font-semibold text-danger hover:text-danger/80">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/>
                        </svg>
                        Delete this breed
                    </button>

                    {{-- A styled confirmation dialog rather than a native
                         confirm() popup. --}}
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
                                You're about to remove <strong class="text-ink">{{ $breed->name }}</strong> from the database. This cannot be undone.
                            </p>
                            <div class="mt-6 flex gap-3">
                                <button type="button" x-on:click="deleteOpen = false"
                                        class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">
                                    Cancel
                                </button>
                                <button type="button" x-on:click="document.getElementById('delete-breed-form').submit()"
                                        class="flex-1 rounded-full bg-danger px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:brightness-110">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.breeds.index') }}" class="rounded-full border border-line-strong bg-surface px-5 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">
                    Cancel
                </a>
                <button type="submit" class="rounded-full bg-primary-vivid px-6 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
                    {{ $editing ? 'Save changes' : 'Add breed' }}
                </button>
            </div>
        </div>
    </form>

    @if ($editing)
        <form id="delete-breed-form" method="POST" action="{{ route('admin.breeds.destroy', $breed) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif

</x-admin.shell>

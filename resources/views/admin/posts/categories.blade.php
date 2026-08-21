@php
    $defaultIcon = 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z';
    $palette = ['#F47C6B', '#5DA0E4', '#A9C3A0', '#EF9F27', '#EA7B7A', '#4F6C49'];
@endphp

<x-admin.shell active="categories" title="Categories">

    <div x-data="categoriesAdmin({{ \Illuminate\Support\Js::from($categories) }})">

        <div class="flex flex-wrap items-start justify-between gap-4 animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
            <div>
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Blog categories</h2>
                <p class="mt-1 text-sm text-ink-muted">How posts are grouped across the site.</p>
            </div>
            <button type="button" x-on:click="openAdd()"
                    class="flex items-center gap-2 rounded-full bg-primary-vivid px-5 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Add Category
            </button>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <template x-for="cat in categories" :key="cat.id">
                <div class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <span class="dot-color flex size-10 items-center justify-center rounded-xl text-white" x-bind:style="`--dot-color:${cat.color}`">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path x-bind:d="cat.icon || '{{ $defaultIcon }}'"/>
                            </svg>
                        </span>
                        <span class="rounded-full px-2 py-0.5 text-[11px] font-bold uppercase" x-bind:class="cat.is_active ? 'bg-accent-light text-accent-dark' : 'bg-surface-soft text-ink-muted'" x-text="cat.is_active ? 'Active' : 'Hidden'"></span>
                    </div>
                    <p class="mt-4 font-heading text-lg font-bold text-ink" x-text="cat.name"></p>
                    <p class="text-sm text-ink-muted"><span x-text="cat.posts_count"></span> posts</p>
                    <p class="mt-1 line-clamp-2 text-xs text-ink-muted" x-text="cat.description || ''"></p>
                    <div class="mt-4 flex items-center gap-1 border-t border-line pt-3">
                        <button type="button" x-on:click="openEdit(cat)" class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-primary-light hover:text-primary">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16.5 4.5a2.1 2.1 0 0 1 3 3L7.5 19.5 3 21l1.5-4.5Z"/></svg>
                        </button>
                        <button type="button" x-on:click="deleteOpen = true; deleteId = cat.id; deleteName = cat.name"
                                class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-danger-light hover:text-danger">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/></svg>
                        </button>
                    </div>
                </div>
            </template>

            <template x-if="categories.length === 0">
                <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-line bg-surface py-16 text-center shadow-sm">
                    <p class="text-sm font-semibold text-ink">No categories yet</p>
                    <p class="mt-1 text-sm text-ink-muted">Add one to start organizing posts.</p>
                </div>
            </template>
        </div>

        {{-- Add / edit modal --}}
        <div x-cloak x-show="modalOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm" x-on:keydown.escape.window="modalOpen = false">
            <div x-show="modalOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="modalOpen = false" class="w-full max-w-sm rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading text-lg font-extrabold text-ink" x-text="editingId ? 'Edit category' : 'Add category'"></h3>
                    <button type="button" x-on:click="modalOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>

                <label class="mt-4 block text-sm font-semibold text-ink">Name</label>
                <input type="text" x-model="name" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                <label class="mt-3 block text-sm font-semibold text-ink">Description</label>
                <textarea x-model="description" rows="2" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"></textarea>

                <p class="mt-3 text-xs font-semibold text-ink-muted uppercase">Color</p>
                <div class="mt-1.5 flex gap-2">
                    @foreach ($palette as $c)
                        <button type="button" x-on:click="color = '{{ $c }}'" class="dot-color size-7 rounded-full ring-2 ring-offset-2" x-bind:class="color === '{{ $c }}' ? 'ring-primary' : 'ring-transparent'" style="--dot-color:{{ $c }}"></button>
                    @endforeach
                </div>

                <label class="mt-4 flex cursor-pointer items-center gap-2 text-sm font-semibold text-ink">
                    <input type="checkbox" x-model="isActive" class="size-4 rounded border-line-strong text-primary focus:ring-primary/30">
                    Active
                </label>

                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="modalOpen = false" class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">Cancel</button>
                    <button type="button" x-on:click="save()" x-bind:disabled="saving" class="flex-1 rounded-full bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95 disabled:opacity-60">
                        <span x-text="saving ? 'Saving…' : 'Save Category'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Delete confirmation modal --}}
        <div x-cloak x-show="deleteOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm" x-on:keydown.escape.window="deleteOpen = false">
            <div x-show="deleteOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="deleteOpen = false" class="w-full max-w-sm rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex justify-end">
                    <button type="button" x-on:click="deleteOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>
                <div class="-mt-2 flex flex-col items-center text-center">
                    <span class="flex size-14 items-center justify-center rounded-full bg-danger-light text-danger">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/></svg>
                    </span>
                    <h3 class="mt-4 font-heading text-lg font-extrabold text-ink">Delete "<span x-text="deleteName"></span>"?</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">Categories with posts assigned can't be deleted.</p>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="deleteOpen = false" class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">Cancel</button>
                    <button type="button" x-on:click="confirmDelete()" x-bind:disabled="deleting" class="flex-1 rounded-full bg-danger px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:brightness-110 disabled:opacity-60">
                        <span x-text="deleting ? 'Deleting…' : 'Yes, Delete'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function categoriesAdmin(initial) {
        return {
            categories: initial,
            modalOpen: false, editingId: null,
            name: '', description: '', color: '{{ $palette[0] }}', isActive: true,
            deleteOpen: false, deleteId: null, deleteName: '',
            saving: false, deleting: false,

            openAdd() {
                this.editingId = null; this.name = ''; this.description = ''; this.color = '{{ $palette[0] }}'; this.isActive = true;
                this.modalOpen = true;
            },

            openEdit(cat) {
                this.editingId = cat.id; this.name = cat.name; this.description = cat.description || '';
                this.color = cat.color; this.isActive = !!cat.is_active;
                this.modalOpen = true;
            },

            async save() {
                if (!this.name.trim()) return;
                this.saving = true;
                try {
                    const url = this.editingId ? `/admin/post-categories/${this.editingId}` : '/admin/post-categories';
                    const res = await fetch(url, {
                        method: this.editingId ? 'PUT' : 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ name: this.name, description: this.description, color: this.color, is_active: this.isActive }),
                    });
                    if (!res.ok) throw new Error();
                    const category = await res.json();
                    const idx = this.categories.findIndex((c) => c.id === category.id);
                    if (idx > -1) this.categories[idx] = { ...this.categories[idx], ...category };
                    else this.categories.push({ ...category, posts_count: 0 });
                    this.modalOpen = false;
                    showToast('success', this.editingId ? 'Category updated' : 'Category added', category.name);
                } catch (e) {
                    showToast('error', 'Could not save category', 'That name might already be taken.');
                } finally {
                    this.saving = false;
                }
            },

            async confirmDelete() {
                this.deleting = true;
                try {
                    const res = await fetch(`/admin/post-categories/${this.deleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });
                    if (!res.ok) {
                        const data = await res.json().catch(() => null);
                        throw new Error(data?.message || 'Could not delete');
                    }
                    this.categories = this.categories.filter((c) => c.id !== this.deleteId);
                    this.deleteOpen = false;
                    showToast('success', 'Category deleted', this.deleteName);
                } catch (e) {
                    showToast('error', 'Could not delete category', e.message || 'Please try again.');
                } finally {
                    this.deleting = false;
                }
            },
        };
    }
    </script>
    @endpush

</x-admin.shell>

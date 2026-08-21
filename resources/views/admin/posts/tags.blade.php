@php
    $palette = ['#F47C6B', '#5DA0E4', '#A9C3A0', '#EF9F27', '#EA7B7A', '#4F6C49'];
@endphp

<x-admin.shell active="tags" title="Tags">

    <div x-data="tagsAdmin({{ \Illuminate\Support\Js::from($tags) }})">

        <div class="flex flex-wrap items-start justify-between gap-4 animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
            <div>
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Tags</h2>
                <p class="mt-1 text-sm text-ink-muted">Topic labels attached to posts. Larger pills are used more.</p>
            </div>
            <button type="button" x-on:click="openAdd()"
                    class="flex items-center gap-2 rounded-full bg-primary-vivid px-5 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Add Tag
            </button>
        </div>

        {{-- Tag cloud --}}
        <div class="mt-6 rounded-2xl border border-line bg-surface p-6 shadow-sm">
            <template x-if="tags.length === 0">
                <p class="text-sm text-ink-muted">No tags yet — add one, or they'll be created automatically from post edits.</p>
            </template>
            <div class="flex flex-wrap items-center gap-2.5">
                <template x-for="tag in tags" :key="tag.id">
                    <button type="button" x-on:click="openEdit(tag)"
                            class="dot-color rounded-full px-3 py-1.5 font-semibold text-white transition hover:brightness-95"
                            x-bind:style="`--dot-color:${tag.color}; font-size:${Math.min(0.8 + tag.posts_count * 0.12, 1.6)}rem`"
                            x-text="tag.name"></button>
                </template>
            </div>
        </div>

        {{-- Table view --}}
        <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-5 py-3 font-semibold">Tag</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Posts</th>
                            <th scope="col" class="px-5 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        <template x-for="tag in tags" :key="'row-' + tag.id">
                            <tr class="transition-colors hover:bg-surface-section/60">
                                <td class="px-5 py-3.5">
                                    <span class="flex items-center gap-2 font-semibold text-ink">
                                        <span class="dot-color size-2.5 rounded-full" x-bind:style="`--dot-color:${tag.color}`"></span>
                                        <span x-text="tag.name"></span>
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-ink-muted" x-text="tag.posts_count"></td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" x-on:click="openEdit(tag)" class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-primary-light hover:text-primary">
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16.5 4.5a2.1 2.1 0 0 1 3 3L7.5 19.5 3 21l1.5-4.5Z"/></svg>
                                        </button>
                                        <button type="button" x-on:click="deleteOpen = true; deleteId = tag.id; deleteName = tag.name"
                                                class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-danger-light hover:text-danger">
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add / edit modal --}}
        <div x-cloak x-show="modalOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm" x-on:keydown.escape.window="modalOpen = false">
            <div x-show="modalOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="modalOpen = false" class="w-full max-w-sm rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading text-lg font-extrabold text-ink" x-text="editingId ? 'Edit tag' : 'Add tag'"></h3>
                    <button type="button" x-on:click="modalOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>

                <label class="mt-4 block text-sm font-semibold text-ink">Tag name</label>
                <input type="text" x-model="name" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                <p class="mt-3 text-xs font-semibold text-ink-muted uppercase">Color</p>
                <div class="mt-1.5 flex gap-2">
                    @foreach ($palette as $c)
                        <button type="button" x-on:click="color = '{{ $c }}'" class="dot-color size-7 rounded-full ring-2 ring-offset-2" x-bind:class="color === '{{ $c }}' ? 'ring-primary' : 'ring-transparent'" style="--dot-color:{{ $c }}"></button>
                    @endforeach
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="modalOpen = false" class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">Cancel</button>
                    <button type="button" x-on:click="save()" x-bind:disabled="saving" class="flex-1 rounded-full bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95 disabled:opacity-60">
                        <span x-text="saving ? 'Saving…' : 'Save Tag'"></span>
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
                    <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">This removes the tag from every post that uses it.</p>
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
    function tagsAdmin(initial) {
        return {
            tags: initial,
            modalOpen: false, editingId: null,
            name: '', color: '{{ $palette[0] }}',
            deleteOpen: false, deleteId: null, deleteName: '',
            saving: false, deleting: false,

            openAdd() {
                this.editingId = null; this.name = ''; this.color = '{{ $palette[0] }}';
                this.modalOpen = true;
            },

            openEdit(tag) {
                this.editingId = tag.id; this.name = tag.name; this.color = tag.color;
                this.modalOpen = true;
            },

            async save() {
                if (!this.name.trim()) return;
                this.saving = true;
                try {
                    const url = this.editingId ? `/admin/post-tags/${this.editingId}` : '/admin/post-tags';
                    const res = await fetch(url, {
                        method: this.editingId ? 'PUT' : 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ name: this.name, color: this.color }),
                    });
                    if (!res.ok) throw new Error();
                    const tag = await res.json();
                    const idx = this.tags.findIndex((t) => t.id === tag.id);
                    if (idx > -1) this.tags[idx] = { ...this.tags[idx], ...tag };
                    else this.tags.push({ ...tag, posts_count: 0 });
                    this.modalOpen = false;
                    showToast('success', this.editingId ? 'Tag updated' : 'Tag added', tag.name);
                } catch (e) {
                    showToast('error', 'Could not save tag', 'That name might already be taken.');
                } finally {
                    this.saving = false;
                }
            },

            async confirmDelete() {
                this.deleting = true;
                try {
                    const res = await fetch(`/admin/post-tags/${this.deleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });
                    if (!res.ok) throw new Error();
                    this.tags = this.tags.filter((t) => t.id !== this.deleteId);
                    this.deleteOpen = false;
                    showToast('success', 'Tag deleted', this.deleteName);
                } catch (e) {
                    showToast('error', 'Could not delete tag', 'Please try again.');
                } finally {
                    this.deleting = false;
                }
            },
        };
    }
    </script>
    @endpush

</x-admin.shell>

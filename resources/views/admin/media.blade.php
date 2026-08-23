<x-admin.shell active="media" title="Media">

    <div x-data="mediaAdmin({{ \Illuminate\Support\Js::from($media) }}, {{ \Illuminate\Support\Js::from($categories) }})">

        <div class="flex flex-wrap items-start justify-between gap-4 animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
            <div>
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Media</h2>
                <p class="mt-1 text-sm text-ink-muted">
                    Upload a PNG, JPEG or anything else — it's converted to WebP automatically, named what you tell it, and ready to use anywhere on the site.
                </p>
            </div>
            <button type="button" x-on:click="uploadOpen = true"
                    class="flex items-center gap-2 rounded-full bg-primary-vivid px-5 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                Upload Images
            </button>
        </div>

        {{-- Stats --}}
        <div class="mt-6 grid grid-cols-2 gap-4">
            <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Images</p>
                <p class="mt-1 font-heading text-2xl font-extrabold text-ink">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Storage used</p>
                <p class="mt-1 font-heading text-2xl font-extrabold text-ink">{{ $stats['size'] }}</p>
            </div>
        </div>

        {{-- Category filter, each with a live count --}}
        <div class="mt-6 flex flex-wrap gap-2">
            <button type="button" x-on:click="activeCategory = 'all'"
                    class="rounded-full border px-4 py-1.5 text-sm font-semibold transition"
                    x-bind:class="activeCategory === 'all' ? 'border-primary bg-primary-light text-primary' : 'border-line-strong text-ink-muted hover:border-primary hover:text-primary'">
                All <span x-text="media.length"></span>
            </button>
            @foreach ($categories as $cat)
                <button type="button" x-on:click="activeCategory = '{{ $cat }}'"
                        class="rounded-full border px-4 py-1.5 text-sm font-semibold capitalize transition"
                        x-bind:class="activeCategory === '{{ $cat }}' ? 'border-primary bg-primary-light text-primary' : 'border-line-strong text-ink-muted hover:border-primary hover:text-primary'">
                    {{ $cat }} <span x-text="media.filter((m) => m.category === '{{ $cat }}').length"></span>
                </button>
            @endforeach
        </div>

        {{-- Grid --}}
        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            <template x-for="item in filtered" :key="item.id">
                <div class="group relative overflow-hidden rounded-2xl border border-line bg-surface shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="aspect-square overflow-hidden bg-surface-section">
                        <img :src="item.url" :alt="item.alt_text || item.name" loading="lazy"
                             class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    </div>

                    <div class="absolute inset-x-0 top-0 flex items-center justify-between gap-1 bg-gradient-to-b from-ink/60 to-transparent p-2 opacity-0 transition-opacity group-hover:opacity-100">
                        <button type="button" x-on:click="copyUrl(item)" title="Copy URL"
                                class="flex size-8 items-center justify-center rounded-lg bg-surface/90 text-ink-muted transition hover:text-primary">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 9h10a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V10a1 1 0 0 1 1-1Z M5 15H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1"/></svg>
                        </button>
                        <div class="flex gap-1">
                            <button type="button" x-on:click="openEdit(item)" title="Edit"
                                    class="flex size-8 items-center justify-center rounded-lg bg-surface/90 text-ink-muted transition hover:text-primary">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16.5 4.5a2.1 2.1 0 0 1 3 3L7.5 19.5 3 21l1.5-4.5Z"/></svg>
                            </button>
                            <button type="button" x-on:click="deleteOpen = true; deleteId = item.id; deleteName = item.name" title="Delete"
                                    class="flex size-8 items-center justify-center rounded-lg bg-surface/90 text-ink-muted transition hover:text-danger">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="p-3">
                        <p class="truncate text-sm font-semibold text-ink" x-text="item.name"></p>
                        <div class="mt-1 flex items-center justify-between gap-2 text-xs text-ink-muted">
                            <span class="truncate rounded-full bg-surface-soft px-2 py-0.5 capitalize" x-text="item.category"></span>
                            <span x-text="item.width + '×' + item.height"></span>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="filtered.length === 0">
                <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-line-strong py-16 text-center">
                    <p class="text-sm font-semibold text-ink">No images here yet</p>
                    <p class="mt-1 text-sm text-ink-muted">Upload one, or pick a different category above.</p>
                </div>
            </template>
        </div>

        {{-- ══ Upload modal ═══════════════════════════════════════════════ --}}
        <div x-cloak x-show="uploadOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm" x-on:keydown.escape.window="uploadOpen = false">
            <div x-show="uploadOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="uploadOpen = false" class="w-full max-w-lg rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading text-lg font-extrabold text-ink">Upload images</h3>
                    <button type="button" x-on:click="uploadOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>

                {{-- Dropzone --}}
                <div x-on:click="$refs.fileInput.click()"
                     x-on:dragover.prevent="dragOver = true" x-on:dragleave.prevent="dragOver = false"
                     x-on:drop.prevent="dragOver = false; onFilesSelected($event.dataTransfer.files)"
                     class="mt-4 flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed px-6 py-8 text-center transition"
                     x-bind:class="dragOver ? 'border-primary bg-primary-light/40' : 'border-line-strong hover:border-primary'">
                    <svg class="size-8 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                    <p class="mt-2 text-sm font-semibold text-ink">Click to browse, or drop images here</p>
                    <p class="mt-0.5 text-xs text-ink-muted">PNG, JPEG, WebP, GIF or AVIF — one or several at once</p>
                    <input type="file" x-ref="fileInput" multiple accept="image/*" class="hidden" x-on:change="onFilesSelected($event.target.files)">
                </div>

                {{-- Selected files --}}
                <template x-if="files.length">
                    <div class="mt-4 flex flex-wrap gap-2">
                        <template x-for="(f, i) in files" :key="i">
                            <div class="relative size-16 overflow-hidden rounded-lg border border-line bg-surface-section">
                                <img :src="f.previewUrl" class="h-full w-full object-cover" alt="">
                                <button type="button" x-on:click="files.splice(i, 1)"
                                        class="absolute top-0.5 right-0.5 flex size-5 items-center justify-center rounded-full bg-ink/70 text-white">
                                    <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="files.length === 1">
                    <div>
                        <label class="mt-4 block text-sm font-semibold text-ink">Name</label>
                        <input type="text" x-model="uploadName" placeholder="e.g. maine-coon-hero" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                        <label class="mt-3 block text-sm font-semibold text-ink">Alt text</label>
                        <input type="text" x-model="uploadAlt" placeholder="Describes the image for screen readers and search" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>
                </template>

                <p class="mt-4 text-xs text-ink-muted" x-show="files.length > 1">
                    Each image keeps its original filename as its name — rename any of them afterward from the grid.
                </p>

                <label class="mt-4 block text-sm font-semibold text-ink">Category</label>
                <select x-model="uploadCategory" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink capitalize focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    <template x-for="cat in categories" :key="cat">
                        <option :value="cat" x-text="cat"></option>
                    </template>
                </select>

                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="uploadOpen = false" class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">Cancel</button>
                    <button type="button" x-on:click="upload()" x-bind:disabled="uploading || !files.length" class="flex-1 rounded-full bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95 disabled:opacity-60">
                        <span x-text="uploading ? 'Uploading…' : 'Upload'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ══ Edit modal ═════════════════════════════════════════════════ --}}
        <div x-cloak x-show="editOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm" x-on:keydown.escape.window="editOpen = false">
            <div x-show="editOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="editOpen = false" class="w-full max-w-sm rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading text-lg font-extrabold text-ink">Edit image</h3>
                    <button type="button" x-on:click="editOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>

                <label class="mt-4 block text-sm font-semibold text-ink">Name</label>
                <input type="text" x-model="editName" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                <label class="mt-3 block text-sm font-semibold text-ink">Alt text</label>
                <input type="text" x-model="editAlt" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                <label class="mt-3 block text-sm font-semibold text-ink">Category</label>
                <select x-model="editCategory" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink capitalize focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    <template x-for="cat in categories" :key="cat">
                        <option :value="cat" x-text="cat"></option>
                    </template>
                </select>

                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="editOpen = false" class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">Cancel</button>
                    <button type="button" x-on:click="saveEdit()" x-bind:disabled="saving" class="flex-1 rounded-full bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95 disabled:opacity-60">
                        <span x-text="saving ? 'Saving…' : 'Save'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ══ Delete confirmation modal ══════════════════════════════════ --}}
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
                    <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">
                        The file is removed from storage too — anything on the site still pointing at it will break.
                    </p>
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
    function mediaAdmin(initialMedia, categories) {
        return {
            media: initialMedia,
            categories: categories,
            activeCategory: 'all',

            uploadOpen: false, dragOver: false, uploading: false,
            files: [], uploadName: '', uploadAlt: '', uploadCategory: categories[0] ?? 'general',

            editOpen: false, editingId: null, editName: '', editAlt: '', editCategory: '', saving: false,

            deleteOpen: false, deleteId: null, deleteName: '', deleting: false,

            get filtered() {
                return this.activeCategory === 'all'
                    ? this.media
                    : this.media.filter((m) => m.category === this.activeCategory);
            },

            onFilesSelected(fileList) {
                this.files = Array.from(fileList)
                    .filter((f) => f.type.startsWith('image/'))
                    .map((file) => ({ file, previewUrl: URL.createObjectURL(file) }));
            },

            async upload() {
                if (!this.files.length) return;
                this.uploading = true;

                const formData = new FormData();
                this.files.forEach(({ file }) => formData.append('images[]', file));
                if (this.files.length === 1 && this.uploadName.trim()) formData.append('name', this.uploadName.trim());
                if (this.files.length === 1 && this.uploadAlt.trim()) formData.append('alt_text', this.uploadAlt.trim());
                formData.append('category', this.uploadCategory);

                try {
                    const res = await fetch('{{ route("admin.media.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });
                    if (!res.ok) throw new Error();
                    const data = await res.json();
                    this.media = [...data.media.slice().reverse(), ...this.media];
                    showToast('success', data.media.length > 1 ? `${data.media.length} images uploaded` : 'Image uploaded', 'Converted to WebP and ready to use.');
                    this.uploadOpen = false;
                    this.files.forEach((f) => URL.revokeObjectURL(f.previewUrl));
                    this.files = []; this.uploadName = ''; this.uploadAlt = '';
                    this.$refs.fileInput.value = '';
                } catch (e) {
                    showToast('error', 'Upload failed', 'Check the file types and sizes, then try again.');
                } finally {
                    this.uploading = false;
                }
            },

            openEdit(item) {
                this.editingId = item.id; this.editName = item.name;
                this.editAlt = item.alt_text || ''; this.editCategory = item.category;
                this.editOpen = true;
            },

            async saveEdit() {
                if (!this.editName.trim()) return;
                this.saving = true;
                try {
                    const res = await fetch(`/admin/media/${this.editingId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ name: this.editName, alt_text: this.editAlt, category: this.editCategory }),
                    });
                    if (!res.ok) throw new Error();
                    const updated = await res.json();
                    const idx = this.media.findIndex((m) => m.id === updated.id);
                    if (idx > -1) this.media[idx] = updated;
                    this.editOpen = false;
                    showToast('success', 'Image updated', updated.name);
                } catch (e) {
                    showToast('error', 'Could not save', 'Please try again.');
                } finally {
                    this.saving = false;
                }
            },

            async confirmDelete() {
                this.deleting = true;
                try {
                    const res = await fetch(`/admin/media/${this.deleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });
                    if (!res.ok) throw new Error();
                    this.media = this.media.filter((m) => m.id !== this.deleteId);
                    this.deleteOpen = false;
                    showToast('success', 'Image deleted', this.deleteName);
                } catch (e) {
                    showToast('error', 'Could not delete', 'Please try again.');
                } finally {
                    this.deleting = false;
                }
            },

            copyUrl(item) {
                navigator.clipboard.writeText(window.location.origin + item.url);
                showToast('success', 'URL copied', item.url);
            },
        };
    }
    </script>
    @endpush

</x-admin.shell>

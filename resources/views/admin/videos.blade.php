<x-admin.shell active="videos" title="Videos">

    <div x-data="videoAdmin({{ \Illuminate\Support\Js::from($videos) }}, {{ \Illuminate\Support\Js::from($categories) }})">

        <div class="flex flex-wrap items-start justify-between gap-4 animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
            <div>
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Videos</h2>
                <p class="mt-1 text-sm text-ink-muted">
                    Upload an MP4 or WebM, name it, and it's ready to use anywhere on the site. Nothing is converted here, so export from your camera or editor as MP4 or WebM first.
                </p>
            </div>
            <button type="button" x-on:click="uploadOpen = true"
                    class="flex items-center gap-2 rounded-full bg-primary-vivid px-5 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                Upload Video
            </button>
        </div>

        {{-- Stats --}}
        <div class="mt-6 grid grid-cols-2 gap-4">
            <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                <p class="text-xs font-semibold tracking-wide text-ink-muted uppercase">Videos</p>
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
                All <span x-text="videos.length"></span>
            </button>
            @foreach ($categories as $cat)
                <button type="button" x-on:click="activeCategory = '{{ $cat }}'"
                        class="rounded-full border px-4 py-1.5 text-sm font-semibold capitalize transition"
                        x-bind:class="activeCategory === '{{ $cat }}' ? 'border-primary bg-primary-light text-primary' : 'border-line-strong text-ink-muted hover:border-primary hover:text-primary'">
                    {{ $cat }} <span x-text="videos.filter((v) => v.category === '{{ $cat }}').length"></span>
                </button>
            @endforeach
        </div>

        {{-- Grid --}}
        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
            <template x-for="item in filtered" :key="item.id">
                <div class="group relative overflow-hidden rounded-2xl border border-line bg-surface shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="relative aspect-square overflow-hidden bg-ink">
                        <video :src="item.url" preload="metadata" muted playsinline
                               class="h-full w-full object-cover"></video>
                        <span class="pointer-events-none absolute inset-0 flex items-center justify-center bg-ink/20">
                            <span class="flex size-10 items-center justify-center rounded-full bg-surface/90 text-ink shadow-sm">
                                <svg class="size-5 translate-x-0.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5-11-6.5Z"/></svg>
                            </span>
                        </span>
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
                            <span x-text="formatSize(item.size_bytes)"></span>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="filtered.length === 0">
                <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-line-strong py-16 text-center">
                    <p class="text-sm font-semibold text-ink">No videos here yet</p>
                    <p class="mt-1 text-sm text-ink-muted">Upload one, or pick a different category above.</p>
                </div>
            </template>
        </div>

        {{-- ══ Upload modal ═══════════════════════════════════════════════ --}}
        <div x-cloak x-show="uploadOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm" x-on:keydown.escape.window="uploadOpen = false">
            <div x-show="uploadOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="uploadOpen = false" class="w-full max-w-lg rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading text-lg font-extrabold text-ink">Upload a video</h3>
                    <button type="button" x-on:click="uploadOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>

                {{-- Dropzone --}}
                <template x-if="!file">
                    <div x-on:click="$refs.fileInput.click()"
                         x-on:dragover.prevent="dragOver = true" x-on:dragleave.prevent="dragOver = false"
                         x-on:drop.prevent="dragOver = false; onFileSelected($event.dataTransfer.files)"
                         class="mt-4 flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed px-6 py-8 text-center transition"
                         x-bind:class="dragOver ? 'border-primary bg-primary-light/40' : 'border-line-strong hover:border-primary'">
                        <svg class="size-8 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
                        <p class="mt-2 text-sm font-semibold text-ink">Click to browse, or drop a video here</p>
                        <p class="mt-0.5 text-xs text-ink-muted">MP4 or WebM only, up to 200 MB</p>
                        <input type="file" x-ref="fileInput" accept="video/mp4,video/webm" class="hidden" x-on:change="onFileSelected($event.target.files)">
                    </div>
                </template>

                {{-- Selected file preview --}}
                <template x-if="file">
                    <div class="mt-4">
                        <div class="relative overflow-hidden rounded-xl border border-line bg-ink">
                            <video :src="previewUrl" controls playsinline class="max-h-56 w-full"></video>
                            <button type="button" x-on:click="removeFile()" x-show="!uploading"
                                    class="absolute top-2 right-2 flex size-7 items-center justify-center rounded-full bg-ink/70 text-white">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                            </button>
                        </div>
                        <p class="mt-2 truncate text-xs text-ink-muted" x-text="file.name + ' · ' + formatSize(file.size)"></p>
                    </div>
                </template>

                {{-- Upload progress --}}
                <template x-if="uploading">
                    <div class="mt-4">
                        <div class="h-2 w-full overflow-hidden rounded-full bg-surface-section">
                            <div class="h-full rounded-full bg-primary-vivid transition-all duration-150" x-bind:style="`width: ${uploadProgress}%`"></div>
                        </div>
                        <p class="mt-1.5 text-xs font-semibold text-ink-muted" x-text="uploadProgress + '% uploaded'"></p>
                    </div>
                </template>

                <template x-if="file && !uploading">
                    <div>
                        <label class="mt-4 block text-sm font-semibold text-ink">Name</label>
                        <input type="text" x-model="uploadName" placeholder="e.g. maine-coon-walking" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                        <label class="mt-3 block text-sm font-semibold text-ink">Description</label>
                        <input type="text" x-model="uploadDescription" placeholder="What this clip shows, for your own reference" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                        <label class="mt-3 block text-sm font-semibold text-ink">Category</label>
                        <select x-model="uploadCategory" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink capitalize focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            <template x-for="cat in categories" :key="cat">
                                <option :value="cat" x-text="cat"></option>
                            </template>
                        </select>
                    </div>
                </template>

                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="uploadOpen = false" x-bind:disabled="uploading" class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary disabled:opacity-60">Cancel</button>
                    <button type="button" x-on:click="upload()" x-bind:disabled="uploading || !file" class="flex-1 rounded-full bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95 disabled:opacity-60">
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
                    <h3 class="font-heading text-lg font-extrabold text-ink">Edit video</h3>
                    <button type="button" x-on:click="editOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>

                <label class="mt-4 block text-sm font-semibold text-ink">Name</label>
                <input type="text" x-model="editName" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                <label class="mt-3 block text-sm font-semibold text-ink">Description</label>
                <input type="text" x-model="editDescription" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

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
    function videoAdmin(initialVideos, categories) {
        return {
            videos: initialVideos,
            categories: categories,
            activeCategory: 'all',

            uploadOpen: false, dragOver: false, uploading: false, uploadProgress: 0,
            file: null, previewUrl: null, uploadName: '', uploadDescription: '', uploadCategory: categories[0] ?? 'general',

            editOpen: false, editingId: null, editName: '', editDescription: '', editCategory: '', saving: false,

            deleteOpen: false, deleteId: null, deleteName: '', deleting: false,

            get filtered() {
                return this.activeCategory === 'all'
                    ? this.videos
                    : this.videos.filter((v) => v.category === this.activeCategory);
            },

            formatSize(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1024 ** 2) return (bytes / 1024).toFixed(1) + ' KB';
                if (bytes < 1024 ** 3) return (bytes / 1024 ** 2).toFixed(1) + ' MB';
                return (bytes / 1024 ** 3).toFixed(2) + ' GB';
            },

            onFileSelected(fileList) {
                const picked = fileList[0];
                if (!picked || !['video/mp4', 'video/webm'].includes(picked.type)) {
                    showToast('error', 'Not a supported file', 'Only MP4 or WebM videos can be uploaded here.');
                    return;
                }
                this.file = picked;
                this.previewUrl = URL.createObjectURL(picked);
                this.uploadName = picked.name.replace(/\.[^.]+$/, '');
            },

            removeFile() {
                if (this.previewUrl) URL.revokeObjectURL(this.previewUrl);
                this.file = null; this.previewUrl = null;
                // The dropzone, and its ref'd input, only exist in the DOM
                // while no file is selected (see the x-if above), so the
                // ref is gone by the time this runs after a successful
                // upload — guard it rather than assume it is mounted.
                if (this.$refs.fileInput) this.$refs.fileInput.value = '';
            },

            // A real progress bar matters here in a way it doesn't for the
            // image uploader, since a video can take a while — fetch() has no
            // upload-progress event, so this uses XMLHttpRequest instead.
            upload() {
                if (!this.file) return;
                this.uploading = true;
                this.uploadProgress = 0;

                const formData = new FormData();
                formData.append('video', this.file);
                if (this.uploadName.trim()) formData.append('name', this.uploadName.trim());
                if (this.uploadDescription.trim()) formData.append('description', this.uploadDescription.trim());
                formData.append('category', this.uploadCategory);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route("admin.videos.store") }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) this.uploadProgress = Math.round((e.loaded / e.total) * 100);
                });

                xhr.addEventListener('load', () => {
                    this.uploading = false;
                    if (xhr.status !== 201) {
                        const message = (() => {
                            try { return JSON.parse(xhr.responseText)?.message; } catch (e) { return null; }
                        })();
                        showToast('error', 'Upload failed', message || 'Check the file type and size, then try again.');
                        return;
                    }
                    const data = JSON.parse(xhr.responseText);
                    this.videos = [data.video, ...this.videos];
                    showToast('success', 'Video uploaded', 'Ready to use anywhere on the site.');
                    this.uploadOpen = false;
                    this.removeFile();
                    this.uploadName = ''; this.uploadDescription = '';
                });

                xhr.addEventListener('error', () => {
                    this.uploading = false;
                    showToast('error', 'Upload failed', 'Check your connection and try again.');
                });

                xhr.send(formData);
            },

            openEdit(item) {
                this.editingId = item.id; this.editName = item.name;
                this.editDescription = item.description || ''; this.editCategory = item.category;
                this.editOpen = true;
            },

            async saveEdit() {
                if (!this.editName.trim()) return;
                this.saving = true;
                try {
                    const res = await fetch(`/admin/videos/${this.editingId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ name: this.editName, description: this.editDescription, category: this.editCategory }),
                    });
                    if (!res.ok) throw new Error();
                    const updated = await res.json();
                    const idx = this.videos.findIndex((v) => v.id === updated.id);
                    if (idx > -1) this.videos[idx] = updated;
                    this.editOpen = false;
                    showToast('success', 'Video updated', updated.name);
                } catch (e) {
                    showToast('error', 'Could not save', 'Please try again.');
                } finally {
                    this.saving = false;
                }
            },

            async confirmDelete() {
                this.deleting = true;
                try {
                    const res = await fetch(`/admin/videos/${this.deleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });
                    if (!res.ok) throw new Error();
                    this.videos = this.videos.filter((v) => v.id !== this.deleteId);
                    this.deleteOpen = false;
                    showToast('success', 'Video deleted', this.deleteName);
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

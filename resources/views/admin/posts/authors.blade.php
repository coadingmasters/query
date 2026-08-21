<x-admin.shell active="authors" title="Authors">

    <div x-data="authorsAdmin({{ \Illuminate\Support\Js::from($authors) }})">

        <div class="flex flex-wrap items-start justify-between gap-4 animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
            <div>
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Authors</h2>
                <p class="mt-1 text-sm text-ink-muted">Who bylines and stands behind what's published.</p>
            </div>
            <button type="button" x-on:click="openAdd()"
                    class="flex items-center gap-2 rounded-full bg-primary-vivid px-5 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Add Author
            </button>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <template x-for="author in authors" :key="author.id">
                <div class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex items-start gap-3">
                        <template x-if="author.photo_url">
                            <img x-bind:src="author.photo_url" class="size-14 shrink-0 rounded-full object-cover" alt="">
                        </template>
                        <template x-if="!author.photo_url">
                            <span class="flex size-14 shrink-0 items-center justify-center rounded-full bg-primary-light text-lg font-bold text-primary" x-text="author.name.charAt(0)"></span>
                        </template>
                        <div class="min-w-0 flex-1">
                            <p class="font-heading text-base font-bold text-ink" x-text="author.name"></p>
                            <p class="text-xs text-ink-muted" x-text="author.credentials || ''"></p>
                            <p class="mt-0.5 text-xs font-semibold text-ink-muted"><span x-text="author.posts_count"></span> posts</p>
                        </div>
                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-bold uppercase" x-bind:class="author.is_active ? 'bg-accent-light text-accent-dark' : 'bg-surface-soft text-ink-muted'" x-text="author.is_active ? 'Active' : 'Hidden'"></span>
                    </div>
                    <p class="mt-3 line-clamp-2 text-sm text-ink-muted" x-text="author.bio || ''"></p>
                    <div class="mt-4 flex items-center gap-1 border-t border-line pt-3">
                        <button type="button" x-on:click="openEdit(author)" class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-primary-light hover:text-primary">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16.5 4.5a2.1 2.1 0 0 1 3 3L7.5 19.5 3 21l1.5-4.5Z"/></svg>
                        </button>
                        <a x-bind:href="`/admin/posts?author=${author.id}`" title="View posts" class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-info-light hover:text-info">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z"/></svg>
                        </a>
                        <button type="button" x-on:click="deleteOpen = true; deleteId = author.id; deleteName = author.name"
                                class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-danger-light hover:text-danger">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/></svg>
                        </button>
                    </div>
                </div>
            </template>

            <template x-if="authors.length === 0">
                <div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-line bg-surface py-16 text-center shadow-sm">
                    <p class="text-sm font-semibold text-ink">No authors yet</p>
                    <p class="mt-1 text-sm text-ink-muted">Add one so posts can be bylined.</p>
                </div>
            </template>
        </div>

        {{-- Add / edit modal --}}
        <div x-cloak x-show="modalOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm" x-on:keydown.escape.window="modalOpen = false">
            <div x-show="modalOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="modalOpen = false" class="w-full max-w-sm rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading text-lg font-extrabold text-ink" x-text="editingId ? 'Edit author' : 'Add author'"></h3>
                    <button type="button" x-on:click="modalOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>

                <div class="mt-4 flex justify-center">
                    <button type="button" x-on:click="$refs.photoInput.click()" class="relative flex size-20 items-center justify-center overflow-hidden rounded-full border-2 border-dashed border-line-strong bg-surface-section text-ink-muted transition hover:border-primary hover:text-primary">
                        <template x-if="photoPreview">
                            <img x-bind:src="photoPreview" class="size-full object-cover" alt="">
                        </template>
                        <template x-if="!photoPreview">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4m0 0 4 4m-4-4L8 8M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>
                        </template>
                    </button>
                    <input x-ref="photoInput" type="file" accept="image/jpeg,image/png,image/webp" class="hidden" x-on:change="handlePhoto($event.target.files[0])">
                </div>

                <label class="mt-4 block text-sm font-semibold text-ink">Full name</label>
                <input type="text" x-model="name" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                <label class="mt-3 block text-sm font-semibold text-ink">Email</label>
                <input type="email" x-model="email" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                <label class="mt-3 block text-sm font-semibold text-ink">Credentials</label>
                <input type="text" x-model="credentials" placeholder="e.g. DVM, Cornell University" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                <label class="mt-3 block text-sm font-semibold text-ink">Bio</label>
                <textarea x-model="bio" rows="3" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"></textarea>

                <div class="mt-3 grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-ink">Twitter</label>
                        <input type="url" x-model="twitterUrl" placeholder="https://" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ink">LinkedIn</label>
                        <input type="url" x-model="linkedinUrl" placeholder="https://" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>
                </div>

                <label class="mt-4 flex cursor-pointer items-center gap-2 text-sm font-semibold text-ink">
                    <input type="checkbox" x-model="isActive" class="size-4 rounded border-line-strong text-primary focus:ring-primary/30">
                    Active author
                </label>

                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="modalOpen = false" class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">Cancel</button>
                    <button type="button" x-on:click="save()" x-bind:disabled="saving" class="flex-1 rounded-full bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95 disabled:opacity-60">
                        <span x-text="saving ? 'Saving…' : 'Save Author'"></span>
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
                    <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">Authors with posts assigned can't be deleted.</p>
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
    function authorsAdmin(initial) {
        return {
            authors: initial,
            modalOpen: false, editingId: null,
            name: '', email: '', credentials: '', bio: '', twitterUrl: '', linkedinUrl: '', isActive: true,
            photoPreview: null, photoFile: null,
            deleteOpen: false, deleteId: null, deleteName: '',
            saving: false, deleting: false,

            openAdd() {
                this.editingId = null; this.name = ''; this.email = ''; this.credentials = ''; this.bio = '';
                this.twitterUrl = ''; this.linkedinUrl = ''; this.isActive = true;
                this.photoPreview = null; this.photoFile = null;
                this.modalOpen = true;
            },

            openEdit(author) {
                this.editingId = author.id; this.name = author.name; this.email = author.email || '';
                this.credentials = author.credentials || ''; this.bio = author.bio || '';
                this.twitterUrl = author.twitter_url || ''; this.linkedinUrl = author.linkedin_url || '';
                this.isActive = !!author.is_active;
                this.photoPreview = author.photo_url || null; this.photoFile = null;
                this.modalOpen = true;
            },

            handlePhoto(file) {
                if (!file) return;
                this.photoFile = file;
                const reader = new FileReader();
                reader.onload = (e) => { this.photoPreview = e.target.result; };
                reader.readAsDataURL(file);
            },

            async save() {
                if (!this.name.trim()) return;
                this.saving = true;
                try {
                    const form = new FormData();
                    form.append('name', this.name);
                    form.append('email', this.email);
                    form.append('credentials', this.credentials);
                    form.append('bio', this.bio);
                    form.append('twitter_url', this.twitterUrl);
                    form.append('linkedin_url', this.linkedinUrl);
                    form.append('is_active', this.isActive ? 1 : 0);
                    if (this.photoFile) form.append('photo', this.photoFile);
                    if (this.editingId) form.append('_method', 'PUT');

                    const res = await fetch(this.editingId ? `/admin/authors/${this.editingId}` : '/admin/authors', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: form,
                    });
                    if (!res.ok) throw new Error();
                    const author = await res.json();
                    const idx = this.authors.findIndex((a) => a.id === author.id);
                    if (idx > -1) this.authors[idx] = { ...this.authors[idx], ...author };
                    else this.authors.push({ ...author, posts_count: 0 });
                    this.modalOpen = false;
                    showToast('success', this.editingId ? 'Author updated' : 'Author added', author.name);
                } catch (e) {
                    showToast('error', 'Could not save author', 'Please check the fields and try again.');
                } finally {
                    this.saving = false;
                }
            },

            async confirmDelete() {
                this.deleting = true;
                try {
                    const res = await fetch(`/admin/authors/${this.deleteId}`, {
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
                    this.authors = this.authors.filter((a) => a.id !== this.deleteId);
                    this.deleteOpen = false;
                    showToast('success', 'Author deleted', this.deleteName);
                } catch (e) {
                    showToast('error', 'Could not delete author', e.message || 'Please try again.');
                } finally {
                    this.deleting = false;
                }
            },
        };
    }
    </script>
    @endpush

</x-admin.shell>

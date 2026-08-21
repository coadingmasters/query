<x-admin.shell active="redirects" title="Redirects">

    <div x-data="redirectsAdmin({{ \Illuminate\Support\Js::from($redirects) }})">

        <div class="flex flex-wrap items-start justify-between gap-4 animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
            <div>
                <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Redirects</h2>
                <p class="mt-1 text-sm text-ink-muted">Send an old URL somewhere new — useful whenever a page moves or a slug changes.</p>
            </div>
            <button type="button" x-on:click="openAdd()"
                    class="flex items-center gap-2 rounded-full bg-primary-vivid px-5 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Add Redirect
            </button>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface-section text-xs tracking-wider text-ink-muted uppercase">
                            <th scope="col" class="px-5 py-3 font-semibold">From</th>
                            <th scope="col" class="px-5 py-3 font-semibold">To</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Type</th>
                            <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                            <th scope="col" class="px-5 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        <template x-for="r in redirects" :key="r.id">
                            <tr class="transition-colors hover:bg-surface-section/60">
                                <td class="px-5 py-3.5 font-mono text-xs font-semibold text-ink" x-text="r.from_path"></td>
                                <td class="px-5 py-3.5 font-mono text-xs text-ink-muted truncate max-w-xs" x-text="r.to_path"></td>
                                <td class="px-5 py-3.5">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-bold" x-bind:class="r.status_code === 301 ? 'bg-info-light text-info' : 'bg-warning-light text-warning'" x-text="r.status_code"></span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold" x-bind:class="r.is_active ? 'bg-accent-light text-accent-dark' : 'bg-surface-soft text-ink-muted'" x-text="r.is_active ? 'Active' : 'Off'"></span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" x-on:click="openEdit(r)" class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-primary-light hover:text-primary">
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16.5 4.5a2.1 2.1 0 0 1 3 3L7.5 19.5 3 21l1.5-4.5Z"/></svg>
                                        </button>
                                        <button type="button" x-on:click="deleteOpen = true; deleteId = r.id; deleteName = r.from_path"
                                                class="flex size-8 items-center justify-center rounded-lg text-ink-muted transition hover:bg-danger-light hover:text-danger">
                                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="redirects.length === 0">
                            <tr><td colspan="5" class="px-5 py-16 text-center text-sm text-ink-muted">No redirects yet.</td></tr>
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
                    <h3 class="font-heading text-lg font-extrabold text-ink" x-text="editingId ? 'Edit redirect' : 'Add redirect'"></h3>
                    <button type="button" x-on:click="modalOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>

                <label class="mt-4 block text-sm font-semibold text-ink">From path</label>
                <input type="text" x-model="fromPath" placeholder="/old-page" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 font-mono text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                <label class="mt-3 block text-sm font-semibold text-ink">To</label>
                <input type="text" x-model="toPath" placeholder="/new-page or https://…" class="mt-1 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 font-mono text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                <p class="mt-3 text-xs font-semibold text-ink-muted uppercase">Type</p>
                <div class="mt-1.5 flex gap-2">
                    <label class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl border border-line-strong px-3 py-2 text-sm font-semibold text-ink has-checked:border-primary has-checked:bg-primary-light/40">
                        <input type="radio" value="301" x-model.number="statusCode" class="size-4 text-primary focus:ring-primary/30">
                        301 (permanent)
                    </label>
                    <label class="flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl border border-line-strong px-3 py-2 text-sm font-semibold text-ink has-checked:border-primary has-checked:bg-primary-light/40">
                        <input type="radio" value="302" x-model.number="statusCode" class="size-4 text-primary focus:ring-primary/30">
                        302 (temporary)
                    </label>
                </div>

                <label class="mt-4 flex cursor-pointer items-center gap-2 text-sm font-semibold text-ink">
                    <input type="checkbox" x-model="isActive" class="size-4 rounded border-line-strong text-primary focus:ring-primary/30">
                    Active
                </label>

                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="modalOpen = false" class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">Cancel</button>
                    <button type="button" x-on:click="save()" x-bind:disabled="saving" class="flex-1 rounded-full bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95 disabled:opacity-60">
                        <span x-text="saving ? 'Saving…' : 'Save Redirect'"></span>
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
                    <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">Visitors to that URL will start getting a normal 404 again.</p>
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
    function redirectsAdmin(initial) {
        return {
            redirects: initial,
            modalOpen: false, editingId: null,
            fromPath: '', toPath: '', statusCode: 301, isActive: true,
            deleteOpen: false, deleteId: null, deleteName: '',
            saving: false, deleting: false,

            openAdd() {
                this.editingId = null; this.fromPath = ''; this.toPath = ''; this.statusCode = 301; this.isActive = true;
                this.modalOpen = true;
            },

            openEdit(r) {
                this.editingId = r.id; this.fromPath = r.from_path; this.toPath = r.to_path;
                this.statusCode = r.status_code; this.isActive = !!r.is_active;
                this.modalOpen = true;
            },

            async save() {
                if (!this.fromPath.trim() || !this.toPath.trim()) return;
                this.saving = true;
                try {
                    const url = this.editingId ? `/admin/redirects/${this.editingId}` : '/admin/redirects';
                    const res = await fetch(url, {
                        method: this.editingId ? 'PUT' : 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ from_path: this.fromPath, to_path: this.toPath, status_code: this.statusCode, is_active: this.isActive }),
                    });
                    if (!res.ok) throw new Error();
                    const redirect = await res.json();
                    const idx = this.redirects.findIndex((r) => r.id === redirect.id);
                    if (idx > -1) this.redirects[idx] = redirect;
                    else this.redirects.unshift(redirect);
                    this.modalOpen = false;
                    showToast('success', this.editingId ? 'Redirect updated' : 'Redirect added', `${redirect.from_path} → ${redirect.to_path}`);
                } catch (e) {
                    showToast('error', 'Could not save redirect', 'That from-path might already be taken, or it points at itself.');
                } finally {
                    this.saving = false;
                }
            },

            async confirmDelete() {
                this.deleting = true;
                try {
                    const res = await fetch(`/admin/redirects/${this.deleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    });
                    if (!res.ok) throw new Error();
                    this.redirects = this.redirects.filter((r) => r.id !== this.deleteId);
                    this.deleteOpen = false;
                    showToast('success', 'Redirect deleted', this.deleteName);
                } catch (e) {
                    showToast('error', 'Could not delete redirect', 'Please try again.');
                } finally {
                    this.deleting = false;
                }
            },
        };
    }
    </script>
    @endpush

</x-admin.shell>

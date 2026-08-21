@php
    $statCards = [
        ['label' => 'Total posts', 'value' => $counts['total'], 'tone' => 'primary', 'status' => '', 'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z'],
        ['label' => 'Published', 'value' => $counts['published'], 'tone' => 'accent', 'status' => 'published', 'icon' => 'M5 13l4 4L19 7'],
        ['label' => 'Scheduled', 'value' => $counts['scheduled'], 'tone' => 'warning', 'status' => 'scheduled', 'icon' => 'M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z'],
        ['label' => 'Drafts', 'value' => $counts['draft'], 'tone' => 'info', 'status' => 'draft', 'icon' => 'M16.5 4.5a2.1 2.1 0 0 1 3 3L7.5 19.5 3 21l1.5-4.5Z'],
    ];
    $toneClasses = [
        'primary' => 'bg-primary-light text-primary',
        'accent' => 'bg-accent-light text-accent-dark',
        'warning' => 'bg-warning-light text-warning',
        'info' => 'bg-info-light text-info',
    ];
@endphp

<x-admin.shell active="posts" title="Blog Posts">

    <div class="flex flex-wrap items-start justify-between gap-4 animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
        <div>
            <h2 class="font-heading text-2xl font-extrabold tracking-tight text-ink">Blog posts</h2>
            <p class="mt-1 text-sm text-ink-muted">Everything published (or waiting to be) on the PurrQuery blog.</p>
        </div>
        <a href="{{ route('admin.posts.create') }}"
           class="flex items-center gap-2 rounded-full bg-primary-vivid px-5 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            New Post
        </a>
    </div>

    <x-admin.toast :message="session('status')"/>

    <div x-data="postsAdmin({{ \Illuminate\Support\Js::from($filters) }})"
         x-on:posts:refresh.window="fetchPosts()">

        {{-- Stat cards --}}
        <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ($statCards as $i => $stat)
                <button type="button" x-on:click="setStatus('{{ $stat['status'] }}')"
                        class="stat-card-pop animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both] rounded-2xl border border-line bg-surface p-5 text-left shadow-sm transition hover:shadow-md"
                        style="--pop-delay: {{ $i * 70 }}ms">
                    <span class="flex size-10 items-center justify-center rounded-xl {{ $toneClasses[$stat['tone']] }}">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $stat['icon'] }}"/>
                        </svg>
                    </span>
                    <p class="mt-4 text-xs font-semibold tracking-wide text-ink-muted uppercase">{{ $stat['label'] }}</p>
                    <p class="mt-1 font-heading text-3xl font-extrabold text-ink"
                       x-data="{ n: 0 }" x-init="let t = setInterval(() => { n < {{ $stat['value'] }} ? n++ : clearInterval(t) }, Math.max(1200 / Math.max({{ $stat['value'] }}, 1), 12))"
                       x-text="n">0</p>
                </button>
            @endforeach
        </div>

        {{-- Filter bar --}}
        <div class="mt-6 flex flex-wrap items-center gap-3">
            <div class="relative min-w-[220px] flex-1">
                <svg class="pointer-events-none absolute top-1/2 left-3.5 size-4 -translate-y-1/2 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>
                </svg>
                <label for="post-search" class="sr-only">Search posts</label>
                <input id="post-search" type="text" x-model.debounce.300ms="q" placeholder="Search posts..." autocomplete="off"
                       class="w-full rounded-xl border border-line-strong bg-surface py-2.5 pr-4 pl-10 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
            </div>

            <label for="post-status" class="sr-only">Filter by status</label>
            <select id="post-status" x-model="status" class="rounded-xl border border-line-strong bg-surface px-4 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                <option value="">All status</option>
                <option value="draft">Draft</option>
                <option value="scheduled">Scheduled</option>
                <option value="published">Published</option>
            </select>

            <label for="post-category" class="sr-only">Filter by category</label>
            <select id="post-category" x-model="category" class="rounded-xl border border-line-strong bg-surface px-4 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                <option value="">All categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            <label for="post-author" class="sr-only">Filter by author</label>
            <select id="post-author" x-model="author" class="rounded-xl border border-line-strong bg-surface px-4 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                <option value="">All authors</option>
                @foreach ($authors as $a)
                    <option value="{{ $a->id }}">{{ $a->name }}</option>
                @endforeach
            </select>

            <label for="post-sort" class="sr-only">Sort</label>
            <select id="post-sort" x-model="sort" class="rounded-xl border border-line-strong bg-surface px-4 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                <option value="newest">Sort: Newest</option>
                <option value="oldest">Sort: Oldest</option>
            </select>

            <button type="button" x-on:click="clear()" x-bind:disabled="!active"
                    class="rounded-xl border border-line-strong bg-surface px-5 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:border-line-strong disabled:hover:text-ink-muted">
                Clear filters
            </button>
        </div>

        {{-- Grid: server-rendered, swapped via AJAX on filter/page change --}}
        <div class="relative mt-6" data-posts-container>
            <div data-posts-grid-wrap>
                @include('admin.posts.partials.grid', ['posts' => $posts, 'filters' => $filters])
            </div>

            {{-- Skeleton loader, shown while an AJAX request is in flight --}}
            <div x-cloak x-show="loading" x-transition.opacity class="absolute inset-0 grid grid-cols-1 gap-5 bg-surface-section sm:grid-cols-2 lg:grid-cols-3">
                @for ($i = 0; $i < 6; $i++)
                    <div class="overflow-hidden rounded-2xl border border-line bg-surface shadow-sm">
                        <div class="skeleton aspect-video w-full"></div>
                        <div class="space-y-2.5 p-5">
                            <div class="skeleton h-3 w-1/3 rounded"></div>
                            <div class="skeleton h-4 w-5/6 rounded"></div>
                            <div class="skeleton h-3 w-full rounded"></div>
                            <div class="skeleton h-3 w-2/3 rounded"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- Delete confirmation modal --}}
        <div x-cloak x-show="deleteOpen" x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm"
             x-on:keydown.escape.window="deleteOpen = false">
            <div x-show="deleteOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="deleteOpen = false"
                 class="w-full max-w-sm rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex justify-end">
                    <button type="button" x-on:click="deleteOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>
                <div class="-mt-2 flex flex-col items-center text-center">
                    <span class="flex size-14 items-center justify-center rounded-full bg-danger-light text-danger">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/>
                        </svg>
                    </span>
                    <h3 class="mt-4 font-heading text-lg font-extrabold text-ink">Delete "<span x-text="deleteName"></span>"?</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-ink-muted">
                        This action cannot be undone. The post will be permanently removed from your site.
                    </p>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="deleteOpen = false"
                            class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">
                        Cancel
                    </button>
                    <button type="button" x-on:click="confirmDelete()" x-bind:disabled="deleting"
                            class="flex flex-1 items-center justify-center gap-2 rounded-full bg-danger px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:brightness-110 disabled:opacity-60">
                        <svg x-show="deleting" x-cloak class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-opacity="0.25"/><path d="M21 12a9 9 0 0 0-9-9"/></svg>
                        <span x-text="deleting ? 'Deleting…' : 'Yes, Delete'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Duplicate modal --}}
        <div x-cloak x-show="duplicateOpen" x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm"
             x-on:keydown.escape.window="duplicateOpen = false">
            <div x-show="duplicateOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="duplicateOpen = false"
                 class="w-full max-w-sm rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex items-start justify-between">
                    <h3 class="font-heading text-lg font-extrabold text-ink">Duplicate post</h3>
                    <button type="button" x-on:click="duplicateOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>
                <p class="mt-1 text-sm text-ink-muted">Creating a copy of "<span x-text="duplicateTitle"></span>"</p>

                <label class="mt-4 block text-sm font-semibold text-ink">New title</label>
                <input type="text" x-model="duplicateNewTitle" class="mt-1.5 w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">

                <p class="mt-4 text-xs font-semibold text-ink-muted uppercase">Status</p>
                <p class="mt-1 flex items-center gap-1.5 text-sm text-ink">
                    <span class="size-2 rounded-full bg-ink-muted"></span> Draft <span class="text-ink-muted">(recommended)</span>
                </p>

                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="duplicateOpen = false"
                            class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">
                        Cancel
                    </button>
                    <button type="button" x-on:click="confirmDuplicate()" x-bind:disabled="duplicating"
                            class="flex flex-1 items-center justify-center gap-2 rounded-full bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95 disabled:opacity-60">
                        <svg x-show="duplicating" x-cloak class="size-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-opacity="0.25"/><path d="M21 12a9 9 0 0 0-9-9"/></svg>
                        <span x-text="duplicating ? 'Creating…' : 'Create Copy'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
<script>
function postsAdmin(initialFilters) {
    return {
        q: initialFilters.q || '',
        status: initialFilters.status || '',
        category: initialFilters.category || '',
        author: initialFilters.author || '',
        sort: initialFilters.sort || 'newest',
        loading: false,
        deleteOpen: false, deleteId: null, deleteName: '',
        duplicateOpen: false, duplicateId: null, duplicateTitle: '', duplicateNewTitle: '',
        deleting: false, duplicating: false,
        _skipNextFetch: false,

        get active() {
            return this.q || this.status || this.category || this.author || this.sort !== 'newest';
        },

        init() {
            this.$watch('q', () => this.fetchPosts());
            this.$watch('status', () => this.fetchPosts());
            this.$watch('category', () => this.fetchPosts());
            this.$watch('author', () => this.fetchPosts());
            this.$watch('sort', () => this.fetchPosts());

            document.querySelector('[data-posts-container]').addEventListener('click', (e) => {
                const link = e.target.closest('[data-posts-pagination] a');
                if (!link) return;
                e.preventDefault();
                this.fetchPosts(link.href);
            });
        },

        setStatus(status) {
            this.status = status;
        },

        clear() {
            this.q = ''; this.status = ''; this.category = ''; this.author = ''; this.sort = 'newest';
        },

        buildUrl() {
            const params = new URLSearchParams();
            if (this.q) params.set('q', this.q);
            if (this.status) params.set('status', this.status);
            if (this.category) params.set('category', this.category);
            if (this.author) params.set('author', this.author);
            if (this.sort !== 'newest') params.set('sort', this.sort);
            const qs = params.toString();
            return '{{ route("admin.posts.index") }}' + (qs ? `?${qs}` : '');
        },

        async fetchPosts(url = null) {
            const target = url || this.buildUrl();
            this.loading = true;
            try {
                const res = await fetch(target, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                const wrap = document.querySelector('[data-posts-grid-wrap]');
                wrap.innerHTML = html;
                window.Alpine.initTree(wrap);
                window.history.pushState({}, '', target);
            } catch (e) {
                showToast('error', 'Could not load posts', 'Check your connection and try again.');
            } finally {
                this.loading = false;
            }
        },

        async confirmDelete() {
            this.deleting = true;
            try {
                const res = await fetch(`/admin/posts/${this.deleteId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: '_method=DELETE',
                });
                if (!res.ok) throw new Error();
                const card = document.querySelector(`[data-post-card="${this.deleteId}"]`);
                this.deleteOpen = false;
                if (card) {
                    card.style.transition = 'opacity 400ms ease, transform 400ms ease, max-height 400ms ease, margin 400ms ease';
                    card.style.maxHeight = card.offsetHeight + 'px';
                    requestAnimationFrame(() => {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.95)';
                        card.style.maxHeight = '0px';
                        card.style.marginTop = '0px';
                        card.style.marginBottom = '0px';
                    });
                    setTimeout(() => this.fetchPosts(), 420);
                } else {
                    this.fetchPosts();
                }
                showToast('success', 'Post deleted', this.deleteName);
            } catch (e) {
                showToast('error', 'Could not delete post', 'Please try again.');
            } finally {
                this.deleting = false;
            }
        },

        async confirmDuplicate() {
            this.duplicating = true;
            try {
                const res = await fetch(`/admin/posts/${this.duplicateId}/duplicate`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ title: this.duplicateNewTitle }),
                });
                if (!res.ok) throw new Error();
                const data = await res.json().catch(() => null);
                this.duplicateOpen = false;
                showToast('success', 'Post duplicated', this.duplicateNewTitle);
                if (data && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    this.fetchPosts();
                }
            } catch (e) {
                showToast('error', 'Could not duplicate post', 'Please try again.');
            } finally {
                this.duplicating = false;
            }
        },
    };
}

async function changeStatus(postId, status, cardEl) {
    try {
        const res = await fetch(`/admin/posts/${postId}/status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status }),
        });
        if (!res.ok) throw new Error();
        const data = await res.json();

        cardEl.dispatchEvent(new CustomEvent('posts:refresh', { bubbles: true }));
        showToast('success', 'Status updated', `Now ${data.badge.label.toLowerCase()}`);
    } catch (e) {
        showToast('error', 'Could not update status', 'Please try again.');
    }
}

</script>
@endpush

</x-admin.shell>

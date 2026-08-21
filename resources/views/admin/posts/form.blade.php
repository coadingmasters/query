@php
    $editing = $post->exists;
    $input = 'w-full rounded-xl border border-line-strong bg-surface px-3.5 py-2.5 text-sm text-ink transition focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none';
    $label = 'block text-sm font-semibold text-ink';

    $initial = [
        'title' => old('title', $post->title) ?: '',
        'slug' => old('slug', $post->slug) ?: '',
        'excerpt' => old('excerpt', $post->excerpt) ?: '',
        'status' => old('status', $post->status ?: 'draft'),
        'publishedAt' => old('published_at', $post->published_at?->format('Y-m-d\TH:i')) ?: '',
        'isFeatured' => (bool) old('is_featured', $post->is_featured),
        'categoryId' => (string) old('category_id', $post->category_id ?: ''),
        'authorId' => (string) old('author_id', $post->author_id ?: ''),
        'tags' => $editing ? $post->tags->pluck('name')->values() : [],
        'faqs' => $editing && $post->faqs->isNotEmpty()
            ? $post->faqs->map(fn ($f) => ['question' => $f->question, 'answer' => $f->answer])->values()
            : [['question' => '', 'answer' => '']],
        'metaTitle' => old('meta_title', $post->meta_title) ?: '',
        'metaDescription' => old('meta_description', $post->meta_description) ?: '',
        'imageUrl' => $post->featured_image ? Storage::url($post->featured_image) : null,
        'imageAlt' => old('featured_image_alt', $post->featured_image_alt) ?: '',
        'wordCount' => $post->content ? str_word_count(strip_tags($post->content)) : 0,
        'content' => old('content', $post->content) ?: '',
    ];
@endphp

<x-admin.shell active="posts" :title="$editing ? 'Edit '.$post->title : 'New post'">

    <div x-data="postForm({{ \Illuminate\Support\Js::from($initial) }})">

        <div class="flex items-center gap-2 text-sm text-ink-muted animate-[result-pop_0.5s_cubic-bezier(0.16,1,0.3,1)_both]">
            <a href="{{ route('admin.posts.index') }}" class="font-semibold text-primary hover:text-primary-hover">Posts</a>
            <span>/</span>
            <span>{{ $editing ? $post->title : 'New post' }}</span>
        </div>

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

        <form method="POST" action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
              enctype="multipart/form-data" x-on:submit="beforeSubmit()">
            @csrf
            @if ($editing) @method('PUT') @endif

            <input type="hidden" name="status" x-ref="statusInput" x-bind:value="status">
            <input type="hidden" name="slug" x-ref="slugInput" x-bind:value="slug">
            <input type="hidden" name="is_featured" x-ref="featuredInput" x-bind:value="isFeatured ? 1 : 0">
            <input type="hidden" name="published_at" x-ref="publishedAtInput" x-bind:value="status === 'draft' ? '' : publishedAt">
            <input type="hidden" name="content" x-ref="contentInput">
            <template x-for="(tag, i) in tags" :key="i"><input type="hidden" name="tags[]" x-bind:value="tag"></template>
            <template x-for="(faq, i) in faqs" :key="i">
                <span>
                    <input type="hidden" class="faq-question-input" x-bind:name="`faqs[${i}][question]`" x-bind:value="faq.question">
                    <input type="hidden" class="faq-answer-input" x-bind:name="`faqs[${i}][answer]`" x-bind:value="faq.answer">
                </span>
            </template>

            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-[1fr_360px]">

                {{-- ── Left column ──────────────────────────────────────── --}}
                <div class="space-y-6">
                    <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
                        <label for="title" class="sr-only">Title</label>
                        <input id="title" name="title" type="text" required x-model="title"
                               placeholder="Enter post title..."
                               class="w-full border-none p-0 font-heading text-2xl font-extrabold text-ink placeholder:text-ink-muted focus:ring-0 focus:outline-none">

                        <div class="mt-2 flex items-center gap-1.5 text-sm text-ink-muted">
                            <span>purrquery.com/blog/</span>
                            <template x-if="!slugEditing">
                                <span class="flex items-center gap-1.5">
                                    <span class="font-semibold text-ink" x-text="slug || '…'"></span>
                                    <button type="button" x-on:click="slugEditing = true" class="text-ink-muted transition hover:text-primary">
                                        <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16.5 4.5a2.1 2.1 0 0 1 3 3L7.5 19.5 3 21l1.5-4.5Z"/></svg>
                                    </button>
                                </span>
                            </template>
                            <template x-if="slugEditing">
                                <input type="text" x-model="slug" x-on:input="_slugTouched = true" x-on:blur="slugEditing = false"
                                       class="rounded-lg border border-line-strong bg-surface px-2 py-1 text-sm text-ink focus:border-primary focus:ring-1 focus:ring-primary/20 focus:outline-none">
                            </template>
                        </div>
                    </div>

                    {{-- Rich text editor --}}
                    <div class="rounded-2xl border border-line bg-surface p-4 shadow-sm">
                        <div id="editor" class="prose-editor"></div>
                    </div>

                    {{-- Excerpt --}}
                    <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
                        <label for="excerpt" class="{{ $label }}">Excerpt</label>
                        <p class="mt-0.5 text-xs text-ink-muted">Used as the meta description if the SEO field below is left empty.</p>
                        <textarea id="excerpt" name="excerpt" rows="3" x-model="excerpt" maxlength="500" class="{{ $input }} mt-2"></textarea>
                        <p class="mt-1.5 text-right text-xs" x-bind:class="excerpt.length > 160 ? 'text-warning' : 'text-ink-muted'">
                            <span x-text="excerpt.length"></span>/160 characters
                        </p>
                    </div>

                    {{-- Featured image --}}
                    <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
                        <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Featured image</h3>

                        <div x-show="!imageUrl" x-cloak
                             x-on:click="$refs.imageInput.click()"
                             x-on:dragover.prevent="dragging = true" x-on:dragleave.prevent="dragging = false"
                             x-on:drop.prevent="dragging = false; handleImageFile($event.dataTransfer.files[0])"
                             x-bind:class="dragging ? 'border-primary bg-primary-light/30' : 'border-line-strong'"
                             class="mt-3 flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed py-10 text-center transition">
                            <svg class="size-8 text-ink-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4m0 0 4 4m-4-4L8 8M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>
                            <p class="mt-2 text-sm font-semibold text-ink">Drag &amp; drop, or click to browse</p>
                            <p class="mt-1 text-xs text-ink-muted">JPG, PNG or WebP, up to 2MB</p>
                        </div>

                        <div x-show="imageUrl" x-cloak class="mt-3">
                            <div class="relative overflow-hidden rounded-xl border border-line">
                                <img x-bind:src="imageUrl" class="aspect-video w-full object-cover" alt="">
                                <button type="button" x-on:click="removeImage()"
                                        class="absolute top-2 right-2 flex size-8 items-center justify-center rounded-full bg-ink/60 text-white transition hover:bg-danger">
                                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-ink-muted" x-text="imageMeta"></p>

                            <label for="featured_image_alt" class="mt-3 block text-xs font-semibold text-ink">Alt text</label>
                            <input id="featured_image_alt" name="featured_image_alt" type="text" x-model="imageAlt"
                                   placeholder="Describe the image for accessibility" class="{{ $input }} mt-1">
                        </div>

                        <input x-ref="imageInput" type="file" name="featured_image" accept="image/jpeg,image/png,image/webp" class="hidden"
                               x-on:change="handleImageFile($event.target.files[0])">
                        <input type="hidden" name="remove_featured_image" x-bind:value="removeExisting ? 1 : 0">
                    </div>

                    {{-- FAQ builder --}}
                    <div class="rounded-2xl border border-line bg-surface p-6 shadow-sm">
                        <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Frequently asked questions</h3>
                        <p class="mt-0.5 text-xs text-ink-muted">FAQs appear at the bottom of the post and generate Google rich-snippet schema.</p>

                        <div class="mt-4 space-y-3">
                            <template x-for="(faq, i) in faqs" :key="i">
                                <div class="rounded-xl border border-line bg-surface-section p-4">
                                    <label class="text-xs font-semibold text-ink-muted">Question</label>
                                    <input type="text" x-model="faq.question" placeholder="Why do cats purr?" class="{{ $input }} mt-1">
                                    <label class="mt-2.5 block text-xs font-semibold text-ink-muted">Answer</label>
                                    <textarea x-model="faq.answer" rows="2" placeholder="Cats purr when content, and sometimes when stressed…" class="{{ $input }} mt-1"></textarea>
                                    <div class="mt-2 flex justify-end gap-1">
                                        <button type="button" x-on:click="moveFaq(i, -1)" x-bind:disabled="i === 0" class="flex size-7 items-center justify-center rounded-lg text-ink-muted transition hover:bg-surface hover:text-ink disabled:opacity-30">
                                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m18 15-6-6-6 6"/></svg>
                                        </button>
                                        <button type="button" x-on:click="moveFaq(i, 1)" x-bind:disabled="i === faqs.length - 1" class="flex size-7 items-center justify-center rounded-lg text-ink-muted transition hover:bg-surface hover:text-ink disabled:opacity-30">
                                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                                        </button>
                                        <button type="button" x-on:click="faqs.splice(i, 1)" x-bind:disabled="faqs.length === 1" class="flex size-7 items-center justify-center rounded-lg text-danger transition hover:bg-danger-light disabled:opacity-30">
                                            <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7m2 0v13a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 7 20V7h10Z"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button type="button" x-on:click="faqs.length < 10 && faqs.push({ question: '', answer: '' })" x-bind:disabled="faqs.length >= 10"
                                class="mt-3 flex items-center gap-1.5 text-sm font-semibold text-primary transition hover:text-primary-hover disabled:cursor-not-allowed disabled:opacity-50">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                            Add FAQ
                        </button>
                    </div>
                </div>

                {{-- ── Right sidebar ────────────────────────────────────── --}}
                <div class="space-y-6">

                    {{-- Publish panel --}}
                    <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Publish</h3>
                        <div class="mt-3 space-y-2">
                            @foreach (['draft' => ['Draft', 'gray'], 'scheduled' => ['Scheduled', 'amber'], 'published' => ['Published', 'green']] as $value => [$statusLabel, $tone])
                                <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-line-strong px-3.5 py-2.5 text-sm font-semibold text-ink transition has-checked:border-primary has-checked:bg-primary-light/40">
                                    <input type="radio" name="status_radio" value="{{ $value }}" x-model="status" class="size-4 text-primary focus:ring-primary/30">
                                    {{ $statusLabel }}
                                </label>
                            @endforeach
                        </div>

                        <div x-show="status === 'scheduled'" x-cloak class="mt-3">
                            <label class="text-xs font-semibold text-ink-muted">Publish date &amp; time</label>
                            <input type="datetime-local" x-model="publishedAt" class="{{ $input }} mt-1">
                        </div>
                        <div x-show="status === 'published'" x-cloak class="mt-3">
                            <label class="text-xs font-semibold text-ink-muted">Published at</label>
                            <input type="datetime-local" x-model="publishedAt" class="{{ $input }} mt-1">
                        </div>

                        <div class="mt-4 flex gap-2">
                            <button type="submit" x-on:click="status = 'draft'"
                                    class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">
                                Save Draft
                            </button>
                            <button type="submit" x-on:click="if (status === 'draft') status = 'published'"
                                    class="flex-1 rounded-full bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">
                                {{ $editing ? 'Save changes' : 'Publish' }} →
                            </button>
                        </div>
                    </div>

                    {{-- Visibility --}}
                    <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <label class="flex cursor-pointer items-center justify-between">
                            <span class="text-sm font-semibold text-ink">Featured post</span>
                            <button type="button" x-on:click="toggleFeatured()"
                                    class="flex size-9 items-center justify-center rounded-full transition"
                                    x-bind:class="isFeatured ? 'bg-primary-light text-primary' : 'bg-surface-soft text-ink-muted'">
                                <svg class="size-5" x-bind:class="spinStar && 'star-spin'" viewBox="0 0 24 24" x-bind:fill="isFeatured ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m12 2 2.9 6.6 7.1.7-5.4 4.7 1.6 7-6.2-3.7-6.2 3.7 1.6-7-5.4-4.7 7.1-.7Z"/>
                                </svg>
                            </button>
                        </label>
                        <p class="mt-1 text-xs text-ink-muted">Shows this post in the homepage's featured slot.</p>
                    </div>

                    {{-- Category --}}
                    <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Category</h3>
                        <select name="category_id" x-model="categoryId" class="{{ $input }} mt-3">
                            <option value="">No category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" x-on:click="categoryModalOpen = true" class="mt-2 text-xs font-semibold text-primary hover:text-primary-hover">+ Add new category</button>
                    </div>

                    {{-- Tags --}}
                    <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Tags</h3>
                        <div class="mt-3 flex flex-wrap gap-1.5" x-show="tags.length">
                            <template x-for="(tag, i) in tags" :key="i">
                                <span class="tag-pop flex items-center gap-1 rounded-full bg-info-light px-2.5 py-1 text-xs font-semibold text-info">
                                    <span x-text="tag"></span>
                                    <button type="button" x-on:click="removeTag(i)" class="text-info/70 hover:text-info">
                                        <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                                    </button>
                                </span>
                            </template>
                        </div>
                        <div class="relative mt-2.5">
                            <input type="text" x-model="tagInput" x-on:input.debounce.200ms="searchTags()"
                                   x-on:keydown.enter.prevent="addTag(tagInput)" x-on:keydown.comma.prevent="addTag(tagInput)"
                                   placeholder="Type to add a tag…" class="{{ $input }}">
                            <div x-show="tagSuggestions.length" x-cloak class="absolute inset-x-0 top-full z-10 mt-1 rounded-xl border border-line bg-surface p-1 shadow-lg">
                                <template x-for="s in tagSuggestions" :key="s.id">
                                    <button type="button" x-on:click="addTag(s.name)" class="block w-full rounded-lg px-2.5 py-1.5 text-left text-sm text-ink hover:bg-surface-section" x-text="s.name"></button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Author --}}
                    <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Author</h3>
                        <select name="author_id" x-model="authorId" class="{{ $input }} mt-3">
                            <option value="">Unassigned</option>
                            @foreach ($authors as $a)
                                <option value="{{ $a->id }}">{{ $a->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" x-on:click="authorModalOpen = true" class="mt-2 text-xs font-semibold text-primary hover:text-primary-hover">+ Add new author</button>
                    </div>

                    {{-- SEO panel --}}
                    <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm" x-data="{ open: true }">
                        <button type="button" x-on:click="open = !open" class="flex w-full items-center justify-between">
                            <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">SEO settings</h3>
                            <svg class="size-4 text-ink-muted transition" x-bind:class="open && 'rotate-180'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </button>

                        <div x-show="open" x-cloak class="mt-3 space-y-4">
                            <div>
                                <label for="meta_title" class="text-xs font-semibold text-ink-muted">Meta title</label>
                                <input id="meta_title" name="meta_title" type="text" x-model="metaTitle" maxlength="60" class="{{ $input }} mt-1">
                                <div class="mt-1 h-1 w-full overflow-hidden rounded-full bg-surface-soft">
                                    <div class="h-full transition-all" x-bind:class="metaTitle.length > 60 ? 'bg-danger' : metaTitle.length >= 50 ? 'bg-warning' : 'bg-accent'" x-bind:style="`width:${Math.min(metaTitle.length / 60 * 100, 100)}%`"></div>
                                </div>
                                <p class="mt-1 text-right text-xs text-ink-muted"><span x-text="metaTitle.length"></span>/60</p>
                            </div>

                            <div>
                                <label for="meta_description" class="text-xs font-semibold text-ink-muted">Meta description</label>
                                <textarea id="meta_description" name="meta_description" rows="3" x-model="metaDescription" maxlength="160" class="{{ $input }} mt-1"></textarea>
                                <div class="mt-1 h-1 w-full overflow-hidden rounded-full bg-surface-soft">
                                    <div class="h-full transition-all" x-bind:class="metaDescription.length > 160 ? 'bg-danger' : metaDescription.length >= 130 ? 'bg-warning' : 'bg-accent'" x-bind:style="`width:${Math.min(metaDescription.length / 160 * 100, 100)}%`"></div>
                                </div>
                                <p class="mt-1 text-right text-xs text-ink-muted"><span x-text="metaDescription.length"></span>/160</p>
                            </div>

                            <div class="rounded-xl border border-line bg-surface-section p-3">
                                <p class="truncate text-xs text-success">purrquery.com › blog › <span x-text="slug || 'your-post'"></span></p>
                                <p class="mt-0.5 truncate text-sm font-medium text-info" x-text="(metaTitle || title || 'Your post title') + ' | PurrQuery'"></p>
                                <p class="mt-0.5 line-clamp-2 text-xs text-ink-muted" x-text="metaDescription || excerpt || 'Add a meta description or excerpt to see a preview here.'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Reading time --}}
                    <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <h3 class="font-heading text-sm font-bold tracking-wider text-ink uppercase">Reading time</h3>
                        <p class="mt-2 flex items-center gap-1.5 text-sm text-ink">
                            <svg class="size-4 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 8v4l3 3M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/></svg>
                            Estimated: <span class="font-semibold" x-text="Math.max(1, Math.ceil(wordCount / 200))"></span> min read
                        </p>
                        <p class="mt-1 text-sm text-ink-muted">Word count: <span x-text="wordCount.toLocaleString()"></span> words</p>
                    </div>
                </div>
            </div>
        </form>

        {{-- Add category modal --}}
        <div x-cloak x-show="categoryModalOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm" x-on:keydown.escape.window="categoryModalOpen = false">
            <div x-show="categoryModalOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="categoryModalOpen = false" class="w-full max-w-sm rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading text-lg font-extrabold text-ink">Add category</h3>
                    <button type="button" x-on:click="categoryModalOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>
                <label class="mt-4 block text-sm font-semibold text-ink">Name</label>
                <input type="text" x-model="newCategoryName" class="{{ $input }} mt-1">
                <p class="mt-3 text-xs font-semibold text-ink-muted uppercase">Color</p>
                <div class="mt-1.5 flex gap-2">
                    <template x-for="c in ['#F47C6B','#5DA0E4','#A9C3A0','#EF9F27','#EA7B7A','#4F6C49']" :key="c">
                        <button type="button" x-on:click="newCategoryColor = c" class="dot-color size-7 rounded-full ring-2 ring-offset-2" x-bind:class="newCategoryColor === c ? 'ring-primary' : 'ring-transparent'" x-bind:style="`--dot-color:${c}`"></button>
                    </template>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="categoryModalOpen = false" class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">Cancel</button>
                    <button type="button" x-on:click="saveCategory()" class="flex-1 rounded-full bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">Save Category</button>
                </div>
            </div>
        </div>

        {{-- Add author modal --}}
        <div x-cloak x-show="authorModalOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-sm" x-on:keydown.escape.window="authorModalOpen = false">
            <div x-show="authorModalOpen" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-5" x-transition:enter-end="opacity-100 translate-y-0"
                 x-on:click.outside="authorModalOpen = false" class="w-full max-w-sm rounded-2xl bg-surface p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading text-lg font-extrabold text-ink">Add author</h3>
                    <button type="button" x-on:click="authorModalOpen = false" class="flex size-7 items-center justify-center rounded-full text-ink-muted transition hover:bg-surface-section hover:text-ink">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                    </button>
                </div>
                <label class="mt-4 block text-sm font-semibold text-ink">Full name</label>
                <input type="text" x-model="newAuthorName" class="{{ $input }} mt-1">
                <label class="mt-3 block text-sm font-semibold text-ink">Credentials</label>
                <input type="text" x-model="newAuthorCredentials" placeholder="e.g. DVM, Cornell University" class="{{ $input }} mt-1">
                <div class="mt-6 flex gap-3">
                    <button type="button" x-on:click="authorModalOpen = false" class="flex-1 rounded-full border border-line-strong bg-surface px-4 py-2.5 text-sm font-semibold text-ink-muted transition hover:border-primary hover:text-primary">Cancel</button>
                    <button type="button" x-on:click="saveAuthor()" class="flex-1 rounded-full bg-primary-vivid px-4 py-2.5 text-sm font-bold text-ink shadow-sm transition hover:brightness-95">Save Author</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
function postForm(initial) {
    // Kept outside the reactive data object on purpose: Alpine wraps every
    // data property in a reactive Proxy, and CKEditor's internal event
    // emitter throws when accessed through one ('_events' proxy invariant).
    let editorInstance = null;

    return {
        ...initial,
        slugEditing: false,
        dragging: false,
        removeExisting: false,
        imageMeta: '',
        spinStar: false,
        tagInput: '',
        tagSuggestions: [],
        categoryModalOpen: false,
        authorModalOpen: false,
        newCategoryName: '',
        newCategoryColor: '#F47C6B',
        newAuthorName: '',
        newAuthorCredentials: '',
        _slugTouched: !!initial.slug,

        init() {
            this.$watch('title', () => {
                if (!this._slugTouched) {
                    this.slug = this.title.toLowerCase().trim()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/(^-|-$)/g, '');
                }
            });

            ClassicEditor.create(document.getElementById('editor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', '|', 'undo', 'redo'],
            }).then((editor) => {
                editorInstance = editor;
                editor.setData(this.content || '');
                this.wordCount = this.countWords(editor.getData());
                editor.model.document.on('change:data', () => {
                    this.wordCount = this.countWords(editor.getData());
                });
            });
        },

        countWords(html) {
            const text = html.replace(/<[^>]*>/g, ' ').trim();
            return text ? text.split(/\s+/).length : 0;
        },

        beforeSubmit() {
            // x-bind:value on a hidden input can lag a fast-changing source
            // (typed text, a click that immediately submits) past the point
            // the browser reads form values, so every field that changes
            // right before submission is force-synced here as the final,
            // authoritative write rather than trusted to have already landed.
            if (editorInstance) {
                this.$refs.contentInput.value = editorInstance.getData();
            }
            this.$refs.statusInput.value = this.status;
            this.$refs.slugInput.value = this.slug;
            this.$refs.featuredInput.value = this.isFeatured ? 1 : 0;
            this.$refs.publishedAtInput.value = this.status === 'draft' ? '' : this.publishedAt;

            const questionInputs = this.$root.querySelectorAll('.faq-question-input');
            const answerInputs = this.$root.querySelectorAll('.faq-answer-input');
            this.faqs.forEach((faq, i) => {
                if (questionInputs[i]) questionInputs[i].value = faq.question;
                if (answerInputs[i]) answerInputs[i].value = faq.answer;
            });
        },

        toggleFeatured() {
            this.isFeatured = !this.isFeatured;
            this.spinStar = true;
            setTimeout(() => { this.spinStar = false; }, 300);
        },

        handleImageFile(file) {
            if (!file) return;
            if (file.size > 2 * 1024 * 1024) {
                showToast('error', 'Image too large', 'Max file size is 2MB.');
                return;
            }
            this.removeExisting = false;
            const reader = new FileReader();
            reader.onload = (e) => { this.imageUrl = e.target.result; };
            reader.readAsDataURL(file);
            const kb = Math.round(file.size / 1024);
            this.imageMeta = `${file.name} · ${kb} KB`;

            const dt = new DataTransfer();
            dt.items.add(file);
            this.$refs.imageInput.files = dt.files;
        },

        removeImage() {
            this.imageUrl = null;
            this.imageMeta = '';
            this.removeExisting = true;
            this.$refs.imageInput.value = '';
        },

        addTag(name) {
            name = name.trim().replace(/,$/, '');
            if (!name || this.tags.includes(name)) { this.tagInput = ''; return; }
            this.tags.push(name);
            this.tagInput = '';
            this.tagSuggestions = [];
        },

        removeTag(i) {
            this.tags.splice(i, 1);
        },

        async searchTags() {
            if (!this.tagInput.trim()) { this.tagSuggestions = []; return; }
            try {
                const res = await fetch(`/admin/post-tags-search?q=${encodeURIComponent(this.tagInput.trim())}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                this.tagSuggestions = (await res.json()).filter((t) => !this.tags.includes(t.name));
            } catch (e) { this.tagSuggestions = []; }
        },

        moveFaq(i, dir) {
            const j = i + dir;
            if (j < 0 || j >= this.faqs.length) return;
            [this.faqs[i], this.faqs[j]] = [this.faqs[j], this.faqs[i]];
        },

        async saveCategory() {
            if (!this.newCategoryName.trim()) return;
            try {
                const res = await fetch('{{ route("admin.post-categories.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ name: this.newCategoryName, color: this.newCategoryColor }),
                });
                if (!res.ok) throw new Error();
                const category = await res.json();
                const select = document.querySelector('select[name="category_id"]');
                const opt = document.createElement('option');
                opt.value = category.id; opt.textContent = category.name;
                select.appendChild(opt);
                this.categoryId = String(category.id);
                this.categoryModalOpen = false;
                this.newCategoryName = '';
                showToast('success', 'Category added', category.name);
            } catch (e) {
                showToast('error', 'Could not add category', 'That name might already be taken.');
            }
        },

        async saveAuthor() {
            if (!this.newAuthorName.trim()) return;
            try {
                const res = await fetch('{{ route("admin.authors.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ name: this.newAuthorName, credentials: this.newAuthorCredentials }),
                });
                if (!res.ok) throw new Error();
                const author = await res.json();
                const select = document.querySelector('select[name="author_id"]');
                const opt = document.createElement('option');
                opt.value = author.id; opt.textContent = author.name;
                select.appendChild(opt);
                this.authorId = String(author.id);
                this.authorModalOpen = false;
                this.newAuthorName = ''; this.newAuthorCredentials = '';
                showToast('success', 'Author added', author.name);
            } catch (e) {
                showToast('error', 'Could not add author', 'Please try again.');
            }
        },
    };
}
</script>
@endpush

</x-admin.shell>

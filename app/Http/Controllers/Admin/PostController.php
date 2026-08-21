<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::query()
            ->with(['author', 'category'])
            ->search($request->string('q')->toString() ?: null)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->integer('category')))
            ->when($request->filled('author'), fn ($q) => $q->where('author_id', $request->integer('author')))
            ->orderBy('created_at', $request->string('sort', 'newest') === 'oldest' ? 'asc' : 'desc')
            ->paginate(12)
            ->withQueryString();

        $counts = [
            'total' => Post::count(),
            'published' => Post::published()->count(),
            'scheduled' => Post::scheduled()->count(),
            'draft' => Post::draft()->count(),
        ];

        $view = $request->ajax() ? 'admin.posts.partials.grid' : 'admin.posts.index';

        return view($view, [
            'posts' => $posts,
            'counts' => $counts,
            'categories' => PostCategory::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            // Explicit keys, not $request->only() — an empty PHP array
            // encodes to a JSON array, not an object, and initialFilters.sort
            // on a JS array silently resolves to Array.prototype.sort.
            'filters' => [
                'q' => $request->string('q')->toString(),
                'status' => $request->string('status')->toString(),
                'category' => $request->string('category')->toString(),
                'author' => $request->string('author')->toString(),
                'sort' => $request->string('sort')->toString(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.form', [
            'post' => new Post,
            'categories' => PostCategory::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $post = DB::transaction(function () use ($request, $data) {
            $post = Post::create($data);
            $this->syncTags($request, $post);
            $this->syncFaqs($request, $post);

            return $post;
        });

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Post created.');
    }

    public function edit(Post $post): View
    {
        $post->load(['tags', 'faqs' => fn ($q) => $q->ordered()]);

        return view('admin.posts.form', [
            'post' => $post,
            'categories' => PostCategory::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request, $post);

        DB::transaction(function () use ($request, $post, $data) {
            $post->update($data);
            $this->syncTags($request, $post);
            $this->syncFaqs($request, $post);
        });

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Post deleted.');
    }

    public function toggleFeatured(Post $post): JsonResponse
    {
        $post->update(['is_featured' => ! $post->is_featured]);

        return response()->json(['is_featured' => $post->is_featured]);
    }

    public function updateStatus(Request $request, Post $post): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['draft', 'scheduled', 'published'])],
        ]);

        if ($data['status'] === 'published' && ! $post->published_at) {
            $data['published_at'] = now();
        }

        $post->update($data);

        return response()->json([
            'status' => $post->status,
            'badge' => $post->status_badge,
        ]);
    }

    public function duplicate(Request $request, Post $post): JsonResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $copy = $post->replicate(['views_count']);
        $copy->title = $data['title'] ?: 'Copy of '.$post->title;
        $copy->slug = Str::slug($copy->title).'-'.Str::random(4);
        $copy->status = 'draft';
        $copy->published_at = null;
        $copy->views_count = 0;
        $copy->save();

        $copy->tags()->sync($post->tags->pluck('id'));

        foreach ($post->faqs as $faq) {
            $copy->faqs()->create([
                'question' => $faq->question,
                'answer' => $faq->answer,
                'sort_order' => $faq->sort_order,
            ]);
        }

        return response()->json(['redirect' => route('admin.posts.edit', $copy)]);
    }

    private function syncTags(Request $request, Post $post): void
    {
        $names = collect($request->input('tags', []))
            ->map(fn ($name) => trim((string) $name))
            ->filter()
            ->unique();

        $ids = $names->map(function (string $name) {
            $tag = PostTag::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)]);

            return $tag->id;
        });

        $post->tags()->sync($ids);
        PostTag::query()->update(['posts_count' => DB::raw('(select count(*) from post_tag where post_tag.tag_id = post_tags.id)')]);
    }

    private function syncFaqs(Request $request, Post $post): void
    {
        $post->faqs()->delete();

        collect($request->input('faqs', []))
            ->filter(fn ($faq) => filled($faq['question'] ?? null) && filled($faq['answer'] ?? null))
            ->values()
            ->take(10)
            ->each(function (array $faq, int $i) use ($post) {
                $post->faqs()->create([
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'sort_order' => $i,
                ]);
            });
    }

    private function validated(Request $request, ?Post $post = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($post?->id)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'remove_featured_image' => ['nullable', 'boolean'],
            'featured_image_alt' => ['nullable', 'string', 'max:255'],
            'author_id' => ['nullable', 'exists:authors,id'],
            'category_id' => ['nullable', 'exists:post_categories,id'],
            'status' => ['required', Rule::in(['draft', 'scheduled', 'published'])],
            'is_featured' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            if ($post?->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        } elseif ($request->boolean('remove_featured_image') && $post?->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
            $data['featured_image'] = null;
        }

        unset($data['remove_featured_image']);

        return $data;
    }
}

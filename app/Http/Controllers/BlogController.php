<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use App\Support\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    // An icon per category, so the topic row reads as a set of things
    // rather than a row of identical pills.
    private const ICONS = [
        'Behavior' => ['M12 20.5c-3.6-2.2-7-4.6-7-8.4A3.9 3.9 0 0 1 12 9.6a3.9 3.9 0 0 1 7 2.5c0 3.8-3.4 6.2-7 8.4Z'],
        'Food Safety' => ['M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z', 'm9.4 12.2 1.9 1.9 3.6-3.7'],
        'Feeding' => ['M3.5 12.5h17a8.5 8.5 0 0 1-17 0Z', 'M6 9.2c0-1.6 1.4-2.2 1.4-3.4M10.5 9.2c0-1.6 1.4-2.2 1.4-3.4M15 9.2c0-1.6 1.4-2.2 1.4-3.4'],
        'Health' => ['M8 3v5a4 4 0 0 0 8 0V3', 'M6 3h4M14 3h4', 'M12 12v3a4 4 0 0 0 8 0v-.5', 'M20 12.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z'],
        'Getting Started' => ['M12 21.5a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19Z', 'm8.4 12.2 2.4 2.4 4.8-4.8'],
    ];

    // Real copy per category rather than a templated "{name} guides" title,
    // since a templated one reads as thin to the same algorithm this page
    // exists to satisfy.
    private const CATEGORY_META = [
        'health' => [
            'title' => 'Cat Health Guides: Symptoms, Illness & Care',
            'description' => 'Cat health guides on symptoms, common illnesses and everyday care, researched from published veterinary sources with those sources named.',
        ],
        'behavior' => [
            'title' => 'Cat Behavior Guides: Why Cats Do What They Do',
            'description' => 'Guides explaining common cat behaviors, from kneading to purring, researched from published veterinary and feline-behavior sources.',
        ],
        'feeding' => [
            'title' => 'Cat Feeding Guides: How Much & What to Feed',
            'description' => 'Feeding guides covering how much to feed a cat and what to look for in cat food, researched from published veterinary sources.',
        ],
        'food-safety' => [
            'title' => 'Cat Food Safety Guides: What Is Safe to Eat',
            'description' => 'Guides on which human foods are safe or dangerous for cats, researched from published veterinary sources with those sources named.',
        ],
        'getting-started' => [
            'title' => 'Getting Started With a New Cat',
            'description' => 'Guides for new cat owners covering the essentials of bringing a cat home, researched from published veterinary sources.',
        ],
    ];

    public function index(Request $request): View|RedirectResponse
    {
        // The topic filter used to live behind a query string on this same
        // URL, which gave every category the same title, description and
        // canonical as the page itself — invisible to Google as anything but
        // a duplicate. A stray inbound link or crawl of the old pattern now
        // lands on the real page instead of a dead end.
        if ($request->filled('topic')) {
            $category = PostCategory::whereRaw('LOWER(name) = ?', [mb_strtolower($request->string('topic'))])->first();

            if ($category) {
                return redirect()->route('blog.category', $category->slug, 301);
            }
        }

        return $this->render(
            Post::published()->with('category')->orderByDesc('published_at')->get(),
            title: 'Cat Care Guides: Behavior, Feeding & Health | '.config('app.name'),
            description: 'Cat care guides from '.config('app.name').'. Behavior, feeding, health '
                .'and life stages, researched from published veterinary sources with '
                .'those sources named.',
            path: '/blog',
        );
    }

    public function category(PostCategory $category): View
    {
        $posts = Post::published()
            ->with('category')
            ->where('category_id', $category->id)
            ->orderByDesc('published_at')
            ->get();

        if ($posts->isEmpty()) {
            throw new NotFoundHttpException;
        }

        $meta = self::CATEGORY_META[$category->slug] ?? [
            'title' => $category->name.' Guides',
            'description' => 'Cat care guides on '.mb_strtolower($category->name).' from '.config('app.name').'.',
        ];

        return $this->render(
            $posts,
            title: $meta['title'].' | '.config('app.name'),
            description: $meta['description'],
            path: '/blog/category/'.$category->slug,
            activeCategory: $category->name,
        );
    }

    private function render($posts, string $title, string $description, string $path, ?string $activeCategory = null): View
    {
        $url = rtrim(config('app.url'), '/');

        $featured = $posts->firstWhere('is_featured', true) ?? $posts->first();
        $rest = $featured ? $posts->reject(fn (Post $p): bool => $p->is($featured))->values() : $posts;

        $crumbs = $activeCategory
            ? ['Home' => '/', 'Blog' => '/blog', $activeCategory => null]
            : ['Home' => '/', 'Blog' => null];

        return view('blog.index', [
            'icons' => self::ICONS,
            'featured' => $featured,
            'side' => $rest->take(4),
            'title' => $title,
            'description' => $description,
            'canonical' => $url.$path,
            'posts' => $posts,
            'activeCategory' => $activeCategory,
            // All categories, not just the ones in $posts, so a category
            // page can still link across to its siblings.
            'categories' => $this->categoryNames(Post::published()->with('category')->get()),
            'schema' => Schema::graph([
                [
                    '@type' => 'CollectionPage',
                    '@id' => $url.$path.'#page',
                    'url' => $url.$path,
                    'name' => $title,
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                // Only the articles that exist. Listing the unwritten ones
                // would describe a library that is not there.
                Schema::itemList($path.'#articles', 'Cat care articles',
                    $posts->map(fn (Post $p): array => [
                        'name' => $p->title,
                        'description' => $p->excerpt,
                    ])->all()),
                Schema::breadcrumbs($path, $crumbs),
            ]),
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->with(['category', 'author', 'faqs' => fn ($q) => $q->ordered()])
            ->first();

        if (! $post) {
            throw new NotFoundHttpException;
        }

        $url = rtrim(config('app.url'), '/');
        $path = '/blog/'.$post->slug;
        $description = $post->meta_description ?: $post->excerpt;

        // Neighbouring articles this one points readers to next: other
        // published guides in the same topic, not a hand-picked list, so the
        // section never dangles on a slug that gets renamed or unpublished.
        // Random rather than newest-first, so every post in a category gets
        // a fair share of inbound links over time instead of the same
        // handful of recent posts always winning the slot.
        $posts = Post::published()
            ->with('category')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->inRandomOrder()
            ->take(6)
            ->get();

        // A thin category (or one this post has alone) can't fill six on its
        // own — top up from the rest of the blog so no post is ever left
        // with zero or one related link just because of what category it's in.
        if ($posts->count() < 6) {
            $posts = $posts->concat(
                Post::published()
                    ->with('category')
                    ->whereNotIn('id', $posts->pluck('id')->push($post->id))
                    ->inRandomOrder()
                    ->take(6 - $posts->count())
                    ->get()
            );
        }

        return view('blog.show', [
            'title' => $post->meta_title ?: $post->title,
            'topics' => $this->categoryNames(Post::published()->with('category')->get()),
            'description' => $description,
            'canonical' => $url.$path,
            'post' => $post,
            'posts' => $posts,
            // A share on Facebook or Reddit should carry the post's own
            // photo, not the site's generic default. og:image has to be a
            // full URL, which featured_image_url alone is not.
            'ogImage' => $post->featured_image_url ? $url.$post->featured_image_url : null,
            'ogImageAlt' => $post->featured_image_alt,
            'ogType' => 'article',
            'articlePublishedTime' => $post->published_at?->toAtomString(),
            'articleModifiedTime' => $post->updated_at?->toAtomString(),
            'schema' => Schema::graph([
                [
                    '@type' => 'Article',
                    '@id' => $url.$path.'#article',
                    'headline' => $post->title,
                    'description' => $post->excerpt,
                    'articleSection' => $post->category?->name,
                    'datePublished' => $post->published_at?->toAtomString(),
                    'dateModified' => $post->updated_at?->toAtomString(),
                    'inLanguage' => 'en-US',
                    'isPartOf' => ['@id' => $url.'/#website'],
                    'publisher' => ['@id' => $url.'/#organization'],
                    'mainEntityOfPage' => ['@id' => $url.$path.'#page'],
                ] + (config('author.founder.name') ? [
                    'author' => ['@id' => $url.'/#founder'],
                ] : []),
                [
                    '@type' => 'WebPage',
                    '@id' => $url.$path.'#page',
                    'url' => $url.$path,
                    'name' => $post->meta_title ?: $post->title,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                Schema::faq($path.'#faq', $post->faqs
                    ->map(fn ($faq): array => ['q' => $faq->question, 'a' => $faq->answer])->all()),
                Schema::breadcrumbs($path, [
                    'Home' => '/',
                    'Blog' => '/blog',
                    $post->title => null,
                ]),
            ]),
        ]);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Post>  $posts
     * @return \Illuminate\Support\Collection<int, array{name: string, slug: string}>
     */
    private function categoryNames($posts)
    {
        return $posts->pluck('category')->filter()->unique('id')
            ->map(fn (PostCategory $c): array => ['name' => $c->name, 'slug' => $c->slug])
            ->values();
    }
}

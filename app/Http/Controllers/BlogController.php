<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\Schema;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    public function index(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');

        $posts = Post::published()
            ->with('category')
            ->orderByDesc('published_at')
            ->get();

        $featured = $posts->firstWhere('is_featured', true) ?? $posts->first();
        $rest = $featured ? $posts->reject(fn (Post $p): bool => $p->is($featured))->values() : $posts;

        $description = 'Cat care guides from '.$name.'. Behavior, feeding, health '
            .'and life stages, researched from published veterinary sources with '
            .'those sources named.';

        // An icon per category, so the topic row reads as a set of things
        // rather than a row of identical pills.
        $icons = [
            'Behavior' => ['M12 20.5c-3.6-2.2-7-4.6-7-8.4A3.9 3.9 0 0 1 12 9.6a3.9 3.9 0 0 1 7 2.5c0 3.8-3.4 6.2-7 8.4Z'],
            'Food Safety' => ['M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z', 'm9.4 12.2 1.9 1.9 3.6-3.7'],
            'Feeding' => ['M3.5 12.5h17a8.5 8.5 0 0 1-17 0Z', 'M6 9.2c0-1.6 1.4-2.2 1.4-3.4M10.5 9.2c0-1.6 1.4-2.2 1.4-3.4M15 9.2c0-1.6 1.4-2.2 1.4-3.4'],
            'Health' => ['M8 3v5a4 4 0 0 0 8 0V3', 'M6 3h4M14 3h4', 'M12 12v3a4 4 0 0 0 8 0v-.5', 'M20 12.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z'],
            'Getting Started' => ['M12 21.5a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19Z', 'm8.4 12.2 2.4 2.4 4.8-4.8'],
        ];

        return view('blog.index', [
            'icons' => $icons,
            'featured' => $featured,
            'side' => $rest->take(4),
            'title' => 'Cat Care Guides: Behavior, Feeding & Health | '.$name,
            'description' => $description,
            'canonical' => $url.'/blog',
            'posts' => $posts,
            'categories' => $this->categoryNames($posts),
            'schema' => Schema::graph([
                [
                    '@type' => 'CollectionPage',
                    '@id' => $url.'/blog#page',
                    'url' => $url.'/blog',
                    'name' => 'Cat Care Blog',
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                // Only the articles that exist. Listing the unwritten ones
                // would describe a library that is not there.
                Schema::itemList('/blog#articles', 'Cat care articles',
                    $posts->map(fn (Post $p): array => [
                        'name' => $p->title,
                        'description' => $p->excerpt,
                    ])->all()),
                Schema::breadcrumbs('/blog', ['Home' => '/', 'Blog' => null]),
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
        $posts = Post::published()
            ->with('category')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

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

    /** @param  \Illuminate\Support\Collection<int, Post>  $posts */
    private function categoryNames($posts)
    {
        return $posts->pluck('category.name')->filter()->unique()->values();
    }
}

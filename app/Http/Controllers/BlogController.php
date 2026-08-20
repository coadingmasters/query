<?php

namespace App\Http\Controllers;

use App\Support\Schema;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    public function index(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');

        // Written ones first. A grid that opens with four "coming soon" cards
        // tells a reader the blog is empty before they have read a word.
        $posts = collect(config('catalog.posts'))
            ->sortByDesc(fn (array $p): int => isset($p['url']) ? 1 : 0)
            ->values();

        $live = $posts->filter(fn (array $p): bool => isset($p['url']));
        $featuredSlug = $live->first()['slug'] ?? $posts->first()['slug'] ?? null;

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
            'side' => $posts->reject(fn (array $p): bool => $featuredSlug === $p['slug'])->take(4),
            'latest' => $posts->reject(fn (array $p): bool => $featuredSlug === $p['slug'])->take(4),
            'title' => 'Cat Care Blog | Guides From '.$name,
            'description' => $description,
            'canonical' => $url.'/blog',
            'posts' => $posts,
            'live' => $live,
            'categories' => $posts->pluck('category')->unique()->values(),
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
                Schema::itemList($url.'/blog#articles', 'Cat care articles',
                    $live->map(fn (array $p): array => [
                        'name' => $p['title'],
                        'description' => $p['excerpt'],
                    ])->all()),
                Schema::breadcrumbs('/blog', ['Home' => '/', 'Blog' => null]),
            ]),
        ]);
    }

    public function show(string $slug): View
    {
        $post = config("blog.$slug");

        if (! $post || ! view()->exists("blog.posts.$slug")) {
            throw new NotFoundHttpException;
        }

        $url = rtrim(config('app.url'), '/');
        $path = '/blog/'.$post['slug'];

        // The tools and neighbouring articles this one points at, resolved
        // here so the view is not digging through two catalogues.
        $tools = collect(config('catalog.tools'))
            ->whereIn('slug', $post['related_tools'])
            ->values();

        $posts = collect(config('catalog.posts'))
            ->whereIn('slug', $post['related_posts'])
            ->values();

        return view('blog.show', [
            'title' => $post['meta_title'],
            'topics' => collect(config('catalog.posts'))->pluck('category')->unique()->values(),
            'description' => $post['excerpt'],
            'canonical' => $url.$path,
            'post' => $post,
            'tools' => $tools,
            'posts' => $posts,
            'schema' => Schema::graph([
                [
                    '@type' => 'Article',
                    '@id' => $url.$path.'#article',
                    'headline' => $post['title'],
                    'description' => $post['excerpt'],
                    'articleSection' => $post['category'],
                    'datePublished' => $post['published'],
                    'dateModified' => $post['updated'],
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
                    'name' => $post['meta_title'],
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                Schema::faq($url.$path.'#faq', collect($post['faq'])
                    ->map(fn (array $i): array => ['q' => $i['q'], 'a' => $i['a']])->all()),
                Schema::breadcrumbs($path, [
                    'Home' => '/',
                    'Blog' => '/#blog',
                    $post['title'] => null,
                ]),
            ]),
        ]);
    }
}

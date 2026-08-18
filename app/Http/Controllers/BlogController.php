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

        $description = 'Cat care guides from '.$name.'. Behavior, feeding, health '
            .'and life stages, researched from published veterinary sources with '
            .'those sources named.';

        return view('blog.index', [
            'title' => 'Cat Care Blog | '.$name,
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

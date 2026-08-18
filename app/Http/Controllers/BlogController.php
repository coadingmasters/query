<?php

namespace App\Http\Controllers;

use App\Support\Schema;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
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

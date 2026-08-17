<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');

        // Counted from the catalogue rather than typed in, so the page cannot
        // drift out of step with the site as tools and guides are added.
        $tools = count(config('catalog.tools'));
        $foods = count(config('catalog.foods'));
        $posts = count(config('catalog.posts'));

        $title = 'About '.$name.' — Trusted Cat Care Tools & Guides';
        $description = 'PurrQuery is a free hub of cat care tools and '
            .'research-backed food-safety guides — built for cat owners who want '
            .'clear, honest answers without a sign-up.';

        return view('about', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.'/about',
            'stats' => [
                [$tools, 'Free cat care tools'],
                [$foods, 'Food safety categories'],
                [$foods + $posts, 'Guides and articles'],
                ['$0', 'Cost to use, always'],
            ],
            'schema' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'AboutPage',
                        '@id' => $url.'/about#page',
                        'url' => $url.'/about',
                        'name' => $title,
                        'description' => $description,
                        'isPartOf' => ['@id' => $url.'/#website'],
                        'about' => ['@id' => $url.'/#organization'],
                    ],
                    [
                        '@type' => 'Organization',
                        '@id' => $url.'/#organization',
                        'name' => $name,
                        'url' => $url,
                        'email' => config('brand.email'),
                        'description' => $description,
                        'logo' => $url.'/og-image.png',
                        'founder' => ['@id' => $url.'/about#founder'],
                    ],
                    [
                        '@type' => 'Person',
                        '@id' => $url.'/about#founder',
                        'name' => 'Ahsan Nawaz',
                        'jobTitle' => 'Founder and developer',
                        'worksFor' => ['@id' => $url.'/#organization'],
                    ],
                    // Breadcrumbs give Google the path to show under the result
                    // instead of a bare URL.
                    [
                        '@type' => 'BreadcrumbList',
                        '@id' => $url.'/about#breadcrumb',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $url.'/'],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'About'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}

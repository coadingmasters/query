<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ComingSoonController extends Controller
{
    /**
     * The pre-launch holding page.
     *
     * It answers with a normal 200 rather than a 503 so search engines can
     * index the domain and start associating it with the brand before the
     * full site ships.
     */
    public function __invoke(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');
        $title = $name.' | '.config('brand.tagline').', launching soon';
        $description = config('brand.description');

        return view('coming-soon', [
            'title' => $title,
            'description' => $description,
            // Trailing slash so the canonical tag, og:url and the sitemap all
            // name the home page identically. Mismatched forms make search
            // engines pick their own canonical instead of honoring ours.
            'canonical' => $url.'/',
            'url' => $url,
            'email' => config('brand.email'),
            'rotates' => config('brand.rotates'),
            'schema' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Organization',
                        '@id' => $url.'/#organization',
                        'name' => $name,
                        'url' => $url,
                        'email' => config('brand.email'),
                        'description' => $description,
                        'logo' => $url.'/og-image.png',
                    ],
                    [
                        '@type' => 'WebSite',
                        '@id' => $url.'/#website',
                        'name' => $name,
                        'url' => $url,
                        'description' => $description,
                        'inLanguage' => str_replace('_', '-', app()->getLocale()),
                        'publisher' => ['@id' => $url.'/#organization'],
                    ],
                ],
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * The XML sitemap.
     *
     * Generated rather than kept as a static file so the URLs always follow
     * APP_URL and new pages can be added here as the site grows.
     */
    public function __invoke(): Response
    {
        // lastmod tells a crawler whether a re-fetch is worth it. It is taken
        // from each page's own view file rather than "now", because claiming a
        // page changed today when it did not trains crawlers to ignore it.
        $base = rtrim(config('app.url'), '/');

        $pages = [
            ['', 'views/home.blade.php', '1.0'],
            ['/about', 'views/about.blade.php', '0.7'],
            ['/contact', 'views/contact.blade.php', '0.5'],
            ['/terms', 'views/terms.blade.php', '0.3'],
            ['/privacy', 'views/privacy.blade.php', '0.3'],
        ];

        $urls = collect($pages)->map(function (array $page) use ($base): array {
            [$path, $view, $priority] = $page;
            $file = resource_path($view);

            return [
                'loc' => $base.($path ?: '/'),
                'lastmod' => date('Y-m-d', is_file($file) ? filemtime($file) : time()),
                'priority' => $priority,
            ];
        })->all();

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}

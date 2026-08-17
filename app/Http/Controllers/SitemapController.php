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
        // from the deployed view file rather than "now", because claiming a
        // page changed today when it did not trains crawlers to ignore it.
        $home = resource_path('views/home.blade.php');

        $urls = [
            [
                'loc' => rtrim(config('app.url'), '/').'/',
                'lastmod' => date('Y-m-d', is_file($home) ? filemtime($home) : time()),
                'priority' => '1.0',
            ],
        ];

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}

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
        $urls = [
            ['loc' => rtrim(config('app.url'), '/').'/', 'priority' => '1.0'],
        ];

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}

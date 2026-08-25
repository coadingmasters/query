<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Response;

class RobotsController extends Controller
{
    private const DEFAULT = <<<'TXT'
        User-agent: *
        Allow: /
        Disallow: /cdn-cgi/

        Sitemap: {sitemap}
        TXT;

    public function __invoke(): Response
    {
        $body = Setting::current()->robots_txt
            ?: str_replace('{sitemap}', route('sitemap'), self::DEFAULT);

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }
}

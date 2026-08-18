<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ManifestController extends Controller
{
    /**
     * The web app manifest.
     *
     * Served from a route rather than a static file so the name and colours
     * follow config. A static copy went stale once already, when the brand
     * spelling changed.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'name' => config('app.name'),
            'short_name' => config('app.name'),
            'description' => config('brand.description'),
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#F8F7FF',
            'theme_color' => '#F47C6B',
            'icons' => [
                ['src' => '/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png'],
                ['src' => '/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any'],
            ],
        ], 200, ['Content-Type' => 'application/manifest+json']);
    }
}

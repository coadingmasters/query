<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mirrors the meta robots tag as an HTTP header.
 *
 * For an ordinary HTML page the meta tag is what Google actually reads, and
 * this header is redundant with it — not missing, not broken, just optional.
 * It earns its keep on responses a meta tag cannot reach: a PDF, an image, or
 * any other non-HTML file the site starts serving later. Setting it here once
 * means that day does not depend on remembering to.
 */
class AddRobotsHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $response->headers->has('X-Robots-Tag')) {
            $response->headers->set(
                'X-Robots-Tag',
                'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'
            );
        }

        return $response;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Overrides Laravel's private/no-store default on read-only content routes,
 * so Cloudflare can serve them from the edge. A plain headers->set() is
 * discarded: Symfony recomputes Cache-Control from its own internal
 * directive state on every write, so the semantic setters are what actually
 * stick.
 *
 * Any Set-Cookie the response still carries (a first-visit pq_vid) is
 * stripped by Cloudflare from what it caches — the three write endpoints
 * these pages link to no longer read a page-embedded token, so a shared
 * cached copy never breaks them.
 */
class SetPublicCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethodCacheable() && $response->getStatusCode() === 200) {
            $response->setPublic();
            $response->setMaxAge(0);
            $response->setSharedMaxAge(300);
            $response->setStaleWhileRevalidate(86400);
        }

        return $response;
    }
}

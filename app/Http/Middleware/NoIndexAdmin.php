<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admin pages have no reason to appear in search results, and the global
 * robots header defaults to indexable. Setting it here, before
 * AddRobotsHeader runs, wins because that middleware only fills the header
 * in when a response does not already carry one.
 */
class NoIndexAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Checked before routing gets a chance to 404 — the whole point of a
 * redirect is to catch a URL that no longer resolves to anything (a
 * renamed post, a restructured page), so this has to run ahead of route
 * matching, not after it.
 */
class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('get') || $request->is('admin*') || $request->is('build/*') || $request->is('storage/*')) {
            return $next($request);
        }

        $map = Redirect::activeMap();
        $path = '/'.ltrim($request->path(), '/');

        if (isset($map[$path])) {
            [$to, $status] = $map[$path];

            return redirect($to, $status);
        }

        return $next($request);
    }
}

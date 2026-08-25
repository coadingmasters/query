<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use App\Support\VisitorIdentity;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Records one row per real, public page view — the minimum needed for
 * "most visited page", "most used tool", "top post" and a traffic-source
 * split on the admin analytics page. Deliberately excludes admin routes,
 * non-GET requests, non-HTML responses (assets, the sitemap, feeds), and
 * anything that looks like a bot.
 *
 * Runs as terminable middleware: the write happens in terminate(), after
 * the response has already been sent to the visitor (FPM's
 * fastcgi_finish_request under the hood), so a database insert never adds
 * to anyone's perceived page-load time.
 */
class TrackPageView
{
    private const SEARCH_ENGINES = ['google.', 'bing.', 'yahoo.', 'duckduckgo.', 'baidu.', 'yandex.'];

    private const SOCIAL_NETWORKS = ['facebook.', 'instagram.', 'twitter.', 'x.com', 't.co', 'pinterest.', 'reddit.', 'linkedin.', 'tiktok.'];

    // Chrome's newer --headless=new mode deliberately sends the same UA as a
    // real browser (the old mode's giveaway "HeadlessChrome" string is gone),
    // so QA/dev tooling hitting the live site has to self-identify instead of
    // being caught by a generic signature. "curl" covers plain curl checks.
    private const BOT_SIGNATURES = ['bot', 'spider', 'crawl', 'slurp', 'facebookexternalhit', 'preview', 'headless', 'curl', 'purrquery-qa'];

    public function handle(Request $request, Closure $next): Response
    {
        // The cookie has to be queued here, before the response is sent —
        // terminate() runs after fastcgi_finish_request, too late for headers.
        // Cheap and side-effect-free otherwise, so it's fine to do this even
        // for requests that won't end up counted as a page view below.
        if ($request->isMethod('get') && ! $request->is('admin*', 'build/*', 'storage/*')) {
            $request->attributes->set('visitor_token', app(VisitorIdentity::class)->tokenFor($request));
        }

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if ($this->shouldTrack($request, $response)) {
            $this->record($request);
        }
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('get') || $request->ajax() || $request->wantsJson()) {
            return false;
        }

        if ($request->is('admin*') || $request->is('build/*') || $request->is('storage/*')) {
            return false;
        }

        // A 404 is Laravel's own error view, served as text/html — without
        // this, every scanner probing for /wp-admin/install.php or similar
        // shows up as a real, and rank-worthy, page view.
        if ($response->getStatusCode() !== 200) {
            return false;
        }

        if (! str_starts_with((string) $response->headers->get('Content-Type'), 'text/html')) {
            return false;
        }

        $userAgent = strtolower((string) $request->userAgent());

        foreach (self::BOT_SIGNATURES as $signature) {
            if (str_contains($userAgent, $signature)) {
                return false;
            }
        }

        return true;
    }

    private function record(Request $request): void
    {
        $referrer = $request->headers->get('referer');
        $referrerHost = $referrer ? parse_url($referrer, PHP_URL_HOST) : null;
        $ownHost = $request->getHost();

        $token = $request->attributes->get('visitor_token');
        $visitor = $token ? app(VisitorIdentity::class)->resolve($request, $token) : null;

        PageView::create([
            'visitor_id' => $visitor?->id,
            'path' => '/'.ltrim($request->path(), '/'),
            'source' => $this->classify($referrerHost, $ownHost),
            'referrer_host' => $referrerHost && $referrerHost !== $ownHost ? $referrerHost : null,
            'created_at' => now(),
        ]);
    }

    private function classify(?string $referrerHost, string $ownHost): string
    {
        if (! $referrerHost || $referrerHost === $ownHost) {
            return 'direct';
        }

        foreach (self::SEARCH_ENGINES as $needle) {
            if (str_contains($referrerHost, $needle)) {
                return 'organic';
            }
        }

        foreach (self::SOCIAL_NETWORKS as $needle) {
            if (str_contains($referrerHost, $needle)) {
                return 'social';
            }
        }

        return 'referral';
    }
}

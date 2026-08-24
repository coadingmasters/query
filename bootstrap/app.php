<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(\App\Http\Middleware\HandleRedirects::class);
        $middleware->append(\App\Http\Middleware\AddRobotsHeader::class);
        $middleware->append(\App\Http\Middleware\TrackPageView::class);
        $middleware->alias(['noindex' => \App\Http\Middleware\NoIndexAdmin::class]);
        $middleware->redirectGuestsTo(fn () => route('admin.login'));

        // A self-generated, non-sensitive UUID — nothing is gained by
        // encrypting it, and an APP_KEY rotation would otherwise silently
        // turn every returning visitor into a "new" one.
        $middleware->encryptCookies(except: [\App\Support\VisitorIdentity::COOKIE]);

        // Behind a CDN or load balancer (Cloudflare, nginx, a managed host)
        // the real visitor scheme and host arrive in X-Forwarded-* headers.
        // Without trusting them Laravel sees plain http and generates
        // http:// canonical and sitemap URLs on an https:// site.
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
                | Request::HEADER_X_FORWARDED_AWS_ELB,
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

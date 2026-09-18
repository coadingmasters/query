<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddSecurityHeaders
{
    private const CLARITY = 'https://www.clarity.ms https://*.clarity.ms';

    /** Cloudflare injects its analytics beacon into every response; it is not in the markup. */
    private const CLOUDFLARE = 'https://static.cloudflareinsights.com https://cloudflareinsights.com';

    /** 'unsafe-eval' for Alpine, 'unsafe-inline' for the views' inline blocks, blob: for CKEditor and the PDF tools. */
    private const CSP = [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' ".self::CLARITY.' '.self::CLOUDFLARE,
        "style-src 'self' 'unsafe-inline'",
        "img-src 'self' data: blob: https:",
        "media-src 'self' blob:",
        "frame-src 'self' blob:",
        "worker-src 'self' blob:",
        "font-src 'self'",
        "connect-src 'self' blob: ".self::CLARITY.' '.self::CLOUDFLARE,
        "object-src 'none'",
        "frame-ancestors 'self'",
        "base-uri 'self'",
        "form-action 'self'",
        'upgrade-insecure-requests',
    ];

    private const HEADERS = [
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-Content-Type-Options' => 'nosniff',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=(), interest-cohort=()',
        'Cross-Origin-Opener-Policy' => 'same-origin',
        'X-XSS-Protection' => '0',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return $response;
        }

        $response->headers->add(self::HEADERS);
        $response->headers->set('Content-Security-Policy', implode('; ', self::CSP));

        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains');
        }

        // PHP emits this from the SAPI, so the header bag alone does not drop it.
        $response->headers->remove('X-Powered-By');
        header_remove('X-Powered-By');

        return $response;
    }
}

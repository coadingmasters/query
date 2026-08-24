<?php

namespace App\Support;

use App\Models\IpRange;
use App\Models\Visitor;
use DeviceDetector\DeviceDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

/**
 * Resolves the Visitor behind a request from the pq_vid cookie, creating one
 * (with a one-time geo + device lookup) the first time it's missing. Shared
 * by TrackPageView and the click-logging endpoint so a browser is only ever
 * geolocated and UA-parsed once, not on every request.
 *
 * Split in two because TrackPageView is terminable middleware: its DB write
 * happens in terminate(), after the response has already gone out, so the
 * cookie can't be queued there — it has to be queued during handle(), before
 * headers are sent. tokenFor() does that cheap, cookie-only part up front;
 * resolve() does the actual DB work afterward, keyed on the same token.
 */
class VisitorIdentity
{
    public const COOKIE = 'pq_vid';

    /** Call during handle(), before the response is sent. Reuses the existing token or mints and queues a new one. */
    public function tokenFor(Request $request): string
    {
        $token = $request->cookie(self::COOKIE);

        if ($token) {
            return $token;
        }

        $token = (string) Str::uuid();

        Cookie::queue(Cookie::make(
            self::COOKIE, $token, 60 * 24 * 365 * 2,
            httpOnly: true, sameSite: 'lax'
        ));

        return $token;
    }

    /** Call any time after tokenFor() has run for this request — safe from terminate(). */
    public function resolve(Request $request, string $token): Visitor
    {
        $visitor = Visitor::where('token', $token)->first();

        if ($visitor) {
            $visitor->update([
                'last_seen_at' => now(),
                'visits_count' => $visitor->visits_count + 1,
            ]);

            return $visitor;
        }

        return $this->create($request, $token);
    }

    private function create(Request $request, string $token): Visitor
    {
        $ip = $request->ip();
        $country = IpRange::countryFor($ip);

        $userAgent = (string) $request->userAgent();
        $dd = new DeviceDetector($userAgent);
        $dd->parse();

        return Visitor::create([
            'token' => $token,
            'ip_address' => $ip,
            'country_code' => $country?->country_code,
            'country_name' => $country?->country_name,
            'device_type' => $dd->getDeviceName() ?: 'desktop',
            'browser' => $dd->getClient('name'),
            'browser_version' => $dd->getClient('version'),
            'os' => $dd->getOs('name'),
            'user_agent' => $userAgent,
            'first_seen_at' => now(),
            'last_seen_at' => now(),
            'visits_count' => 1,
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpRange extends Model
{
    public $timestamps = false;

    protected $fillable = ['ip_from', 'ip_to', 'country_code', 'country_name'];

    /**
     * Null for anything that resolves in neither ip_ranges (IPv4) nor
     * ipv6_ranges (IPv6). The single entry point both TrackPageView and the
     * click endpoint call through, so neither has to know which table an
     * address actually lives in.
     */
    public static function countryFor(string $ip): IpRange|Ipv6Range|null
    {
        if (str_contains($ip, ':')) {
            return Ipv6Range::countryFor($ip);
        }

        $long = ip2long($ip);

        if ($long === false) {
            return null;
        }

        $unsigned = sprintf('%u', $long);

        return static::query()
            ->where('ip_from', '<=', $unsigned)
            ->where('ip_to', '>=', $unsigned)
            ->first();
    }
}

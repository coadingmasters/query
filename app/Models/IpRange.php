<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpRange extends Model
{
    public $timestamps = false;

    protected $fillable = ['ip_from', 'ip_to', 'country_code', 'country_name'];

    /** Null for anything that isn't a routable IPv4 address (including every IPv6 visitor — see the ip_ranges migration). */
    public static function countryFor(string $ip): ?self
    {
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

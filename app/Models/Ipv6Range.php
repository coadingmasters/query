<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ipv6Range extends Model
{
    public $timestamps = false;

    protected $fillable = ['ip_from', 'ip_to', 'country_code', 'country_name'];

    /** Null for anything that isn't a valid IPv6 address, or that falls outside every imported range. */
    public static function countryFor(string $ip): ?self
    {
        $binary = @inet_pton($ip);

        if ($binary === false || strlen($binary) !== 16) {
            return null;
        }

        return static::query()
            ->where('ip_from', '<=', $binary)
            ->where('ip_to', '>=', $binary)
            ->first();
    }
}

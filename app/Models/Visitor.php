<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'token', 'ip_address', 'country_code', 'country_name', 'region', 'city',
        'device_type', 'browser', 'browser_version', 'os', 'user_agent',
        'first_seen_at', 'last_seen_at', 'visits_count',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'visits_count' => 'integer',
    ];

    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class);
    }

    public function clickEvents(): HasMany
    {
        return $this->hasMany(ClickEvent::class);
    }

    /**
     * A country's flag emoji is just its two-letter code re-encoded: each
     * letter maps to a "regional indicator" codepoint, and the pair of
     * them is what every OS renders as that country's flag. No image, no
     * lookup table, and it stays correct for any code we might store.
     */
    public function getCountryFlagAttribute(): string
    {
        $code = strtoupper((string) $this->country_code);

        if (! preg_match('/^[A-Z]{2}$/', $code)) {
            return '';
        }

        return mb_chr(0x1F1E6 + ord($code[0]) - 65).mb_chr(0x1F1E6 + ord($code[1]) - 65);
    }
}

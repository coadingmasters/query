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
}

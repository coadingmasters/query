<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    public $timestamps = false;

    protected $fillable = ['path', 'source', 'referrer_host', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subDays(7));
    }

    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }
}

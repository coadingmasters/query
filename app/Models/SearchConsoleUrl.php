<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchConsoleUrl extends Model
{
    protected $fillable = [
        'url', 'coverage_state', 'verdict', 'indexing_state',
        'robots_txt_state', 'last_crawl_time', 'checked_at',
    ];

    protected $casts = [
        'last_crawl_time' => 'datetime',
        'checked_at' => 'datetime',
    ];

    public function isIndexed(): bool
    {
        return $this->coverage_state === 'Submitted and indexed';
    }
}

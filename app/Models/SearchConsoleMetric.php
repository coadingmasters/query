<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchConsoleMetric extends Model
{
    protected $fillable = ['date', 'clicks', 'impressions', 'ctr', 'position'];

    protected $casts = ['date' => 'date'];
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchConsoleMetric;
use App\Models\SearchConsoleUrl;
use Illuminate\Contracts\View\View;

class SearchConsoleController extends Controller
{
    public function index(): View
    {
        $urls = SearchConsoleUrl::orderByDesc('checked_at')->get();

        $counts = [
            'indexed' => $urls->where('coverage_state', 'Submitted and indexed')->count(),
            'not_indexed' => $urls->where('coverage_state', '!=', 'Submitted and indexed')->count(),
            'total' => $urls->count(),
        ];

        $byState = $urls->groupBy('coverage_state')->map->count()->sortDesc();

        $metrics = SearchConsoleMetric::orderBy('date')->get();

        return view('admin.search-console', [
            'urls' => $urls,
            'counts' => $counts,
            'byState' => $byState,
            'metrics' => $metrics,
            'lastChecked' => $urls->max('checked_at'),
        ]);
    }
}

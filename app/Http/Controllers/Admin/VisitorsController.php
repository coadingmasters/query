<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClickEvent;
use App\Models\PageView;
use App\Models\Visitor;
use Illuminate\Contracts\View\View;

class VisitorsController extends Controller
{
    public function index(): View
    {
        $total = Visitor::count();
        $today = Visitor::whereDate('first_seen_at', now()->toDateString())->count();
        $pageViews = PageView::whereNotNull('visitor_id')->count();
        $clicks = ClickEvent::count();

        $deviceCounts = Visitor::query()
            ->selectRaw('device_type, count(*) as total')
            ->groupBy('device_type')
            ->pluck('total', 'device_type');
        $deviceMax = max($deviceCounts->max(), 1);
        $deviceChart = $deviceCounts->map(fn ($count, $type) => [
            'label' => ucfirst($type ?: 'Unknown'),
            'count' => (int) $count,
            'percent' => (int) round($count / $deviceMax * 100),
        ])->values();

        $countryCounts = Visitor::query()
            ->whereNotNull('country_name')
            ->selectRaw('country_name, count(*) as total')
            ->groupBy('country_name')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'country_name');
        $countryMax = max($countryCounts->max(), 1);
        $countryChart = $countryCounts->map(fn ($count, $name) => [
            'label' => $name,
            'count' => (int) $count,
            'percent' => (int) round($count / $countryMax * 100),
        ])->values();

        return view('admin.visitors.index', [
            'visitors' => Visitor::query()
                ->withCount('pageViews', 'clickEvents')
                ->latest('last_seen_at')
                ->paginate(25),
            'counts' => ['total' => $total, 'today' => $today, 'pageViews' => $pageViews, 'clicks' => $clicks],
            'deviceChart' => $deviceChart,
            'countryChart' => $countryChart,
        ]);
    }

    public function show(Visitor $visitor): View
    {
        $timeline = $visitor->pageViews->map(fn ($view) => [
            'type' => 'view',
            'at' => $view->created_at,
            'path' => $view->path,
            'source' => $view->source,
        ])->concat($visitor->clickEvents->map(fn ($click) => [
            'type' => 'click',
            'at' => $click->created_at,
            'path' => $click->path,
            'label' => $click->label,
            'href' => $click->href,
        ]))->sortByDesc('at')->values();

        return view('admin.visitors.show', [
            'visitor' => $visitor,
            'timeline' => $timeline,
        ]);
    }
}

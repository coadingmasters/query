<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use App\Models\Subscriber;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    private const TOOL_LABELS = [
        '/tools/cat-age-calculator' => 'Cat Age Calculator',
        '/tools/cat-calorie-calculator' => 'Cat Calorie Calculator',
        '/tools/cat-vaccination-tracker' => 'Vaccination Tracker',
        '/tools/cat-pregnancy-calculator' => 'Cat Pregnancy Calculator',
    ];

    public function index(): View
    {
        $range = now()->subDays(30);

        $counts = [
            'today' => PageView::today()->count(),
            'week' => PageView::thisWeek()->count(),
            'month' => PageView::thisMonth()->count(),
        ];

        $topPage = PageView::thisMonth()
            ->select('path', DB::raw('count(*) as views'))
            ->groupBy('path')
            ->orderByDesc('views')
            ->first();

        $topTool = PageView::thisMonth()
            ->where('path', 'like', '/tools/%')
            ->select('path', DB::raw('count(*) as views'))
            ->groupBy('path')
            ->orderByDesc('views')
            ->first();

        $topPost = PageView::thisMonth()
            ->where('path', 'like', '/blog/%')
            ->select('path', DB::raw('count(*) as views'))
            ->groupBy('path')
            ->orderByDesc('views')
            ->first();

        $newSubscribersToday = Subscriber::whereDate('created_at', now()->toDateString())->count();

        $sourceCounts = PageView::thisMonth()
            ->select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->pluck('total', 'source');

        $sourceMax = max($sourceCounts->max(), 1);
        $sourceChart = collect(['direct', 'organic', 'social', 'referral'])->map(fn ($source) => [
            'label' => ucfirst($source),
            'count' => (int) ($sourceCounts[$source] ?? 0),
            'percent' => (int) round(($sourceCounts[$source] ?? 0) / $sourceMax * 100),
        ])->values();

        $topPages = PageView::thisMonth()
            ->select('path', DB::raw('count(*) as views'))
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(10)
            ->get()
            ->map(fn ($row) => ['path' => $row->path, 'label' => $this->labelFor($row->path), 'views' => $row->views]);

        $toolUsage = PageView::thisMonth()
            ->where('path', 'like', '/tools/%')
            ->select('path', DB::raw('count(*) as views'))
            ->groupBy('path')
            ->orderByDesc('views')
            ->get()
            ->map(fn ($row) => ['path' => $row->path, 'label' => self::TOOL_LABELS[$row->path] ?? $row->path, 'views' => $row->views]);

        $topPosts = PageView::thisMonth()
            ->where('path', 'like', '/blog/%')
            ->select('path', DB::raw('count(*) as views'))
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(8)
            ->get()
            ->map(fn ($row) => ['path' => $row->path, 'label' => $this->labelFor($row->path), 'views' => $row->views]);

        return view('admin.analytics', [
            'counts' => $counts,
            'topPageLabel' => $topPage ? $this->labelFor($topPage->path) : null,
            'topToolLabel' => $topTool ? (self::TOOL_LABELS[$topTool->path] ?? $topTool->path) : null,
            'topPostLabel' => $topPost ? $this->labelFor($topPost->path) : null,
            'newSubscribersToday' => $newSubscribersToday,
            'sourceChart' => $sourceChart,
            'topPages' => $topPages,
            'toolUsage' => $toolUsage,
            'topPosts' => $topPosts,
            'hasData' => PageView::query()->exists(),
        ]);
    }

    private function labelFor(string $path): string
    {
        if (isset(self::TOOL_LABELS[$path])) {
            return self::TOOL_LABELS[$path];
        }

        if (str_starts_with($path, '/blog/')) {
            $slug = trim(substr($path, strlen('/blog/')), '/');

            return config("blog.{$slug}.title", $slug);
        }

        return match ($path) {
            '/' => 'Home',
            '/blog' => 'Blog index',
            '/about' => 'About',
            '/contact' => 'Contact',
            '/faq' => 'FAQ',
            default => $path,
        };
    }
}

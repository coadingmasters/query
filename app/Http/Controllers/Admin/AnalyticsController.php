<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
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

        $topPages = $this->toChartData(
            PageView::thisMonth()->select('path', DB::raw('count(*) as views'))->groupBy('path')->orderByDesc('views')->limit(10)->get(),
            fn ($row) => $this->labelFor($row->path)
        );

        $toolUsage = $this->toChartData(
            PageView::thisMonth()->where('path', 'like', '/tools/%')->select('path', DB::raw('count(*) as views'))->groupBy('path')->orderByDesc('views')->get(),
            fn ($row) => self::TOOL_LABELS[$row->path] ?? $row->path
        );

        $topPosts = $this->toChartData(
            PageView::thisMonth()->where('path', 'like', '/blog/%')->select('path', DB::raw('count(*) as views'))->groupBy('path')->orderByDesc('views')->limit(8)->get(),
            fn ($row) => $this->labelFor($row->path)
        );

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

    /** Shapes a ranked ["path","views"] result set into x-admin.wave-chart's ["label","count","percent"] format. */
    private function toChartData($rows, callable $label): Collection
    {
        $max = max($rows->max('views'), 1);

        return $rows->map(fn ($row) => [
            'label' => $label($row),
            'count' => $row->views,
            'percent' => (int) round($row->views / $max * 100),
        ])->values();
    }

    private function labelFor(string $path): string
    {
        if (isset(self::TOOL_LABELS[$path])) {
            return self::TOOL_LABELS[$path];
        }

        if (str_starts_with($path, '/blog/')) {
            $slug = trim(substr($path, strlen('/blog/')), '/');

            return Post::where('slug', $slug)->value('title') ?? $slug;
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

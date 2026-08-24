<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleFeedback;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Subscriber;
use App\Models\Visitor;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => $this->stats(),
            'categoryData' => $this->categoryData(),
            'activitySeries' => $this->activitySeries(),
            'feedback' => $this->feedback(),
            'recentMessages' => ContactMessage::latest()->take(5)->get(),
            'visitorTrend' => $this->visitorTrend(),
            'visitorTotals' => $this->visitorTotals(),
            'deviceChart' => $this->deviceChart(),
            'recentVisitors' => Visitor::latest('last_seen_at')->take(5)->get(),
        ]);
    }

    /** @return array<int, array{label: string, value: int, icon: string, tone: string}> */
    private function stats(): array
    {
        return [
            ['label' => 'Blog Posts', 'value' => Post::count(), 'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z', 'tone' => 'primary', 'href' => route('admin.posts.index')],
            ['label' => 'Contact Messages', 'value' => ContactMessage::count(), 'icon' => 'm3 7 8.5 6L20 7M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z', 'tone' => 'accent', 'href' => route('admin.messages.index')],
            ['label' => 'Subscribers', 'value' => Subscriber::count(), 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75M12.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z', 'tone' => 'info', 'href' => route('admin.subscribers.index')],
            ['label' => 'Article Reactions', 'value' => ArticleFeedback::count(), 'icon' => 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z', 'tone' => 'warning', 'href' => route('admin.feedback.index')],
        ];
    }

    /** Real post counts per category, from the posts table — nothing invented. */
    private function categoryData(): array
    {
        $counts = Post::with('category')
            ->get()
            ->groupBy(fn (Post $post) => $post->category?->name ?? 'Uncategorized')
            ->map(fn ($posts) => count($posts))
            ->sortDesc();

        $max = $counts->max() ?: 1;

        return $counts->map(fn ($count, $label) => [
            'label' => $label,
            'count' => $count,
            'percent' => (int) round($count / $max * 100),
        ])->values()->all();
    }

    /**
     * Contact messages and new subscribers per day, for the last 14 days.
     * Almost certainly a flat, mostly-empty line on a brand new site, and
     * that is the honest picture rather than a reason to fake a curve.
     */
    private function activitySeries(): array
    {
        $days = collect(range(13, 0))->map(fn ($i) => Carbon::today()->subDays($i));

        $messagesByDay = ContactMessage::query()
            ->where('created_at', '>=', Carbon::today()->subDays(13))
            ->get()
            ->groupBy(fn ($m) => $m->created_at->toDateString())
            ->map->count();

        $subscribersByDay = Subscriber::query()
            ->where('created_at', '>=', Carbon::today()->subDays(13))
            ->get()
            ->groupBy(fn ($s) => $s->created_at->toDateString())
            ->map->count();

        return $days->map(fn (Carbon $day) => [
            'date' => $day->toDateString(),
            'label' => $day->format('M j'),
            'messages' => $messagesByDay->get($day->toDateString(), 0),
            'subscribers' => $subscribersByDay->get($day->toDateString(), 0),
        ])->values()->all();
    }

    /** New visitors per day, last 7 days — shaped for x-admin.wave-chart. */
    private function visitorTrend(): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));

        $byDay = Visitor::query()
            ->where('first_seen_at', '>=', Carbon::today()->subDays(6))
            ->get()
            ->groupBy(fn (Visitor $v) => $v->first_seen_at->toDateString())
            ->map->count();

        $max = max(1, $byDay->max() ?? 0);

        return $days->map(fn (Carbon $day) => [
            'label' => $day->format('D'),
            'count' => $byDay->get($day->toDateString(), 0),
            'percent' => (int) round($byDay->get($day->toDateString(), 0) / $max * 100),
        ])->values()->all();
    }

    private function visitorTotals(): array
    {
        return [
            'total' => Visitor::count(),
            'today' => Visitor::whereDate('first_seen_at', now()->toDateString())->count(),
            'week' => Visitor::where('first_seen_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /** Desktop/mobile/tablet split across every visitor on file — shaped for x-admin.pie-chart. */
    private function deviceChart(): array
    {
        $counts = Visitor::query()
            ->selectRaw('device_type, count(*) as total')
            ->groupBy('device_type')
            ->pluck('total', 'device_type');

        return $counts->map(fn ($count, $type) => [
            'label' => ucfirst($type ?: 'Unknown'),
            'count' => (int) $count,
        ])->values()->all();
    }

    private function feedback(): array
    {
        $helpful = ArticleFeedback::where('helpful', true)->count();
        $notHelpful = ArticleFeedback::where('helpful', false)->count();
        $total = $helpful + $notHelpful;

        return [
            'helpful' => $helpful,
            'not_helpful' => $notHelpful,
            'total' => $total,
            'helpful_percent' => $total > 0 ? (int) round($helpful / $total * 100) : 0,
        ];
    }
}

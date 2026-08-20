<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleFeedback;
use Illuminate\Contracts\View\View;

class FeedbackController extends Controller
{
    public function index(): View
    {
        $bySlug = ArticleFeedback::query()
            ->selectRaw('slug, sum(case when helpful then 1 else 0 end) as helpful, sum(case when helpful then 0 else 1 end) as not_helpful')
            ->groupBy('slug')
            ->get()
            ->map(function ($row) {
                $total = $row->helpful + $row->not_helpful;

                return [
                    'slug' => $row->slug,
                    'title' => config("blog.{$row->slug}.title", $row->slug),
                    'helpful' => (int) $row->helpful,
                    'not_helpful' => (int) $row->not_helpful,
                    'total' => $total,
                    'helpful_percent' => $total > 0 ? (int) round($row->helpful / $total * 100) : 0,
                ];
            })
            ->sortByDesc('total')
            ->values();

        return view('admin.feedback', [
            'articles' => $bySlug,
            'totalHelpful' => (int) $bySlug->sum('helpful'),
            'totalNotHelpful' => (int) $bySlug->sum('not_helpful'),
        ]);
    }
}

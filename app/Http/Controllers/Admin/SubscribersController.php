<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Contracts\View\View;

class SubscribersController extends Controller
{
    public function index(): View
    {
        $activeCount = Subscriber::whereNull('unsubscribed_at')->count();
        $unsubscribedCount = Subscriber::whereNotNull('unsubscribed_at')->count();
        $total = $activeCount + $unsubscribedCount;

        return view('admin.subscribers', [
            'subscribers' => Subscriber::latest()->paginate(20),
            'activeCount' => $activeCount,
            'unsubscribedCount' => $unsubscribedCount,
            'counts' => ['total' => $total, 'active' => $activeCount, 'unsubscribed' => $unsubscribedCount],
            'statusChart' => [
                ['label' => 'Active', 'count' => $activeCount, 'percent' => $total ? (int) round($activeCount / $total * 100) : 0],
                ['label' => 'Unsubscribed', 'count' => $unsubscribedCount, 'percent' => $total ? (int) round($unsubscribedCount / $total * 100) : 0],
            ],
        ]);
    }
}

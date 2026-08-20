<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Contracts\View\View;

class SubscribersController extends Controller
{
    public function index(): View
    {
        return view('admin.subscribers', [
            'subscribers' => Subscriber::latest()->paginate(20),
            'activeCount' => Subscriber::whereNull('unsubscribed_at')->count(),
            'unsubscribedCount' => Subscriber::whereNotNull('unsubscribed_at')->count(),
        ]);
    }
}

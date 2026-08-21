<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MessagesController extends Controller
{
    public function index(): View
    {
        $messages = ContactMessage::latest()->paginate(15);

        $total = ContactMessage::count();
        $unhandled = ContactMessage::whereNull('handled_at')->count();
        $handled = $total - $unhandled;

        return view('admin.messages', [
            'messages' => $messages,
            'counts' => ['total' => $total, 'new' => $unhandled, 'handled' => $handled],
            'statusChart' => [
                ['label' => 'New', 'count' => $unhandled, 'percent' => $total ? (int) round($unhandled / $total * 100) : 0],
                ['label' => 'Handled', 'count' => $handled, 'percent' => $total ? (int) round($handled / $total * 100) : 0],
            ],
        ]);
    }

    public function markHandled(ContactMessage $message): RedirectResponse
    {
        $message->update(['handled_at' => $message->handled_at ? null : now()]);

        return back();
    }
}

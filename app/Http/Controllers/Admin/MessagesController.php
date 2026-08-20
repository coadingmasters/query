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
        return view('admin.messages', [
            'messages' => ContactMessage::latest()->paginate(15),
        ]);
    }

    public function markHandled(ContactMessage $message): RedirectResponse
    {
        $message->update(['handled_at' => $message->handled_at ? null : now()]);

        return back();
    }
}

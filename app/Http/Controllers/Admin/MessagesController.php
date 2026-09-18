<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReply;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessagesController extends Controller
{
    public function index(Request $request): View
    {
        $messages = ContactMessage::query()
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', '%'.$request->string('q').'%')
                ->orWhere('email', 'like', '%'.$request->string('q').'%')
                ->orWhere('subject', 'like', '%'.$request->string('q').'%')
                ->orWhere('message', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('status'), fn ($q) => $request->string('status')->toString() === 'handled'
                ? $q->whereNotNull('handled_at')
                : $q->whereNull('handled_at'))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->date('to')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $total = ContactMessage::count();
        $unhandled = ContactMessage::whereNull('handled_at')->count();
        $handled = $total - $unhandled;

        $filters = [
            'q' => $request->string('q')->toString(),
            'status' => $request->string('status')->toString(),
            'from' => $request->string('from')->toString(),
            'to' => $request->string('to')->toString(),
        ];

        $view = $request->ajax() ? 'admin.messages-partials.grid' : 'admin.messages';

        return view($view, [
            'messages' => $messages,
            'filters' => $filters,
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

    public function reply(Request $request, ContactMessage $message): JsonResponse
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:5000']]);

        Mail::to($message->email)->send(new ContactMessageReply($message, $data['body']));

        $message->update(['handled_at' => $message->handled_at ?? now()]);

        return response()->json(['handled' => true]);
    }

    public function destroy(ContactMessage $message): JsonResponse
    {
        $message->delete();

        return response()->json(['deleted' => true]);
    }
}

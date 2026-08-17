<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // The honeypot is invisible to people, so anything in it is a bot.
        // It is answered with the same success message a person gets, because
        // telling a bot it was caught only invites another attempt.
        if ($request->filled('website')) {
            return back()->with('subscribed', true);
        }

        $data = $request->validate([
            'email' => ['required', 'string', 'email:rfc', 'max:254'],
        ]);

        // firstOrNew keeps a repeat sign-up idempotent. unsubscribed_at is set
        // directly rather than mass assigned, so it stays off the fillable list
        // and cannot be driven from request input.
        $subscriber = Subscriber::firstOrNew(['email' => mb_strtolower($data['email'])]);
        $subscriber->unsubscribed_at = null;
        $subscriber->save();

        return back()->with('subscribed', true);
    }
}

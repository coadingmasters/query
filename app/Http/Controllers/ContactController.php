<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Support\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /** Subjects offered in the form, and the only values accepted. */
    private const SUBJECTS = [
        'A question about cat care',
        'A correction to a guide',
        'A tool that is not working',
        'Partnership or advertising',
        'Something else',
    ];

    public function show(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');

        $description = 'Get in touch with PurrQuery — questions about cat care, '
            .'corrections to a guide, or anything that is not working. Every '
            .'message is read.';

        return view('contact', [
            'title' => 'Contact '.$name.' — Questions, Corrections, Feedback',
            'description' => $description,
            'canonical' => $url.'/contact',
            'subjects' => self::SUBJECTS,
            'schema' => Schema::graph([
                [
                    '@type' => 'ContactPage',
                    '@id' => $url.'/contact#page',
                    'url' => $url.'/contact',
                    'name' => 'Contact '.$name,
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                [
                    // No telephone or postal address: the site has neither, and
                    // inventing contact details is worse than omitting them —
                    // someone eventually tries to use them.
                    '@type' => 'ContactPoint',
                    '@id' => $url.'/contact#point',
                    'contactType' => 'customer support',
                    'email' => config('brand.email'),
                    'availableLanguage' => 'English',
                ],
                Schema::breadcrumbs('/contact', ['Home' => '/', 'Contact' => null]),
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Invisible to a person, so anything in it came from a bot. Answered
        // with the same success message, because telling a bot it was caught
        // only invites a second attempt.
        if ($request->filled('website')) {
            return back()->with('sent', true);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email:rfc', 'max:254'],
            'subject' => ['required', 'string', 'in:'.implode(',', self::SUBJECTS)],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
        ], [
            'message.min' => 'Please give us a little more detail so we can actually help.',
        ]);

        $contact = ContactMessage::create($data);

        // The message is safe in the database first; the notification is a
        // convenience on top. If mail is misconfigured the send fails here,
        // and losing the notification must not mean losing the message.
        try {
            Log::info('New contact message', [
                'id' => $contact->id,
                'subject' => $contact->subject,
                'from' => $contact->email,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('sent', true);
    }
}

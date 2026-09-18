<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ContactMessageReply extends Mailable
{
    use SerializesModels;

    public function __construct(
        public ContactMessage $original,
        public string $body,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [new Address($this->original->email, $this->original->name)],
            replyTo: [new Address(config('brand.email'), config('app.name'))],
            subject: Str::startsWith($this->original->subject, 'Re:')
                ? $this->original->subject
                : 'Re: '.$this->original->subject,
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.contact-reply');
    }
}

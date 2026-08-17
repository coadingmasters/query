<?php

/*
|--------------------------------------------------------------------------
| Contact page
|--------------------------------------------------------------------------
| Copy for /contact.
|
| There is no telephone number and no postal address here, because the site
| has neither. Contact details that do not work are worse than none at all —
| someone will eventually try to use them. Nor is there a promised response
| time in hours: this is a one-person project, and a promise that cannot be
| kept costs more trust than saying nothing.
*/

return [

    /*
     | Answered on the page, so a reader who only needs one of these never has
     | to write at all. Each is genuinely the most common version of itself.
     */
    'faqs' => [
        [
            'question' => 'Do you give veterinary advice?',
            'answer' => 'No. Everything here is general information, and it cannot '
                .'take the place of a vet who can actually examine your cat. If '
                .'something is wrong, or you are worried, speak to a vet — and in an '
                .'emergency, contact an emergency clinic straight away.',
        ],
        [
            'question' => 'I think a guide is wrong. What should I do?',
            'answer' => 'Tell us, and point us at the source if you have one. '
                .'Corrections are the most useful message we get: a wrong answer '
                .'about what a cat can eat is worth fixing quickly, and we would '
                .'rather hear it from you than leave it up.',
        ],
        [
            'question' => 'Can I suggest a tool or a guide?',
            'answer' => 'Yes, and it genuinely shapes what gets built next. The '
                .'library grows from the questions cat owners actually ask, so the '
                .'ones that come up repeatedly move to the front.',
        ],
        [
            'question' => 'Do you accept sponsored posts or paid links?',
            'answer' => 'The site is intended to be funded by advertising and '
                .'affiliate links in future, and there are none on it today. What '
                .'we will not do is let a payment decide what a guide says, or '
                .'publish a paid recommendation without labelling it as one.',
        ],
        [
            'question' => 'Is my message stored anywhere?',
            'answer' => 'Your name, email and message are stored so we can reply, '
                .'and nothing else — no tracking, no analytics profile, no passing '
                .'your address to anyone.',
        ],
    ],

    /*
     | What to expect, stated honestly. "We read every message" is true and
     | costs nothing to keep; "we reply within 24 hours" would not be.
     */
    'expectations' => [
        [
            'title' => 'Every message is read',
            'body' => 'By a person, not a filter. Corrections and bug reports get '
                .'looked at first.',
            'paths' => ['M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5Z', 'm3.5 8 7.6 5a1.6 1.6 0 0 0 1.8 0l7.6-5'],
        ],
        [
            'title' => 'Replies take a few days',
            'body' => 'This is a small project, so it is not instant. It is not a '
                .'black hole either.',
            'paths' => ['M12 6v6l4 2', 'M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20Z'],
        ],
        [
            'title' => 'Nothing is passed on',
            'body' => 'Your details are used to reply to you, and for nothing else '
                .'at all.',
            'paths' => ['M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z', 'm9.4 12.2 1.9 1.9 3.6-3.7'],
        ],
    ],
];

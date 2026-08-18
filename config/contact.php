<?php

/*
|--------------------------------------------------------------------------
| Contact page
|--------------------------------------------------------------------------
| Copy for /contact.
|
| There is no telephone number and no postal address here, because the site
| has neither. Contact details that do not work are worse than none at all.
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
                .'something is wrong, or you are worried, speak to a veterinarian. In an '
                .'emergency, contact an emergency clinic right away.',
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
                .'affiliate links in the future, and there are none on it today. What '
                .'we will not do is let a payment decide what a guide says, or '
                .'publish a paid recommendation without labeling it as one.',
        ],
        [
            'question' => 'Is my message stored anywhere?',
            'answer' => 'Your name, email and message are stored so we can reply, '
                .'and nothing else. No tracking, no analytics profile, no passing '
                .'your address to anyone.',
        ],
    ],

    /*
     | What to expect, stated honestly. "Every message is read" is true and
     | costs nothing to keep; "we reply within 24 hours" would not be.
     */
    'steps' => [
        [
            'title' => 'We receive your message',
            'body' => 'Every message is read by a real person, not a filter.',
            'paths' => [
                'M4 13h4l1.5 2.5h5L16 13h4',
                'M6.2 5h11.6a2 2 0 0 1 1.9 1.4L21 13v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4l1.3-6.6A2 2 0 0 1 6.2 5Z',
            ],
        ],
        [
            'title' => 'We review and research',
            'body' => 'We look into it properly rather than guessing at an answer.',
            'paths' => ['M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14Z', 'm20 20-4-4'],
        ],
        [
            'title' => 'We reply to you',
            'body' => 'You hear back by email. It takes a few days, not minutes.',
            'paths' => ['M21 12a8 8 0 0 1-8 8H4l2-3a8 8 0 1 1 15-5Z', 'M8.5 12h.01M12 12h.01M15.5 12h.01'],
        ],
    ],

    /*
     | The three reassurances beside the headline. Each is a plain statement
     | of fact. None of them is a service-level promise.
     */
    'assurances' => [
        [
            'title' => 'Real people',
            'body' => 'No bots here',
            'paths' => ['M16 20v-1.5a3.5 3.5 0 0 0-3.5-3.5h-5A3.5 3.5 0 0 0 4 18.5V20', 'M10 12a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7Z', 'M20 20v-1.5a3.5 3.5 0 0 0-2.6-3.4M15.5 5.2a3.5 3.5 0 0 1 0 6.6'],
        ],
        [
            'title' => 'We read it all',
            'body' => 'Every message',
            'paths' => ['M20 15.5a2.5 2.5 0 0 1-2.5 2.5H9l-4 3v-3H6.5A2.5 2.5 0 0 1 4 15.5v-8A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5Z'],
        ],
        [
            'title' => 'Cat lovers',
            'body' => 'We get it',
            'paths' => ['M12 20.5c-3.6-2.2-7-4.6-7-8.4A3.9 3.9 0 0 1 12 9.6a3.9 3.9 0 0 1 7 2.5c0 3.8-3.4 6.2-7 8.4Z'],
        ],
    ],
];

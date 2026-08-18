<?php

/*
|--------------------------------------------------------------------------
| About page
|--------------------------------------------------------------------------
| Copy for /about. It lives here rather than in the template so the counts
| can be derived from the catalogue instead of typed out and left to rot.
|
| Every figure below is either computed or verifiably true today. Claims
| that were supplied but cannot be backed — "100+ guides", "50,000+ monthly
| visits", "4.9 star average rating", "thousands of cat owners" — are
| deliberately absent. The site is new, has no ratings and no traffic
| history, and inventing that is both misleading to readers and the kind of
| thing Google's quality guidance treats as a trust signal gone wrong.
*/

return [

    'offers' => [
        [
            'title' => 'Free Cat Care Tools',
            'body' => 'Six free calculators and checkers: the Cat Age Calculator, '
                .'Calorie Calculator, Weight Checker, Name Generator, Vaccination '
                .'Tracker and Breed Quiz. Every one gives instant results with no '
                .'sign-up.',
            'tone' => 'primary',
            'paths' => [
                'M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0H5a2 2 0 0 1-2-2v-4m6 6h10a2 2 0 0 0 2-2v-4M3 9h18M3 15h18',
            ],
        ],
        [
            'title' => 'Researched Food Guides',
            'body' => 'Our food-safety guides cover fruit, vegetables, meat and '
                .'seafood, dairy and eggs, grains, herbs, treats and the foods that '
                .'are outright toxic — with the verdict stated before the detail, '
                .'so an answer takes seconds.',
            'tone' => 'accent',
            'paths' => [
                'M12 7.5v13',
                'M3.5 18.2a.8.8 0 0 1-.8-.8V4.9a.8.8 0 0 1 .8-.8h4.9A3.6 3.6 0 0 1 12 7.5a3.6 3.6 0 0 1 3.6-3.4h4.9a.8.8 0 0 1 .8.8v12.5a.8.8 0 0 1-.8.8h-5.4A3.1 3.1 0 0 0 12 20.5a3.1 3.1 0 0 0-3.1-2.3Z',
            ],
        ],
        [
            'title' => 'Practical Health Writing',
            'body' => 'From spotting the early signs of illness to understanding what '
                .'a breed needs, our health writing sticks to what is useful. Plain '
                .'English, no filler, and clear about where the guidance comes from.',
            'tone' => 'info',
            'paths' => [
                'M12 20.5C7 17.6 3.5 14.4 3.5 10.4A4.4 4.4 0 0 1 12 8.2a4.4 4.4 0 0 1 8.5 2.2c0 4-3.5 7.2-8.5 10.1Z',
            ],
        ],
    ],

    'values' => [
        [
            'title' => 'Accuracy above everything',
            'body' => 'Guidance here is written from published veterinary sources, not '
                .'from memory or guesswork. Where the evidence is thin or opinion is '
                .'split, we say so rather than picking the tidier answer.',
        ],
        [
            'title' => 'Free, and staying that way',
            'body' => 'Every tool and guide is free, with no account, no paywall and '
                .'no email required to see a result. That is the whole point of the '
                .'site, not an introductory offer.',
        ],
        [
            'title' => 'Built for real cat owners',
            'body' => 'This is written for the person holding the cat, not for other '
                .'specialists. If a sentence needs a glossary, it gets rewritten.',
        ],
        [
            'title' => 'Transparent about the money',
            'body' => 'PurrQuery is free to use and is intended to be funded by '
                .'advertising and affiliate links in future. There are none on the '
                .'site today. When that changes it will be labelled clearly, and it '
                .'will never decide what a guide says.',
        ],
    ],

    /*
     | Mission is what the site does today; vision is where it is going.
     | Kept as two short blocks rather than a long list — the page is meant
     | to be read, not waded through.
     */
    'purpose' => [
        [
            'label' => 'Our mission',
            'title' => 'Put a straight answer within reach',
            'body' => 'To make accurate, practical cat care knowledge free and easy '
                .'to reach for every owner — no account, no payment, and no need to '
                .'weigh up nine conflicting sources before deciding what to do. '
                .'Every tool gives an instant result, and every guide states its '
                .'answer before its reasoning.',
            'tone' => 'primary',
            'paths' => [
                'M12 21.5a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19Z',
                'M12 16.2a4.2 4.2 0 1 0 0-8.4 4.2 4.2 0 0 0 0 8.4Z',
                'M12 13.4a1.4 1.4 0 1 0 0-2.8 1.4 1.4 0 0 0 0 2.8Z',
            ],
        ],
        [
            'label' => 'Our vision',
            'title' => 'The place owners check first',
            'body' => 'A cat care resource people come back to because it is '
                .'consistently right, and because it is honest about the limits of '
                .'what is known. Growing from the questions owners actually ask, '
                .'staying free as it grows, and never letting what pays for the '
                .'site decide what a guide says.',
            'tone' => 'accent',
            'paths' => [
                'M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z',
                'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z',
            ],
        ],
    ],

    /*
     | The four short pillars beside the mission statement. Each is a
     | description of how the site is made, not a claim about how many people
     | use it — there is no traffic history to draw on yet.
     */
    'pillars' => [
        [
            'title' => 'Made for cat owners',
            'body' => 'Written for the person holding the cat, not for other specialists.',
            'tone' => 'primary',
            'paths' => ['M12 20.5c-3.6-2.2-7-4.6-7-8.4A3.9 3.9 0 0 1 12 9.6a3.9 3.9 0 0 1 7 2.5c0 3.8-3.4 6.2-7 8.4Z'],
        ],
        [
            'title' => 'Checked before it ships',
            'body' => 'Guidance comes from published veterinary sources, not from memory.',
            'tone' => 'accent',
            'paths' => ['M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z', 'm9.4 12.2 1.9 1.9 3.6-3.7'],
        ],
        [
            'title' => 'Honest about limits',
            'body' => 'Where the evidence is thin or opinion is split, we say so.',
            'tone' => 'primary',
            'paths' => ['M12 21.5a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19Z', 'M12 8.2v5', 'M12 16.2h.01'],
        ],
        [
            'title' => 'Always improving',
            'body' => 'The library grows from the questions owners actually send us.',
            'tone' => 'accent',
            'paths' => ['M4 17.5 9 12l3.5 3.5L20 8', 'M15 8h5v5'],
        ],
    ],

    /*
     | The trust list. "Ad-free" was supplied and is not used: the site is
     | intended to carry advertising in future, and promising otherwise on
     | the about page would be a claim we would have to break.
     */
    'trust' => [
        'Written from published veterinary sources, with the reasoning shown',
        'Tools give an instant answer — no sign-up, no paywall, no email',
        'Nothing about your cat is sent anywhere; the tools run in your browser',
        'Clear about what is not known, instead of guessing to fill a gap',
        'Corrections are welcome, and they get looked at first',
    ],
];

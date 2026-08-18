<?php

/*
|--------------------------------------------------------------------------
| Author and reviewer
|--------------------------------------------------------------------------
| Who stands behind what is published here.
|
| Cat health is what Google classifies as YMYL — "Your Money or Your Life" —
| where its quality guidance leans hardest on who wrote a thing and whether
| they can be checked. An anonymous site asking people to act on health
| information is the weakest position there is.
|
| Nothing below is invented. The name is read from the environment and every
| section that uses it disappears when it is unset, because a made-up author
| is worse than no author: it is the exact signal the guidance is built to
| catch, and it would be a lie told to readers about who is advising them.
|
| To switch it on, set SITE_AUTHOR_NAME in .env and edit the bio below.
*/

return [

    'founder' => [

        'name' => env('SITE_AUTHOR_NAME'),

        /*
         | What this person actually is. Founder, developer and editor is a
         | true description and a useful one. What it must never say is
         | "veterinarian", "vet nurse" or "animal nutritionist" unless that
         | is literally true — see the reviewer block below for how clinical
         | credibility is meant to be earned here.
         */
        'role' => env('SITE_AUTHOR_ROLE', 'Founder, developer and editor'),

        // One line under the name. Kept separate so it can be used as a
        // byline on tool and article pages without the full bio.
        'tagline' => 'Builds the tools, edits every guide, and answers the contact form.',

        'bio' => [
            'I built PurrQuery because looking up an ordinary question about a '
                .'cat meant reading nine pages that disagreed with each other, and '
                .'none of them said where their answer came from.',
            'I am not a veterinarian, and nothing here is written as though I '
                .'were. Guides are researched from published veterinary sources '
                .'and established feeding guidance, those sources are named, and '
                .'anything that edges towards diagnosis says to speak to a vet '
                .'instead of guessing.',
            'Every tool on the site is one I wrote and use. If something here is '
                .'wrong, I would rather hear it from you than leave it up, and '
                .'corrections go to the front of the queue.',
        ],

        // A file in resources/images, without the extension. A real photograph
        // carries more weight than an illustration here; leave it unset rather
        // than substituting a stock face.
        'image' => env('SITE_AUTHOR_IMAGE'),

        /*
         | Profiles that let a reader check the person exists. This is the part
         | that turns a name into a verifiable one, so it is worth more than
         | the bio text. Empty entries are dropped.
         */
        'profiles' => array_values(array_filter([
            env('SITE_AUTHOR_LINKEDIN'),
            env('SITE_AUTHOR_X'),
            env('SITE_AUTHOR_GITHUB'),
            env('SITE_AUTHOR_SITE'),
        ])),
    ],

    /*
     | The veterinary reviewer, once there is one.
     |
     | This is the piece that changes how the health content is read, and it
     | cannot be invented or supplied by an AI: it needs a licensed vet who has
     | actually read the guides and agreed to be named. Set the name and the
     | date they reviewed, and the byline and schema appear on their own.
     */
    'reviewer' => [
        'name' => env('SITE_REVIEWER_NAME'),
        'credentials' => env('SITE_REVIEWER_CREDENTIALS'),   // e.g. "DVM"
        'reviewed_on' => env('SITE_REVIEWER_DATE'),          // ISO date
        'profile' => env('SITE_REVIEWER_PROFILE'),
    ],
];

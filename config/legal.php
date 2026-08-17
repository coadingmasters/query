<?php

/*
|--------------------------------------------------------------------------
| Legal
|--------------------------------------------------------------------------
| Terms of use for PurrQuery.
|
| Two things to know about this file.
|
| First: it is not legal advice, and it was not written by a lawyer. It is a
| careful, standard set of terms for a free informational website, and it is
| a sound starting point — but before relying on it, have someone qualified
| in your jurisdiction read it.
|
| Second: LEGAL_JURISDICTION must be set. A governing-law clause that does
| not name a place is close to useless, so set it in .env to the country
| whose law should apply, and the clause below names it.
*/

$jurisdiction = env('LEGAL_JURISDICTION');

return [

    'jurisdiction' => $jurisdiction,

    // The date the current wording took effect. Bump it whenever the terms
    // change in a way that matters, so a reader can tell what they agreed to.
    'terms_effective' => env('LEGAL_TERMS_EFFECTIVE', '2026-08-18'),

    'terms' => [
        [
            'id' => 'agreement',
            'heading' => 'Agreement to these terms',
            'body' => [
                'These terms govern your use of PurrQuery. By visiting the site or using any tool or guide on it, you accept them. If you do not accept them, please do not use the site.',
                'We may update these terms as the site changes. The date at the top of this page shows when the current version took effect, and continuing to use PurrQuery after a change means you accept the revised terms.',
            ],
        ],
        [
            'id' => 'what-we-provide',
            'heading' => 'What PurrQuery provides',
            'body' => [
                'PurrQuery is a free website offering cat care calculators, food-safety guides and written articles. There is no account to create, no subscription, and no charge for anything on the site.',
                'The tools run in your browser and give an estimate based on the figures you enter. They are general aids, not measurements, and their output is only ever as good as the information given to them.',
            ],
        ],
        [
            'id' => 'not-veterinary-advice',
            'heading' => 'Not veterinary advice',
            'highlight' => true,
            'body' => [
                'This is the most important term on this page. Everything published on PurrQuery is general information. It is not veterinary advice, it is not a diagnosis, and it is not a treatment plan. It cannot take the place of a vet who can examine your cat.',
                'Always consult a qualified veterinary professional about your cat’s health, diet or behaviour, particularly before changing what your cat eats or how it is cared for. If your cat is unwell or you believe there is an emergency, contact your vet or an emergency animal clinic immediately rather than relying on anything you read here.',
                'You are responsible for decisions you make about your cat. PurrQuery does not accept liability for the consequences of acting on general information published on the site.',
            ],
        ],
        [
            'id' => 'acceptable-use',
            'heading' => 'Acceptable use',
            'body' => [
                'You may read, use and share PurrQuery for personal, non-commercial purposes. You may link to any page on the site freely.',
                'You may not use the site in a way that breaks the law, interferes with it working for anyone else, or attempts to gain access to parts of it that are not public. Scraping the site at scale, republishing its content as your own, or using automated systems to overload it are all outside acceptable use.',
                'You may not submit anything through our forms that is unlawful, abusive, deliberately misleading, or that you do not have the right to send.',
            ],
        ],
        [
            'id' => 'intellectual-property',
            'heading' => 'Content and ownership',
            'body' => [
                'The text, design, tools, code and branding on PurrQuery belong to us unless stated otherwise, and are protected by copyright and related rights.',
                'You are welcome to quote short extracts with clear credit and a link back to the page you took them from. Reproducing whole guides, or presenting our material as your own, is not permitted without written permission.',
            ],
        ],
        [
            'id' => 'your-submissions',
            'heading' => 'What you send us',
            'body' => [
                'If you contact us or subscribe to updates, you give us the details needed to respond to you or to send what you asked for. We use them for that and nothing else, and we do not sell or share them.',
                'If you send a suggestion, a correction or feedback, you allow us to act on it and to incorporate it into the site without owing you payment or credit. Please do not send anything confidential through the contact form.',
            ],
        ],
        [
            'id' => 'advertising',
            'heading' => 'Advertising and affiliate links',
            'body' => [
                'PurrQuery is free to use and is intended to be funded in future by advertising and affiliate links. Where an affiliate link earns us a commission, that will be disclosed clearly on the page it appears on.',
                'Commercial arrangements do not decide what our guides say. A product being advertised or linked does not amount to a recommendation of it, and we do not accept payment to change the substance of published guidance.',
            ],
        ],
        [
            'id' => 'third-parties',
            'heading' => 'Links to other websites',
            'body' => [
                'Some pages link to sites we do not run — veterinary sources, references and similar. Those links are provided because they are useful, not as an endorsement of everything on them.',
                'We have no control over other websites and are not responsible for their content, their accuracy or how they handle your data. Their terms and privacy policies apply once you leave PurrQuery.',
            ],
        ],
        [
            'id' => 'availability',
            'heading' => 'Availability of the site',
            'body' => [
                'We aim to keep PurrQuery available and working, but we do not guarantee it. The site may be unavailable during maintenance, because of a fault, or for reasons outside our control.',
                'We may change, suspend or withdraw any part of the site, including any individual tool or guide, without notice. Nothing here is a promise that a particular feature will continue to exist.',
            ],
        ],
        [
            'id' => 'liability',
            'heading' => 'Limitation of liability',
            'body' => [
                'PurrQuery is provided as it is, without warranties of any kind. We do not warrant that the content is complete, current or free of error, or that the site will be uninterrupted or secure.',
                'To the fullest extent the law allows, we are not liable for loss or damage arising from your use of the site or from reliance on anything published on it, including loss relating to the health of an animal.',
                'Nothing in these terms limits liability that cannot lawfully be limited, including liability for death or personal injury caused by negligence, or for fraud.',
            ],
        ],
        [
            'id' => 'governing-law',
            'heading' => 'Governing law',
            'body' => [
                $jurisdiction
                    ? 'These terms, and any dispute arising from them or from your use of PurrQuery, are governed by the laws of '.$jurisdiction.', and the courts of '.$jurisdiction.' have exclusive jurisdiction.'
                    : 'These terms, and any dispute arising from them or from your use of PurrQuery, are governed by the laws of the country in which the site is operated, and the courts of that country have exclusive jurisdiction.',
                'If any part of these terms is found to be unenforceable, the rest continues to apply.',
            ],
        ],
        [
            'id' => 'contact',
            'heading' => 'Getting in touch',
            'body' => [
                'If anything on this page is unclear, or you need to raise something about these terms, please use the contact page or write to us directly. We would rather answer a question than have you guess.',
            ],
        ],
    ],

    'privacy_effective' => env('LEGAL_PRIVACY_EFFECTIVE', '2026-08-18'),

    /*
     | Every statement below was checked against the schema and the live
     | response headers before it was written. The subscribers table holds an
     | email and nothing else; contact_messages holds a name, email, subject
     | and message; the only cookies set are the session and CSRF pair; and
     | the pages make no third-party requests at all. A privacy policy that
     | overstates or understates what a site does is worse than none, because
     | it is the one document people are entitled to rely on.
     */
    'privacy_summary' => [
        'We collect an email address if you subscribe, and your name, email and message if you write to us. Nothing else.',
        'No analytics, no tracking cookies, no advertising scripts, and no third-party requests from our pages.',
        'We never sell or share your details, and you can have them deleted by asking.',
    ],

    'privacy' => [
        [
            'id' => 'scope',
            'heading' => 'What this policy covers',
            'body' => [
                'This policy explains what PurrQuery does with personal information when you use the site. It covers purrquery.com and everything published on it.',
                'PurrQuery is free and has no accounts. You can read every guide and use every tool without giving us anything at all — the only information we hold is what you actively send us.',
            ],
        ],
        [
            'id' => 'what-we-collect',
            'heading' => 'What we collect',
            'body' => [
                'There are exactly two ways you can give us personal information, and one thing recorded automatically.',
            ],
            'list' => [
                'If you subscribe to updates: your email address. Nothing else — not your name, not where you subscribed from.',
                'If you use the contact form: your name, email address, the subject you chose and the message you wrote.',
                'Automatically: our hosting provider keeps standard server logs, which include IP addresses and browser details, in the ordinary course of running the server. Your IP is also used briefly to rate-limit our forms so they cannot be flooded.',
            ],
        ],
        [
            'id' => 'why',
            'heading' => 'Why we hold it, and on what basis',
            'body' => [
                'The email address you subscribe with is used to send the updates you asked for, and for nothing else. The basis for that is your consent, which you give by subscribing and can withdraw at any time.',
                'What you send through the contact form is used to read and answer your message. The basis for that is our legitimate interest in replying to people who write to us — you would reasonably expect a reply.',
                'Server logs and rate limiting exist to keep the site available and to stop abuse. That is also a legitimate interest, and the data is not used to build any profile of you.',
            ],
        ],
        [
            'id' => 'cookies',
            'heading' => 'Cookies',
            'body' => [
                'PurrQuery sets two cookies, both strictly necessary and both first-party. One keeps track of your session; the other carries a token that protects our forms against cross-site request forgery. Neither identifies you, follows you between sites, or is used for advertising.',
                'We do not use analytics. There is no Google Analytics, no pixel, no heatmap and no tracker of any kind on this site, and our pages make no requests to third-party servers — even the fonts are served from our own domain.',
                'Because the only cookies are strictly necessary ones, there is no consent banner. If that ever changes, you will be asked before any non-essential cookie is set.',
            ],
        ],
        [
            'id' => 'advertising',
            'heading' => 'Advertising and affiliate links',
            'body' => [
                'There is no advertising on PurrQuery today and no advertising cookies are set.',
                'The site is intended to be funded by advertising and affiliate links in future. Ad networks generally do set cookies and may use them to personalise what they show. Before any of that goes live, this policy will be updated to name the networks involved and explain what they collect, and consent will be requested where the law requires it.',
            ],
            'highlight' => true,
        ],
        [
            'id' => 'sharing',
            'heading' => 'Who else sees it',
            'body' => [
                'We do not sell your personal information, and we do not share it with anyone for their own marketing.',
                'The site runs on hosting provided by Hostinger, which necessarily processes the data stored on the server on our behalf. Beyond that, we pass your information to no one, except where we are legally required to.',
            ],
        ],
        [
            'id' => 'retention',
            'heading' => 'How long we keep it',
            'body' => [
                'Subscriber email addresses are kept until you unsubscribe or ask us to remove you. If you unsubscribe we keep a record that the address opted out, so a later sign-up form cannot quietly add you back.',
                'Contact messages are kept while we deal with them and for a reasonable period afterwards, so we have context if you write again. Ask us to delete a message and we will.',
                'Server logs are retained by our host under their own schedule, which is short and measured in weeks rather than years.',
            ],
        ],
        [
            'id' => 'your-rights',
            'heading' => 'Your rights',
            'body' => [
                'Wherever you live, you can ask us what we hold about you, ask us to correct it, or ask us to delete it. If you are in the UK or EU, the GDPR gives you those rights explicitly, along with the right to object to processing, to request a copy of your data, and to complain to your data protection authority. If you are in California, the CCPA gives you comparable rights, including the right to know and to delete — and note that we have no personal information to sell in the first place.',
                'To exercise any of them, write to us at the address below. We do not require you to create an account or fill in a form to make a request, and we will not charge you for it.',
                'Unsubscribing is simpler still: every update we send includes a one-click unsubscribe link.',
            ],
        ],
        [
            'id' => 'security',
            'heading' => 'How it is protected',
            'body' => [
                'The whole site is served over HTTPS, so anything you send us is encrypted in transit. Stored data sits in a database that is not publicly reachable, and access to it is limited to what is needed to run the site.',
                'No system is perfectly secure, and we will not pretend otherwise. What we can say is that we hold as little as possible, which is the most effective protection available: information that was never collected cannot be exposed.',
            ],
        ],
        [
            'id' => 'children',
            'heading' => 'Children',
            'body' => [
                'PurrQuery is written for adults looking after cats, and is not directed at children. We do not knowingly collect personal information from anyone under 16.',
                'If you believe a child has sent us their details, tell us and we will delete them.',
            ],
        ],
        [
            'id' => 'international',
            'heading' => 'Where your data is held',
            'body' => [
                'The site is hosted in Europe. If you are visiting from elsewhere, including the United States, the information you send us is processed there.',
                'The same protections described in this policy apply wherever you are reading from.',
            ],
        ],
        [
            'id' => 'changes',
            'heading' => 'Changes to this policy',
            'body' => [
                'We will update this policy when what we do changes — most likely when advertising is introduced. The date at the top shows when the current version took effect.',
                'If a change materially affects how we handle information you have already given us, we will make that clear rather than quietly revising the page.',
            ],
        ],
        [
            'id' => 'contact',
            'heading' => 'Getting in touch',
            'body' => [
                'For anything about this policy, or to make a request about your data, use the contact page or email us directly. We answer these ourselves — there is no privacy department to be routed through.',
            ],
        ],
    ],
];

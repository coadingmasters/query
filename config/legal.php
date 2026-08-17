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
];

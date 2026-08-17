<?php

/*
|--------------------------------------------------------------------------
| Brand
|--------------------------------------------------------------------------
| Copy and identity that appears in more than one place — page titles, meta
| descriptions, structured data and the footer. Keeping it here means the
| wording stays consistent across the site and search results.
|
| The name is "PurrQuery", matching purrquery.com. It was previously spelled
| "PuurQuery", which also put the contact address on puurquery.com — a domain
| the project does not own, so that mail would have bounced.
*/

return [
    'tagline' => env('BRAND_TAGLINE', 'Smart tools and clear answers for cat owners'),

    'description' => env('BRAND_DESCRIPTION', 'Free cat care tools and '
        .'research-backed guides. Work out your cat’s age, calories and ideal '
        .'weight, and check what is safe to feed — no sign-up needed.'),

    'email' => env('BRAND_EMAIL', 'hello@purrquery.com'),

    // Shown under the hero as short proof points.
    'promises' => ['100% free', 'PDF reports', 'No sign-up', 'Research-backed'],
];

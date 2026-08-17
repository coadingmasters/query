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

    /*
     | Open Graph wants a language_TERRITORY pair, which app()->getLocale()
     | ("en") does not carry. Stated once here rather than assembled inline.
     */
    'og_locale' => env('BRAND_OG_LOCALE', 'en_GB'),

    /*
     | The page title leads with what people search for and closes with the
     | brand. A new site has no brand recognition to trade on, so the keywords
     | earn the click; the name still appears, just last.
     */
    'home_title' => env('BRAND_HOME_TITLE', 'Free Cat Care Tools & Food Safety Guides'),

    // Shown under the hero as short proof points.
    'promises' => ['100% free', 'PDF reports', 'No sign-up', 'Research-backed'],
];

<?php

/*
|--------------------------------------------------------------------------
| Brand
|--------------------------------------------------------------------------
| Copy and identity that appears in more than one place: page titles, meta
| descriptions, structured data and the footer. Keeping it here means the
| wording stays consistent across the site and search results.
|
| The name is "PurrQuery", matching purrquery.com. It was previously spelled
| "PuurQuery", which also put the contact address on puurquery.com, a domain
| the project does not own, so that mail would have bounced.
*/

return [
    'tagline' => env('BRAND_TAGLINE', 'Smart tools and clear answers for cat owners'),

    'description' => env('BRAND_DESCRIPTION', 'Free cat care tools and '
        .'research-backed guides. Work out your cat’s age, calories and ideal '
        .'weight, and check what is safe to feed. No sign-up needed.'),

    'email' => env('BRAND_EMAIL', 'hello@purrquery.com'),

    // The html lang attribute. "en-US" rather than a bare "en", so spelling
    // and date conventions are declared rather than guessed at.
    'lang' => env('BRAND_LANG', 'en-US'),

    /*
     | Open Graph wants a language_TERRITORY pair, which app()->getLocale()
     | ("en") does not carry. Stated once here rather than assembled inline.
     |
     | US, not GB. The audience is American, and the pair is one of the
     | signals telling search and social which market a page is written for.
     */
    'og_locale' => env('BRAND_OG_LOCALE', 'en_US'),

    /*
     | The page title leads with what people search for and closes with the
     | brand. A new site has no brand recognition to trade on, so the keywords
     | earn the click; the name still appears, just last.
     */
    'home_title' => env('BRAND_HOME_TITLE', 'Free Cat Care Tools & Food Safety Guides'),

    // Shown under the hero as short proof points.
    'promises' => ['100% free', 'PDF reports', 'No sign-up', 'Research-backed'],

    // og:image on every page that doesn't set its own. A path relative to
    // the site root (not a full URL) so it works the same whether it's this
    // committed default or an admin-uploaded storage file.
    'og_image' => '/og-image.png',

    // twitter:card. "summary_large_image" needs a wide image (the default
    // above is), "summary" suits a square logo instead.
    'twitter_card' => 'summary_large_image',

    // Organization JSON-LD logo. Null falls back to og_image in Schema.php
    // rather than being duplicated here.
    'schema_logo' => null,

    // Organization JSON-LD sameAs — the business's own social accounts, not
    // the founder's (see author.founder.profiles for that). Empty entries
    // are dropped, same reasoning as the founder profiles: unverifiable
    // sameAs claims are worse than none.
    'social' => [],
];

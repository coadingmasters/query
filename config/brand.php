<?php

/*
|--------------------------------------------------------------------------
| Brand
|--------------------------------------------------------------------------
| Copy and identity that appears in more than one place — page titles, meta
| descriptions, structured data and the footer. Keeping it here means the
| wording stays consistent across the site and search results.
*/

return [
    'tagline' => env('BRAND_TAGLINE', 'Free online tools and practical guides'),

    'description' => env('BRAND_DESCRIPTION', 'PuurQuery is building a fast, '
        .'ad-light home for free online tools, calculators and practical '
        .'how-to guides. We are putting the finishing touches on it now.'),

    'email' => env('BRAND_EMAIL', 'hello@puurquery.com'),

    // Rotated one at a time under the wordmark on the launch screen.
    'rotates' => ['online tools', 'calculators', 'how-to guides', 'converters'],
];

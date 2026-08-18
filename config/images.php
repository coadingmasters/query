<?php

/*
|--------------------------------------------------------------------------
| Images
|--------------------------------------------------------------------------
| Source images live in resources/images/ at full size. `php scripts/
| build-images.php` reads this file, then writes cropped, resized and
| recompressed copies into public/images/ — that is what the site serves.
|
| Each preset is generated at 1x and 2x so high-density screens get a sharp
| file without every visitor paying for the larger one.
*/

return [

    /*
     | Display size in CSS pixels: [width, height]. The source is centre
     | cropped to this aspect ratio, so a preset change re-crops rather than
     | squashing the picture.
     */
    'presets' => [
        'hero' => [780, 624],
        'tool' => [400, 250],
        'food' => [300, 200],
        'blog' => [600, 400],
        'logo' => [96, 96],
        'story' => [560, 420],

        // Cut-out artwork: a subject on transparency, with its doodles baked
        // into the file. The ratios match the sources, so the centre crop is
        // a no-op and no whisker or raised paw gets clipped off.
        'cutout' => [600, 400],
        'cutout-sm' => [320, 213],
        'cutout-tall' => [360, 311],

        // The about page runs its artwork larger than the contact page does.
        'feature' => [640, 427],
        'portrait-wide' => [760, 507],
        'banner' => [380, 301],
    ],

    /*
     | WebP quality per preset. The hero is the largest thing on screen and
     | the one Core Web Vitals measures, so it is worth a few more KB; the
     | small cards hold detail fine lower down.
     */
    'quality' => [
        'hero' => 82,
        'tool' => 78,
        'food' => 78,
        'blog' => 80,

        // The mark carries fine linework and sits on every page.
        'logo' => 90,
        'story' => 80,

        // Flat illustration over transparency compresses well, but banding
        // shows in the soft gradients below this.
        'cutout' => 84,
        'cutout-sm' => 84,
        'cutout-tall' => 84,

        'feature' => 82,
        'portrait-wide' => 82,
        'banner' => 82,
    ],

    /*
     | Which preset each image is built for. Adding an image here and running
     | the build script is all that is needed to use it in a page.
     */
    'images' => [
        'purrquerylogo' => 'logo',

        'purrquery-hero-cat-owner-smiling' => 'hero',

        'purrquery-orange-tabby-cat-hero' => 'cutout',
        'purrquery-cat-waving-paw' => 'cutout-tall',
        'purrquery-cat-saying-hi' => 'cutout-sm',
        'purrquery-cat-yarn-ball' => 'cutout-sm',
        'purrquery-cat-food-bowl-heart' => 'cutout-sm',

        'purrquery-about-hero-three-kittens' => 'cutout',
        'purrquery-cat-cozy-blanket' => 'feature',
        'purrquery-cat-lover-cuddling-cat' => 'portrait-wide',
        'purrquery-happy-tabby-cat-relaxing' => 'feature',
        'purrquery-care-smarter-cat-illustration' => 'banner',

        'cat-pregnancy-calculator-kitten' => 'tool',
        'cat-age-calculator-senior-tabby-cat' => 'tool',
        'cat-calorie-calculator-cat-food-bowl' => 'tool',
        'cat-weight-checker-cat-on-scale' => 'tool',
        'cat-name-generator-cute-kitten' => 'tool',
        'cat-vaccination-tracker-vet-examination' => 'tool',
        'cat-breed-quiz-multiple-cat-breeds' => 'tool',

        'can-cats-eat-fruits-fresh-colorful-fruits' => 'food',
        'can-cats-eat-vegetables-fresh-greens' => 'food',
        'can-cats-eat-meat-seafood-chicken-fish' => 'food',
        'can-cats-eat-dairy-eggs-milk-cheese' => 'food',
        'toxic-foods-cats-must-avoid-dangerous' => 'food',
        'can-cats-eat-grains-seeds-rice-oats' => 'food',
        'can-cats-eat-sweets-desserts-unsafe' => 'food',
        'can-cats-eat-junk-food-fast-food' => 'food',
        'can-cats-eat-herbs-spices-basil-mint' => 'food',
        'can-cats-eat-cat-treats-snacks' => 'food',

        'can-cats-eat-broccoli-cat-sniffing' => 'blog',
        'how-much-feed-cat-eating-food-bowl' => 'blog',
        'signs-cat-is-sick-vet-examination' => 'blog',
        'best-indoor-cat-food-premium-ingredients' => 'blog',
        'can-cats-eat-chicken-cat-looking' => 'blog',
        'new-cat-owner-guide-couple-kitten' => 'blog',
    ],

    /*
     | Stand-ins for artwork that has not been shot yet: "the image named on
     | the left does not exist, borrow the one on the right for now".
     |
     | To replace one for real, drop the properly named file into
     | resources/images/ and delete its line here. Nothing else changes —
     | the pages already reference the real name.
     */
    /*
     | Take a region of the source before anything else, as [x, y, w, h] in
     | source pixels. The care-smarter illustration carries its own headline
     | across the top, which would repeat the live heading beside it, and a
     | wide cream frame that shows as a pale blob on a tinted band. Only the
     | kitten and its bed are wanted.
     */
    'crops' => [
        'purrquery-care-smarter-cat-illustration' => [575, 285, 700, 555],
    ],

    /*
     | Artwork that arrived as a subject on flat white rather than on alpha.
     | Listed here, the build seeds a fill from the border and spreads it
     | through connected near-white pixels only, so the surround goes and the
     | subject's own white does not.
     */
    'keyed' => [
        'purrquery-about-hero-three-kittens',
    ],

    'placeholders' => [
        'cat-pregnancy-calculator-kitten' => 'cat-name-generator-cute-kitten',
        'how-much-feed-cat-eating-food-bowl' => 'cat-calorie-calculator-cat-food-bowl',
        'signs-cat-is-sick-vet-examination' => 'cat-vaccination-tracker-vet-examination',
        'best-indoor-cat-food-premium-ingredients' => 'can-cats-eat-cat-treats-snacks',
        'can-cats-eat-chicken-cat-looking' => 'can-cats-eat-meat-seafood-chicken-fish',
        'new-cat-owner-guide-couple-kitten' => 'cat-name-generator-cute-kitten',
    ],
];

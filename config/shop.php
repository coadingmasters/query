<?php

/**
 * The shop's product catalog: a small, curated list rather than a full
 * storefront. Each category and pick can carry a `media` name — an
 * admin-uploaded photo (category "general") that replaces the icon
 * automatically once one exists, the same fallback pattern the homepage
 * category cards use. Nothing here has a price, rating or purchase link
 * yet: none of that is real until actual products and an affiliate
 * account are chosen, and publishing invented numbers would be exactly
 * the kind of fake social proof this site does not do anywhere else.
 */
return [

    'categories' => [
        [
            'slug' => 'feeding',
            'title' => 'Feeding',
            'description' => 'Bowls, automatic feeders, water fountains and more.',
            'tone' => 'primary',
            'media' => 'shop-category-feeding',
            'icon' => 'M3.5 12.5h17a8.5 8.5 0 0 1-17 0Z|M6 9.2c0-1.6 1.4-2.2 1.4-3.4M10.5 9.2c0-1.6 1.4-2.2 1.4-3.4M15 9.2c0-1.6 1.4-2.2 1.4-3.4',
        ],
        [
            'slug' => 'grooming',
            'title' => 'Grooming',
            'description' => 'Brushes, nail care, grooming tools and more.',
            'tone' => 'warning',
            'media' => 'shop-category-grooming',
            'icon' => 'M12 21c-4.2-2.5-8-5.2-8-9.4A4.4 4.4 0 0 1 12 9a4.4 4.4 0 0 1 8 2.6c0 4.2-3.8 6.9-8 9.4Z',
        ],
        [
            'slug' => 'health-and-wellness',
            'title' => 'Health & Wellness',
            'description' => 'Dental care, wellness products, care accessories and more.',
            'tone' => 'accent',
            'media' => 'shop-category-health',
            'icon' => 'M8 3v5a4 4 0 0 0 8 0V3|M6 3h4M14 3h4|M12 12v3a4 4 0 0 0 8 0v-.5|M20 12.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z',
        ],
        [
            'slug' => 'comfort',
            'title' => 'Comfort',
            'description' => 'Beds, blankets, carriers and more.',
            'tone' => 'info',
            'media' => 'shop-category-comfort',
            'icon' => 'M4 18v-6a8 8 0 0 1 16 0v6|M2 18h20v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1Z|M4 14h16',
        ],
        [
            'slug' => 'play-and-enrichment',
            'title' => 'Play & Enrichment',
            'description' => 'Toys, scratchers, cat trees and more.',
            'tone' => 'danger',
            'media' => 'shop-category-play',
            'icon' => 'M12 3v3.5M12 17.5V21M3 12h3.5M17.5 12H21M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18|M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z',
        ],
        [
            'slug' => 'litter-and-hygiene',
            'title' => 'Litter & Hygiene',
            'description' => 'Litter boxes, mats, cleaning accessories and more.',
            'tone' => 'primary',
            'media' => 'shop-category-litter',
            'icon' => 'M4 8h16l-1.5 11a2 2 0 0 1-2 1.8H7.5a2 2 0 0 1-2-1.8L4 8Z|M9 8V6a3 3 0 0 1 6 0v2',
        ],
    ],

    // Placeholders, not a live catalog: no price, rating or purchase link
    // until real products are chosen and an affiliate program is set up.
    'picks' => [
        ['name' => 'Cat Water Fountain', 'category' => 'feeding', 'media' => 'shop-pick-water-fountain'],
        ['name' => 'Automatic Cat Feeder', 'category' => 'feeding', 'media' => 'shop-pick-feeder'],
        ['name' => 'Self-Cleaning Cat Brush', 'category' => 'grooming', 'media' => 'shop-pick-brush'],
        ['name' => 'Multi-Level Cat Tree', 'category' => 'play-and-enrichment', 'media' => 'shop-pick-cat-tree'],
        ['name' => 'Stainless Steel Litter Box', 'category' => 'litter-and-hygiene', 'media' => 'shop-pick-litter-box'],
    ],

];

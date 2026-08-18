<?php

/*
|--------------------------------------------------------------------------
| Cat age calculator
|--------------------------------------------------------------------------
| The model behind /tools/cat-age-calculator.
|
| Two things every competitor gets wrong are worth stating here. The first is
| "multiply by seven", which is not how cats age: a cat does most of its
| growing up in year one and then slows sharply. The second is stopping at a
| number. A converted age is a novelty; what an owner can act on is the life
| stage it lands in and what that stage changes about their care.
|
| Life stages follow the 2021 AAFP/AAHA Feline Life Stage Guidelines, which
| collapsed the older five-stage model into four. Care guidance per stage is
| the general published consensus and is deliberately conservative: anything
| that edges towards diagnosis says to see a vet.
*/

return [

    /*
     | The conversion itself.
     |
     | Year one is worth about 15 human years and year two about another 9,
     | which puts a two-year-old at roughly 24. Every year after that adds
     | about 4. Below one year the milestones are spaced by month, because a
     | kitten changes faster than a yearly figure can describe.
     */
    'first_year' => 15,
    'second_year' => 24,
    'per_year_after' => 4,

    // Month-by-month for the first year, where a single rate would be wrong.
    'kitten_months' => [
        1 => 1, 2 => 2, 3 => 4, 4 => 6, 5 => 8, 6 => 10,
        7 => 11, 8 => 12, 9 => 13, 10 => 14, 11 => 14, 12 => 15,
    ],

    /*
     | AAFP/AAHA 2021. Ages are in cat years; `until` is exclusive.
     */
    'stages' => [
        [
            'id' => 'kitten',
            'name' => 'Kitten',
            'from' => 0,
            'until' => 1,
            'summary' => 'Growing fast, and everything is being learned at once.',
            'tone' => 'primary',
            'vet' => 'Every 3 to 4 weeks until the vaccine course is finished, then a check at 6 months.',
            'care' => [
                'Kitten food, which is higher in calories and protein than adult food.',
                'Finish the core vaccine course, and agree a parasite plan with your vet.',
                'Neutering is usually discussed around 4 to 6 months.',
                'Handle the paws, ears and mouth early so grooming and vet visits are easy later.',
            ],
        ],
        [
            'id' => 'young-adult',
            'name' => 'Young adult',
            'from' => 1,
            'until' => 7,
            'summary' => 'Fully grown and at their physical peak. The easiest years to coast through.',
            'tone' => 'accent',
            'vet' => 'Once a year, even when nothing seems wrong.',
            'care' => [
                'Switch to adult food and measure portions. This is when quiet weight gain starts.',
                'Keep up dental care. Dental disease is common and it begins now, silently.',
                'Establish a baseline at the annual visit, so later changes mean something.',
                'Keep them busy: play, height to climb, and something to scratch.',
            ],
        ],
        [
            'id' => 'mature-adult',
            'name' => 'Mature adult',
            'from' => 7,
            'until' => 11,
            'summary' => 'Middle age. Changes start here, and they are easy to miss.',
            'tone' => 'warning',
            'vet' => 'Once a year, with baseline bloodwork and urine testing worth discussing.',
            'care' => [
                'Watch the weight in both directions. Gaining and losing both matter.',
                'Ask about screening bloodwork. Kidney and thyroid changes show up here first.',
                'Note any change in drinking or litter tray habits and mention it.',
                'Keep food, water and trays easy to reach as they slow down.',
            ],
        ],
        [
            'id' => 'senior',
            'name' => 'Senior',
            'from' => 11,
            'until' => null,
            'summary' => 'Older, and worth watching closely. Most of what goes wrong is treatable when caught early.',
            'tone' => 'info',
            'vet' => 'Every 6 months, with routine bloodwork and blood pressure checks.',
            'care' => [
                'Weigh regularly. Weight loss in an older cat is a reason to call the vet, not to wait.',
                'Arthritis is common and rarely obvious. Watch for reluctance to jump.',
                'Low-sided litter trays and a step up to favourite spots help more than you would think.',
                'Increased thirst, appetite changes and hiding all warrant a visit.',
            ],
        ],
    ],

    /*
     | Lifestyle changes life expectancy more than almost anything else an
     | owner controls. The ranges below are the commonly published figures,
     | and they are presented as ranges because that is what they are.
     */
    'lifestyles' => [
        'indoor' => [
            'label' => 'Indoor only',
            'life_expectancy' => [12, 18],
            'note' => 'Indoor cats avoid traffic, fights and most infectious disease, and they live substantially longer for it.',
        ],
        'both' => [
            'label' => 'Indoor and outdoor',
            'life_expectancy' => [10, 15],
            'note' => 'Outdoor access brings enrichment and risk in the same door. Keep vaccines and parasite cover current.',
        ],
        'outdoor' => [
            'label' => 'Outdoor mostly',
            'life_expectancy' => [5, 10],
            'note' => 'Traffic, fights and infectious disease account for most of the difference. Neutering and vaccination narrow the gap.',
        ],
    ],

    /*
     | Sources, named on the page. This is the cheapest authority available to
     | a tool like this, and most competitors skip it.
     */
    'sources' => [
        [
            'name' => '2021 AAHA/AAFP Feline Life Stage Guidelines',
            'url' => 'https://catvets.com/guidelines/practice-guidelines/life-stage-guidelines',
            'note' => 'The four life stages used here, and the care priorities for each.',
        ],
        [
            'name' => 'American Veterinary Medical Association: pet ageing',
            'url' => 'https://www.avma.org/resources-tools/pet-owners',
            'note' => 'General guidance on how pets age relative to people.',
        ],
        [
            'name' => 'Cornell Feline Health Center',
            'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center',
            'note' => 'Background on senior cat care and age-related disease.',
        ],
    ],
];

<?php

/*
|--------------------------------------------------------------------------
| Cat calorie calculator
|--------------------------------------------------------------------------
| The model behind /tools/cat-calorie-calculator.
|
| Every number here is one of two things: the NRC's resting energy
| requirement formula (RER = 70 x weight_kg^0.75), which is not in dispute,
| or a maintenance-energy multiplier drawn from the published range in the
| 2006 NRC Nutrient Requirements of Dogs and Cats and the AAFCO life-stage
| categories. Where NRC gives a range rather than one number (nursing, for
| instance), a representative point is picked and said so in the note.
|
| This file is the single source of truth for both the PHP-rendered
| reference tables further down the page and the JSON model handed to the
| calculator's JavaScript, so the two can never drift apart.
*/

return [

    /*
     | id must match the value on the life-stage radio button.
     | Kitten stages and the two reproductive stages carry a fixed
     | multiplier; every other stage depends on neuter status.
     */
    'life_stages' => [
        [
            'id' => 'kitten-0-4',
            'label' => 'Kitten (0–4 months)',
            'kind' => 'fixed',
            'multiplier' => 3.0,
            'note' => 'Growing fast: kittens this age can need up to three times an adult\'s resting energy.',
        ],
        [
            'id' => 'kitten-4-12',
            'label' => 'Kitten (4–12 months)',
            'kind' => 'fixed',
            'multiplier' => 2.0,
            'note' => 'Growth is slowing but still real. Most cats reach adult weight around 10 to 12 months.',
        ],
        [
            'id' => 'young-adult',
            'label' => 'Young Adult (1–3 years)',
            'kind' => 'neuter',
            'multiplier_neutered' => 1.6,
            'multiplier_intact' => 1.8,
            'note' => 'Full grown. Neutering lowers energy needs by roughly 20 to 30%, which is why the multiplier drops.',
        ],
        [
            'id' => 'adult',
            'label' => 'Adult (3–7 years)',
            'kind' => 'neuter',
            'multiplier_neutered' => 1.6,
            'multiplier_intact' => 1.8,
            'note' => 'The multiplier does not change from young adult; metabolism is fairly stable through this stretch.',
        ],
        [
            'id' => 'mature-adult',
            'label' => 'Mature Adult (7–10 years)',
            'kind' => 'neuter',
            'multiplier_neutered' => 1.4,
            'multiplier_intact' => 1.6,
            'note' => 'A modest step down. Lean mass and activity both tend to ease off gradually starting here.',
        ],
        [
            'id' => 'senior',
            'label' => 'Senior (10+ years)',
            'kind' => 'neuter',
            'multiplier_neutered' => 1.2,
            'multiplier_intact' => 1.4,
            'note' => 'Some senior cats need less, some need more if a condition like hyperthyroidism is driving weight loss. A vet check matters more than the formula here.',
        ],
        [
            'id' => 'pregnant',
            'label' => 'Pregnant',
            'kind' => 'pregnancy',
            'multiplier_early' => 1.6,
            'multiplier_late' => 2.0,
            'note' => 'Energy needs rise through gestation, and rise fastest in the final third.',
        ],
        [
            'id' => 'nursing',
            'label' => 'Nursing / Lactating',
            'kind' => 'nursing',
            'multiplier_min' => 2.0,
            'multiplier_max' => 2.5,
            'note' => 'Nursing is the highest demand a cat\'s body has, and the real number depends heavily on litter size. This estimate leans on the low end for a thinner queen and the high end for one in good condition; a nursing queen should never be food-restricted.',
        ],
    ],

    // Applied on top of the life-stage multiplier.
    'activity' => [
        'low' => ['value' => 0.9, 'label' => 'Low', 'description' => 'Indoor, sleeps a lot, minimal play'],
        'moderate' => ['value' => 1.0, 'label' => 'Moderate', 'description' => 'Indoor, regular play sessions'],
        'high' => ['value' => 1.2, 'label' => 'High', 'description' => 'Outdoor access, very active, hunts'],
    ],

    // Percent adjustment applied to the final estimate, not the multiplier.
    'bcs' => [
        1 => ['label' => 'Underweight', 'percent' => 0.15],
        2 => ['label' => 'Lean', 'percent' => 0.05],
        3 => ['label' => 'Ideal', 'percent' => 0.0],
        4 => ['label' => 'Overweight', 'percent' => -0.15],
        5 => ['label' => 'Obese', 'percent' => -0.25],
    ],

    'living' => [
        'indoor' => ['value' => 0.9, 'label' => 'Indoor Only'],
        'mixed' => ['value' => 1.0, 'label' => 'Indoor + Outdoor'],
        'outdoor' => ['value' => 1.2, 'label' => 'Outdoor'],
    ],

    'food' => [
        'dry_kcal_per_cup' => 350,
        'wet_kcal_per_can' => 95,
        'wet_can_oz' => 5.5,
    ],

    'sources' => [
        [
            'name' => 'National Research Council: Nutrient Requirements of Dogs and Cats (2006)',
            'url' => 'https://nap.nationalacademies.org/catalog/10668/nutrient-requirements-of-dogs-and-cats',
            'note' => 'The RER formula and the maintenance-energy multiplier ranges this calculator is built from.',
        ],
        [
            'name' => 'AAFCO: Cat Food Nutrient Profiles',
            'url' => 'https://www.aafco.org/consumers/understanding-pet-food/',
            'note' => 'Life-stage categories for complete and balanced feline diets.',
        ],
        [
            'name' => 'Cornell Feline Health Center: feeding your cat',
            'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center/health-information/feeding',
            'note' => 'Background on energy needs and portioning by life stage.',
        ],
        [
            'name' => 'AAFP/AAHA Weight Management Guidelines',
            'url' => 'https://catvets.com/guidelines/practice-guidelines/weight-management-guidelines',
            'note' => 'The 9-point-scale-derived 5-point Body Condition Score used in this calculator.',
        ],
    ],
];

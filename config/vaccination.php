<?php

/*
|--------------------------------------------------------------------------
| Cat vaccination tracker
|--------------------------------------------------------------------------
| The model behind /tools/cat-vaccination-tracker.
|
| Every vaccine is a short chain of doses. Each dose knows how it is timed:
| off the cat's date of birth if it is the first in the chain, or off the
| previous dose's date otherwise. That is the whole engine — the JavaScript
| just walks each chain, seeding it from whatever dates the owner entered,
| and works out what is next.
|
| Schedules follow the AAFP 2020 Feline Vaccination Advisory Panel report and
| the WSAVA 2024 vaccination guidelines. Rabies timing is a legal minimum in
| most US states as much as a medical one, and state law varies, which is
| flagged on that chain rather than asserted as one national rule.
*/

return [

    'vaccines' => [
        'fvrcp' => [
            'label' => 'FVRCP',
            'full_name' => 'Feline viral rhinotracheitis, calicivirus, panleukopenia',
            'group' => 'core',
            'condition' => 'always',
            'doses' => [
                ['id' => 'fvrcp-1', 'label' => 'FVRCP (dose 1)', 'kitten_only' => true, 'from_dob_days' => 56, 'from_prev_days' => null],
                ['id' => 'fvrcp-2', 'label' => 'FVRCP (dose 2)', 'kitten_only' => true, 'from_dob_days' => 84, 'from_prev_days' => 21],
                ['id' => 'fvrcp-3', 'label' => 'FVRCP (dose 3)', 'kitten_only' => true, 'from_dob_days' => 112, 'from_prev_days' => 21],
                ['id' => 'fvrcp-booster-1yr', 'label' => 'FVRCP 1-year booster', 'kitten_only' => false, 'from_dob_days' => 365, 'from_prev_days' => 365],
                ['id' => 'fvrcp-adult', 'label' => 'FVRCP adult booster', 'kitten_only' => false, 'from_dob_days' => null, 'from_prev_days' => 1095, 'recurring' => true],
            ],
        ],
        'rabies' => [
            'label' => 'Rabies',
            'full_name' => 'Rabies',
            'group' => 'core',
            'condition' => 'always',
            'note' => 'Timing here is a common pattern, not a national rule. Rabies vaccination is legally required in most US states, even for indoor-only cats, and the interval between boosters is set by state law and by which product your vet uses. Confirm both with your vet.',
            'doses' => [
                ['id' => 'rabies-1', 'label' => 'Rabies (first dose)', 'kitten_only' => false, 'from_dob_days' => 112, 'from_prev_days' => null],
                ['id' => 'rabies-booster-1yr', 'label' => 'Rabies 1-year booster', 'kitten_only' => false, 'from_dob_days' => 477, 'from_prev_days' => 365],
                ['id' => 'rabies-next', 'label' => 'Rabies booster', 'kitten_only' => false, 'from_dob_days' => null, 'from_prev_days' => 1095, 'recurring' => true],
            ],
        ],
        'felv' => [
            'label' => 'FeLV',
            'full_name' => 'Feline leukemia virus',
            'group' => 'lifestyle',
            // Core for every kitten under AAFP 2020; lifestyle-risk for adults.
            'condition' => 'kitten_or_outdoor_or_multicat',
            'note' => 'A cat should be tested for FeLV before their first dose. Vaccinating a cat that already carries the virus does not help them, and a positive result changes what the household needs to know.',
            'doses' => [
                ['id' => 'felv-1', 'label' => 'FeLV (dose 1)', 'kitten_only' => false, 'from_dob_days' => 56, 'from_prev_days' => null],
                ['id' => 'felv-2', 'label' => 'FeLV (dose 2)', 'kitten_only' => false, 'from_dob_days' => 84, 'from_prev_days' => 25],
                ['id' => 'felv-booster', 'label' => 'FeLV annual booster', 'kitten_only' => false, 'from_dob_days' => null, 'from_prev_days' => 365, 'recurring' => true],
            ],
        ],
        'bordetella' => [
            'label' => 'Bordetella',
            'full_name' => 'Bordetella bronchiseptica',
            'group' => 'lifestyle',
            'condition' => 'boarding',
            'doses' => [
                ['id' => 'bordetella-1', 'label' => 'Bordetella', 'kitten_only' => false, 'from_dob_days' => 56, 'from_prev_days' => null],
                ['id' => 'bordetella-booster', 'label' => 'Bordetella annual booster', 'kitten_only' => false, 'from_dob_days' => null, 'from_prev_days' => 365, 'recurring' => true],
            ],
        ],
        'chlamydophila' => [
            'label' => 'Chlamydophila',
            'full_name' => 'Chlamydophila felis',
            'group' => 'lifestyle',
            'condition' => 'multicat',
            'doses' => [
                ['id' => 'chlamydophila-1', 'label' => 'Chlamydophila', 'kitten_only' => false, 'from_dob_days' => 63, 'from_prev_days' => null],
                ['id' => 'chlamydophila-booster', 'label' => 'Chlamydophila annual booster', 'kitten_only' => false, 'from_dob_days' => null, 'from_prev_days' => 365, 'recurring' => true],
            ],
        ],
    ],

    // AAFP's recommended injection sites, so a reaction lump can be matched
    // to the vaccine that caused it.
    'injection_sites' => [
        ['vaccine' => 'FVRCP', 'site' => 'Right shoulder, above the elbow'],
        ['vaccine' => 'Rabies', 'site' => 'Right hind leg, below the knee'],
        ['vaccine' => 'FeLV', 'site' => 'Left hind leg, below the knee'],
    ],

    'sources' => [
        [
            'name' => '2020 AAFP Feline Vaccination Advisory Panel Report',
            'url' => 'https://catvets.com/guidelines/practice-guidelines/feline-vaccination-guidelines',
            'note' => 'The core vs. lifestyle vaccine split and dosing intervals this tracker is built from.',
        ],
        [
            'name' => 'WSAVA Vaccination Guidelines',
            'url' => 'https://wsava.org/global-guidelines/vaccination-guidelines/',
            'note' => 'International consensus on core and non-core feline vaccines.',
        ],
        [
            'name' => 'Cornell Feline Health Center: vaccinations',
            'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center/health-information/feline-health-topics/vaccinations',
            'note' => 'Background on individual vaccines and what each protects against.',
        ],
        [
            'name' => 'AAFP/VAFSTF Vaccine-Associated Feline Sarcoma Guidelines',
            'url' => 'https://catvets.com/guidelines/practice-guidelines/feline-vaccination-guidelines',
            'note' => 'Recommended injection sites and the 3-2-3 rule for a lump at an injection site.',
        ],
    ],
];

<?php

/*
|--------------------------------------------------------------------------
| Cat breeds
|--------------------------------------------------------------------------
| A static list for the age calculator's breed field, and the seed of the
| breed section that comes later.
|
| `life_expectancy` is the commonly published range in years. Ranges, not
| single numbers, because that is what the published figures are and a cat
| is not an average.
|
| `watch` names the conditions a breed is recognised as predisposed to. It is
| there because it is genuinely useful to an owner and because it is what
| makes this more than a dropdown. It is not a diagnosis and the tool says so
| every time it shows one.
|
| Mixed breed sits first: most cats are, and it is the sensible default.
*/

return [
    ['id' => 'mixed', 'name' => 'Mixed breed or unknown', 'life_expectancy' => [12, 18], 'watch' => null],
    ['id' => 'domestic-shorthair', 'name' => 'Domestic Shorthair', 'life_expectancy' => [12, 18], 'watch' => null],
    ['id' => 'domestic-longhair', 'name' => 'Domestic Longhair', 'life_expectancy' => [12, 18], 'watch' => null],

    ['id' => 'abyssinian', 'name' => 'Abyssinian', 'life_expectancy' => [12, 15], 'watch' => 'Pyruvate kinase deficiency and progressive retinal atrophy.'],
    ['id' => 'american-shorthair', 'name' => 'American Shorthair', 'life_expectancy' => [15, 20], 'watch' => 'Hypertrophic cardiomyopathy.'],
    ['id' => 'bengal', 'name' => 'Bengal', 'life_expectancy' => [12, 16], 'watch' => 'Hypertrophic cardiomyopathy and progressive retinal atrophy.'],
    ['id' => 'birman', 'name' => 'Birman', 'life_expectancy' => [12, 16], 'watch' => 'Hypertrophic cardiomyopathy.'],
    ['id' => 'british-shorthair', 'name' => 'British Shorthair', 'life_expectancy' => [12, 17], 'watch' => 'Hypertrophic cardiomyopathy and polycystic kidney disease.'],
    ['id' => 'burmese', 'name' => 'Burmese', 'life_expectancy' => [16, 18], 'watch' => 'Diabetes mellitus and hypokalaemia.'],
    ['id' => 'devon-rex', 'name' => 'Devon Rex', 'life_expectancy' => [14, 17], 'watch' => 'Patellar luxation and hereditary myopathy.'],
    ['id' => 'exotic-shorthair', 'name' => 'Exotic Shorthair', 'life_expectancy' => [12, 15], 'watch' => 'Flat-faced breathing difficulty and polycystic kidney disease.'],
    ['id' => 'himalayan', 'name' => 'Himalayan', 'life_expectancy' => [12, 15], 'watch' => 'Polycystic kidney disease and flat-faced breathing difficulty.'],
    ['id' => 'maine-coon', 'name' => 'Maine Coon', 'life_expectancy' => [12, 15], 'watch' => 'Hypertrophic cardiomyopathy, hip dysplasia and spinal muscular atrophy.'],
    ['id' => 'manx', 'name' => 'Manx', 'life_expectancy' => [12, 15], 'watch' => 'Manx syndrome, affecting the spine and nerves.'],
    ['id' => 'norwegian-forest', 'name' => 'Norwegian Forest Cat', 'life_expectancy' => [14, 16], 'watch' => 'Glycogen storage disease type IV and hypertrophic cardiomyopathy.'],
    ['id' => 'oriental-shorthair', 'name' => 'Oriental Shorthair', 'life_expectancy' => [12, 15], 'watch' => 'Progressive retinal atrophy and dental disease.'],
    ['id' => 'persian', 'name' => 'Persian', 'life_expectancy' => [12, 17], 'watch' => 'Polycystic kidney disease and flat-faced breathing difficulty.'],
    ['id' => 'ragamuffin', 'name' => 'Ragamuffin', 'life_expectancy' => [12, 16], 'watch' => 'Hypertrophic cardiomyopathy and polycystic kidney disease.'],
    ['id' => 'ragdoll', 'name' => 'Ragdoll', 'life_expectancy' => [12, 17], 'watch' => 'Hypertrophic cardiomyopathy.'],
    ['id' => 'russian-blue', 'name' => 'Russian Blue', 'life_expectancy' => [15, 20], 'watch' => null],
    ['id' => 'savannah', 'name' => 'Savannah', 'life_expectancy' => [12, 20], 'watch' => 'Hypertrophic cardiomyopathy.'],
    ['id' => 'scottish-fold', 'name' => 'Scottish Fold', 'life_expectancy' => [11, 15], 'watch' => 'Osteochondrodysplasia, a painful joint condition linked to the folded ear gene.'],
    ['id' => 'siamese', 'name' => 'Siamese', 'life_expectancy' => [15, 20], 'watch' => 'Progressive retinal atrophy, asthma and amyloidosis.'],
    ['id' => 'siberian', 'name' => 'Siberian', 'life_expectancy' => [12, 18], 'watch' => 'Hypertrophic cardiomyopathy.'],
    ['id' => 'sphynx', 'name' => 'Sphynx', 'life_expectancy' => [13, 15], 'watch' => 'Hypertrophic cardiomyopathy and skin conditions.'],
    ['id' => 'tonkinese', 'name' => 'Tonkinese', 'life_expectancy' => [14, 18], 'watch' => 'Asthma and gingivitis.'],
    ['id' => 'turkish-angora', 'name' => 'Turkish Angora', 'life_expectancy' => [12, 18], 'watch' => 'Deafness in white, blue-eyed cats, and ataxia.'],
];

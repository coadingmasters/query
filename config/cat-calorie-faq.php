<?php

/*
|--------------------------------------------------------------------------
| Cat calorie calculator FAQ
|--------------------------------------------------------------------------
| Rendered into the page and mirrored into FAQPage markup. Google requires
| that markup to describe content the visitor can actually read, so these
| live in the HTML open or shut rather than being injected by a script.
*/

return [
    [
        'q' => 'How many calories should a cat eat per day?',
        'a' => 'Most adult cats need somewhere between 200 and 300 kcal a day, but the honest answer depends on weight, whether they are neutered, and how active they are. A 10 lb neutered adult cat with average activity needs roughly 245 kcal a day; an intact cat the same weight needs closer to 275. The calculator above works it out from your specific cat rather than an average one.',
    ],
    [
        'q' => "How do I calculate my cat's calorie needs?",
        'a' => 'Start with resting energy requirement, or RER: 70 times your cat\'s weight in kilograms raised to the power of 0.75. That is roughly what your cat burns doing nothing at all. Multiply RER by a life-stage factor between 1.2 and 3.0 to get maintenance energy requirement, then adjust for activity level, indoor or outdoor living, and body condition. The calculator on this page does all of that automatically.',
    ],
    [
        'q' => 'Do neutered cats need fewer calories?',
        'a' => 'Yes, noticeably fewer. Neutering lowers a cat\'s metabolic rate, and the standard maintenance multiplier reflects that: about 1.6 times RER for a neutered adult versus 1.8 times RER for an intact one, roughly a 10 to 15% difference. Feeding a neutered cat as though they were still intact is one of the more common, quiet causes of weight gain after the surgery.',
    ],
    [
        'q' => 'How many calories are in a can of cat food?',
        'a' => 'It varies by brand and formula, but a standard 5.5 oz can of wet cat food is typically around 95 to 180 kcal, with many mainstream brands landing near 95 to 110 kcal. The only way to know for certain is the calorie statement on the label, usually printed as kcal per can or kcal per 100 grams. This calculator uses 95 kcal per can as a working average.',
    ],
    [
        'q' => 'How many calories are in a cup of dry cat food?',
        'a' => 'Dry cat food generally runs between 300 and 450 kcal per cup depending on how calorie-dense the formula is, with 350 kcal per cup as a reasonable average for a standard adult maintenance food. Kitten and weight-management formulas can sit well outside that range, so checking the actual bag matters more than any average once you know which food you are feeding.',
    ],
    [
        'q' => 'How many calories does a kitten need per day?',
        'a' => 'A young kitten under 4 months old needs about three times their resting energy requirement, which is a lot relative to their size: a 2 lb kitten needs roughly 145 kcal a day, close to what a 10 lb adult cat needs. From 4 to 12 months that multiplier drops to about twice RER as growth slows. Kitten food is formulated to be more calorie- and protein-dense specifically to meet this.',
    ],
    [
        'q' => 'Can I feed my cat the same amount every day?',
        'a' => 'As a rough routine, yes, but the amount itself should not be fixed forever. A cat\'s needs shift with age, activity, the season, and especially with any change in body condition. The practical approach is to feed a consistent measured amount, then check body condition every few weeks and adjust the total by about 10% up or down if weight is trending the wrong way.',
    ],
    [
        'q' => 'What happens if I underfeed my cat?',
        'a' => 'Short term, an underfed cat becomes more vocal around mealtimes, loses energy, and starts to show ribs and spine more easily than they should. Left uncorrected, weight loss in a cat is never something to wait out: unlike in people, a cat that stops eating enough for even a few days risks a serious liver condition called hepatic lipidosis. Unexplained or persistent weight loss is a reason to see a vet, not just add more food.',
    ],
];

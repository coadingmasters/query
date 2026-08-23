<?php

/*
|--------------------------------------------------------------------------
| Cat weight checker
|--------------------------------------------------------------------------
| The model behind /tools/cat-weight-checker.
|
| The BCS labels and scale match config/cat-calorie.php exactly (5-point:
| Underweight, Lean, Ideal, Overweight, Obese) so a visitor using both
| tools sees one consistent system, not two competing ones.
|
| "deviation" is this tool's own number, distinct from cat-calorie.php's
| "percent" (which adjusts a calorie target, not a weight estimate): it is
| roughly how far current weight sits from ideal at that BCS, drawn from
| the widely-cited veterinary approximation that each point of body
| condition score away from ideal corresponds to about 10% of body weight.
| Presented on the page as a range, not a false-precision single number.
*/

return [

    'bcs' => [
        1 => [
            'label' => 'Underweight',
            'short' => 'Ribs, spine and hip bones are visible from across the room, with no fat cover felt over them. An obvious waist and abdominal tuck are visible from above and the side.',
            'deviation' => 0.22,
        ],
        2 => [
            'label' => 'Lean',
            'short' => 'Ribs are easily felt with only a thin layer over them, and are sometimes visible. A clear waist is visible from above, behind the ribs.',
            'deviation' => 0.09,
        ],
        3 => [
            'label' => 'Ideal',
            'short' => 'Ribs are easily felt with a slight fat cover, not visible. A visible waist is behind the ribs, and a small tucked-up abdomen is present.',
            'deviation' => 0.0,
        ],
        4 => [
            'label' => 'Overweight',
            'short' => 'Ribs are difficult to feel under a moderate fat cover. The waist is barely visible or absent from above, and the abdomen may sag slightly.',
            'deviation' => 0.17,
        ],
        5 => [
            'label' => 'Obese',
            'short' => 'Ribs cannot be felt under a heavy fat cover. There is no waist, and a prominent, rounded abdomen with a heavy fat pad hangs underneath.',
            'deviation' => 0.30,
        ],
    ],

    // Above this age, the tool switches tone: weight LOSS in an older cat
    // is treated as something to raise with a vet rather than manage alone,
    // since it is as often a symptom (kidney disease, hyperthyroidism) as
    // it is straightforward overfeeding — see the senior-cat-care guide.
    'senior_age_years' => 10,

    /*
     | Safe weight-loss rate for cats, not dogs: cats that lose weight too
     | fast are at real risk of hepatic lipidosis, a serious and sometimes
     | fatal liver condition, which is why this stays conservative and every
     | "reduce food" result repeats the same caution.
     */
    'max_safe_loss_percent_per_week' => 0.015,

    'weigh_methods' => [
        [
            'title' => 'The hold-and-subtract method',
            'text' => 'Weigh yourself alone on a bathroom scale, then weigh yourself holding your cat. Subtract the first number from the second. Works with any household scale, though it rounds to whatever precision your scale offers, which matters more for a small cat or kitten.',
        ],
        [
            'title' => 'Carrier or box method',
            'text' => 'Place an empty carrier or box on a kitchen or baby scale and tare it to zero, then put your cat inside. More precise than the hold method, especially for kittens and cats under about 8lb (3.6kg).',
        ],
        [
            'title' => 'Ask your vet\'s office',
            'text' => 'Most veterinary clinics will let you bring a cat in to use their scale for free, no appointment needed. It is the most accurate option, and worth doing at least once to check your home method against.',
        ],
    ],

    'sources' => [
        [
            'name' => 'WSAVA Global Nutrition Committee: Body Condition Score',
            'url' => 'https://wsava.org/global-guidelines/global-nutrition-guidelines/',
            'note' => 'The body condition scoring framework this tool\'s BCS descriptions are drawn from.',
        ],
        [
            'name' => 'Cornell Feline Health Center',
            'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center',
            'note' => 'Background on feline obesity, safe weight loss and hepatic lipidosis risk.',
        ],
        [
            'name' => 'American Association of Feline Practitioners: weight management guidelines',
            'url' => 'https://catvets.com/guidelines/practice-guidelines',
            'note' => 'Clinical guidance on safe rates of weight loss and monitoring in cats.',
        ],
    ],

];

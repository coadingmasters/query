<?php

/*
|--------------------------------------------------------------------------
| Cat pregnancy, week by week
|--------------------------------------------------------------------------
| The nine weeks shown on /tools/cat-pregnancy-calculator.
|
| This lives in PHP and is rendered into the page rather than held in a
| JavaScript object. It is the most substantial writing on the tool, and the
| phrases people search are in it: "cat pregnancy week by week" and
| "when do cats show signs of pregnancy". Content that only exists inside a script
| is content a crawler may never read, so the markup carries it and the
| script only decides which card is highlighted.
|
| Figures are the ones supplied in the brief. Anything that shades towards
| diagnosis points at a vet instead, because a page about a pregnant animal
| is not the place to encourage guessing.
*/

return [
    [
        'week' => 1,
        'title' => 'Fertilization',
        'what_happens' => 'Mating triggers ovulation, and fertilization follows '
            .'within about a day. The fertilized eggs begin travelling down the '
            .'oviducts towards the uterine horns, where they will settle. '
            .'Nothing is detectable yet, by you or by a vet.',
        'visible_signs' => 'None. Your cat looks and behaves exactly as she did before.',
        'care_tips' => [
            'Keep feeding and routine exactly as normal. Nothing needs to change yet.',
            'Note the mating date somewhere. Every estimate from here counts from it.',
        ],
        'vet_action' => null,
    ],
    [
        'week' => 2,
        'title' => 'Implantation',
        'what_happens' => 'The embryos reach the uterine horns and implant in the '
            .'uterine lining, spacing themselves out along it. Cell division is '
            .'rapid but the embryos are still microscopic. A pregnancy cannot be '
            .'confirmed by any method at this stage.',
        'visible_signs' => 'Still none. Any change you notice this week is very '
            .'unlikely to be pregnancy.',
        'care_tips' => [
            'Carry on with her usual food and portions. She does not need extra yet.',
            'Keep her indoors if she is normally allowed out, to avoid a second mating.',
        ],
        'vet_action' => null,
    ],
    [
        'week' => 3,
        'title' => 'Pinking up',
        'what_happens' => 'The placenta forms around day 15, connecting each embryo '
            .'to the uterine wall. By roughly day 21 the embryos are large enough '
            .'to show on an ultrasound as small C-shapes. This is the first week '
            .'anything is visible from the outside.',
        'visible_signs' => 'Pinking up: the nipples redden and become more '
            .'prominent, usually around day 18 to 21. It is subtle, and easiest '
            .'to spot on a cat who has not had a litter before.',
        'care_tips' => [
            'Look for pinking up rather than feeling her belly. Pressing on an early pregnancy can do harm.',
            'Keep her weight steady; there is no need to increase food yet.',
        ],
        'vet_action' => 'An ultrasound from about day 21 can confirm the pregnancy.',
    ],
    [
        'week' => 4,
        'title' => 'Confirmation',
        'what_happens' => 'The kittens are developing quickly and the uterus is '
            .'noticeably enlarged. Hormone levels peak around now, which is why '
            .'this is the week appetite and mood most often change. A vet can '
            .'confirm the pregnancy reliably by ultrasound.',
        'visible_signs' => 'Some cats go off their food or vomit occasionally, '
            .'the feline version of morning sickness. The belly may feel slightly '
            .'firm to a vet palpating it.',
        'care_tips' => [
            'If she is off her food, offer smaller meals more often rather than pushing a full bowl.',
            'Leave palpation to your vet. Untrained hands can damage a pregnancy.',
        ],
        'vet_action' => 'A good week to confirm the pregnancy and get a rough kitten count.',
    ],
    [
        'week' => 5,
        'title' => 'Rapid growth',
        'what_happens' => 'The kittens grow faster this week than at any point so '
            .'far. Claws form, fur begins to develop, and the skeleton starts to '
            .'harden. The uterus expands considerably to accommodate them.',
        'visible_signs' => 'The belly is now clearly rounded and the weight gain '
            .'is obvious. Appetite usually returns and increases.',
        'care_tips' => [
            'Start increasing her food. She needs more calories now, not more meals at the same size.',
            'Discourage jumping from height; her balance and shape are changing.',
        ],
        'vet_action' => null,
    ],
    [
        'week' => 6,
        'title' => 'Movement',
        'what_happens' => 'The kittens measure a little over an inch (3cm) and are recognizably '
            .'cats. Their organs are largely formed and they begin to move. From '
            .'here the remaining growth is mostly size and weight.',
        'visible_signs' => 'You may feel movement if you rest a hand gently on '
            .'her side. She will sleep more, and eat noticeably more.',
        'care_tips' => [
            'Switch to kitten food. It carries the higher protein and calories she needs to feed them.',
            'Feed little and often; a full stomach is uncomfortable with a crowded abdomen.',
        ],
        'vet_action' => null,
    ],
    [
        'week' => 7,
        'title' => 'Nesting begins',
        'what_happens' => 'The kittens reach roughly 125mm and are close to their '
            .'birth proportions. They are gaining weight steadily, and the space '
            .'left in the abdomen is shrinking.',
        'visible_signs' => 'She becomes less active and starts looking for quiet, '
            .'enclosed places: under beds, in closets, at the back of cabinets.',
        'care_tips' => [
            'Set up a nesting box now: a quiet, warm, enclosed spot lined with clean bedding.',
            'Show her the box but do not force it. She will choose, and she may choose elsewhere.',
        ],
        'vet_action' => 'Worth asking your vet what to expect at the birth, and what their out-of-hours number is.',
    ],
    [
        'week' => 8,
        'title' => 'Fully formed',
        'what_happens' => 'The kittens are fully formed and are putting on the last '
            .'of their birth weight. They shift position as they run out of room, '
            .'and movement becomes easy to see from outside.',
        'visible_signs' => 'Milk may appear at the nipples. She may groom her belly '
            .'more, eat less as the kittens crowd her stomach, and become restless.',
        'care_tips' => [
            'Keep the nesting box ready and the house calm. A stressed cat can delay labor.',
            'Have your vet’s emergency number written down somewhere you will find it quickly.',
        ],
        'vet_action' => 'Birth is possible from about day 58 onwards, so be ready from now.',
    ],
    [
        'week' => 9,
        'title' => 'Full term',
        'what_happens' => 'The pregnancy is at term and birth is imminent. The '
            .'kittens move into position, and the whole process can begin with '
            .'very little warning.',
        'visible_signs' => 'Active nesting, restlessness and pacing. Many cats stop '
            .'eating in the final day. A drop in body temperature below 100°F (37.8°C) '
            .'usually means labor within about 24 hours.',
        'care_tips' => [
            'Leave her alone in the nesting box unless something is wrong. Watching too closely can stall labor.',
            'Take her temperature only if she tolerates it easily; a stressed cat is worse off than an unmeasured one.',
        ],
        'vet_action' => 'Call a vet if she strains for more than 20 minutes without producing a kitten, '
            .'if more than two hours pass between kittens, or if anything looks wrong.',
    ],
];

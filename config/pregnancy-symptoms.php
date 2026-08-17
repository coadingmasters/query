<?php

/*
|--------------------------------------------------------------------------
| Symptom-based estimation
|--------------------------------------------------------------------------
| Used when the mating date is unknown. Each symptom has an earliest day it
| normally appears; the latest of those across everything ticked is taken as
| the current day of the pregnancy.
|
| That gives a floor, not a measurement. A cat showing a day-55 sign is at
| least day 55 — she may be further along — so the result is presented as an
| estimate throughout and never as a date to plan around.
|
| min_day is the source of truth. The script reads it off the rendered
| markup, so these values cannot drift out of step with what is displayed.
*/

return [
    [
        'id' => 'pinking',
        'question' => 'Have you noticed nipple pinking or redness?',
        'detail' => 'The nipples look pinker and stand out more than usual.',
        'min_day' => 15,
    ],
    [
        'id' => 'appetite',
        'question' => 'Has her appetite increased noticeably?',
        'detail' => 'She is asking for more food, or finishing what she used to leave.',
        'min_day' => 25,
    ],
    [
        'id' => 'belly',
        'question' => 'Is her belly visibly rounded?',
        'detail' => 'A clear change in shape, not just after a large meal.',
        'min_day' => 30,
    ],
    [
        'id' => 'movement',
        'question' => 'Can you feel or see the kittens moving?',
        'detail' => 'Movement under the skin when she is lying still.',
        'min_day' => 45,
    ],
    [
        'id' => 'nesting',
        'question' => 'Has she started nesting?',
        'detail' => 'Seeking out quiet, enclosed places — cupboards, under beds.',
        'min_day' => 55,
    ],
    [
        'id' => 'milk',
        'question' => 'Have her nipples started leaking milk?',
        'detail' => 'Droplets of milk at the nipple, or damp fur around it.',
        'min_day' => 56,
    ],
    [
        'id' => 'restless',
        'question' => 'Is she alternating between very inactive and restless?',
        'detail' => 'Long stretches of sleep broken by pacing and settling repeatedly.',
        'min_day' => 60,
    ],
];

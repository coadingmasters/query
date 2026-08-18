<?php

/*
|--------------------------------------------------------------------------
| Cat pregnancy FAQ
|--------------------------------------------------------------------------
| Shown on /tools/cat-pregnancy-calculator, and the source for that page's
| FAQPage markup. Rendered into the HTML, not injected, because Google requires FAQ
| structured data to describe content a visitor can read, and content that
| only exists in a script is content a crawler may never see.
|
| These questions are deliberately distinct from the general set on /faq.
| Asking the same thing on two URLs splits the signal between them.
|
| Written to be answered plainly and to stop short of diagnosis. Where an
| answer could change what someone does about a live animal, it says to ask
| a vet rather than encouraging a decision from a web page.
*/

return [
    [
        'id' => 'how-long',
        'q' => 'How long is a cat pregnant?',
        'a' => 'A cat is pregnant for around 63 to 67 days, or roughly nine '
            .'weeks. Sixty-five days is the usual working figure, and breed '
            .'makes a small difference. Siamese queens often deliver a little '
            .'earlier, Maine Coons a little later. Anything from day 58 onwards '
            .'can produce healthy kittens, but a pregnancy running past day 70 '
            .'should be discussed with your vet.',
    ],
    [
        'id' => 'early-signs',
        'q' => 'What are the early signs of cat pregnancy?',
        'a' => 'The first reliable sign is pinking up, usually around day 15 to '
            .'21, when the nipples become pinker and more prominent. Some cats '
            .'go off their food or vomit occasionally in the third and fourth '
            .'weeks, much like morning sickness. A rounded belly and steady '
            .'weight gain follow from about week five. Before day 15 there is '
            .'usually nothing to see at all.',
    ],
    [
        'id' => 'vet-visit',
        'q' => 'When should I take my pregnant cat to the vet?',
        'a' => 'Book a visit as soon as you suspect she is pregnant. From about '
            .'day 21 an ultrasound can confirm it, and by day 40 an x-ray can '
            .'count the skeletons, which is genuinely useful. Knowing how many '
            .'kittens to expect tells you when the birth is finished. Go sooner '
            .'if she is vomiting repeatedly, losing weight, bleeding, or seems '
            .'unwell in any way.',
    ],
    [
        'id' => 'feeding',
        'q' => 'What should I feed my pregnant cat?',
        'a' => 'Keep her on her normal food for the first month, then move her '
            .'onto kitten food from around week five. Kitten food carries the '
            .'higher protein, calories and calcium she needs while the kittens '
            .'grow fastest. Her intake will rise by roughly a quarter to a half '
            .'by late pregnancy, so feed smaller meals more often as the '
            .'kittens crowd her stomach. Avoid supplements unless your vet has '
            .'asked for them.',
    ],
    [
        'id' => 'litter-size',
        'q' => 'How many kittens can a cat have?',
        'a' => 'A typical litter is four to six kittens, though anything from '
            .'one to eight is common. First-time queens and older cats tend '
            .'towards smaller litters, and larger breeds towards bigger ones. '
            .'An x-ray after day 40 is the only reliable way to know the number '
            .'in advance, and it is worth doing so you know whether she has '
            .'finished.',
    ],
    [
        'id' => 'pinking-up',
        'q' => 'What is “pinking up” in cats?',
        'a' => 'Pinking up is the change in a queen’s nipples during early '
            .'pregnancy: they become pinker, slightly enlarged and easier to '
            .'see against the fur. It usually happens between day 15 and 21 and '
            .'is the earliest visible sign of pregnancy. It is easiest to spot '
            .'on a cat who has not had a litter before. On its own it is '
            .'suggestive rather than conclusive. A veterinarian can confirm.',
    ],
    [
        'id' => 'spay',
        'q' => 'When is it too late to spay a pregnant cat?',
        'a' => 'A pregnant cat can be spayed, and it is a routine decision '
            .'vets discuss often, but it becomes more complex and carries more '
            .'risk as the pregnancy advances. Many vets are reluctant in the '
            .'final third, when the kittens are viable. If you are considering '
            .'it, speak to your vet right away rather than waiting, because the '
            .'options narrow with every week.',
    ],
    [
        'id' => 'labor-signs',
        'q' => 'What are the signs of labor in cats?',
        'a' => 'A drop in body temperature below about 100°F (37.8°C) usually means '
            .'labor within twenty-four hours. In the hours beforehand she will '
            .'often stop eating, become restless, pace, and settle repeatedly '
            .'into her nesting spot. Visible contractions and straining follow. '
            .'Call your vet if she strains hard for more than twenty minutes '
            .'without producing a kitten, or if more than two hours pass '
            .'between kittens.',
    ],
    [
        'id' => 'accuracy',
        'q' => 'Is this calculator accurate?',
        'a' => 'With a known mating date it is as accurate as the biology '
            .'allows. The due date is arithmetic, but birth naturally falls '
            .'within a window of a few days either side, which is why we show a '
            .'range rather than a single day. The symptom-based estimate is '
            .'rougher, around plus or minus five days, because signs tell you '
            .'the earliest she can be rather than exactly where she is. Neither '
            .'replaces an ultrasound.',
    ],
];

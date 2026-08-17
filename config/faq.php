<?php

/*
|--------------------------------------------------------------------------
| FAQ
|--------------------------------------------------------------------------
| Questions and answers for /faq.
|
| These are deliberately different questions from the "can cats eat X" set
| on the home page. Repeating those here would put the same answer on two
| URLs and leave Google to pick which one matters, which is a fight you lose
| against yourself.
|
| Answers are general information. Anything that edges towards diagnosis or
| dosage says to see a vet instead of guessing, because that is the honest
| answer and because health content that pretends otherwise is exactly what
| gets a site treated as untrustworthy.
*/

return [

    'groups' => [

        [
            'id' => 'using-purrquery',
            'title' => 'Using PurrQuery',
            'blurb' => 'What the site is, what it costs, and how far to trust it.',
            'items' => [
                [
                    'q' => 'Is PurrQuery really free?',
                    'a' => 'Yes, entirely. Every tool and every guide is free to use, with no account, no trial and no payment. There is no premium tier holding the useful part back.',
                ],
                [
                    'q' => 'Do I need to create an account?',
                    'a' => 'No. Nothing on the site is behind a login. You can use every calculator and read every guide without telling us who you are — the only information we hold is what you actively send us through a form.',
                ],
                [
                    'q' => 'Where does your information come from?',
                    'a' => 'Guides are written from published veterinary sources and established feeding guidance rather than from opinion. Where the evidence is thin or genuinely disputed, we say so rather than picking whichever answer sounds tidier.',
                ],
                [
                    'q' => 'Are your tools a substitute for a vet?',
                    'a' => 'No, and they are not meant to be. The calculators give a general estimate from the numbers you enter. A vet can weigh your cat, examine it and account for the things a form cannot know. Use the tools to get oriented, not to diagnose.',
                ],
                [
                    'q' => 'How do you make money if everything is free?',
                    'a' => 'At the moment we do not. The plan is advertising and affiliate links in future, disclosed clearly where they appear. What we will not do is let a commercial arrangement change what a guide says.',
                ],
            ],
        ],

        [
            'id' => 'feeding',
            'title' => 'Feeding and diet',
            'blurb' => 'How much, how often, and what to look at on the label.',
            'items' => [
                [
                    'q' => 'How much should I feed my cat each day?',
                    'a' => 'It depends on weight, age, whether your cat is neutered and how active it is — which is why the number on the packet is only ever a starting point. As a rough guide, an average adult indoor cat needs somewhere around 200 to 250 calories a day, but the honest answer is to work it out from your own cat and check against body condition every few weeks.',
                ],
                [
                    'q' => 'How many times a day should a cat eat?',
                    'a' => 'Most adult cats do well on two measured meals a day. Kittens need more frequent, smaller meals because they cannot hold much at once. Cats are natural grazers, so some owners split the daily amount across more meals — the total across the day matters far more than how it is divided.',
                ],
                [
                    'q' => 'Is wet food better than dry food?',
                    'a' => 'Neither is simply better. Wet food carries far more moisture, which matters because cats have a weak thirst drive and often do not drink enough. Dry food is cheaper, keeps better and is easier to measure. Many owners feed both. What matters most is that it is complete and balanced for your cat’s life stage.',
                ],
                [
                    'q' => 'How do I switch my cat to a new food?',
                    'a' => 'Slowly. Change it over about a week, starting with roughly a quarter new food to three quarters old, and shifting the ratio every couple of days. A sudden switch commonly causes an upset stomach, and it also makes cats suspicious of the new food.',
                ],
                [
                    'q' => 'My cat begs constantly — is it actually hungry?',
                    'a' => 'Sometimes, but food-seeking is often boredom or learned behaviour: it worked once, so it gets repeated. Check the daily amount is right for your cat first. If the portions are correct and the weight is healthy, more play and activity usually helps more than more food.',
                ],
                [
                    'q' => 'Do cats need treats?',
                    'a' => 'No, but they are useful for training and bonding. The usual guidance is to keep treats under about a tenth of daily calories, and to subtract them from meals rather than adding them on top — treats are the most common quiet cause of weight gain.',
                ],
            ],
        ],

        [
            'id' => 'health',
            'title' => 'Health and vet care',
            'blurb' => 'What is normal, what is not, and when it cannot wait.',
            'items' => [
                [
                    'q' => 'How often should a cat see a vet?',
                    'a' => 'A healthy adult cat is generally seen once a year. Kittens need a course of visits in their first months, and cats over about seven are often checked twice a year because problems develop faster and are easier to catch early.',
                ],
                [
                    'q' => 'What are the early signs a cat is unwell?',
                    'a' => 'Cats hide illness well, so the first signs are usually changes rather than symptoms: eating or drinking noticeably more or less, hiding, grooming less or over-grooming one spot, litter tray changes, or simply being less interested in things it normally likes. A change that lasts more than a day or two is worth a call.',
                ],
                [
                    'q' => 'When is it an emergency?',
                    'a' => 'Straining in the litter tray without producing urine, difficulty breathing, collapse, repeated vomiting, a suspected poisoning, or an obvious injury all need a vet immediately rather than in the morning. A male cat unable to urinate is a genuine emergency and hours matter.',
                ],
                [
                    'q' => 'Which vaccinations does a cat need?',
                    'a' => 'Core vaccinations typically cover cat flu, feline infectious enteritis and, depending on where you are, rabies. Outdoor cats are often also vaccinated against feline leukaemia. Exactly which and how often varies by country and by your cat’s circumstances, so the schedule comes from your vet.',
                ],
                [
                    'q' => 'Do indoor cats still need parasite treatment?',
                    'a' => 'Usually yes, though often less frequently. Fleas arrive on clothing and on other pets, and some worms are picked up from things that come indoors. Your vet will set an interval that fits how your cat actually lives.',
                ],
                [
                    'q' => 'Should I brush my cat’s teeth?',
                    'a' => 'If your cat will tolerate it, yes — dental disease is very common and painful, and it is easier to prevent than to treat. Use toothpaste made for cats, never human toothpaste, and build up slowly. Bad breath is worth mentioning to your vet rather than accepting as normal.',
                ],
            ],
        ],

        [
            'id' => 'age-and-weight',
            'title' => 'Age, weight and growth',
            'blurb' => 'Reading your cat’s age and condition without guesswork.',
            'items' => [
                [
                    'q' => 'How do I convert my cat’s age to human years?',
                    'a' => 'Not by multiplying by seven — cats age quickly at first and then level off. A one-year-old cat is roughly a fifteen-year-old person, a two-year-old is about twenty-four, and after that each cat year adds roughly four human years.',
                ],
                [
                    'q' => 'When is a cat considered senior?',
                    'a' => 'Around eleven, though many vets start senior monitoring at about seven, when age-related conditions become worth screening for. Plenty of cats live well into their late teens.',
                ],
                [
                    'q' => 'How do I tell if my cat is overweight?',
                    'a' => 'Feel rather than look. You should be able to feel the ribs easily under a thin layer of fat, without pressing. Seen from above there should be a visible waist behind the ribs, and from the side the belly should tuck up rather than hang. If the ribs are hard to find, that is the sign.',
                ],
                [
                    'q' => 'My cat is losing weight without me changing anything. Why?',
                    'a' => 'Unexplained weight loss is one of the more important signs in cats and has several possible causes, some of them serious and several of them very treatable when caught early. It is a reason to book an appointment rather than to watch and wait.',
                ],
                [
                    'q' => 'When do kittens stop growing?',
                    'a' => 'Most cats reach adult size somewhere between nine and twelve months, though they fill out for a while after that. Larger breeds such as Maine Coons keep growing considerably longer, sometimes to three or four years.',
                ],
            ],
        ],

        [
            'id' => 'behaviour',
            'title' => 'Behaviour and home life',
            'blurb' => 'The everyday things that are easier to fix than they look.',
            'items' => [
                [
                    'q' => 'Why does my cat ignore the litter tray?',
                    'a' => 'Litter tray problems are usually about the tray, not defiance: too dirty, too small, wrong litter, sited somewhere busy, or too few trays in a multi-cat home — the usual rule is one per cat plus one. Rule out a medical cause first, because urinary problems present the same way and need treating quickly.',
                ],
                [
                    'q' => 'How do I stop my cat scratching the furniture?',
                    'a' => 'Scratching is not optional behaviour — it maintains claws and marks territory — so the aim is to redirect it. Put a sturdy post next to the spot being scratched, make sure it is tall enough for a full stretch, and reward using it. Declawing is an amputation and is banned in many countries.',
                ],
                [
                    'q' => 'Should my cat go outside?',
                    'a' => 'It depends more on where you live than on the cat. Outdoor access brings exercise and stimulation, alongside traffic, disease and fights. Indoor cats live longer on average but need more from you: vertical space, play, and things to investigate. Neither choice is automatically the right one.',
                ],
                [
                    'q' => 'How long can I leave my cat alone?',
                    'a' => 'A healthy adult cat is generally fine alone for a working day with food, water and a clean tray. Beyond about twenty-four hours someone should be checking in — not only for feeding, but because a cat that becomes unwell alone can deteriorate quickly.',
                ],
            ],
        ],
    ],
];

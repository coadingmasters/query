<?php

/*
|--------------------------------------------------------------------------
| Blog
|--------------------------------------------------------------------------
| Metadata for each article. The body lives in its own Blade view under
| resources/views/blog/posts, because long-form writing wants real markup for
| callouts, figures and links, and a PHP string array is a poor place for it.
|
| `related_tools` and `related_posts` are what wire the site together. Every
| article points at the tools that answer the same question and at its
| neighbours, and the tools point back. A page nobody can reach from anywhere
| else is a page Google treats as an orphan.
*/

return [

    'why-do-cats-knead' => [
        'slug' => 'why-do-cats-knead',
        'title' => 'Why Do Cats Knead? Making Biscuits Explained',

        // Under 60 characters so Google shows it whole. Carries both phrases
        // people search with, because they are the same question.
        'meta_title' => 'Why Do Cats Knead? Making Biscuits Explained',

        // 153 characters. Google truncates a snippet around 155-160, so this
        // is written to display in full rather than getting cut mid-word.
        'excerpt' => 'Kneading, or making biscuits, is a comfort behavior cats keep '
            .'from kittenhood. What it means when they knead you, a blanket, at '
            .'night, or with claws out.',

        'category' => 'Behavior',
        'minutes' => 8,
        'published' => '2026-08-18',
        'updated' => '2026-08-18',

        'image' => 'why-do-cats-knead-hero',
        'alt' => 'Tabby cat kneading a soft cream blanket with both front paws',

        /*
         | The answer Google lifts for a featured snippet, and the first thing
         | a reader sees. Kept under 60 words on purpose.
         */
        'answer' => 'Cats knead because it is a comfort behavior left over from '
            .'kittenhood, when they pressed against their mother to start milk '
            .'flowing. Adult cats keep doing it when they feel safe and content. '
            .'Kneading also leaves scent from glands in their paws, which marks '
            .'you or their bed as familiar territory.',

        'faq' => [
            [
                'q' => 'Why do cats make biscuits?',
                'a' => 'Making biscuits is the same behavior as kneading, named after the way the paws push like a baker working dough. Kittens do it to stimulate milk from their mother, and adult cats keep it as a self-soothing habit tied to feeling safe.',
            ],
            [
                'q' => 'Why does my cat knead me and not anyone else?',
                'a' => 'Because you are the person they associate with safety. Kneading is a vulnerable, relaxed behavior, and cats do it where they feel least on guard. Scent marking plays a part too: the glands in their paw pads leave your smell mixed with theirs.',
            ],
            [
                'q' => 'Why does my cat knead with claws out?',
                'a' => 'It is not aggression. Claws extend and retract naturally as the paw flexes, and most cats are not aware they are digging in. Keep nails trimmed and put a folded blanket on your lap rather than pushing your cat away, which teaches them that affection ends badly.',
            ],
            [
                'q' => 'Why do cats knead blankets?',
                'a' => 'Soft fabric feels similar to a mother cat, so blankets, cushions and jumpers all trigger the same response. Cats often pair it with purring, drooling or suckling the fabric, which are all part of the same comfort behavior.',
            ],
            [
                'q' => 'Why do cats knead at night?',
                'a' => 'Evenings are usually when the house is quiet and the cat settles down with you, which is exactly the state that brings the behavior out. Cats are also crepuscular, so a burst of activity followed by settling in is a normal rhythm rather than a problem.',
            ],
            [
                'q' => 'Should I stop my cat from kneading?',
                'a' => 'No. It is a normal, healthy sign of contentment. If the claws hurt, manage the claws rather than the behavior: trim them regularly and use a barrier. Stopping a cat mid-knead teaches nothing except that settling on you is unwelcome.',
            ],
            [
                'q' => 'Is kneading ever a sign of a problem?',
                'a' => 'Rarely. Kneading itself is normal at any age. What is worth a vet visit is a sudden change in any behavior alongside other signs: hiding, appetite changes, over-grooming the same spot, or vocalising when touched. The kneading is not the symptom, the change is.',
            ],
            [
                'q' => 'Do all cats knead?',
                'a' => 'No, and a cat that never kneads is not unhappy or unbonded. It is one of several ways cats show contentment, alongside slow blinking, head-butting, purring and simply choosing to sit near you.',
            ],
        ],

        'sources' => [
            [
                'name' => 'Cornell Feline Health Center',
                'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center',
                'note' => 'Background on normal feline behavior and body language.',
            ],
            [
                'name' => 'ASPCA: cat behavior',
                'url' => 'https://www.aspca.org/pet-care/cat-care/common-cat-behavior-issues',
                'note' => 'Guidance on which behaviors are normal and which need attention.',
            ],
            [
                'name' => 'International Cat Care',
                'url' => 'https://icatcare.org/articles/',
                'note' => 'Reference material on kitten development and adult behavior.',
            ],
        ],

        // Wiring. Tools that answer a related question, and where to go next.
        'related_tools' => ['cat-age-calculator', 'cat-pregnancy-calculator'],
        'related_posts' => ['signs-your-cat-is-sick', 'new-cat-owner-guide'],
    ],
    'why-is-my-cat-sneezing' => [
        'slug' => 'why-is-my-cat-sneezing',
        'title' => 'Cat Sneezing: When It\'s Normal and When to Worry',

        'meta_title' => 'Cat Sneezing: When It\'s Normal and When to Worry',

        'excerpt' => 'An occasional sneeze is nothing. Runny eyes, a stuffy nose or '
            .'sneezing that will not stop points at an infection that is worth a '
            .'vet visit.',

        'category' => 'Health',
        'minutes' => 9,
        'published' => '2026-08-20',
        'updated' => '2026-08-20',

        'image' => 'why-is-my-cat-sneezing-hero',
        'alt' => 'Tabby cat mid-sneeze on a soft blanket at home',

        'answer' => 'A single sneeze is usually just dust or a tickle, the same as '
            .'it is for a person. Sneezing that repeats, or comes with a runny '
            .'nose, watery eyes or discharge, is more often an upper respiratory '
            .'infection. It needs a vet if it lasts more than a couple of days, '
            .'or your cat stops eating.',

        'faq' => [
            [
                'q' => 'Is it normal for a cat to sneeze?',
                'a' => 'Yes, occasionally. Dust, a strong smell or a change in the air can trigger a single sneeze, just as it does in a person, and it means nothing on its own. What is not normal is sneezing that repeats through the day, or that keeps happening over more than a day or two.',
            ],
            [
                'q' => 'Why does my cat keep sneezing?',
                'a' => 'Repeated sneezing over several hours or days is usually either an ongoing irritant, such as dusty litter, a scented candle or cleaning spray, or an upper respiratory infection. Cats carry feline herpesvirus and calicivirus at very high rates, and both cause cold-like symptoms that include sneezing.',
            ],
            [
                'q' => 'Why is my cat sneezing but acting completely normal?',
                'a' => 'A cat that is eating, drinking and playing normally alongside a mild sneeze is usually dealing with a minor irritant or the early days of a cold. Keep watching rather than worrying: it is the cats that stop eating, hide, or develop discharge that need a same-day vet visit.',
            ],
            [
                'q' => 'What does it mean if my cat is sneezing and has a runny nose?',
                'a' => 'Sneezing paired with nasal discharge is the clearest sign of an upper respiratory infection. Clear discharge is more consistent with a viral cause; thick yellow or green discharge suggests a secondary bacterial infection has set in, which usually needs antibiotics from a vet rather than time alone.',
            ],
            [
                'q' => 'Why is my kitten sneezing so much?',
                'a' => 'Kittens catch upper respiratory infections easily, especially if they came from a shelter or a large litter, and their symptoms tend to hit harder than an adult cat\'s. A kitten that stops eating is a more urgent case than an adult with the same symptoms, because kittens dehydrate and lose weight fast.',
            ],
            [
                'q' => 'What causes a cat to sneeze?',
                'a' => 'The common causes are, roughly in order of likelihood: viral upper respiratory infection, environmental irritants such as dust or fragrance, allergies, a foreign body caught in the nose, dental disease affecting the roots near the nasal passage, and, less commonly, nasal polyps or a fungal infection in older cats.',
            ],
            [
                'q' => 'When should I take my sneezing cat to the vet?',
                'a' => 'Book a visit if sneezing lasts more than two or three days, if there is any blood, if discharge turns yellow or green, if your cat stops eating or seems lethargic, or if only one nostril is affected, which can point at something physically stuck rather than an infection.',
            ],
            [
                'q' => 'Can I give my cat anything at home for sneezing?',
                'a' => 'A humidifier or a few minutes in a steamy bathroom can loosen congestion, and gently wiping discharge away keeps your cat more comfortable. There is no over-the-counter medication that is safe to give a cat without a vet\'s direction; several common human cold remedies are toxic to cats.',
            ],
        ],

        'sources' => [
            [
                'name' => 'Cornell Feline Health Center: upper respiratory infections',
                'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center',
                'note' => 'Background on feline herpesvirus and calicivirus, the most common causes of sneezing.',
            ],
            [
                'name' => 'ASPCA: cat health and wellness',
                'url' => 'https://www.aspca.org/pet-care/cat-care',
                'note' => 'General guidance on recognising illness in cats.',
            ],
            [
                'name' => 'VCA Animal Hospitals: sneezing in cats',
                'url' => 'https://vcahospitals.com/know-your-pet/sneezing-in-cats',
                'note' => 'Clinical overview of causes and when sneezing needs veterinary attention.',
            ],
        ],

        'related_tools' => ['cat-age-calculator'],
        'related_posts' => ['why-do-cats-knead', 'signs-your-cat-is-sick'],
    ],

];

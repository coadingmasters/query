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
];

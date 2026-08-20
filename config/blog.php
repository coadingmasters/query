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
        'title' => 'Why Is My Cat Sneezing? When It\'s Normal and When to Worry',

        'meta_title' => 'Why Is My Cat Sneezing? When It\'s Normal and When to Worry',

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
                'a' => 'Sneezing paired with nasal discharge is one of the clearest signs of an upper respiratory infection. Clear discharge tends to go with the early, viral stage; thick yellow or green discharge can mean a secondary bacterial infection has joined it. Color alone cannot confirm that on its own, and it is not something to diagnose from home: a vet examines the cat and decides whether antibiotics are actually needed.',
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
                'note' => 'General guidance on recognizing illness in cats.',
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

    'signs-your-cat-is-sick' => [
        'slug' => 'signs-your-cat-is-sick',
        'title' => 'How to Tell If Your Cat Is Sick: 12 Signs to Watch For',

        'meta_title' => 'How to Tell If Your Cat Is Sick: 12 Signs to Watch For',

        'excerpt' => 'Cats hide illness well. Appetite loss, hiding, low energy and '
            .'litter box changes are usually the first signs, before anything '
            .'looks seriously wrong.',

        'category' => 'Health',
        'minutes' => 10,
        'published' => '2026-08-20',
        'updated' => '2026-08-20',

        'image' => 'signs-cat-is-sick-hero',
        'alt' => 'Tabby cat resting quietly, looking subdued',

        'answer' => 'Cats hide illness as a survival instinct, so the first signs '
            .'are usually subtle: eating less, hiding more, seeming low on '
            .'energy, or a change in litter box habits. One sign on its own is '
            .'often nothing. Two or more together, or anything that lasts more '
            .'than a day, is worth a call to the vet.',

        'faq' => [
            [
                'q' => 'How can I tell if my cat is sick if they seem fine otherwise?',
                'a' => 'Watch behavior more than mood. A cat can still purr and greet you while eating less than usual or sleeping in a different spot than normal. The reliable signals are changes from your own cat\'s normal pattern: appetite, energy, litter box habits, and how they hold themselves.',
            ],
            [
                'q' => 'Why do cats hide when they are sick?',
                'a' => 'It is an instinct left over from being both predator and prey in the wild. A visibly weak animal is a target, so cats default to hiding discomfort rather than showing it. That is also why a cat who does let you see them struggling is often further along than a first-time sign.',
            ],
            [
                'q' => 'What is the most common early sign of illness in cats?',
                'a' => 'A change in appetite, either eating less or stopping altogether, is usually the first thing owners notice, followed by hiding more than usual. Neither one confirms anything specific on its own; they are what prompt a closer look at everything else.',
            ],
            [
                'q' => 'How long should I wait before taking my cat to the vet?',
                'a' => 'For a single mild sign in a cat who is otherwise acting normally, a day of closer watching is reasonable. For anything severe, such as repeated vomiting, labored breathing, or a cat who will not move, same-day care is the right call. Not eating for more than 24 hours is also a reason to call, especially in a cat who is already thin.',
            ],
            [
                'q' => 'Can indoor cats get sick even if they never go outside?',
                'a' => 'Yes. Indoor cats are protected from traffic, fights and many infectious diseases, but they still develop kidney disease, diabetes, dental disease, urinary blockages and cancer at similar rates to cats that go outdoors. Being indoors lowers risk, it does not remove it.',
            ],
            [
                'q' => 'Is it normal for an older cat to slow down?',
                'a' => 'Some slowing down is normal with age, but it should be gradual, not sudden. A senior cat who stops jumping onto a favorite spot within a week, rather than over months, is showing a change worth mentioning to a vet rather than aging alone.',
            ],
            [
                'q' => 'What should I tell the vet when I call about a sick cat?',
                'a' => 'What changed, and when. Vets work from a comparison to your cat\'s normal, so \'stopped eating yesterday morning\' or \'has been hiding under the bed for two days\' is more useful than \'acting sick\'. Mention every change you have noticed, even ones that seem unrelated.',
            ],
            [
                'q' => 'When is a sick cat an emergency?',
                'a' => 'Straining in the litter box with little or no urine, labored or open-mouthed breathing, collapse, repeated vomiting, or a cat who will not respond to you are all same-hour emergencies, not same-day. A blocked bladder in particular can become fatal within a day if untreated.',
            ],
        ],

        'sources' => [
            [
                'name' => 'Cornell Feline Health Center',
                'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center',
                'note' => 'Background on recognizing illness and common feline diseases.',
            ],
            [
                'name' => 'American Association of Feline Practitioners: feline life stage guidelines',
                'url' => 'https://catvets.com/guidelines/practice-guidelines/life-stage-guidelines',
                'note' => 'Guidance on the health checks and warning signs relevant to each life stage.',
            ],
            [
                'name' => 'ASPCA: cat health and wellness',
                'url' => 'https://www.aspca.org/pet-care/cat-care',
                'note' => 'General guidance on recognizing illness in cats.',
            ],
            [
                'name' => 'VCA Animal Hospitals: signs of illness in cats',
                'url' => 'https://vcahospitals.com/know-your-pet/recognizing-signs-of-illness-in-cats',
                'note' => 'Clinical overview of common warning signs and when they need attention.',
            ],
        ],

        'related_tools' => ['cat-age-calculator'],
        'related_posts' => ['why-is-my-cat-sneezing', 'why-do-cats-knead'],
    ],

    'how-much-should-i-feed-my-cat' => [
        'slug' => 'how-much-should-i-feed-my-cat',
        'title' => 'How Much Should I Feed My Cat? A Feeding Guide',

        'meta_title' => 'How Much Should I Feed My Cat? A Feeding Guide',

        'excerpt' => 'Portion guidance on the bag is written for an average cat '
            .'that does not exist. Work out how much to feed yours from '
            .'weight, food type and life stage.',

        'category' => 'Feeding',
        'minutes' => 10,
        'published' => '2026-08-20',
        'updated' => '2026-08-20',

        'image' => 'how-much-to-feed-cat-hero',
        'alt' => 'Cat sitting beside a bowl being filled with a measured scoop of kibble',

        'answer' => 'Most cats need roughly 20 calories per pound of body '
            .'weight a day, adjusted for activity, age and whether they are '
            .'neutered. Rather than trust the amount on the bag, work it out '
            .'from your own cat\'s weight and check their body condition '
            .'every few weeks, since that tells you more than any formula.',

        'faq' => [
            [
                'q' => 'Why is the feeding guide on the bag not accurate?',
                'a' => 'It is written for an average cat at an average activity level, and your cat is not an average. It is also written by the brand selling the food, and a generous guide sells more of it. Treat the number on the bag as a starting point to weigh against your cat\'s actual body condition, not a target.',
            ],
            [
                'q' => 'How many calories does my cat actually need?',
                'a' => 'A common starting point is around 20 calories per pound of body weight for an average neutered adult indoor cat, though the more precise version vets use is a formula based on weight, adjusted up for kittens and pregnant or nursing cats, and down for weight loss. It is a starting point either way, not a fixed number.',
            ],
            [
                'q' => 'Should I feed my cat wet food or dry food?',
                'a' => 'Both can be nutritionally complete; they are not interchangeable cup for cup. Wet food is roughly 75 to 80 percent water, dry food closer to 10 percent, so the same volume is not the same number of calories. What matters is total calories and a complete, balanced formula, not which texture you choose.',
            ],
            [
                'q' => 'How many times a day should I feed my cat?',
                'a' => 'Two measured meals a day is the most commonly recommended pattern, and it tends to prevent the weight gain that free-feeding dry food, available all day, often causes. Kittens need more frequent, smaller meals; some adult cats do fine on three or four if that suits your schedule better.',
            ],
            [
                'q' => 'My cat is overweight. How do I safely feed them less?',
                'a' => 'Gradually, and ideally with a vet involved. Cutting calories too fast in an overweight cat can cause a serious liver condition called hepatic lipidosis if the cat does not eat enough during the transition. A vet can set a target weight and a safe rate of loss, which is usually only around one to two percent of body weight a week.',
            ],
            [
                'q' => 'How do I know if I am feeding the right amount?',
                'a' => 'Body condition, not the scale alone. You should be able to feel your cat\'s ribs under a light layer of fat without pressing hard, and see a waist when looking down at them. Weigh your cat every few weeks: gradual, unexplained weight change in either direction is the signal to adjust the amount or see a vet.',
            ],
            [
                'q' => 'Are treats okay, and how many can I give?',
                'a' => 'Yes, in moderation. The common guidance is to keep treats under about ten percent of daily calories and subtract them from the day\'s food rather than adding them on top. Treats are one of the most common quiet causes of slow, unnoticed weight gain.',
            ],
            [
                'q' => 'Does a kitten need to eat differently from an adult cat?',
                'a' => 'Yes, meaningfully. Kittens need more calories per pound than adults because they are growing, along with a kitten-specific formula higher in protein and certain nutrients, and more frequent small meals rather than two large ones. Most cats can transition to adult food around twelve months.',
            ],
        ],

        'sources' => [
            [
                'name' => 'Cornell Feline Health Center: feeding your cat',
                'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center/health-information/feeding',
                'note' => 'Guidance on how much and how often to feed a cat, and what a complete diet needs to provide.',
            ],
            [
                'name' => 'WSAVA Global Nutrition Guidelines',
                'url' => 'https://wsava.org/global-guidelines/global-nutrition-guidelines/',
                'note' => 'The nutritional assessment framework, including body condition scoring, that vets use to build a feeding plan.',
            ],
            [
                'name' => 'ASPCA: cat health and wellness',
                'url' => 'https://www.aspca.org/pet-care/cat-care',
                'note' => 'General guidance on feeding, weight and recognizing when something is off.',
            ],
        ],

        'related_tools' => ['cat-age-calculator'],
        'related_posts' => ['signs-your-cat-is-sick', 'why-do-cats-knead'],
    ],

];

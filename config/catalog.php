<?php

/*
|--------------------------------------------------------------------------
| Catalogue
|--------------------------------------------------------------------------
| The tools, food guides and articles shown on the home page. Keeping them
| here means the markup is a loop rather than twenty near-identical blocks,
| and adding a tool is one array entry.
|
| This moves into the database once entries are edited rather than authored.
| The shape below is deliberately the shape those tables will have.
*/

return [

    'tools' => [
        [
            'slug' => 'cat-pregnancy-calculator',
            'url' => '/tools/cat-pregnancy-calculator',
            'title' => 'Cat Pregnancy Calculator',
            'blurb' => 'Work out your cat’s due date and follow the pregnancy week by week.',
            'image' => 'cat-pregnancy-calculator-kitten',
            'alt' => 'Newborn kitten curled up asleep',
        ],
        [
            'slug' => 'cat-age-calculator',
            'url' => '/tools/cat-age-calculator',
            'title' => 'Cat Age Calculator',
            'blurb' => 'Turn your cat’s age into human years, using the life-stage curve vets actually use.',
            'image' => 'cat-age-calculator-senior-tabby-cat',
            'alt' => 'Cats at five life stages from kitten to senior',
        ],
        [
            'slug' => 'cat-calorie-calculator',
            'url' => '/tools/cat-calorie-calculator',
            'title' => 'Cat Calorie Calculator',
            'blurb' => 'Work out how much to feed each day from weight, life stage, activity and body condition.',
            'image' => 'cat-calorie-calculator-cat-food-bowl',
            'alt' => 'Cat beside a bowl of dry food',
        ],
        [
            'slug' => 'cat-weight-checker',
            'title' => 'Weight Checker',
            'blurb' => 'Find out whether your cat is underweight, ideal or carrying too much.',
            'image' => 'cat-weight-checker-cat-on-scale',
            'alt' => 'Fluffy cat sitting on a digital pet scale',
        ],
        [
            'slug' => 'vaccination-tracker',
            'title' => 'Vaccination Tracker',
            'blurb' => 'Build a shot schedule from your cat’s birth date and keep every booster on time.',
            'image' => 'cat-vaccination-tracker-vet-examination',
            'alt' => 'Veterinarian examining a calm tabby cat',
        ],
        [
            'slug' => 'cat-breed-quiz',
            'title' => 'Breed Quiz',
            'blurb' => 'Answer a few questions about coat, build and temperament to narrow down the breed.',
            'image' => 'cat-breed-quiz-multiple-cat-breeds',
            'alt' => 'Persian, Siamese and Maine Coon cats side by side',
        ],
        [
            'slug' => 'cat-name-generator',
            'title' => 'Name Generator',
            'blurb' => 'Thousands of names filtered by style, origin and how they sound when called.',
            'image' => 'cat-name-generator-cute-kitten',
            'alt' => 'Fluffy kitten beside a board of name ideas',
        ],
    ],

    /*
     | verdict drives the badge color on each card: safe, caution or unsafe.
     | Showing the answer on the card itself is the point. Someone worried
     | about what their cat just ate gets it without a second click.
     */
    'foods' => [
        [
            'slug' => 'fruits',
            'title' => 'Fruits',
            'question' => 'Can cats eat fruit?',
            'answer' => 'Small amounts of melon, blueberries or apple flesh are fine. Never grapes or raisins.',
            'verdict' => 'caution',
            'note' => 'Some in small amounts',
            'image' => 'can-cats-eat-fruits-fresh-colorful-fruits',
            'alt' => 'Strawberries, blueberries, watermelon and apple beside a cat',
        ],
        [
            'slug' => 'vegetables',
            'title' => 'Vegetables',
            'question' => 'Can cats eat vegetables?',
            'answer' => 'Plain cooked carrot, broccoli or pumpkin in small amounts. Skip onion, garlic and leek entirely.',
            'verdict' => 'caution',
            'note' => 'Cooked and plain only',
            'image' => 'can-cats-eat-vegetables-fresh-greens',
            'alt' => 'Broccoli, cucumber, spinach and carrots beside a cat',
        ],
        [
            'slug' => 'meat-and-seafood',
            'title' => 'Meat & Seafood',
            'question' => 'Can cats eat meat and fish?',
            'answer' => 'Yes. Plain cooked chicken, turkey or fish, boneless and without salt, oil or seasoning.',
            'verdict' => 'safe',
            'note' => 'Cooked, unseasoned',
            'image' => 'can-cats-eat-meat-seafood-chicken-fish',
            'alt' => 'Chicken, salmon and tuna beside a cat',
        ],
        [
            'slug' => 'dairy-and-eggs',
            'title' => 'Dairy & Eggs',
            'question' => 'Can cats eat dairy and eggs?',
            'answer' => 'Cooked egg is fine. Most adult cats are lactose intolerant, so milk and cheese cause upset.',
            'verdict' => 'caution',
            'note' => 'Most cats are lactose intolerant',
            'image' => 'can-cats-eat-dairy-eggs-milk-cheese',
            'alt' => 'Eggs, milk and cheese beside a cat',
        ],
        [
            'slug' => 'toxic-foods',
            'title' => 'Toxic Foods',
            'question' => 'What foods are toxic to cats?',
            'answer' => 'Onion, garlic, chocolate, grapes, raisins, alcohol and xylitol. Call a vet if any is eaten.',
            'verdict' => 'unsafe',
            'note' => 'Never. Call a vet',
            'image' => 'toxic-foods-cats-must-avoid-dangerous',
            'alt' => 'Chocolate, garlic, onion and grapes marked as dangerous',
        ],
        [
            'slug' => 'grains-and-seeds',
            'title' => 'Grains & Seeds',
            'question' => 'Can cats eat grains and seeds?',
            'answer' => 'Small amounts of cooked rice or oats are harmless, but cats gain nothing nutritionally from them.',
            'verdict' => 'caution',
            'note' => 'Cooked, in tiny amounts',
            'image' => 'can-cats-eat-grains-seeds-rice-oats',
            'alt' => 'Rice, oats, quinoa and seeds in bowls beside a cat',
        ],
        [
            'slug' => 'sweets',
            'title' => 'Sweets',
            'question' => 'Can cats eat sweets?',
            'answer' => 'No. Cats cannot taste sweetness, and chocolate and xylitol are outright poisonous.',
            'verdict' => 'unsafe',
            'note' => 'No nutritional value',
            'image' => 'can-cats-eat-sweets-desserts-unsafe',
            'alt' => 'Chocolate cake, sweets and ice cream beside a cat',
        ],
        [
            'slug' => 'junk-food',
            'title' => 'Junk Food',
            'question' => 'Can cats eat junk food?',
            'answer' => 'No. The salt, fat and seasoning in chips, burgers and pizza are far past what a cat can handle.',
            'verdict' => 'unsafe',
            'note' => 'Salt and fat overload',
            'image' => 'can-cats-eat-junk-food-fast-food',
            'alt' => 'Fries, burger and pizza beside a cat',
        ],
        [
            'slug' => 'herbs-and-spices',
            'title' => 'Herbs & Spices',
            'question' => 'Can cats eat herbs and spices?',
            'answer' => 'Basil and rosemary are harmless in tiny amounts. Onion and garlic powder are dangerous.',
            'verdict' => 'caution',
            'note' => 'A few safe, many are not',
            'image' => 'can-cats-eat-herbs-spices-basil-mint',
            'alt' => 'Basil, mint, rosemary and spice jars beside a cat',
        ],
        [
            'slug' => 'treats-and-snacks',
            'title' => 'Treats & Snacks',
            'question' => 'Can cats eat treats?',
            'answer' => 'Yes, as long as treats stay under a tenth of daily calories so meals keep their nutrition.',
            'verdict' => 'safe',
            'note' => 'Under 10% of daily calories',
            'image' => 'can-cats-eat-cat-treats-snacks',
            'alt' => 'Cat treats in a white bowl beside a cat',
        ],
    ],

    'posts' => [
        [
            'slug' => 'why-do-cats-knead',
            'url' => '/blog/why-do-cats-knead',
            'title' => 'Why Do Cats Knead? Making Biscuits Explained',
            'excerpt' => 'Kneading, or making biscuits, is a comfort behavior cats keep '
                .'from kittenhood. What it means when they do it to you, to a blanket, '
                .'at night, and with claws out.',
            'category' => 'Behavior',
            'minutes' => 8,
            'image' => 'why-do-cats-knead-hero',
            'alt' => 'Tabby cat kneading a soft cream blanket with both front paws',
        ],
        [
            'slug' => 'can-cats-eat-broccoli',
            'url' => '/blog/can-cats-eat-broccoli',
            'title' => 'Can Cats Eat Broccoli? A Safe Feeding Guide',
            'excerpt' => 'Broccoli is one of the few vegetables genuinely safe for '
                .'cats, but only plain, cooked, and in small amounts. Here is why, '
                .'and how much.',
            'category' => 'Food Safety',
            'minutes' => 7,
            'image' => 'can-cats-eat-broccoli-cat-sniffing',
            'alt' => 'Fluffy cat sniffing fresh broccoli florets',
        ],
        [
            'slug' => 'how-much-should-i-feed-my-cat',
            'url' => '/blog/how-much-should-i-feed-my-cat',
            'title' => 'How Much Should I Feed My Cat? A Feeding Guide',
            'excerpt' => 'Portion guidance on the bag is written for an average cat '
                .'that does not exist. Work out how much to feed yours from '
                .'weight, food type and life stage.',
            'category' => 'Feeding',
            'minutes' => 10,
            'image' => 'how-much-to-feed-cat-hero',
            'alt' => 'Cat sitting beside a bowl being filled with a measured scoop of kibble',
        ],
        [
            'slug' => 'why-is-my-cat-sneezing',
            'url' => '/blog/why-is-my-cat-sneezing',
            'title' => 'Why Is My Cat Sneezing? When It\'s Normal and When to Worry',
            'excerpt' => 'An occasional sneeze is nothing. Runny eyes, a stuffy nose or '
                .'sneezing that will not stop points at an infection that is worth a '
                .'vet visit.',
            'category' => 'Health',
            'minutes' => 9,
            'image' => 'why-is-my-cat-sneezing-hero',
            'alt' => 'Tabby cat mid-sneeze on a soft blanket at home',
        ],
        [
            'slug' => 'signs-your-cat-is-sick',
            'url' => '/blog/signs-your-cat-is-sick',
            'title' => 'How to Tell If Your Cat Is Sick: 12 Signs to Watch For',
            'excerpt' => 'Cats hide illness well. Appetite loss, hiding, low energy and '
                .'litter box changes are usually the first signs, before anything '
                .'looks seriously wrong.',
            'category' => 'Health',
            'minutes' => 10,
            'image' => 'signs-cat-is-sick-hero',
            'alt' => 'Tabby cat resting quietly, looking subdued',
        ],
        [
            'slug' => 'best-food-for-indoor-cats',
            'url' => '/blog/best-food-for-indoor-cats',
            'title' => 'Best Food for Indoor Cats: What to Look For',
            'excerpt' => 'Indoor cats burn fewer calories than outdoor ones, but '
                .'life stage matters more than the word indoor on the bag. What '
                .'to actually look for.',
            'category' => 'Feeding',
            'minutes' => 9,
            'image' => 'best-indoor-cat-food-premium-ingredients',
            'alt' => 'Salmon, chicken and fresh ingredients beside a bowl of cat food',
        ],
        [
            'slug' => 'can-cats-eat-chicken',
            'url' => '/blog/can-cats-eat-chicken',
            'title' => 'Can Cats Eat Chicken? Yes, Here Is How',
            'excerpt' => 'Chicken is one of the best things you can feed a cat. '
                .'The rules are about preparation, bones, and portion, not '
                .'whether it is safe.',
            'category' => 'Food Safety',
            'minutes' => 8,
            'image' => 'can-cats-eat-chicken-cat-looking',
            'alt' => 'Cat looking at cooked plain chicken on a white plate',
        ],
        [
            'slug' => 'new-cat-owner-guide',
            'url' => '/blog/new-cat-owner-guide',
            'title' => 'The Complete Guide for New Cat Owners',
            'excerpt' => 'Everything for the first weeks: what to buy, how to '
                .'settle a nervous cat, the vet visits that matter early, and '
                .'what to cat-proof first.',
            'category' => 'Getting Started',
            'minutes' => 12,
            'image' => 'new-cat-owner-guide-couple-kitten',
            'alt' => 'Couple playing with a new kitten on a sofa',
        ],
    ],
];

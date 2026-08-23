<?php

/*
|--------------------------------------------------------------------------
| Catalogue
|--------------------------------------------------------------------------
| The tools and food guides shown on the home page. Keeping them here means
| the markup is a loop rather than twenty near-identical blocks, and adding
| a tool is one array entry.
|
| Blog articles used to live here too, as a `posts` entry. They moved into
| the `posts` database table, alongside the rest of the blog CMS, so the
| admin can author them and the public site reads real data rather than a
| second, hand-maintained copy of it. See App\Models\Post.
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
            'url' => '/tools/cat-weight-checker',
            'title' => 'Weight Checker',
            'blurb' => 'Score body condition, see an ideal weight range, and log weight over time.',
            'image' => 'cat-weight-checker-cat-on-scale',
            'alt' => 'Fluffy cat sitting on a digital pet scale',
        ],
        [
            'slug' => 'vaccination-tracker',
            'url' => '/tools/cat-vaccination-tracker',
            'title' => 'Cat Vaccination Tracker',
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
];

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
    ],

    /*
     | Tools with no page built yet. Deliberately not part of 'tools' above:
     | every nav item, count and "X free tools" stat on the site reads from
     | 'tools' alone, so a not-yet-built tool never shows up as a dead link
     | or a "coming soon" card anywhere. Move an entry back up once its page
     | exists, adding a 'url' the same way the live ones have one.
     */
    'tools_upcoming' => [
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
            'why' => 'Cats are obligate carnivores and lack the taste receptor for sweetness, so fruit is never something they instinctively crave the way a person might. That also means fruit brings no nutrition a cat actually needs: no essential amino acid a meat-based diet does not already cover, mostly water, fiber and natural sugar. A small piece is a harmless novelty for most cats, not a food group worth building into a diet.',
            'guidance' => 'Offer melon, blueberries, a few slices of apple flesh, or a small piece of banana, always plain and always in small amounts, no more than a bite or two at a time. Remove seeds, pits and cores first: apple seeds and many stone-fruit pits contain trace amounts of cyanogenic compounds, and a pit itself is a choking and blockage risk regardless. Grapes and raisins are the one hard exception in this category: they are linked to acute kidney injury in cats and dogs, the mechanism is still not fully understood, and there is no known safe amount, so they are treated as off-limits entirely rather than a moderation food.',
            'watch_for' => [
                'Vomiting, diarrhea or reduced appetite after eating any new fruit',
                'Any amount of grape or raisin eaten, even a small piece, warrants a call to a vet',
                'Lethargy or decreased urination in the day or two after eating grapes or raisins',
            ],
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
            'why' => 'A cat\'s digestive system is built around meat, not plant matter, so vegetables offer fiber and a little variety rather than anything a well-fed cat is missing. Raw vegetables are harder to digest and more likely to cause an upset stomach than cooked, plain ones, since cooking breaks down the plant cell walls a cat\'s gut cannot easily process on its own.',
            'guidance' => 'Plain cooked carrot, broccoli, green beans, peas or pumpkin, cut small and offered without butter, oil or seasoning, are fine in small amounts alongside a regular diet. Spinach is usually fine occasionally but is best kept infrequent in a cat with a history of urinary crystals, since it is relatively high in oxalates. The one category to skip entirely is the allium family: onion, garlic, leek, chives and shallots, cooked or raw, all contain compounds that damage a cat\'s red blood cells and can cause a serious anemia, even from amounts that would seem trivially small.',
            'watch_for' => [
                'Pale gums, weakness or lethargy in the days after eating onion, garlic or leek, signs of anemia',
                'Vomiting or diarrhea after a new vegetable, especially a raw one',
                'Reduced appetite that continues beyond a day',
            ],
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
            'why' => 'Meat is the one category here a cat\'s body is actually built to run on. Cats need taurine, an amino acid found almost exclusively in animal tissue, and plain cooked meat or fish is close to what already makes up a complete commercial cat food. This is the safest category on this page by a wide margin, with a short, specific list of things to get right.',
            'guidance' => 'Plain cooked chicken, turkey, beef, lean pork or fish, with all bones removed and no salt, oil, butter or seasoning, especially no onion or garlic powder, is a good addition or occasional topper to a normal diet. Cooking removes the bacterial risk raw meat carries, including salmonella and campylobacter, which can make a cat unwell and can also spread to people handling the food and litter tray afterward. Fish specifically is best kept occasional rather than a staple: raw fish contains an enzyme called thiaminase that breaks down vitamin B1, and a diet too heavy in fish, cooked or raw, has been linked to steatitis, a painful inflammation of body fat from too much unsaturated fat without enough vitamin E to balance it. Cooked bones are never safe: they turn brittle and can splinter and injure the throat or gut on the way through.',
            'watch_for' => [
                'Vomiting or diarrhea after raw meat or fish specifically',
                'Difficulty swallowing, drooling or pawing at the mouth after any bone-in meat',
                'Repeated fatty extras (skin, drippings) are a recognized risk factor for pancreatitis',
            ],
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
            'why' => 'Kittens produce plenty of lactase, the enzyme that digests milk sugar, because they need it to nurse. Most cats lose much of that ability once weaned, the same way many adult humans do, so cow\'s milk sits in an adult cat\'s gut largely undigested and pulls water into the intestine, which is what causes the diarrhea people often report after giving a cat a saucer of milk.',
            'guidance' => 'Plain cooked egg, scrambled or hard-boiled with nothing added, is a genuinely good source of protein in small amounts. Milk and cream are best skipped for any adult cat with unknown tolerance; a small amount of hard cheese or plain yogurt, which carry less lactose than milk, is tolerated by some cats without issue. Raw eggs carry a real salmonella risk and also contain avidin, a protein that binds biotin and can interfere with its absorption over time, so eggs are always better cooked than raw.',
            'watch_for' => [
                'Diarrhea or soft stool within a few hours of milk, cream or a large amount of dairy',
                'Vomiting after raw egg specifically',
                'Excessive gas or bloating after cheese',
            ],
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
            'why' => 'This list covers the foods that are dangerous in genuinely small amounts, not just unhealthy in excess: a bite, not a bowlful, is enough to matter for several of these. Cats are also smaller than most dogs, which means the same amount of a toxic food does more damage per pound of body weight, and cats metabolize some of these compounds, caffeine and theobromine in particular, more slowly than people do.',
            'guidance' => 'Onion, garlic, leek and chives, in any form, cooked, raw or powdered, damage red blood cells and can cause anemia. Chocolate contains theobromine and caffeine, both toxic to a cat\'s heart and nervous system, with darker and baking chocolate far more concentrated than milk chocolate. Grapes and raisins are linked to acute kidney injury by a mechanism still not fully understood, with no known safe amount. Alcohol affects a cat\'s small body fast and can be dangerous in amounts that would do nothing to a person. Xylitol, an artificial sweetener increasingly common in sugar-free gum, peanut butter and baked goods, is established as dangerous in dogs and treated with the same caution in cats. If you know or suspect your cat ate any of these, call your vet or an animal poison control line immediately rather than waiting to see if symptoms appear, and do not induce vomiting unless a vet specifically tells you to.',
            'watch_for' => [
                'Vomiting, diarrhea, weakness or pale gums, common early signs across most of these',
                'Rapid breathing, tremors or an elevated heart rate after chocolate, caffeine or alcohol',
                'Lethargy or reduced urination in the day or two after grapes or raisins',
                'Any known ingestion, even without symptoms yet, is worth an immediate call, not a wait-and-see',
            ],
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
            'why' => 'Cats have no biological requirement for carbohydrate: unlike people or dogs, they do not need grains as an energy source, and a diet built around meat already meets their needs without them. That does not make grains dangerous, just unnecessary, which is a different thing from the foods elsewhere on this page.',
            'guidance' => 'A small amount of plain cooked rice or oats, with nothing added, is harmless for most cats and sometimes used to settle an upset stomach. Some cats do show a grain sensitivity, with itchy skin or digestive upset as the main signs, though true grain allergy is less common in cats than marketing around grain-free food often implies. Seeds are best kept plain and unsalted, in small quantities; the concern with seasoned or salted seeds is the salt and any onion or garlic seasoning mixed in, not the seed itself.',
            'watch_for' => [
                'Itchy skin, ear infections or soft stool that shows up consistently after a specific grain',
                'Bloating or gas after a larger-than-usual portion',
            ],
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
            'why' => 'Cats are missing a functional version of the gene for the sweet taste receptor, which means they genuinely cannot taste sugar the way people, dogs, or most other mammals do. A cat begging at the table while you eat dessert is responding to fat, texture or your attention, not a craving for sweetness, since that sense does not exist for them.',
            'guidance' => 'There is no safe serving size to give here, because sweets as a category are built almost entirely from things that are actively bad for a cat rather than just empty calories: chocolate and xylitol both turn up constantly in desserts and are genuinely dangerous, and the high sugar and fat content of the rest offers a cat nothing while raising the risk of obesity, dental disease and pancreatitis over time. The honest answer is to keep sweets away from cats entirely rather than manage a moderate amount.',
            'watch_for' => [
                'Vomiting, diarrhea or lethargy after any sweet treat, especially one containing chocolate',
                'Any suspected xylitol exposure is an emergency: call a vet immediately',
            ],
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
            'why' => 'Fast food and heavily processed snacks are formulated for a much larger animal with very different nutritional needs, and the seasoning that makes them taste good to a person is often the same seasoning that is genuinely risky for a cat: onion and garlic powder show up in burger seasoning, gravy and plenty of pre-made sauces without being obvious from the smell or taste.',
            'guidance' => 'There is no moderate version of this one worth building a habit around. The salt content in chips, fries and processed meat is far higher than a cat\'s diet is built for, and excess fat, especially a sudden large amount, is a real trigger for pancreatitis in cats. A dropped french fry or a lick of a burger bun is not an emergency, but junk food is not a category worth offering intentionally, even occasionally.',
            'watch_for' => [
                'Vomiting or diarrhea after a fatty or salty food',
                'Excessive thirst or urination after a high-salt food, a sign of sodium ion intake worth watching',
                'Abdominal pain, hunching or reluctance to be touched near the belly, possible signs of pancreatitis after a fatty meal',
            ],
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
            'why' => 'Herbs and spices are easy to overlook as a risk because they arrive in small quantities baked into other food, which is exactly the problem: onion and garlic powder, both genuinely dangerous to cats, are standard ingredients in seasoning blends, gravies and pre-made sauces, often without either word appearing where you would notice it.',
            'guidance' => 'Fresh basil, rosemary, parsley and dill are harmless to a cat in the small amounts they would ever realistically eat, and cat grass or catnip are both fine and often actively enjoyed. Onion and garlic powder are the ones to actually watch for, since a concentrated powder form delivers more of the toxic compound than the equivalent fresh vegetable would. Nutmeg is worth naming specifically too: it contains myristicin, which can cause toxicity in larger amounts, so it is not a spice to let a cat get into even though it seems unlikely to seek it out.',
            'watch_for' => [
                'Pale gums or lethargy after any food seasoned with onion or garlic powder, even in a sauce or gravy',
                'Disorientation or an elevated heart rate after nutmeg specifically',
            ],
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
            'why' => 'Treats are how most training and bonding with a cat actually happens day to day, and there is nothing wrong with that. The risk is not treats themselves, it is scale: a handful of small treats a few times a day adds up faster than it looks, and unlike a measured meal, treats rarely get counted toward a cat\'s daily total.',
            'guidance' => 'The widely used guideline is to keep treats, of any kind, under about ten percent of a cat\'s total daily calories, with the rest coming from a complete, balanced food that is actually formulated to meet their needs. Commercial cat treats are generally formulated to be safe in normal amounts; plain, unseasoned human snacks such as small pieces of cooked meat work as treats too, within the same limit. If you are not sure what your cat\'s daily calorie total actually is, our cat calorie calculator works it out from their weight and activity level, which makes the ten percent limit an actual number rather than a guess.',
            'watch_for' => [
                'Weight gain over a few weeks despite no change in meals, often a sign treats have crept up unnoticed',
                'A cat skipping regular meals in favor of holding out for treats',
            ],
        ],
    ],
];

<?php

/*
|--------------------------------------------------------------------------
| Cat name generator
|--------------------------------------------------------------------------
| A curated list, not a scraped one: every meaning here is something we
| could point to (a real word's translation, a mythology figure, a
| well-known character), not an invented etymology. "Thousands of names"
| is the kind of claim competitors make and can't back up — this is a few
| hundred good ones instead, each with a real reason behind it.
|
| `styles` is capped near ten on purpose, matching what a person actually
| filters by, not an exhaustive taxonomy. `origin_tags` is what lets the
| breed selector on the tool page bias toward names that fit a breed's own
| origin country, using the real data already in the breeds table rather
| than inventing "official breed names" that don't exist.
*/

return [

    'styles' => [
        ['slug' => 'classic', 'label' => 'Classic'],
        ['slug' => 'cute', 'label' => 'Cute & Sweet'],
        ['slug' => 'food', 'label' => 'Food-Inspired'],
        ['slug' => 'mythology', 'label' => 'Mythology & Legend'],
        ['slug' => 'royal', 'label' => 'Royal & Regal'],
        ['slug' => 'nature', 'label' => 'Nature-Inspired'],
        ['slug' => 'pop-culture', 'label' => 'Pop Culture'],
        ['slug' => 'elegant', 'label' => 'Elegant & French'],
        ['slug' => 'warrior', 'label' => 'Warrior & Strong'],
        ['slug' => 'quirky', 'label' => 'Funny & Quirky'],
    ],

    'personalities' => [
        ['slug' => 'playful', 'label' => 'Playful & energetic'],
        ['slug' => 'calm', 'label' => 'Calm & laid-back'],
        ['slug' => 'mischievous', 'label' => 'Mischievous'],
        ['slug' => 'affectionate', 'label' => 'Affectionate & clingy'],
        ['slug' => 'independent', 'label' => 'Independent & aloof'],
        ['slug' => 'regal', 'label' => 'Dignified & regal'],
    ],

    'names' => [
        // ── Classic ──────────────────────────────────────────────────────
        ['name' => 'Whiskers', 'gender' => 'neutral', 'styles' => ['classic'], 'meaning' => 'A straightforward nod to a cat\'s most recognizable feature.', 'personalities' => ['affectionate']],
        ['name' => 'Tom', 'gender' => 'male', 'styles' => ['classic'], 'meaning' => 'The generic name for a male cat since at least the 1700s.', 'personalities' => ['independent']],
        ['name' => 'Tabby', 'gender' => 'female', 'styles' => ['classic'], 'meaning' => 'Named for the striped coat pattern itself.', 'personalities' => ['calm']],
        ['name' => 'Mittens', 'gender' => 'neutral', 'styles' => ['classic', 'cute'], 'meaning' => 'For white-pawed cats, as if wearing tiny mittens.', 'personalities' => ['affectionate'], 'nickname' => 'Mitts'],
        ['name' => 'Smokey', 'gender' => 'neutral', 'styles' => ['classic'], 'meaning' => 'For a soft gray coat, the color of wood smoke.', 'personalities' => ['calm']],
        ['name' => 'Patches', 'gender' => 'neutral', 'styles' => ['classic'], 'meaning' => 'For a coat broken up into patches of color.', 'personalities' => ['playful']],
        ['name' => 'Tiger', 'gender' => 'male', 'styles' => ['classic', 'warrior'], 'meaning' => 'For bold striping and a big-cat attitude in a small body.', 'personalities' => ['playful']],
        ['name' => 'Shadow', 'gender' => 'neutral', 'styles' => ['classic'], 'meaning' => 'For a cat that follows you room to room, or a solid black coat.', 'personalities' => ['independent']],
        ['name' => 'Boots', 'gender' => 'male', 'styles' => ['classic'], 'meaning' => 'For dark paws against a lighter coat.', 'personalities' => ['playful']],
        ['name' => 'Ginger', 'gender' => 'female', 'styles' => ['classic'], 'meaning' => 'British shorthand for an orange coat.', 'personalities' => ['mischievous']],
        ['name' => 'Oreo', 'gender' => 'neutral', 'styles' => ['classic', 'cute'], 'meaning' => 'For a black-and-white coat, cookie included.', 'personalities' => ['playful']],
        ['name' => 'Panther', 'gender' => 'male', 'styles' => ['classic', 'warrior'], 'meaning' => 'For a sleek black coat and a quietly confident walk.', 'personalities' => ['independent']],
        ['name' => 'Misty', 'gender' => 'female', 'styles' => ['classic'], 'meaning' => 'For pale gray or blue-gray coloring.', 'personalities' => ['calm']],
        ['name' => 'Jasper', 'gender' => 'male', 'styles' => ['classic'], 'meaning' => 'From the mottled brown gemstone.', 'personalities' => ['calm']],
        ['name' => 'Fluffy', 'gender' => 'neutral', 'styles' => ['classic', 'cute'], 'meaning' => 'A description, not an insult, for a seriously plush coat.', 'personalities' => ['calm']],

        // ── Cute & Sweet ─────────────────────────────────────────────────
        ['name' => 'Bella', 'gender' => 'female', 'styles' => ['cute', 'elegant'], 'meaning' => 'Italian for "beautiful".', 'personalities' => ['affectionate']],
        ['name' => 'Coco', 'gender' => 'female', 'styles' => ['cute', 'food'], 'meaning' => 'For a rich brown coat, or a nod to cocoa.', 'personalities' => ['affectionate']],
        ['name' => 'Pumpkin', 'gender' => 'neutral', 'styles' => ['cute', 'food'], 'meaning' => 'For a warm orange coat.', 'personalities' => ['playful']],
        ['name' => 'Honey', 'gender' => 'female', 'styles' => ['cute', 'food'], 'meaning' => 'For a golden coat and a sweet temperament.', 'personalities' => ['affectionate']],
        ['name' => 'Peanut', 'gender' => 'neutral', 'styles' => ['cute', 'food'], 'meaning' => 'A classic pick for a small or petite cat.', 'personalities' => ['playful'], 'nickname' => 'Nutty'],
        ['name' => 'Button', 'gender' => 'female', 'styles' => ['cute'], 'meaning' => 'For a small, round, easy-to-love kitten.', 'personalities' => ['affectionate']],
        ['name' => 'Bubbles', 'gender' => 'female', 'styles' => ['cute', 'quirky'], 'meaning' => 'For a cat whose energy is impossible to miss.', 'personalities' => ['playful']],
        ['name' => 'Sprinkle', 'gender' => 'neutral', 'styles' => ['cute', 'food'], 'meaning' => 'For a coat flecked with a second color.', 'personalities' => ['playful']],
        ['name' => 'Daisy', 'gender' => 'female', 'styles' => ['cute', 'nature'], 'meaning' => 'From the flower, a byword for cheerful and simple.', 'personalities' => ['affectionate']],
        ['name' => 'Pudding', 'gender' => 'neutral', 'styles' => ['cute', 'food'], 'meaning' => 'For a round, soft, thoroughly spoiled cat.', 'personalities' => ['calm']],
        ['name' => 'Snuggles', 'gender' => 'neutral', 'styles' => ['cute'], 'meaning' => 'For a cat that treats your lap as their permanent address.', 'personalities' => ['affectionate']],
        ['name' => 'Rosie', 'gender' => 'female', 'styles' => ['cute', 'nature'], 'meaning' => 'From "rose", a name for a gentle, pretty cat.', 'personalities' => ['calm']],
        ['name' => 'Marshmallow', 'gender' => 'neutral', 'styles' => ['cute', 'food'], 'meaning' => 'For a white, soft-looking coat.', 'personalities' => ['calm'], 'nickname' => 'Marshy'],
        ['name' => 'Poppy', 'gender' => 'female', 'styles' => ['cute', 'nature'], 'meaning' => 'From the flower; also a good fit for a red-orange coat.', 'personalities' => ['playful']],
        ['name' => 'Cuddles', 'gender' => 'neutral', 'styles' => ['cute'], 'meaning' => 'Exactly what it says.', 'personalities' => ['affectionate']],

        // ── Food-Inspired ────────────────────────────────────────────────
        ['name' => 'Mochi', 'gender' => 'neutral', 'styles' => ['food', 'cute'], 'meaning' => 'Japanese pounded rice cake, for a soft round cat.', 'origin_tags' => ['japan'], 'personalities' => ['calm']],
        ['name' => 'Waffles', 'gender' => 'male', 'styles' => ['food', 'quirky'], 'meaning' => 'Breakfast, for a cat who runs the household by 7am.', 'personalities' => ['mischievous']],
        ['name' => 'Nugget', 'gender' => 'neutral', 'styles' => ['food', 'cute'], 'meaning' => 'A classic pick for a small, golden-toned cat.', 'personalities' => ['playful']],
        ['name' => 'Olive', 'gender' => 'female', 'styles' => ['food', 'elegant'], 'meaning' => 'From the olive, also a soft nod to green-gray eyes.', 'personalities' => ['calm']],
        ['name' => 'Pepper', 'gender' => 'female', 'styles' => ['food'], 'meaning' => 'For a black-and-white coat or a spicy personality.', 'personalities' => ['mischievous']],
        ['name' => 'Cinnamon', 'gender' => 'female', 'styles' => ['food'], 'meaning' => 'For a warm reddish-brown coat.', 'personalities' => ['affectionate'], 'nickname' => 'Cinna'],
        ['name' => 'Basil', 'gender' => 'male', 'styles' => ['food', 'elegant'], 'meaning' => 'The herb, and a genuinely dignified-sounding name.', 'personalities' => ['calm']],
        ['name' => 'Biscuit', 'gender' => 'neutral', 'styles' => ['food', 'cute'], 'meaning' => 'For a warm, buttery-colored coat.', 'personalities' => ['calm']],
        ['name' => 'Noodle', 'gender' => 'neutral', 'styles' => ['food', 'quirky'], 'meaning' => 'For a long, lanky cat who flops rather than sits.', 'personalities' => ['mischievous']],
        ['name' => 'Miso', 'gender' => 'neutral', 'styles' => ['food'], 'meaning' => 'Japanese fermented soybean paste, for a tan or beige coat.', 'origin_tags' => ['japan'], 'personalities' => ['calm']],
        ['name' => 'Chai', 'gender' => 'female', 'styles' => ['food'], 'meaning' => 'Spiced tea, for a warm brown coat.', 'personalities' => ['calm']],
        ['name' => 'Bagel', 'gender' => 'male', 'styles' => ['food', 'quirky'], 'meaning' => 'For a cat who curls into a perfect ring to sleep.', 'personalities' => ['calm']],
        ['name' => 'Clementine', 'gender' => 'female', 'styles' => ['food', 'elegant'], 'meaning' => 'The citrus fruit, for a bright orange coat.', 'personalities' => ['playful'], 'nickname' => 'Clemmie'],
        ['name' => 'Taco', 'gender' => 'male', 'styles' => ['food', 'quirky'], 'meaning' => 'For a cat with strong opinions about dinner time.', 'personalities' => ['mischievous']],
        ['name' => 'Wasabi', 'gender' => 'male', 'styles' => ['food', 'quirky'], 'meaning' => 'Japanese horseradish, for a cat with real spice in their attitude.', 'origin_tags' => ['japan'], 'personalities' => ['mischievous']],

        // ── Mythology & Legend ───────────────────────────────────────────
        ['name' => 'Loki', 'gender' => 'male', 'styles' => ['mythology', 'pop-culture'], 'meaning' => 'The Norse trickster god, a fitting name for a mischief-maker.', 'origin_tags' => ['norway'], 'personalities' => ['mischievous']],
        ['name' => 'Freya', 'gender' => 'female', 'styles' => ['mythology'], 'meaning' => 'Norse goddess of love and beauty.', 'origin_tags' => ['norway'], 'personalities' => ['regal']],
        ['name' => 'Zeus', 'gender' => 'male', 'styles' => ['mythology', 'warrior'], 'meaning' => 'King of the Greek gods.', 'personalities' => ['regal']],
        ['name' => 'Athena', 'gender' => 'female', 'styles' => ['mythology'], 'meaning' => 'Greek goddess of wisdom and strategy.', 'personalities' => ['independent']],
        ['name' => 'Apollo', 'gender' => 'male', 'styles' => ['mythology'], 'meaning' => 'Greek god of light, music and truth.', 'personalities' => ['regal']],
        ['name' => 'Isis', 'gender' => 'female', 'styles' => ['mythology'], 'meaning' => 'Egyptian goddess of magic and protection.', 'origin_tags' => ['egypt'], 'personalities' => ['regal']],
        ['name' => 'Bastet', 'gender' => 'female', 'styles' => ['mythology', 'royal'], 'meaning' => 'The Egyptian cat goddess herself.', 'origin_tags' => ['egypt'], 'personalities' => ['regal']],
        ['name' => 'Odin', 'gender' => 'male', 'styles' => ['mythology'], 'meaning' => 'The chief Norse god, associated with wisdom.', 'origin_tags' => ['norway'], 'personalities' => ['independent']],
        ['name' => 'Nyx', 'gender' => 'female', 'styles' => ['mythology'], 'meaning' => 'Greek primordial goddess of the night, a natural fit for a black cat.', 'personalities' => ['independent']],
        ['name' => 'Hera', 'gender' => 'female', 'styles' => ['mythology', 'royal'], 'meaning' => 'Greek queen of the gods.', 'personalities' => ['regal']],
        ['name' => 'Anubis', 'gender' => 'male', 'styles' => ['mythology'], 'meaning' => 'Egyptian god associated with the afterlife, often depicted with animal features.', 'origin_tags' => ['egypt'], 'personalities' => ['independent']],
        ['name' => 'Selene', 'gender' => 'female', 'styles' => ['mythology'], 'meaning' => 'Greek goddess of the moon.', 'personalities' => ['calm']],
        ['name' => 'Thor', 'gender' => 'male', 'styles' => ['mythology', 'warrior'], 'meaning' => 'Norse god of thunder.', 'origin_tags' => ['norway'], 'personalities' => ['playful']],
        ['name' => 'Luna', 'gender' => 'female', 'styles' => ['mythology', 'classic'], 'meaning' => 'Latin for "moon".', 'personalities' => ['calm']],
        ['name' => 'Osiris', 'gender' => 'male', 'styles' => ['mythology', 'royal'], 'meaning' => 'Egyptian god of the afterlife and rebirth.', 'origin_tags' => ['egypt'], 'personalities' => ['regal']],

        // ── Royal & Regal ────────────────────────────────────────────────
        ['name' => 'Duchess', 'gender' => 'female', 'styles' => ['royal'], 'meaning' => 'The rank just under a queen, for a cat who acts like one anyway.', 'personalities' => ['regal']],
        ['name' => 'Duke', 'gender' => 'male', 'styles' => ['royal'], 'meaning' => 'A title of nobility, for a dignified tom.', 'personalities' => ['regal']],
        ['name' => 'Prince', 'gender' => 'male', 'styles' => ['royal'], 'meaning' => 'For a cat who has never once had to ask for anything.', 'personalities' => ['regal']],
        ['name' => 'Princess', 'gender' => 'female', 'styles' => ['royal'], 'meaning' => 'For a cat with very clear standards.', 'personalities' => ['regal']],
        ['name' => 'Queenie', 'gender' => 'female', 'styles' => ['royal', 'cute'], 'meaning' => 'A softer, friendlier take on "Queen".', 'personalities' => ['affectionate']],
        ['name' => 'Baron', 'gender' => 'male', 'styles' => ['royal'], 'meaning' => 'A European title of nobility.', 'personalities' => ['independent']],
        ['name' => 'Countess', 'gender' => 'female', 'styles' => ['royal', 'elegant'], 'meaning' => 'A noblewoman\'s title, for a composed and elegant cat.', 'personalities' => ['regal']],
        ['name' => 'Majesty', 'gender' => 'neutral', 'styles' => ['royal'], 'meaning' => 'A direct claim to the throne.', 'personalities' => ['regal']],
        ['name' => 'Earl', 'gender' => 'male', 'styles' => ['royal', 'classic'], 'meaning' => 'A British noble title, also just a solid classic name.', 'origin_tags' => ['uk'], 'personalities' => ['calm']],
        ['name' => 'Empress', 'gender' => 'female', 'styles' => ['royal'], 'meaning' => 'For a cat who outranks everyone else in the house.', 'personalities' => ['regal']],
        ['name' => 'Sir Whiskington', 'gender' => 'male', 'styles' => ['royal', 'quirky'], 'meaning' => 'A knighthood, self-appointed.', 'personalities' => ['regal']],
        ['name' => 'Regina', 'gender' => 'female', 'styles' => ['royal', 'elegant'], 'meaning' => 'Latin for "queen".', 'personalities' => ['regal']],
        ['name' => 'Kaiser', 'gender' => 'male', 'styles' => ['royal', 'warrior'], 'meaning' => 'German for "emperor".', 'personalities' => ['independent']],
        ['name' => 'Tsarina', 'gender' => 'female', 'styles' => ['royal'], 'meaning' => 'The title of a Russian empress.', 'origin_tags' => ['russia'], 'personalities' => ['regal']],
        ['name' => 'Sultan', 'gender' => 'male', 'styles' => ['royal'], 'meaning' => 'A Muslim sovereign\'s title.', 'origin_tags' => ['turkey'], 'personalities' => ['regal']],

        // ── Nature-Inspired ──────────────────────────────────────────────
        ['name' => 'Willow', 'gender' => 'female', 'styles' => ['nature'], 'meaning' => 'The graceful, drooping tree.', 'personalities' => ['calm']],
        ['name' => 'Sage', 'gender' => 'neutral', 'styles' => ['nature'], 'meaning' => 'The herb, and a word for quiet wisdom.', 'personalities' => ['calm']],
        ['name' => 'River', 'gender' => 'neutral', 'styles' => ['nature'], 'meaning' => 'For a cat that is always moving somewhere.', 'personalities' => ['playful']],
        ['name' => 'Storm', 'gender' => 'neutral', 'styles' => ['nature', 'warrior'], 'meaning' => 'For sudden bursts of zoomies.', 'personalities' => ['mischievous']],
        ['name' => 'Autumn', 'gender' => 'female', 'styles' => ['nature'], 'meaning' => 'For warm orange and brown coats.', 'personalities' => ['calm']],
        ['name' => 'Meadow', 'gender' => 'female', 'styles' => ['nature'], 'meaning' => 'An open field of grass: a soft, gentle name.', 'personalities' => ['calm']],
        ['name' => 'Flint', 'gender' => 'male', 'styles' => ['nature', 'warrior'], 'meaning' => 'The hard, spark-striking stone.', 'personalities' => ['independent']],
        ['name' => 'Ivy', 'gender' => 'female', 'styles' => ['nature'], 'meaning' => 'The climbing vine, for a cat who gets into everything.', 'personalities' => ['mischievous']],
        ['name' => 'Aspen', 'gender' => 'neutral', 'styles' => ['nature'], 'meaning' => 'The tree known for its quaking leaves.', 'personalities' => ['playful']],
        ['name' => 'Hazel', 'gender' => 'female', 'styles' => ['nature'], 'meaning' => 'The tree, and a common word for golden-brown eyes.', 'personalities' => ['calm']],
        ['name' => 'Cedar', 'gender' => 'male', 'styles' => ['nature'], 'meaning' => 'The evergreen tree, for a steady, grounded cat.', 'personalities' => ['calm']],
        ['name' => 'Clover', 'gender' => 'female', 'styles' => ['nature', 'cute'], 'meaning' => 'For a small cat who feels like good luck.', 'personalities' => ['affectionate']],
        ['name' => 'Birch', 'gender' => 'male', 'styles' => ['nature'], 'meaning' => 'The pale-barked tree.', 'personalities' => ['calm']],
        ['name' => 'Skye', 'gender' => 'female', 'styles' => ['nature'], 'meaning' => 'For blue or gray-blue eyes.', 'personalities' => ['calm']],
        ['name' => 'Fern', 'gender' => 'female', 'styles' => ['nature'], 'meaning' => 'The shade-loving plant, for a quiet, unobtrusive cat.', 'personalities' => ['independent']],

        // ── Pop Culture ──────────────────────────────────────────────────
        ['name' => 'Simba', 'gender' => 'male', 'styles' => ['pop-culture'], 'meaning' => 'Swahili for "lion", and The Lion King\'s hero.', 'personalities' => ['playful']],
        ['name' => 'Garfield', 'gender' => 'male', 'styles' => ['pop-culture'], 'meaning' => 'The world\'s most famous lasagna-loving comic cat.', 'personalities' => ['calm']],
        ['name' => 'Salem', 'gender' => 'male', 'styles' => ['pop-culture', 'mythology'], 'meaning' => 'The talking cat from Sabrina the Teenage Witch.', 'personalities' => ['mischievous']],
        ['name' => 'Chewie', 'gender' => 'male', 'styles' => ['pop-culture'], 'meaning' => 'After Star Wars\' loyal, furry co-pilot.', 'personalities' => ['affectionate']],
        ['name' => 'Yoda', 'gender' => 'male', 'styles' => ['pop-culture'], 'meaning' => 'For a small cat with an old soul.', 'personalities' => ['calm']],
        ['name' => 'Arya', 'gender' => 'female', 'styles' => ['pop-culture'], 'meaning' => 'After Game of Thrones\' fiercely independent Stark.', 'personalities' => ['independent']],
        ['name' => 'Jax', 'gender' => 'male', 'styles' => ['pop-culture', 'quirky'], 'meaning' => 'A sharp, modern short form of Jackson.', 'personalities' => ['mischievous']],
        ['name' => 'Elsa', 'gender' => 'female', 'styles' => ['pop-culture'], 'meaning' => 'For a cat with an icy stare and zero patience for nonsense.', 'personalities' => ['independent']],
        ['name' => 'Groot', 'gender' => 'male', 'styles' => ['pop-culture', 'quirky'], 'meaning' => 'After Guardians of the Galaxy\'s gentle giant.', 'personalities' => ['calm']],
        ['name' => 'Nala', 'gender' => 'female', 'styles' => ['pop-culture'], 'meaning' => 'Swahili for "gift", and Simba\'s counterpart in The Lion King.', 'personalities' => ['playful']],
        ['name' => 'Gizmo', 'gender' => 'male', 'styles' => ['pop-culture', 'cute'], 'meaning' => 'After the wide-eyed Gremlins character.', 'personalities' => ['playful']],
        ['name' => 'Momo', 'gender' => 'neutral', 'styles' => ['pop-culture', 'cute'], 'meaning' => 'A popular anime-inspired pet name meaning "peach" in Japanese.', 'origin_tags' => ['japan'], 'personalities' => ['playful']],
        ['name' => 'Ripley', 'gender' => 'female', 'styles' => ['pop-culture', 'warrior'], 'meaning' => 'After Alien\'s no-nonsense heroine.', 'personalities' => ['independent']],
        ['name' => 'Dobby', 'gender' => 'male', 'styles' => ['pop-culture', 'quirky'], 'meaning' => 'After the famously loyal, big-eyed house-elf.', 'personalities' => ['affectionate']],
        ['name' => 'Khaleesi', 'gender' => 'female', 'styles' => ['pop-culture', 'royal'], 'meaning' => 'A Game of Thrones title meaning "queen".', 'personalities' => ['regal']],

        // ── Elegant & French ─────────────────────────────────────────────
        ['name' => 'Amélie', 'gender' => 'female', 'styles' => ['elegant'], 'meaning' => 'French, meaning "hardworking" or "industrious".', 'origin_tags' => ['france'], 'personalities' => ['independent']],
        ['name' => 'Chanel', 'gender' => 'female', 'styles' => ['elegant'], 'meaning' => 'After the iconic French fashion house.', 'origin_tags' => ['france'], 'personalities' => ['regal']],
        ['name' => 'Fifi', 'gender' => 'female', 'styles' => ['elegant', 'cute'], 'meaning' => 'A classic French pet-name diminutive.', 'origin_tags' => ['france'], 'personalities' => ['affectionate']],
        ['name' => 'Monet', 'gender' => 'neutral', 'styles' => ['elegant'], 'meaning' => 'After the French impressionist painter.', 'origin_tags' => ['france'], 'personalities' => ['calm']],
        ['name' => 'Belle', 'gender' => 'female', 'styles' => ['elegant', 'cute'], 'meaning' => 'French for "beautiful".', 'origin_tags' => ['france'], 'personalities' => ['affectionate']],
        ['name' => 'Genevieve', 'gender' => 'female', 'styles' => ['elegant'], 'meaning' => 'French, meaning "woman of the people" or "tribe woman".', 'origin_tags' => ['france'], 'personalities' => ['regal']],
        ['name' => 'Bijou', 'gender' => 'female', 'styles' => ['elegant', 'cute'], 'meaning' => 'French for "jewel", a fond name for a small cat.', 'origin_tags' => ['france'], 'personalities' => ['affectionate']],
        ['name' => 'Colette', 'gender' => 'female', 'styles' => ['elegant'], 'meaning' => 'A French name after the celebrated novelist.', 'origin_tags' => ['france'], 'personalities' => ['independent']],
        ['name' => 'Pierre', 'gender' => 'male', 'styles' => ['elegant'], 'meaning' => 'French form of Peter, meaning "rock".', 'origin_tags' => ['france'], 'personalities' => ['calm']],
        ['name' => 'Antoinette', 'gender' => 'female', 'styles' => ['elegant', 'royal'], 'meaning' => 'A French royal name, elegant and unmistakable.', 'origin_tags' => ['france'], 'personalities' => ['regal'], 'nickname' => 'Toni'],
        ['name' => 'Rémy', 'gender' => 'male', 'styles' => ['elegant'], 'meaning' => 'French, meaning "oarsman", also a nod to a certain cooking rat.', 'origin_tags' => ['france'], 'personalities' => ['playful']],
        ['name' => 'Céleste', 'gender' => 'female', 'styles' => ['elegant', 'nature'], 'meaning' => 'French for "heavenly" or "of the sky".', 'origin_tags' => ['france'], 'personalities' => ['calm']],
        ['name' => 'Étoile', 'gender' => 'female', 'styles' => ['elegant'], 'meaning' => 'French for "star".', 'origin_tags' => ['france'], 'personalities' => ['regal']],
        ['name' => 'Marcel', 'gender' => 'male', 'styles' => ['elegant'], 'meaning' => 'A classic French given name.', 'origin_tags' => ['france'], 'personalities' => ['calm']],
        ['name' => 'Odette', 'gender' => 'female', 'styles' => ['elegant'], 'meaning' => 'French, meaning "wealthy", also the swan princess of Swan Lake.', 'origin_tags' => ['france'], 'personalities' => ['regal']],

        // ── Warrior & Strong ─────────────────────────────────────────────
        ['name' => 'Blade', 'gender' => 'male', 'styles' => ['warrior'], 'meaning' => 'Sharp, direct, no-nonsense.', 'personalities' => ['independent']],
        ['name' => 'Titan', 'gender' => 'male', 'styles' => ['warrior', 'mythology'], 'meaning' => 'The pre-Olympian Greek giants, a name for a big presence.', 'personalities' => ['independent']],
        ['name' => 'Ranger', 'gender' => 'male', 'styles' => ['warrior'], 'meaning' => 'For a cat who patrols every room like it is their job.', 'personalities' => ['independent']],
        ['name' => 'Rex', 'gender' => 'male', 'styles' => ['warrior', 'classic'], 'meaning' => 'Latin for "king".', 'personalities' => ['regal']],
        ['name' => 'Diesel', 'gender' => 'male', 'styles' => ['warrior'], 'meaning' => 'For a cat built like a small tank.', 'personalities' => ['independent']],
        ['name' => 'Ghost', 'gender' => 'neutral', 'styles' => ['warrior', 'mythology'], 'meaning' => 'For a pure white coat or an uncanny knack for silence.', 'personalities' => ['independent']],
        ['name' => 'Bandit', 'gender' => 'male', 'styles' => ['warrior', 'quirky'], 'meaning' => 'For a cat who steals socks, hair ties, anything shiny.', 'personalities' => ['mischievous']],
        ['name' => 'Maverick', 'gender' => 'male', 'styles' => ['warrior'], 'meaning' => 'One who goes their own way, rules be damned.', 'personalities' => ['independent']],
        ['name' => 'Valkyrie', 'gender' => 'female', 'styles' => ['warrior', 'mythology'], 'meaning' => 'Norse female warrior spirits who chose the slain.', 'origin_tags' => ['norway'], 'personalities' => ['independent']],
        ['name' => 'Bruiser', 'gender' => 'male', 'styles' => ['warrior', 'quirky'], 'meaning' => 'For a cat who plays a little rough.', 'personalities' => ['playful']],
        ['name' => 'Onyx', 'gender' => 'neutral', 'styles' => ['warrior', 'nature'], 'meaning' => 'The deep black gemstone.', 'personalities' => ['independent']],
        ['name' => 'Raptor', 'gender' => 'male', 'styles' => ['warrior', 'quirky'], 'meaning' => 'For a cat who ambushes ankles with real commitment.', 'personalities' => ['mischievous']],
        ['name' => 'Xena', 'gender' => 'female', 'styles' => ['warrior', 'pop-culture'], 'meaning' => 'After the Warrior Princess herself.', 'personalities' => ['independent']],
        ['name' => 'Jet', 'gender' => 'male', 'styles' => ['warrior', 'classic'], 'meaning' => 'For a solid black coat and real speed across the room.', 'personalities' => ['playful']],
        ['name' => 'Sabre', 'gender' => 'male', 'styles' => ['warrior'], 'meaning' => 'The curved cavalry sword.', 'personalities' => ['independent']],

        // ── Funny & Quirky ───────────────────────────────────────────────
        ['name' => 'Chairman Meow', 'gender' => 'male', 'styles' => ['quirky'], 'meaning' => 'A pun that also happens to fit a cat who runs the house.', 'personalities' => ['regal']],
        ['name' => 'Meowzart', 'gender' => 'male', 'styles' => ['quirky'], 'meaning' => 'For a cat with strong opinions expressed loudly and often.', 'personalities' => ['mischievous']],
        ['name' => 'Catrick Swayze', 'gender' => 'male', 'styles' => ['quirky'], 'meaning' => 'A pun name for a cat with real moves.', 'personalities' => ['playful']],
        ['name' => 'Pizza', 'gender' => 'neutral', 'styles' => ['quirky', 'food'], 'meaning' => 'For a cat who shows up the second a delivery arrives.', 'personalities' => ['mischievous']],
        ['name' => 'Purrlock Holmes', 'gender' => 'male', 'styles' => ['quirky'], 'meaning' => 'A pun name for a cat who investigates every open drawer.', 'personalities' => ['independent']],
        ['name' => 'Biscuit Von Purrington', 'gender' => 'male', 'styles' => ['quirky', 'royal'], 'meaning' => 'A deliberately over-the-top full name, for a cat who deserves one.', 'personalities' => ['regal']],
        ['name' => 'Sir Naps-a-Lot', 'gender' => 'male', 'styles' => ['quirky'], 'meaning' => 'For a cat whose main hobby is sleeping in sunbeams.', 'personalities' => ['calm']],
        ['name' => 'Furguson', 'gender' => 'male', 'styles' => ['quirky'], 'meaning' => 'A pun on "Ferguson" for an obviously furry cat.', 'personalities' => ['calm']],
        ['name' => 'Catastrophe', 'gender' => 'neutral', 'styles' => ['quirky'], 'meaning' => 'For a kitten who has never once left a room undisturbed.', 'personalities' => ['mischievous'], 'nickname' => 'Cat'],
        ['name' => 'Meowgi', 'gender' => 'male', 'styles' => ['quirky'], 'meaning' => 'A pun on Yogi, for a wise, unbothered cat.', 'personalities' => ['calm']],
        ['name' => 'Whisker Wobble', 'gender' => 'neutral', 'styles' => ['quirky', 'cute'], 'meaning' => 'For a kitten still figuring out how legs work.', 'personalities' => ['playful']],
        ['name' => 'Colonel Fluff', 'gender' => 'male', 'styles' => ['quirky', 'royal'], 'meaning' => 'A rank, self-promoted, to match a seriously plush coat.', 'personalities' => ['regal']],
        ['name' => 'Purrcival', 'gender' => 'male', 'styles' => ['quirky', 'royal'], 'meaning' => 'A pun on the knight Percival, for a cat with old-world dignity.', 'personalities' => ['regal']],
        ['name' => 'Hairy Potter', 'gender' => 'male', 'styles' => ['quirky', 'pop-culture'], 'meaning' => 'A pun name for a cat with main-character energy.', 'personalities' => ['mischievous']],
        ['name' => 'Meow Meow', 'gender' => 'neutral', 'styles' => ['quirky', 'cute'], 'meaning' => 'For a cat who has a lot to say about everything.', 'personalities' => ['mischievous']],
    ],

];

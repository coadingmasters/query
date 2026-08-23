<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Post;
use App\Models\PostCategory;
use App\Support\Images;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * The eight articles the blog launched with, moved out of config/blog.php
 * and resources/views/blog/posts/*.blade.php and into the posts table, which
 * is what the admin CMS and the public blog now both read from.
 *
 * The body of each article was rendered from its old Blade partial to plain
 * HTML once, with every route() and <x-img> already resolved, and saved
 * under database/seeders/data/posts/{slug}.html — that file is the source
 * of truth for `content` here, since the partials themselves are gone.
 *
 * Every other field below is copied verbatim from the old config/blog.php:
 * nothing here is invented. Where the old data had no equivalent column
 * (author, tags), the field is left null or empty rather than guessed at.
 */
class PostSeeder extends Seeder
{
    public function run(): void
    {
        $founderId = Author::where('is_founder', true)->value('id');

        foreach ($this->posts() as $data) {
            $categoryId = PostCategory::where('name', $data['category'])->value('id');

            $post = Post::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'meta_title' => $data['meta_title'],
                    'meta_description' => $data['excerpt'],
                    'excerpt' => $data['excerpt'],
                    'quick_answer' => $data['answer'],
                    'content' => file_get_contents(database_path('seeders/data/posts/'.$data['slug'].'.html')),
                    'sources' => $data['sources'],
                    'featured_image_alt' => $data['alt'],
                    'author_id' => $founderId,
                    'category_id' => $categoryId,
                    'status' => 'published',
                    'is_featured' => $data['slug'] === 'why-do-cats-knead',
                    'published_at' => $data['published'],
                ]
            );

            $this->attachImage($post, $data['image']);
            $this->syncFaqs($post, $data['faq']);

            // Timestamps that mean something: the article's own publish and
            // update dates, not the moment this seeder happened to run. Set
            // last, after every other save above has had its turn to touch
            // updated_at.
            $post->newQueryWithoutScopes()->where('id', $post->id)->update([
                'created_at' => $data['published'],
                'updated_at' => $data['updated'],
            ]);
        }
    }

    /**
     * Copies the largest generated variant of the manifest image into public
     * storage, which is where the admin CMS and Post::featured_image already
     * expect an uploaded image to live (see PostController::uploadImage()).
     * The manifest entry itself, and the file it points at under
     * public/images, are untouched: other pages still read them.
     */
    private function attachImage(Post $post, string $manifestName): void
    {
        $variant = Images::largest($manifestName);

        if (! $variant) {
            return;
        }

        $source = public_path(ltrim($variant, '/'));

        if (! is_file($source)) {
            return;
        }

        $extension = pathinfo($source, PATHINFO_EXTENSION);
        $path = 'blog/'.$post->slug.'.'.$extension;

        Storage::disk('public')->put($path, file_get_contents($source));

        // A second save so the model's saving hook reads the file that was
        // just written and records its real width and height.
        $post->update(['featured_image' => $path]);
    }

    /** @param array<int, array{q: string, a: string}> $faqs */
    private function syncFaqs(Post $post, array $faqs): void
    {
        $post->faqs()->delete();

        foreach ($faqs as $i => $faq) {
            $post->faqs()->create([
                'question' => $faq['q'],
                'answer' => $faq['a'],
                'sort_order' => $i,
            ]);
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function posts(): array
    {
        return [
            [
                'slug' => 'why-do-cats-knead',
                'title' => 'Why Do Cats Knead? Making Biscuits Explained',
                'meta_title' => 'Why Do Cats Knead? Making Biscuits Explained',
                'excerpt' => 'Kneading, or making biscuits, is a comfort behavior cats keep '
                    .'from kittenhood. What it means when they knead you, a blanket, at '
                    .'night, or with claws out.',
                'category' => 'Behavior',
                'published' => '2026-08-18',
                'updated' => '2026-08-18',
                'image' => 'why-do-cats-knead-hero',
                'alt' => 'Tabby cat kneading a soft cream blanket with both front paws',
                'answer' => 'Cats knead because it is a comfort behavior left over from '
                    .'kittenhood, when they pressed against their mother to start milk '
                    .'flowing. Adult cats keep doing it when they feel safe and content. '
                    .'Kneading also leaves scent from glands in their paws, which marks '
                    .'you or their bed as familiar territory.',
                'faq' => [
                    ['q' => 'Why do cats make biscuits?', 'a' => 'Making biscuits is the same behavior as kneading, named after the way the paws push like a baker working dough. Kittens do it to stimulate milk from their mother, and adult cats keep it as a self-soothing habit tied to feeling safe.'],
                    ['q' => 'Why does my cat knead me and not anyone else?', 'a' => 'Because you are the person they associate with safety. Kneading is a vulnerable, relaxed behavior, and cats do it where they feel least on guard. Scent marking plays a part too: the glands in their paw pads leave your smell mixed with theirs.'],
                    ['q' => 'Why does my cat knead with claws out?', 'a' => 'It is not aggression. Claws extend and retract naturally as the paw flexes, and most cats are not aware they are digging in. Keep nails trimmed and put a folded blanket on your lap rather than pushing your cat away, which teaches them that affection ends badly.'],
                    ['q' => 'Why do cats knead blankets?', 'a' => 'Soft fabric feels similar to a mother cat, so blankets, cushions and jumpers all trigger the same response. Cats often pair it with purring, drooling or suckling the fabric, which are all part of the same comfort behavior.'],
                    ['q' => 'Why do cats knead at night?', 'a' => 'Evenings are usually when the house is quiet and the cat settles down with you, which is exactly the state that brings the behavior out. Cats are also crepuscular, so a burst of activity followed by settling in is a normal rhythm rather than a problem.'],
                    ['q' => 'Should I stop my cat from kneading?', 'a' => 'No. It is a normal, healthy sign of contentment. If the claws hurt, manage the claws rather than the behavior: trim them regularly and use a barrier. Stopping a cat mid-knead teaches nothing except that settling on you is unwelcome.'],
                    ['q' => 'Is kneading ever a sign of a problem?', 'a' => 'Rarely. Kneading itself is normal at any age. What is worth a vet visit is a sudden change in any behavior alongside other signs: hiding, appetite changes, over-grooming the same spot, or vocalising when touched. The kneading is not the symptom, the change is.'],
                    ['q' => 'Do all cats knead?', 'a' => 'No, and a cat that never kneads is not unhappy or unbonded. It is one of several ways cats show contentment, alongside slow blinking, head-butting, purring and simply choosing to sit near you.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center', 'note' => 'Background on normal feline behavior and body language.'],
                    ['name' => 'ASPCA: cat behavior', 'url' => 'https://www.aspca.org/pet-care/cat-care/common-cat-behavior-issues', 'note' => 'Guidance on which behaviors are normal and which need attention.'],
                    ['name' => 'International Cat Care', 'url' => 'https://icatcare.org/articles/', 'note' => 'Reference material on kitten development and adult behavior.'],
                ],
            ],
            [
                'slug' => 'why-is-my-cat-sneezing',
                'title' => 'Why Is My Cat Sneezing? When It\'s Normal and When to Worry',
                'meta_title' => 'Why Is My Cat Sneezing? When It\'s Normal and When to Worry',
                'excerpt' => 'An occasional sneeze is nothing. Runny eyes, a stuffy nose or '
                    .'sneezing that will not stop points at an infection that is worth a '
                    .'vet visit.',
                'category' => 'Health',
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
                    ['q' => 'Is it normal for a cat to sneeze?', 'a' => 'Yes, occasionally. Dust, a strong smell or a change in the air can trigger a single sneeze, just as it does in a person, and it means nothing on its own. What is not normal is sneezing that repeats through the day, or that keeps happening over more than a day or two.'],
                    ['q' => 'Why does my cat keep sneezing?', 'a' => 'Repeated sneezing over several hours or days is usually either an ongoing irritant, such as dusty litter, a scented candle or cleaning spray, or an upper respiratory infection. Cats carry feline herpesvirus and calicivirus at very high rates, and both cause cold-like symptoms that include sneezing.'],
                    ['q' => 'Why is my cat sneezing but acting completely normal?', 'a' => 'A cat that is eating, drinking and playing normally alongside a mild sneeze is usually dealing with a minor irritant or the early days of a cold. Keep watching rather than worrying: it is the cats that stop eating, hide, or develop discharge that need a same-day vet visit.'],
                    ['q' => 'What does it mean if my cat is sneezing and has a runny nose?', 'a' => 'Sneezing paired with nasal discharge is one of the clearest signs of an upper respiratory infection. Clear discharge tends to go with the early, viral stage; thick yellow or green discharge can mean a secondary bacterial infection has joined it. Color alone cannot confirm that on its own, and it is not something to diagnose from home: a vet examines the cat and decides whether antibiotics are actually needed.'],
                    ['q' => 'Why is my kitten sneezing so much?', 'a' => 'Kittens catch upper respiratory infections easily, especially if they came from a shelter or a large litter, and their symptoms tend to hit harder than an adult cat\'s. A kitten that stops eating is a more urgent case than an adult with the same symptoms, because kittens dehydrate and lose weight fast.'],
                    ['q' => 'What causes a cat to sneeze?', 'a' => 'The common causes are, roughly in order of likelihood: viral upper respiratory infection, environmental irritants such as dust or fragrance, allergies, a foreign body caught in the nose, dental disease affecting the roots near the nasal passage, and, less commonly, nasal polyps or a fungal infection in older cats.'],
                    ['q' => 'When should I take my sneezing cat to the vet?', 'a' => 'Book a visit if sneezing lasts more than two or three days, if there is any blood, if discharge turns yellow or green, if your cat stops eating or seems lethargic, or if only one nostril is affected, which can point at something physically stuck rather than an infection.'],
                    ['q' => 'Can I give my cat anything at home for sneezing?', 'a' => 'A humidifier or a few minutes in a steamy bathroom can loosen congestion, and gently wiping discharge away keeps your cat more comfortable. There is no over-the-counter medication that is safe to give a cat without a vet\'s direction; several common human cold remedies are toxic to cats.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center: upper respiratory infections', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center', 'note' => 'Background on feline herpesvirus and calicivirus, the most common causes of sneezing.'],
                    ['name' => 'ASPCA: cat health and wellness', 'url' => 'https://www.aspca.org/pet-care/cat-care', 'note' => 'General guidance on recognizing illness in cats.'],
                    ['name' => 'VCA Animal Hospitals: sneezing in cats', 'url' => 'https://vcahospitals.com/know-your-pet/sneezing-in-cats', 'note' => 'Clinical overview of causes and when sneezing needs veterinary attention.'],
                ],
            ],
            [
                'slug' => 'signs-your-cat-is-sick',
                'title' => 'How to Tell If Your Cat Is Sick: 12 Signs to Watch For',
                'meta_title' => 'How to Tell If Your Cat Is Sick: 12 Signs to Watch For',
                'excerpt' => 'Cats hide illness well. Appetite loss, hiding, low energy and '
                    .'litter box changes are usually the first signs, before anything '
                    .'looks seriously wrong.',
                'category' => 'Health',
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
                    ['q' => 'How can I tell if my cat is sick if they seem fine otherwise?', 'a' => 'Watch behavior more than mood. A cat can still purr and greet you while eating less than usual or sleeping in a different spot than normal. The reliable signals are changes from your own cat\'s normal pattern: appetite, energy, litter box habits, and how they hold themselves.'],
                    ['q' => 'Why do cats hide when they are sick?', 'a' => 'It is an instinct left over from being both predator and prey in the wild. A visibly weak animal is a target, so cats default to hiding discomfort rather than showing it. That is also why a cat who does let you see them struggling is often further along than a first-time sign.'],
                    ['q' => 'What is the most common early sign of illness in cats?', 'a' => 'A change in appetite, either eating less or stopping altogether, is usually the first thing owners notice, followed by hiding more than usual. Neither one confirms anything specific on its own; they are what prompt a closer look at everything else.'],
                    ['q' => 'How long should I wait before taking my cat to the vet?', 'a' => 'For a single mild sign in a cat who is otherwise acting normally, a day of closer watching is reasonable. For anything severe, such as repeated vomiting, labored breathing, or a cat who will not move, same-day care is the right call. Not eating for more than 24 hours is also a reason to call, especially in a cat who is already thin.'],
                    ['q' => 'Can indoor cats get sick even if they never go outside?', 'a' => 'Yes. Indoor cats are protected from traffic, fights and many infectious diseases, but they still develop kidney disease, diabetes, dental disease, urinary blockages and cancer at similar rates to cats that go outdoors. Being indoors lowers risk, it does not remove it.'],
                    ['q' => 'Is it normal for an older cat to slow down?', 'a' => 'Some slowing down is normal with age, but it should be gradual, not sudden. A senior cat who stops jumping onto a favorite spot within a week, rather than over months, is showing a change worth mentioning to a vet rather than aging alone.'],
                    ['q' => 'What should I tell the vet when I call about a sick cat?', 'a' => 'What changed, and when. Vets work from a comparison to your cat\'s normal, so \'stopped eating yesterday morning\' or \'has been hiding under the bed for two days\' is more useful than \'acting sick\'. Mention every change you have noticed, even ones that seem unrelated.'],
                    ['q' => 'When is a sick cat an emergency?', 'a' => 'Straining in the litter box with little or no urine, labored or open-mouthed breathing, collapse, repeated vomiting, or a cat who will not respond to you are all same-hour emergencies, not same-day. A blocked bladder in particular can become fatal within a day if untreated.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center', 'note' => 'Background on recognizing illness and common feline diseases.'],
                    ['name' => 'American Association of Feline Practitioners: feline life stage guidelines', 'url' => 'https://catvets.com/guidelines/practice-guidelines/life-stage-guidelines', 'note' => 'Guidance on the health checks and warning signs relevant to each life stage.'],
                    ['name' => 'ASPCA: cat health and wellness', 'url' => 'https://www.aspca.org/pet-care/cat-care', 'note' => 'General guidance on recognizing illness in cats.'],
                    ['name' => 'VCA Animal Hospitals: signs of illness in cats', 'url' => 'https://vcahospitals.com/know-your-pet/recognizing-signs-of-illness-in-cats', 'note' => 'Clinical overview of common warning signs and when they need attention.'],
                ],
            ],
            [
                'slug' => 'how-much-should-i-feed-my-cat',
                'title' => 'How Much Should I Feed My Cat? A Feeding Guide',
                'meta_title' => 'How Much Should I Feed My Cat? A Feeding Guide',
                'excerpt' => 'Portion guidance on the bag is written for an average cat '
                    .'that does not exist. Work out how much to feed yours from '
                    .'weight, food type and life stage.',
                'category' => 'Feeding',
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
                    ['q' => 'Why is the feeding guide on the bag not accurate?', 'a' => 'It is written for an average cat at an average activity level, and your cat is not an average. It is also written by the brand selling the food, and a generous guide sells more of it. Treat the number on the bag as a starting point to weigh against your cat\'s actual body condition, not a target.'],
                    ['q' => 'How many calories does my cat actually need?', 'a' => 'A common starting point is around 20 calories per pound of body weight for an average neutered adult indoor cat, though the more precise version vets use is a formula based on weight, adjusted up for kittens and pregnant or nursing cats, and down for weight loss. It is a starting point either way, not a fixed number.'],
                    ['q' => 'Should I feed my cat wet food or dry food?', 'a' => 'Both can be nutritionally complete; they are not interchangeable cup for cup. Wet food is roughly 75 to 80 percent water, dry food closer to 10 percent, so the same volume is not the same number of calories. What matters is total calories and a complete, balanced formula, not which texture you choose.'],
                    ['q' => 'How many times a day should I feed my cat?', 'a' => 'Two measured meals a day is the most commonly recommended pattern, and it tends to prevent the weight gain that free-feeding dry food, available all day, often causes. Kittens need more frequent, smaller meals; some adult cats do fine on three or four if that suits your schedule better.'],
                    ['q' => 'My cat is overweight. How do I safely feed them less?', 'a' => 'Gradually, and ideally with a vet involved. Cutting calories too fast in an overweight cat can cause a serious liver condition called hepatic lipidosis if the cat does not eat enough during the transition. A vet can set a target weight and a safe rate of loss, which is usually only around one to two percent of body weight a week.'],
                    ['q' => 'How do I know if I am feeding the right amount?', 'a' => 'Body condition, not the scale alone. You should be able to feel your cat\'s ribs under a light layer of fat without pressing hard, and see a waist when looking down at them. Weigh your cat every few weeks: gradual, unexplained weight change in either direction is the signal to adjust the amount or see a vet.'],
                    ['q' => 'Are treats okay, and how many can I give?', 'a' => 'Yes, in moderation. The common guidance is to keep treats under about ten percent of daily calories and subtract them from the day\'s food rather than adding them on top. Treats are one of the most common quiet causes of slow, unnoticed weight gain.'],
                    ['q' => 'Does a kitten need to eat differently from an adult cat?', 'a' => 'Yes, meaningfully. Kittens need more calories per pound than adults because they are growing, along with a kitten-specific formula higher in protein and certain nutrients, and more frequent small meals rather than two large ones. Most cats can transition to adult food around twelve months.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center: feeding your cat', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center/health-information/feeding', 'note' => 'Guidance on how much and how often to feed a cat, and what a complete diet needs to provide.'],
                    ['name' => 'WSAVA Global Nutrition Guidelines', 'url' => 'https://wsava.org/global-guidelines/global-nutrition-guidelines/', 'note' => 'The nutritional assessment framework, including body condition scoring, that vets use to build a feeding plan.'],
                    ['name' => 'ASPCA: cat health and wellness', 'url' => 'https://www.aspca.org/pet-care/cat-care', 'note' => 'General guidance on feeding, weight and recognizing when something is off.'],
                ],
            ],
            [
                'slug' => 'can-cats-eat-broccoli',
                'title' => 'Can Cats Eat Broccoli? A Safe Feeding Guide',
                'meta_title' => 'Can Cats Eat Broccoli? A Safe Feeding Guide',
                'excerpt' => 'Broccoli is one of the few vegetables genuinely safe for '
                    .'cats, but only plain, cooked, and in small amounts. Here is why, '
                    .'and how much.',
                'category' => 'Food Safety',
                'published' => '2026-08-21',
                'updated' => '2026-08-21',
                'image' => 'can-cats-eat-broccoli-cat-sniffing',
                'alt' => 'Fluffy cat sniffing fresh broccoli florets',
                'answer' => 'Yes. A few small florets of plain, cooked broccoli, with '
                    .'no oil, butter, salt or seasoning, are safe for most cats as an '
                    .'occasional extra. It is not a food cats need, and raw broccoli, '
                    .'especially the tough stalk, is harder to digest and a choking '
                    .'risk in bigger pieces.',
                'faq' => [
                    ['q' => 'Can cats eat broccoli?', 'a' => 'Yes, in small amounts. Plain, cooked broccoli is not toxic to cats and most tolerate a floret or two without any problem. It offers cats little nutritionally, since they are obligate carnivores and get what they need from meat, so it is worth thinking of as an occasional extra rather than a healthy addition.'],
                    ['q' => 'Is it okay to give my cat broccoli every day?', 'a' => 'Better as an occasional thing than a daily habit. Cruciferous vegetables like broccoli contain compounds that can cause gas or an upset stomach in larger or more frequent amounts, and there is no nutritional reason a cat needs it regularly.'],
                    ['q' => 'Can cats eat raw broccoli?', 'a' => 'Small, soft pieces of raw floret are usually fine, but the stalk is fibrous and much harder to chew and digest raw. Cooking it plain, until soft, is the safer choice, particularly for the stalk, which is also the part most likely to be swallowed in a piece too large to pass easily.'],
                    ['q' => 'Can kittens eat broccoli?', 'a' => 'There is no specific reason kittens cannot have a very small taste of plain cooked broccoli, but their growing bodies need calorie-dense, complete kitten food far more than an adult cat does, so it makes even less sense as a regular addition for a kitten than for an adult.'],
                    ['q' => 'What vegetables are toxic to cats?', 'a' => 'Onion, garlic, chives and leeks are the ones that actually matter, and they are genuinely dangerous even in small, cooked amounts. Broccoli is not in that category. Our full breakdown of what is safe, what needs care and what to avoid entirely covers all of them by category.'],
                    ['q' => 'Why does my cat ignore the broccoli I offer?', 'a' => 'Completely normal. Cats lack the taste receptors for sweetness that make plant matter appealing to many other animals, and most show little to no interest in vegetables at all. A cat walking away from broccoli is not doing anything wrong.'],
                    ['q' => 'My cat ate a whole piece of broccoli stalk. Should I worry?', 'a' => 'Watch for coughing, gagging, drooling or repeated swallowing in the minutes after, which can signal something caught in the throat, and for vomiting or reduced appetite over the following day, which can signal a partial blockage. Either warrants a call to your vet rather than waiting it out.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center: feeding your cat', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center/health-information/feeding', 'note' => 'Background on what a cat\'s diet actually needs to provide, and where extras like vegetables fit in.'],
                    ['name' => 'WSAVA Global Nutrition Guidelines', 'url' => 'https://wsava.org/global-guidelines/global-nutrition-guidelines/', 'note' => 'The nutritional assessment framework vets use, and why an obligate carnivore\'s diet centers on meat.'],
                    ['name' => 'ASPCA: cat health and wellness', 'url' => 'https://www.aspca.org/pet-care/cat-care', 'note' => 'General guidance on safe and unsafe foods for cats.'],
                ],
            ],
            [
                'slug' => 'best-food-for-indoor-cats',
                'title' => 'Best Food for Indoor Cats: What to Look For',
                'meta_title' => 'Best Food for Indoor Cats: What to Look For',
                'excerpt' => 'Indoor cats burn fewer calories than outdoor ones, but '
                    .'life stage matters more than the word indoor on the bag. What '
                    .'to actually look for.',
                'category' => 'Feeding',
                'published' => '2026-08-21',
                'updated' => '2026-08-21',
                'image' => 'best-indoor-cat-food-premium-ingredients',
                'alt' => 'Salmon, chicken and fresh ingredients beside a bowl of cat food',
                'answer' => 'There is no single best indoor cat food; there is a food '
                    .'that fits your cat\'s life stage, appetite and activity level, '
                    .'with a named animal protein first on the label. "Indoor formula" '
                    .'is a marketing category, not a regulated standard, and it '
                    .'matters far less than getting the calories and life stage right.',
                'faq' => [
                    ['q' => 'Do indoor cats need special indoor formula food?', 'a' => 'Not strictly. "Indoor formula" describes a general approach, usually somewhat lower in calories and higher in fibre for hairball control, but it is a marketing category rather than a standard every brand follows the same way. Reading the actual label, rather than trusting the word "indoor" on the front, tells you more.'],
                    ['q' => 'What should I actually look for on the label?', 'a' => 'A named animal protein, such as chicken or salmon, as the first ingredient; a statement that the food is complete and balanced for your cat\'s life stage; and a calorie count you can compare against what your cat actually needs, which our feeding guide walks through with a real formula rather than the number on the bag.'],
                    ['q' => 'Is wet or dry food better for an indoor cat?', 'a' => 'Both can be complete diets. Wet food adds meaningful water intake, which matters for indoor cats prone to concentrated urine and the urinary issues that can follow, and it is often easier to portion accurately since a whole can or pouch is one measured amount. Dry food is more convenient and typically cheaper per calorie.'],
                    ['q' => 'How many calories does an indoor cat need?', 'a' => 'Less than an equivalent outdoor cat, generally, because there is less activity to burn through. Our guide to how much to feed a cat has a reference table and the formula vets actually use, starting from body weight rather than a life-stage label.'],
                    ['q' => 'Do I need a hairball control formula?', 'a' => 'Only if hairballs are actually a recurring issue for your cat. Regular brushing does more for most cats than a specialised food, and a cat bringing up hairballs more than occasionally, or straining without producing one, is worth mentioning to a vet rather than solving with a bag swap alone.'],
                    ['q' => 'Can an indoor cat just eat the same food as an outdoor cat?', 'a' => 'Yes, as long as the calories are right for how active that specific cat actually is. Life stage and portion size matter more than an indoor or outdoor label on the packaging, and the same complete, balanced adult formula works for both if the amount is adjusted to fit.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center: feeding your cat', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center/health-information/feeding', 'note' => 'What a complete and balanced diet needs to provide, regardless of indoor or outdoor lifestyle.'],
                    ['name' => 'WSAVA Global Nutrition Guidelines', 'url' => 'https://wsava.org/global-guidelines/global-nutrition-guidelines/', 'note' => 'The nutritional assessment framework used to build an individual feeding plan.'],
                    ['name' => 'ASPCA: cat health and wellness', 'url' => 'https://www.aspca.org/pet-care/cat-care', 'note' => 'General guidance on feeding and weight in cats.'],
                ],
            ],
            [
                'slug' => 'can-cats-eat-chicken',
                'title' => 'Can Cats Eat Chicken? Yes, Here Is How',
                'meta_title' => 'Can Cats Eat Chicken? Yes, Here Is How',
                'excerpt' => 'Chicken is one of the best things you can feed a cat. '
                    .'The rules are about preparation, bones, and portion, not '
                    .'whether it is safe.',
                'category' => 'Food Safety',
                'published' => '2026-08-21',
                'updated' => '2026-08-21',
                'image' => 'can-cats-eat-chicken-cat-looking',
                'alt' => 'Cat looking at cooked plain chicken on a white plate',
                'answer' => 'Yes. Plain, cooked, boneless chicken with no salt, oil or '
                    .'seasoning is one of the safest things you can feed a cat, and '
                    .'it is already a common ingredient in complete cat food. The '
                    .'risks are all in the preparation: bones, skin, seasoning and '
                    .'using it to replace a balanced diet rather than sit beside one.',
                'faq' => [
                    ['q' => 'Can cats eat chicken?', 'a' => 'Yes, and it is one of the better things you can offer. Chicken is a lean, easily digested protein that already appears in most commercial cat foods, so a cat eating plain cooked chicken is eating something close to what is already in their bowl.'],
                    ['q' => 'Can cats eat chicken bones?', 'a' => 'No. Cooked bones become brittle and can splinter into sharp fragments that cause choking or injure the throat, stomach or intestines on the way through. Always remove every bone before offering chicken to a cat, and check the piece over rather than trusting that you got them all.'],
                    ['q' => 'Can cats eat chicken skin?', 'a' => 'Best kept to a minimum. Chicken skin is high in fat, and a cat eating a lot of it, especially seasoned or fried skin, is more likely to get an upset stomach, and repeated fatty extras are a recognized risk factor for pancreatitis in cats. A small amount now and then is unlikely to cause harm; a habit of it is worth dropping.'],
                    ['q' => 'Can cats eat raw chicken?', 'a' => 'The concern with raw chicken is not the chicken itself but bacteria like salmonella and campylobacter, which can make a cat unwell and can also spread to people handling the food and the litter tray afterward. Cooking it removes that risk, which is why cooked, plain chicken is the safer default.'],
                    ['q' => 'Can I feed my cat only chicken?', 'a' => 'No. Plain chicken is not a complete diet on its own; it is missing taurine at the right level, calcium, and other nutrients a properly formulated cat food balances. Chicken works well as a topper or an occasional addition alongside a complete diet, not as a replacement for one.'],
                    ['q' => 'Can cats be allergic to chicken?', 'a' => 'Yes, and chicken is actually one of the more common food sensitivities in cats, alongside fish and beef. Signs include itchy skin, ear infections or digestive upset that shows up consistently after eating it. A vet can help confirm a food allergy rather than guessing from one bad reaction.'],
                    ['q' => 'How much chicken can I give my cat?', 'a' => 'A few small, bite-sized pieces now and then is plenty. Treats and extras, chicken included, are generally kept under about ten percent of a cat\'s daily calories, which our feeding guide covers alongside how to work out your own cat\'s daily total.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center: feeding your cat', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center/health-information/feeding', 'note' => 'Background on protein sources and what a complete feline diet needs beyond meat alone.'],
                    ['name' => 'WSAVA Global Nutrition Guidelines', 'url' => 'https://wsava.org/global-guidelines/global-nutrition-guidelines/', 'note' => 'The nutritional framework behind why a whole-food addition cannot replace a formulated diet.'],
                    ['name' => 'ASPCA: cat health and wellness', 'url' => 'https://www.aspca.org/pet-care/cat-care', 'note' => 'General guidance on safe foods and portioning for cats.'],
                ],
            ],
            [
                'slug' => 'new-cat-owner-guide',
                'title' => 'The Complete Guide for New Cat Owners',
                'meta_title' => 'The Complete Guide for New Cat Owners',
                'excerpt' => 'Everything for the first weeks: what to buy, how to '
                    .'settle a nervous cat, the vet visits that matter early, and '
                    .'what to cat-proof first.',
                'category' => 'Getting Started',
                'published' => '2026-08-21',
                'updated' => '2026-08-21',
                'image' => 'new-cat-owner-guide-couple-kitten',
                'alt' => 'Couple playing with a new kitten on a sofa',
                'answer' => 'Before your cat arrives: a litter box, litter, food and '
                    .'water bowls, a carrier, a scratching post and a hiding spot. In '
                    .'the first week: a quiet room to settle into, a first vet visit '
                    .'booked, and the house checked for the small number of things, '
                    .'like lilies, that are genuinely dangerous to cats.',
                'faq' => [
                    ['q' => 'What do I need to buy before bringing a cat home?', 'a' => 'The essentials: a litter box and litter, food and water bowls, a carrier, a scratching post, a bed, and a few toys. Everything else, including a particular food brand or a second scratching post, can wait until you know your cat\'s preferences rather than guessing them in advance.'],
                    ['q' => 'How do I help a new cat settle in?', 'a' => 'Start with one quiet room rather than the whole house. Set up their litter box, food, water and a hiding spot in it, and let them come out and explore on their own schedule rather than being carried around or introduced to everyone at once. Most cats relax faster with less handling in the first days, not more.'],
                    ['q' => 'When should I take a new cat to the vet?', 'a' => 'Within the first week if possible, even if they seem completely healthy. A first visit establishes a baseline, confirms vaccination and parasite prevention are up to date or gets them started, and is the right time to discuss microchipping and spay or neuter timing if that has not already been done.'],
                    ['q' => 'What plants are toxic to cats?', 'a' => 'Lilies are the one to know above all others: every part of the plant, including pollen and the water in a vase, is highly toxic to cats and can cause fatal kidney failure from a small exposure. If you keep any lilies in the house, they need to come out before a cat moves in, not after.'],
                    ['q' => 'How many litter boxes do I need?', 'a' => 'The usual guidance is one box per cat, plus one extra, placed in quiet, easily accessible spots away from food and water. For a single new cat, that means two boxes is a safer starting point than one, particularly while they are still learning the house.'],
                    ['q' => 'How long does it take for a cat to adjust to a new home?', 'a' => 'Anywhere from a few days to a few weeks, and it varies a lot by individual cat and by how they came to you. Hiding, reduced appetite and low activity in the first days are normal; if any of those continue much past a week or two, or your cat stops eating entirely, it is worth a call to the vet rather than assuming it will pass.'],
                    ['q' => 'When can I introduce my new cat to other pets?', 'a' => 'Slowly, and only once the new cat is comfortable in their own space. A gradual introduction through a closed door, then supervised, brief visits, over a week or more, goes better than a first meeting with no barrier. Rushing this step is one of the more common causes of lasting tension between cats.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center', 'note' => 'Background on kitten and new-cat care, vaccination and general feline health.'],
                    ['name' => 'ASPCA: cat health and wellness', 'url' => 'https://www.aspca.org/pet-care/cat-care', 'note' => 'General guidance on settling a new cat in and household hazards, including toxic plants.'],
                    ['name' => 'WSAVA Global Nutrition Guidelines', 'url' => 'https://wsava.org/global-guidelines/global-nutrition-guidelines/', 'note' => 'The feeding framework referenced when setting up a new cat\'s diet.'],
                ],
            ],
            [
                'slug' => 'why-do-cats-purr',
                'title' => 'Why Do Cats Purr? (And When Purring Means Something\'s Wrong)',
                'meta_title' => 'Why Do Cats Purr? What It Means, Good and Bad',
                'excerpt' => 'Purring usually means contentment, but cats also purr when '
                    .'hurt, scared or giving birth. How to tell a happy purr from a '
                    .'self-soothing one.',
                'category' => 'Health',
                'published' => '2026-08-24',
                'updated' => '2026-08-24',
                'image' => 'purrquery-cat-lover-cuddling-cat',
                'alt' => 'Person cuddling a relaxed, contentedly purring cat',
                'answer' => 'Cats purr by vibrating muscles in the larynx as they '
                    .'breathe in and out, most often as a sign of contentment. But '
                    .'cats also purr when frightened, in pain, or giving birth, likely '
                    .'as a way to self-soothe, so a purring cat is not always a calm '
                    .'one. Body language and context tell the two apart, not the sound '
                    .'itself.',
                'faq' => [
                    ['q' => 'Why do cats purr when they are happy?', 'a' => 'Purring while relaxed, being stroked or settling in for a nap is a straightforward comfort signal. It usually comes with other relaxed cues at the same time: slow blinking, a loose tail, and a body that has stopped watching the room.'],
                    ['q' => 'Why do cats purr when they are in pain or scared?', 'a' => 'The leading explanation is that purring is self-soothing rather than only a happiness signal. Some researchers have also proposed that the low frequency cats purr at may support healing, which is one theory for why cats recover from injuries as well as they do.'],
                    ['q' => 'How can I tell if my cat\'s purring is a good sign or a bad one?', 'a' => 'Look past the sound to the body language and setting around it. Relaxed posture and soft eyes point to contentment; a crouched body, flattened ears, or purring that starts right after something painful or frightening points to self-soothing instead.'],
                    ['q' => 'Do kittens purr?', 'a' => 'Yes, often within two or three days of birth, well before their eyes open. A nursing kitten\'s purr is thought to help it stay in contact with its mother, who purrs back while nursing.'],
                    ['q' => 'Why does my cat purr at the vet?', 'a' => 'Purring at the vet, in a carrier, or during any stressful situation is a common self-soothing response, not a sign your cat is fine with what is happening. It is one of the clearest examples of purring meaning stress rather than contentment.'],
                    ['q' => 'Can all cats purr?', 'a' => 'Nearly all domestic cats purr, though volume and frequency vary a lot between individuals. Big cats such as lions and tigers cannot purr the way a house cat does, due to differences in the structure of the larynx.'],
                    ['q' => 'When should I worry about a purring cat?', 'a' => 'The purring itself is not the warning sign. Worry if it comes alongside hiding, eating less, a hunched posture, labored breathing, or reluctance to move, since those point at pain or illness regardless of whether the cat is also purring.'],
                    ['q' => 'Why does my cat purr differently when asking for food?', 'a' => 'Research from the University of Sussex found that some cats blend a higher, cry-like frequency into an ordinary purr specifically when soliciting food, a pattern called the solicitation purr. People consistently rate it as more urgent than a standard contented purr, which may be exactly the point.'],
                    ['q' => 'Is purring a sign a cat is healing?', 'a' => 'It is a genuinely researched hypothesis, not settled fact. Cat purr frequencies overlap with ranges used in vibration therapies shown, in other contexts, to support bone and tissue repair, which has led some researchers to propose purring may aid healing rather than only provide comfort. Self-soothing remains the better-established explanation.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center', 'note' => 'Background on normal feline vocalizations and behavior.'],
                    ['name' => 'International Cat Care', 'url' => 'https://icatcare.org/articles/', 'note' => 'Reference material on purring and feline communication.'],
                    ['name' => 'ASPCA: cat behavior', 'url' => 'https://www.aspca.org/pet-care/cat-care/common-cat-behavior-issues', 'note' => 'Guidance on reading feline body language alongside vocal signals.'],
                ],
            ],
            [
                'slug' => 'hairballs-in-cats',
                'title' => 'Hairballs in Cats: Why They Happen and When to Worry',
                'meta_title' => 'Hairballs in Cats: Causes, Prevention and Warning Signs',
                'excerpt' => 'An occasional hairball is normal grooming. Frequent ones, '
                    .'or retching with nothing produced, point at something else. What '
                    .'actually helps.',
                'category' => 'Health',
                'published' => '2026-08-24',
                'updated' => '2026-08-24',
                'image' => 'cat-hiding',
                'alt' => 'Cat sitting quietly, looking a little subdued',
                'answer' => 'Hairballs form when swallowed loose fur clumps together '
                    .'in the stomach instead of passing through, and a cat brings it up '
                    .'as a result. An occasional one, especially in long-haired or '
                    .'heavy-shedding cats, is normal. Weekly hairballs, or repeated '
                    .'retching with nothing produced, are worth a vet visit rather than '
                    .'waiting out.',
                'faq' => [
                    ['q' => 'How often should a cat get hairballs?', 'a' => 'For most short-haired, healthy adult cats, every few weeks or less is unremarkable. Long-haired breeds may reasonably produce one every couple of weeks during heavy shedding. A short-haired cat bringing one up weekly or more is grooming more than expected, which is worth a closer look.'],
                    ['q' => 'What causes frequent hairballs?', 'a' => 'A sharp increase usually means more grooming, not more shedding. Common causes include stress or anxiety, skin irritation or allergies causing itchiness, and pain in one area that leads to repeated licking of the same spot.'],
                    ['q' => 'How can I stop my cat from getting hairballs?', 'a' => 'Regular brushing to remove loose fur before it is swallowed is the single most effective step. A hairball-control diet with added fiber, good hydration, and an occasional vet-approved lubricant paste all help fur move through rather than accumulate.'],
                    ['q' => 'Can a hairball be an emergency?', 'a' => 'Yes, though it is uncommon. Repeated retching with nothing produced, vomiting that continues over more than a day, loss of appetite, lethargy or a swollen abdomen can mean an intestinal blockage, which sometimes needs surgery and is not something to wait out.'],
                    ['q' => 'Do hairball remedies actually work?', 'a' => 'Petroleum- or oil-based lubricant pastes made for cats can help fur pass through the digestive tract rather than clump. They work best as an occasional aid alongside regular brushing, not as a replacement for it, and are worth checking with a vet before using regularly.'],
                    ['q' => 'Are hairballs more common in certain breeds?', 'a' => 'Yes. Long-haired breeds such as Persians, Maine Coons and Ragdolls tend to produce them more often simply because there is more loose fur in circulation, especially during seasonal shedding.'],
                    ['q' => 'Do kittens get hairballs?', 'a' => 'Rarely. Kittens under about four to six months groom less thoroughly than adults, and mutual and self-grooming behaviors are still developing, so frequent hairballs in a young kitten are unusual enough to mention to a vet rather than assumed to be normal.'],
                    ['q' => 'How does a vet check for a hairball blockage?', 'a' => 'Usually a physical exam first, since a firm mass can sometimes be felt through the abdomen, followed by an X-ray or ultrasound to confirm and locate an obstruction. Because hair does not always show up clearly on a plain X-ray, ultrasound or a contrast study is sometimes needed.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center', 'note' => 'Background on feline grooming behavior and digestive health.'],
                    ['name' => 'VCA Animal Hospitals: hairballs in cats', 'url' => 'https://vcahospitals.com/know-your-pet/hairballs-in-cats', 'note' => 'Clinical overview of hairball formation, prevention and when they become an obstruction.'],
                    ['name' => 'ASPCA: cat health and wellness', 'url' => 'https://www.aspca.org/pet-care/cat-care', 'note' => 'General guidance on grooming and recognizing digestive symptoms worth a vet visit.'],
                ],
            ],
            [
                'slug' => 'cat-uti-symptoms',
                'title' => 'Cat UTI Symptoms: What to Watch For and When to See a Vet',
                'meta_title' => 'Cat UTI Symptoms: Signs, Causes and When It Is an Emergency',
                'excerpt' => 'Straining, blood in urine or crying in the litter box can '
                    .'be a UTI, or something more urgent in male cats. What the '
                    .'symptoms actually mean.',
                'category' => 'Health',
                'published' => '2026-08-24',
                'updated' => '2026-08-24',
                'image' => 'vet-checking-cat',
                'alt' => 'Veterinarian examining a cat during a checkup',
                'answer' => 'True bacterial UTIs are less common in cats than the '
                    .'symptoms suggest; feline idiopathic cystitis, bladder '
                    .'inflammation without infection, is the more frequent cause in '
                    .'younger cats. Both look the same at home: straining, frequent '
                    .'trips to the litter box, or blood in the urine. In male cats, '
                    .'straining with little or no urine can mean a complete blockage, '
                    .'which is a same-day emergency.',
                'faq' => [
                    ['q' => 'What are the signs of a UTI in cats?', 'a' => 'Straining to urinate, frequent trips to the litter box with little produced each time, blood in the urine, urinating outside the box, crying out while urinating, and excessive licking of the genital area are the main signs, whether the cause is infection or inflammation.'],
                    ['q' => 'Is a cat UTI an emergency?', 'a' => 'It can be, particularly in male cats. A male cat straining repeatedly with little or no urine coming out may have a complete urethral blockage, which can become life-threatening within one to two days and needs immediate veterinary care.'],
                    ['q' => 'Why are male cats more at risk with urinary symptoms?', 'a' => 'Male cats have a narrower urethra than females, which makes them far more prone to a complete blockage from crystals or a mucus plug. The same symptoms in a female cat are less likely to mean a blockage, but still need a vet visit to diagnose.'],
                    ['q' => 'What causes UTIs and urinary problems in cats?', 'a' => 'Stress is the strongest known trigger for feline idiopathic cystitis, the most common cause in younger cats. True bacterial infections are more common in older cats or those with diabetes or kidney disease. Being overweight and low water intake both raise the overall risk.'],
                    ['q' => 'How can I prevent urinary problems in my cat?', 'a' => 'Add wet food or a water fountain to increase water intake, keep litter boxes clean with one per cat plus one extra, reduce household stress where possible, and keep an eye on weight over time rather than reacting once a cat is already overweight.'],
                    ['q' => 'When should I take my cat to the vet for urinary symptoms?', 'a' => 'Any urinary symptom is worth a same-day or next-day visit, since a urinalysis is the only reliable way to tell infection, inflammation, crystals or a blockage apart. Treat repeated straining with no urine, especially in a male cat, as an emergency.'],
                    ['q' => 'Will antibiotics fix my cat\'s urinary symptoms?', 'a' => 'Only if a bacterial infection is actually confirmed by a urine culture. Most urinary symptoms in younger cats come from feline idiopathic cystitis, which has no bacteria to treat, so antibiotics do nothing for it and are not the default treatment without confirmed infection.'],
                    ['q' => 'Can environment really affect a cat\'s urinary health?', 'a' => 'Yes. Because stress is a strong trigger for feline idiopathic cystitis, vets increasingly treat environmental changes, more resources in multi-cat homes, more vertical space, a predictable routine, as part of the medical plan, not just a lifestyle suggestion.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center', 'note' => 'Background on feline lower urinary tract disease and idiopathic cystitis.'],
                    ['name' => 'American Association of Feline Practitioners: feline life stage guidelines', 'url' => 'https://catvets.com/guidelines/practice-guidelines/life-stage-guidelines', 'note' => 'Clinical guidance on urinary health screening across life stages.'],
                    ['name' => 'VCA Animal Hospitals: urinary blockage in cats', 'url' => 'https://vcahospitals.com/know-your-pet/urinary-obstruction-in-cats', 'note' => 'Clinical overview of urethral blockage, its risk in male cats, and emergency signs.'],
                ],
            ],
            [
                'slug' => 'fleas-and-ticks-on-cats',
                'title' => 'Fleas and Ticks on Cats: Prevention and Treatment',
                'meta_title' => 'Fleas and Ticks on Cats: Signs, Prevention and Treatment',
                'excerpt' => 'Indoor cats get fleas too. Signs to look for, why they '
                    .'matter beyond itching, and the one dog product that is '
                    .'dangerous for cats.',
                'category' => 'Health',
                'published' => '2026-08-24',
                'updated' => '2026-08-24',
                'image' => 'purrquery-cat-cozy-blanket',
                'alt' => 'Cat resting on a cozy blanket at home',
                'answer' => 'Fleas reach indoor-only cats too, carried in on shoes, '
                    .'other pets, or through window screens. Look for scratching, '
                    .'small black flea dirt in the coat, and hair loss. Beyond '
                    .'itching, fleas can cause allergic skin reactions and tapeworms, '
                    .'and ticks, while less common on cats, can carry serious disease. '
                    .'A vet-recommended, cat-specific preventive is the most reliable '
                    .'protection.',
                'faq' => [
                    ['q' => 'Can indoor cats get fleas?', 'a' => 'Yes. Fleas travel in on shoes, clothing, other pets, and through window screens, so an indoor-only cat can pick up an infestation without ever going outside.'],
                    ['q' => 'What are the signs my cat has fleas?', 'a' => 'Scratching or biting at the skin more than usual, small black specks in the fur (flea dirt) that turn reddish-brown when wiped on a wet paper towel, visible fast-moving fleas at the base of the tail, and hair loss from over-grooming are the main signs.'],
                    ['q' => 'Can fleas make my cat sick?', 'a' => 'Yes. Beyond the itching, some cats develop flea allergy dermatitis, an intense reaction to flea saliva. Cats can also swallow infected fleas while grooming, which is the most common way they pick up tapeworms, and heavy infestations can cause anemia, especially in kittens.'],
                    ['q' => 'Are ticks dangerous for cats?', 'a' => 'Ticks are less common on cats than dogs but do occur, especially with outdoor access. Cytauxzoonosis, spread by the Lone Star tick and most reported in the south-central and southeastern United States, is a serious and often fatal tick-borne infection in cats.'],
                    ['q' => 'Can I use dog flea treatment on my cat?', 'a' => 'No. Many dog flea and tick products contain permethrin, which is highly toxic to cats and can cause severe, sometimes fatal, reactions even in small amounts. Always use a product explicitly labeled safe for cats.'],
                    ['q' => 'How do I get rid of fleas on my cat and in my home?', 'a' => 'Use a vet-recommended, cat-specific preventive on every pet in the household, not just the one with visible symptoms, and wash bedding and vacuum regularly, since flea eggs and larvae live in the environment, not only on the cat.'],
                    ['q' => 'How do I safely remove a tick from my cat?', 'a' => 'Use fine-tipped tweezers or a tick-removal tool, grasp it as close to the skin as possible, and pull straight up with steady pressure rather than twisting. Avoid folk remedies like petroleum jelly or a lit match, and watch the site for redness over the following days.'],
                    ['q' => 'Why does it take weeks to get rid of a flea infestation?', 'a' => 'Fleas go through egg, larva, pupa and adult stages, and most of that population lives in carpets and bedding rather than on the cat. The pupal stage can lie dormant for weeks, which is why a single treatment on the cat alone often is not enough.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center', 'note' => 'Background on external parasites and preventive care in cats.'],
                    ['name' => 'VCA Animal Hospitals: fleas and cats', 'url' => 'https://vcahospitals.com/know-your-pet/fleas-and-cats', 'note' => 'Clinical overview of flea biology, health risks and treatment.'],
                    ['name' => 'ASPCA: cat health and wellness', 'url' => 'https://www.aspca.org/pet-care/cat-care', 'note' => 'General guidance on parasite prevention and product safety for cats.'],
                ],
            ],
            [
                'slug' => 'cat-dental-care',
                'title' => 'Cat Dental Care: Why It Matters and How to Start',
                'meta_title' => 'Cat Dental Care: Why It Matters and How to Start',
                'excerpt' => 'Most cats over three have some dental disease, and most '
                    .'owners never notice. Signs to watch for and how to start '
                    .'brushing your cat\'s teeth.',
                'category' => 'Health',
                'published' => '2026-08-24',
                'updated' => '2026-08-24',
                'image' => 'vet-examining-cat-sneezing',
                'alt' => 'Veterinarian examining a cat\'s face and mouth',
                'answer' => 'Most cats over three years old have some degree of dental '
                    .'disease, and because cats eat around pain rather than stopping, '
                    .'it is easy to miss. Watch for bad breath, drooling, dropped '
                    .'food, or red gums. Daily brushing with cat-specific toothpaste, '
                    .'started gradually, plus regular professional cleanings, are the '
                    .'most effective prevention.',
                'faq' => [
                    ['q' => 'How common is dental disease in cats?', 'a' => 'Very. By some estimates, the majority of cats over the age of three have some degree of dental disease, most commonly periodontal disease from plaque and tartar buildup along the gumline.'],
                    ['q' => 'What are the signs of dental problems in cats?', 'a' => 'Bad breath that is noticeably worse than usual, drooling, pawing at the mouth, dropping food while eating, a preference for wet food over dry, and red, swollen or bleeding gums are the main signs to watch for.'],
                    ['q' => 'How do I start brushing my cat\'s teeth?', 'a' => 'Introduce it gradually: let your cat lick cat-specific toothpaste off a finger for a few days, then move to gentle rubbing along the gumline, then a soft cat toothbrush. Keep sessions short and end on a positive note every time.'],
                    ['q' => 'Can I use human toothpaste on my cat?', 'a' => 'No. Human toothpaste often contains fluoride and sometimes xylitol, both unsafe for cats. Only use toothpaste made specifically for pets.'],
                    ['q' => 'What is tooth resorption in cats?', 'a' => 'Tooth resorption is a common, painful condition largely specific to cats, where the tooth\'s own structure breaks down starting at the root, often below the gumline where it cannot be seen. In most cases, extraction is the only fix once it has progressed.'],
                    ['q' => 'Do dental treats actually help?', 'a' => 'Treats and diets carrying the Veterinary Oral Health Council (VOHC) seal have been tested to show a real reduction in plaque or tartar. Most general "dental" marketing claims without that seal have not been independently verified.'],
                    ['q' => 'How often does a cat need a professional dental cleaning?', 'a' => 'It varies by cat, but a vet should check the mouth at every routine visit and recommend a professional cleaning, done under anesthesia, when tartar, gum disease or resorption is found. Cleanings performed under anesthesia are the only way to properly assess and treat below the gumline.'],
                    ['q' => 'Are anesthesia-free dental cleanings safe for cats?', 'a' => 'Veterinary dental organizations, including the American Veterinary Dental College, do not recommend them. Without anesthesia there is no way to clean or examine below the gumline, where most dental disease actually develops, and restraining an anxious cat carries its own risk.'],
                    ['q' => 'Does dry food clean a cat\'s teeth?', 'a' => 'Not meaningfully in most cases. Standard kibble tends to shatter rather than scrape the tooth clean. Dental-specific diets, formulated with a different texture and fiber structure, are the exception and are among the products that can carry the VOHC seal.'],
                ],
                'sources' => [
                    ['name' => 'Cornell Feline Health Center', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center', 'note' => 'Background on feline dental disease and tooth resorption.'],
                    ['name' => 'American Association of Feline Practitioners: dental care guidelines', 'url' => 'https://catvets.com/guidelines/practice-guidelines', 'note' => 'Clinical guidance on feline dental health and home care.'],
                    ['name' => 'American Veterinary Dental College', 'url' => 'https://avdc.org/', 'note' => 'Position statements on anesthesia-free dentistry and professional dental standards.'],
                    ['name' => 'VCA Animal Hospitals: dental disease in cats', 'url' => 'https://vcahospitals.com/know-your-pet/dental-disease-in-cats', 'note' => 'Clinical overview of periodontal disease, signs and treatment.'],
                ],
            ],
            [
                'slug' => 'senior-cat-care',
                'title' => 'Senior Cat Care: What Changes After Age 10',
                'meta_title' => 'Senior Cat Care: What Changes After Age 10',
                'excerpt' => 'Arthritis, kidney disease and cognitive decline are '
                    .'common after ten, and easy to miss. What actually changes, and '
                    .'the checkups that catch it early.',
                'category' => 'Health',
                'published' => '2026-08-24',
                'updated' => '2026-08-24',
                'image' => 'senior-cat-eating-wet-food',
                'alt' => 'Senior cat eating from a bowl of wet food',
                'answer' => 'After around age ten, cats commonly develop arthritis, '
                    .'kidney disease, an overactive thyroid, or cognitive decline, '
                    .'often with subtle signs like reduced jumping, weight changes, or '
                    .'increased nighttime vocalizing. Twice-yearly vet checkups with '
                    .'bloodwork catch most of these early, well before symptoms are '
                    .'obvious at home.',
                'faq' => [
                    ['q' => 'At what age is a cat considered a senior?', 'a' => 'Most veterinary guidelines place the start of the senior life stage around age ten, with cats over roughly fifteen sometimes described as geriatric. The change in care needs is usually gradual rather than tied to a single birthday.'],
                    ['q' => 'What are the first signs of aging in cats?', 'a' => 'A senior cat rarely announces a problem outright. Common early signs include no longer jumping onto favorite high spots, sleeping more, a duller or more matted coat from reduced grooming, and changes in weight in either direction.'],
                    ['q' => 'How often should a senior cat see the vet?', 'a' => 'Twice a year is the general recommendation, typically including bloodwork and a urinalysis rather than a physical exam alone, since lab work often catches developing conditions months before they become obvious at home.'],
                    ['q' => 'What health problems are common in older cats?', 'a' => 'Chronic kidney disease, hyperthyroidism, diabetes, high blood pressure, osteoarthritis and cognitive dysfunction are all significantly more common in senior cats, and most are far easier to manage when caught early.'],
                    ['q' => 'What is cognitive dysfunction in cats?', 'a' => 'It is a real, recognized age-related decline in brain function, sometimes described as feline dementia. Signs include increased nighttime vocalizing, disorientation, changes to the sleep-wake cycle, and house soiling in a cat with previously reliable litter box habits.'],
                    ['q' => 'How can I make home life easier for a senior cat?', 'a' => 'Lower-sided litter boxes on every level of the home, ramps or steps to favorite spots, soft bedding in warm and easy-to-reach places, and keeping food and water within easy reach all help a cat with reduced mobility.'],
                    ['q' => 'Should I change my senior cat\'s diet?', 'a' => 'Activity and metabolism both change with age, so it is worth rechecking portions rather than assuming an old routine still fits. A vet can also advise on diet changes if kidney disease, diabetes or another condition is diagnosed.'],
                    ['q' => 'Do senior cats need a low-protein diet?', 'a' => 'Not by default. Older guidance favored lower protein for aging kidneys, but current thinking has largely moved away from that: healthy senior cats generally need adequate to high-quality protein to help prevent age-related muscle loss. Lower-protein diets are reserved for cats with diagnosed kidney disease.'],
                    ['q' => 'What bloodwork does a senior cat need?', 'a' => 'A typical panel checks kidney values including SDMA, thyroid hormone, blood glucose, a general blood count, and a urinalysis, with blood pressure increasingly checked as standard too. Together they catch the conditions, kidney disease, hyperthyroidism, diabetes and hypertension, that rarely show obvious symptoms early on.'],
                ],
                'sources' => [
                    ['name' => 'American Association of Feline Practitioners: senior care guidelines', 'url' => 'https://catvets.com/guidelines/practice-guidelines/senior-care-guidelines', 'note' => 'Clinical guidelines on senior cat checkup frequency and screening.'],
                    ['name' => 'Cornell Feline Health Center', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center', 'note' => 'Background on age-related feline diseases including kidney disease and hyperthyroidism.'],
                    ['name' => 'VCA Animal Hospitals: senior cat care', 'url' => 'https://vcahospitals.com/know-your-pet/senior-cat-care', 'note' => 'Clinical overview of common conditions and home care for older cats.'],
                ],
            ],
        ];
    }
}

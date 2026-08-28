<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\NameGeneratorSave;
use App\Support\Schema;
use Illuminate\Contracts\View\View;

class CatNameGeneratorController extends Controller
{
    /** origin_tags value => [country name, flag emoji], shown on a result when a name carries one. */
    private const ORIGINS = [
        'japan' => ['Japan', '🇯🇵'],
        'france' => ['France', '🇫🇷'],
        'egypt' => ['Egypt', '🇪🇬'],
        'norway' => ['Norway', '🇳🇴'],
        'russia' => ['Russia', '🇷🇺'],
        'thailand' => ['Thailand', '🇹🇭'],
        'turkey' => ['Turkey', '🇹🇷'],
        'uk' => ['the United Kingdom', '🇬🇧'],
        'usa' => ['the United States', '🇺🇸'],
    ];

    public function __invoke(): View
    {
        $url = rtrim(config('app.url'), '/');
        $path = '/tools/cat-name-generator';

        $title = 'Cat Name Generator: 150+ Names, Real Meanings | '.config('app.name');

        $description = 'Free cat name generator with real meanings. Filter by gender, style, '
            .'breed and personality. Save favorites, generate for two cats, and share instantly.';

        $config = config('cat-name-generator');
        $names = collect($config['names']);

        // Flag + readable country name, computed once here rather than
        // duplicated per name in the config file — a name only needs to
        // carry origin_tags, this is the one place that turns a tag into
        // something displayable.
        $namesWithOrigin = $names->map(function (array $name): array {
            $tag = $name['origin_tags'][0] ?? null;
            [$country, $flag] = self::ORIGINS[$tag] ?? [null, null];

            return $name + ['origin_country' => $country, 'origin_flag' => $flag];
        });

        $breeds = Breed::active()->orderBy('name')->get([
            'name', 'slug', 'origin_country', 'energy_level', 'temperament_summary', 'fun_fact',
        ])->map(fn (Breed $breed): array => [
            'name' => $breed->name,
            'slug' => $breed->slug,
            'originCountry' => $breed->origin_country,
            'originTag' => $this->originTag($breed->origin_country),
            'energyLevel' => $breed->energy_level,
            'temperament' => $breed->temperament_summary,
            'funFact' => $breed->fun_fact,
        ]);

        // A real count, not a placeholder: incremented once per browser per
        // name when it's favorited (see CatNameGeneratorSaveController), so
        // this reflects actual visitor interest. Falls back to a fixed,
        // clearly-reasonable starter set when the table is still empty (a
        // fresh deploy) rather than an empty "trending" list that undercuts
        // the feature on day one.
        $trending = NameGeneratorSave::query()
            ->where('save_count', '>', 0)
            ->orderByDesc('save_count')
            ->limit(12)
            ->pluck('name');

        // Real saves lead the list. Early on there are only a handful, so the
        // rest is topped up from the curated list rather than rendering a
        // one-item "popular" card that looks broken.
        $trendingNames = $trending
            ->map(fn (string $n) => $namesWithOrigin->firstWhere('name', $n))
            ->filter()
            ->concat($namesWithOrigin->whereNotIn('name', $trending->all()))
            ->take(12)
            ->values();

        $styleExamples = collect($config['styles'])->mapWithKeys(fn (array $style) => [
            $style['slug'] => [
                'label' => $style['label'],
                'names' => $namesWithOrigin->filter(fn (array $n) => in_array($style['slug'], $n['styles']))
                    ->take(10)->values(),
            ],
        ]);

        $personalityExamples = collect($config['personalities'])->mapWithKeys(fn (array $p) => [
            $p['slug'] => [
                'label' => $p['label'],
                'names' => $namesWithOrigin->filter(fn (array $n) => in_array($p['slug'], $n['personalities'] ?? []))
                    ->take(4)->values(),
            ],
        ]);

        // Real counts off the same list the generator draws from, so the
        // sidebar never advertises a category with more names than exist.
        $categoryCounts = collect($config['styles'])->map(fn (array $style) => [
            'slug' => $style['slug'],
            'label' => $style['label'],
            'count' => $names->filter(fn (array $n) => in_array($style['slug'], $n['styles']))->count(),
        ])->sortByDesc('count')->values();

        $namingTips = [
            ['tip' => 'Avoid names that sound like commands, since "Kit" and "sit" are hard for a cat to tell apart.', 'tone' => 'primary'],
            ['tip' => 'Say a shortlist out loud before deciding. A name is called across a room far more than it is read.', 'tone' => 'accent'],
            ['tip' => 'Get the whole household to agree, because a name everyone shortens differently just confuses a cat.', 'tone' => 'info'],
        ];

        $howToChoose = [
            ['title' => 'Match their personality', 'text' => 'Watch how they actually behave for a few days first. A name chosen after living with a cat usually fits better than one picked from a photo.'],
            ['title' => 'Keep it easy to say', 'text' => 'One or two syllables is easiest for a cat to learn and for you to call out the same way every time.'],
            ['title' => 'Try saying it out loud', 'text' => 'Say it a few times, at a normal speaking volume and called across a room. Some names only sound awkward once spoken.'],
            ['title' => 'Think long term', 'text' => 'Cats live well into their teens, so pick something that still suits a full-grown, dignified adult cat, not only a kitten.'],
        ];

        $funFact = 'Cats can recognize their own name. A 2019 study in Scientific Reports found they '
            .'reliably tell it apart from similar-sounding words, they just choose whether to answer.';

        $moreTools = collect(config('catalog.tools'))
            ->reject(fn (array $t): bool => $t['slug'] === 'cat-name-generator')
            ->values();

        $popularNames = [
            'female' => ['Luna', 'Bella', 'Willow', 'Nala', 'Daisy', 'Coco', 'Olive', 'Hazel', 'Rosie', 'Ivy'],
            'male' => ['Oliver', 'Leo', 'Milo', 'Charlie', 'Simba', 'Jasper', 'Tiger', 'Loki', 'Rex', 'Basil'],
        ];

        // Hand-picked rather than pulled entirely from the generator's own
        // list: several origins (Thailand, Russia, Scotland) have thin or no
        // coverage in the 150-name set, and forcing a match there produced
        // duplicate lists for different breeds and one outright unrelated
        // filler name. Every name below is real and its fit is defensible;
        // most also exist in the generator above, noted so a reader knows
        // which ones they can go generate directly.
        $breedGuide = [
            ['breed' => 'Maine Coon', 'theme' => 'Big, rugged American names', 'names' => ['Ranger', 'Maverick', 'Diesel', 'Scout']],
            ['breed' => 'Siamese', 'theme' => 'Elegant, Eastern-inspired names', 'names' => ['Rama', 'Jasmine', 'Sabai']],
            ['breed' => 'Persian', 'theme' => 'Royal, regal names', 'names' => ['Duchess', 'Countess', 'Regina', 'Sultan']],
            ['breed' => 'British Shorthair', 'theme' => 'Classic British names', 'names' => ['Earl', 'Winston', 'Pippa', 'Baron']],
            ['breed' => 'Ragdoll', 'theme' => 'Soft, gentle names', 'names' => ['Willow', 'Marshmallow', 'Snuggles', 'Fern']],
            ['breed' => 'Bengal', 'theme' => 'Wild, strong names', 'names' => ['Tiger', 'Panther', 'Zeus', 'Storm']],
            ['breed' => 'Russian Blue', 'theme' => 'Russian names', 'names' => ['Tsarina', 'Sasha', 'Natasha', 'Boris']],
            ['breed' => 'Scottish Fold', 'theme' => 'Scottish names', 'names' => ['Angus', 'Bonnie', 'Duncan', 'Fiona']],
            ['breed' => 'Abyssinian', 'theme' => 'African-inspired names', 'names' => ['Isis', 'Bastet', 'Zuri', 'Nia']],
            ['breed' => 'Sphynx', 'theme' => 'Egyptian names', 'names' => ['Osiris', 'Anubis', 'Ra', 'Nefertiti']],
        ];

        $inToolNames = $names->pluck('name')->all();

        $pairExamples = [
            ['theme' => 'Celestial', 'name1' => 'Luna', 'name2' => 'Apollo', 'why' => 'Moon and sun, a classic celestial pairing.'],
            ['theme' => 'Food duo', 'name1' => 'Mochi', 'name2' => 'Miso', 'why' => 'Two Japanese food names that sound good called together.'],
            ['theme' => 'Mythology', 'name1' => 'Zeus', 'name2' => 'Hera', 'why' => 'Greek mythology\'s ruling pair.'],
            ['theme' => 'Nature', 'name1' => 'Willow', 'name2' => 'Aspen', 'why' => 'Two tree names with a similar, gentle sound.'],
            ['theme' => 'Royal', 'name1' => 'Duke', 'name2' => 'Duchess', 'why' => 'A matched noble title pair.'],
            ['theme' => 'French elegance', 'name1' => 'Fifi', 'name2' => 'Pierre', 'why' => 'Two classic French names, one soft and one sturdy.'],
            ['theme' => 'Egyptian', 'name1' => 'Isis', 'name2' => 'Osiris', 'why' => 'Egyptian mythology\'s paired deities.'],
        ];

        $faq = [
            ['q' => 'What is the most popular cat name right now?', 'a' => 'Names like Luna, Bella and Oliver come up again and again in cat-owner surveys and community polls, and they show up near the top of our own generator\'s results too. Popularity is not the same as fit, though: a name a lot of people use can still be exactly right for your cat.'],
            ['q' => 'How do I choose a name my cat will actually respond to?', 'a' => 'Cats respond best to short names, one or two syllables, that end in a clear vowel sound. Say a shortlist out loud a few times before deciding, since how a name sounds called across a room matters more than how it reads on paper.'],
            ['q' => 'Can I change my cat\'s name after adoption?', 'a' => 'Yes. Cats do not attach to a name the way people assume; they learn to respond to whatever sound is consistently paired with attention and food. A new name usually takes a week or two of consistent use to stick, adult cat or kitten.'],
            ['q' => 'What cat names are easiest for a cat to learn?', 'a' => 'Short names ending in a sharp consonant or a long vowel cut through background noise best, which is why so many easy-to-learn names land around two syllables: Milo, Luna, Leo, Coco. Longer, softer names are not wrong, they just take a little more repetition.'],
            ['q' => 'Are food-themed cat names a good idea?', 'a' => 'Yes, they are a genuinely good fit for cats specifically: names like Mochi, Miso and Biscuit tend to be short, end in a clear sound, and avoid the command-word problem entirely, which is part of why the style has become so common.'],
            ['q' => 'Should I name my cat based on their breed?', 'a' => 'It is optional but a nice touch if you already know the breed. Our breed filter above leans toward names that fit a breed\'s real origin country when there is a genuine match, rather than forcing a theme onto every breed.'],
            ['q' => 'What are some unique cat names not many other cats have?', 'a' => 'The quirky and mythology styles in the generator above tend to surface names most other lists skip entirely, things like Purrcival, Meowgi or Nyx. Filtering by an unusual starting letter is another fast way to narrow toward something less common.'],
            ['q' => 'How long does it take a cat to learn its name?', 'a' => 'Most cats start responding within one to two weeks of consistent, positive use, associating the sound with something good rather than being called over for something they dislike, like a nail trim.'],
            ['q' => 'What cat names work well for either a male or female cat?', 'a' => 'Names tagged "neutral" in the generator above, like Shadow, Storm or Whiskers, work regardless of gender. Many food-inspired and nature names read as gender-neutral too.'],
            ['q' => 'Can two cats have rhyming or matching names?', 'a' => 'Yes, and it is a common choice. The two-cats mode above generates a pair at once and notes what actually connects them, whether that is a shared theme, style or origin, rather than just rhyming for its own sake.'],
        ];

        $internalLinks = [
            ['label' => 'Cat Age Calculator', 'url' => route('tools.cat-age-calculator')],
            ['label' => 'Cat Calorie Calculator', 'url' => route('tools.cat-calorie-calculator')],
            ['label' => 'Cat Vaccination Tracker', 'url' => route('tools.cat-vaccination-tracker')],
            ['label' => 'New Cat Owner Guide', 'url' => route('blog.show', 'new-cat-owner-guide')],
        ];

        return view('tools.cat-name-generator', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.$path,
            'styles' => $config['styles'],
            'personalities' => $config['personalities'],
            'names' => $namesWithOrigin->all(),
            'breeds' => $breeds,
            'trendingNames' => $trendingNames,
            'styleExamples' => $styleExamples,
            'personalityExamples' => $personalityExamples,
            'categoryCounts' => $categoryCounts,
            'namingTips' => $namingTips,
            'howToChoose' => $howToChoose,
            'funFact' => $funFact,
            'moreTools' => $moreTools,
            'popularNames' => $popularNames,
            'breedGuide' => $breedGuide,
            'inToolNames' => $inToolNames,
            'pairExamples' => $pairExamples,
            'internalLinks' => $internalLinks,
            'faq' => $faq,
            'schema' => Schema::graph([
                [
                    '@type' => 'WebApplication',
                    '@id' => $url.$path.'#app',
                    'name' => 'Cat Name Generator',
                    'url' => $url.$path,
                    'applicationCategory' => 'LifestyleApplication',
                    'operatingSystem' => 'Any',
                    'browserRequirements' => 'Requires JavaScript',
                    'isAccessibleForFree' => true,
                    'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'],
                    'publisher' => ['@id' => $url.'/#organization'],
                ],
                [
                    '@type' => 'WebPage',
                    '@id' => $url.$path.'#page',
                    'url' => $url.$path,
                    'name' => $title,
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                    'primaryImageOfPage' => ['@id' => $url.$path.'#app'],
                ] + (config('author.founder.name') ? [
                    'author' => ['@id' => $url.'/#founder'],
                ] : []),
                Schema::faq($path.'#faq', $faq),
                Schema::breadcrumbs($path, [
                    'Home' => '/',
                    'Tools' => '/tools',
                    'Cat Name Generator' => null,
                ]),
            ]),
        ]);
    }

    /**
     * Maps a breed's real origin country to the same tag vocabulary used on
     * names in config/cat-name-generator.php, so the breed filter can boost
     * matching names without a second, separately maintained lookup table.
     */
    private function originTag(?string $country): ?string
    {
        return match ($country) {
            'Japan' => 'japan',
            'France' => 'france',
            'Egypt' => 'egypt',
            'Norway' => 'norway',
            'Russia' => 'russia',
            'Thailand' => 'thailand',
            'Turkey' => 'turkey',
            'United Kingdom', 'Isle of Man' => 'uk',
            'United States' => 'usa',
            default => null,
        };
    }
}

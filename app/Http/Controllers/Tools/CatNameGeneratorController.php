<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Support\Schema;
use Illuminate\Contracts\View\View;

class CatNameGeneratorController extends Controller
{
    public function __invoke(): View
    {
        $url = rtrim(config('app.url'), '/');
        $path = '/tools/cat-name-generator';

        $title = 'Cat Name Generator: 150+ Names by Style, Breed & Personality';

        $description = 'Free cat name generator. Filter by gender, style, personality or '
            .'breed for a name that fits, with a real meaning behind every one.';

        $config = config('cat-name-generator');

        // Only the fields the tool actually uses, kept lean since this ships
        // to the client as JSON for the generator to filter through.
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

        $faq = [
            ['q' => 'How does the breed filter choose names?', 'a' => 'Selecting a breed biases the results toward names that fit that breed\'s real origin country when we have a good match (Japanese names for a Japanese Bobtail, French names for a Chartreux), and shows that breed\'s actual temperament and energy level alongside the result, pulled from our breeds guide rather than invented for this tool.'],
            ['q' => 'Are the name meanings real?', 'a' => 'Yes. Every meaning here is a real translation, mythology reference or well-known character, not an invented backstory. If we were not confident a meaning was accurate, the name is not in the list.'],
            ['q' => 'Does this save my cat\'s name anywhere?', 'a' => 'No account is needed and nothing is sent to a server. Favorites are saved in your browser only, using local storage, so they stay on this device and are never seen by us.'],
            ['q' => 'Can I use this for a kitten I have not brought home yet?', 'a' => 'Yes. The generator does not need any information about a real cat. Filter by the traits or style you are hoping for and generate as many times as you like.'],
            ['q' => 'What if I want a name for two cats at once?', 'a' => 'Generate one name, save it as a favorite, then generate again for the second. Favorites keep a running list so you can compare pairs before deciding.'],
        ];

        return view('tools.cat-name-generator', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.$path,
            'styles' => $config['styles'],
            'personalities' => $config['personalities'],
            'names' => $config['names'],
            'breeds' => $breeds,
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

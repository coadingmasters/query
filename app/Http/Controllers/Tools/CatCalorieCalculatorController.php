<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Support\Schema;
use Illuminate\Contracts\View\View;

class CatCalorieCalculatorController extends Controller
{
    private const LB_TO_KG = 0.45359237;

    public function __invoke(): View
    {
        $url = rtrim(config('app.url'), '/');
        $path = '/tools/cat-calorie-calculator';

        // 60 characters, inside what Google will show.
        $title = 'Cat Calorie Calculator: How Much to Feed Your Cat';

        $description = 'Free cat calorie calculator using the vet-standard RER formula. '
            .'Enter weight, life stage and activity for an instant daily target and '
            .'feeding portions.';

        $stages = config('cat-calorie.life_stages');
        $activity = config('cat-calorie.activity');
        $bcs = config('cat-calorie.bcs');
        $living = config('cat-calorie.living');
        $food = config('cat-calorie.food');
        $faq = config('cat-calorie-faq');

        return view('tools.cat-calorie-calculator', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.$path,
            'stages' => $stages,
            'activity' => $activity,
            'bcs' => $bcs,
            'living' => $living,
            'food' => $food,
            'sources' => config('cat-calorie.sources'),
            'faq' => $faq,
            'weightTable' => $this->weightTable(),
            'lifeStageTable' => $this->lifeStageTable($stages),
            'model' => [
                'stages' => $stages,
                'activity' => $activity,
                'bcs' => $bcs,
                'living' => $living,
                'food' => $food,
            ],
            'schema' => Schema::graph([
                [
                    '@type' => 'WebApplication',
                    '@id' => $url.$path.'#app',
                    'name' => 'Cat Calorie Calculator',
                    'url' => $url.$path,
                    'applicationCategory' => 'HealthApplication',
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
                Schema::faq($url.$path.'#faq', collect($faq)
                    ->map(fn (array $i): array => ['q' => $i['q'], 'a' => $i['a']])->all()),
                Schema::breadcrumbs($path, [
                    'Home' => '/',
                    'Tools' => '/#tools',
                    'Cat Calorie Calculator' => null,
                ]),
            ]),
        ]);
    }

    private static function rer(float $weightLb): float
    {
        $kg = $weightLb * self::LB_TO_KG;

        return 70 * ($kg ** 0.75);
    }

    /** Weight (lb) -> kcal/day for a neutered adult, an intact adult, and a kitten. */
    private function weightTable(): array
    {
        return collect([4, 6, 8, 10, 12, 14, 16])->map(fn (int $lb): array => [
            'weight' => $lb,
            'neutered' => (int) round(self::rer($lb) * 1.6),
            'intact' => (int) round(self::rer($lb) * 1.8),
            'kitten' => (int) round(self::rer($lb) * 2.0),
        ])->all();
    }

    /** Typical kcal/day for a 10 lb cat at each life stage's representative multiplier. */
    private function lifeStageTable(array $stages): array
    {
        $rer10 = self::rer(10);

        return collect($stages)->map(function (array $stage) use ($rer10): array {
            $multiplier = match ($stage['kind']) {
                'fixed' => $stage['multiplier'],
                'neuter' => $stage['multiplier_neutered'],
                'pregnancy' => ($stage['multiplier_early'] + $stage['multiplier_late']) / 2,
                'nursing' => ($stage['multiplier_min'] + $stage['multiplier_max']) / 2,
            };

            return [
                'label' => $stage['label'],
                'multiplier' => $multiplier,
                'kcal' => (int) round($rer10 * $multiplier),
                'note' => $stage['note'],
            ];
        })->all();
    }
}

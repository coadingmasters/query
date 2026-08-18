<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Support\Schema;
use Illuminate\Contracts\View\View;

class CatAgeCalculatorController extends Controller
{
    public function __invoke(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');
        $path = '/tools/cat-age-calculator';

        // 60 characters, inside what Google will show.
        $title = 'Cat Age Calculator | Cat Years to Human Years';

        $description = 'Free cat age calculator. Enter a birthday or an age and get '
            .'human years, the AAFP life stage your cat is in, and what that stage '
            .'changes about their care.';

        $faq = config('cat-age-faq');

        return view('tools.cat-age-calculator', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.$path,
            'stages' => config('cat-age.stages'),
            'breeds' => config('cat-breeds'),
            'lifestyles' => config('cat-age.lifestyles'),
            'sources' => config('cat-age.sources'),
            'faq' => $faq,
            'model' => [
                'firstYear' => config('cat-age.first_year'),
                'secondYear' => config('cat-age.second_year'),
                'perYearAfter' => config('cat-age.per_year_after'),
                'kittenMonths' => config('cat-age.kitten_months'),
                'stages' => config('cat-age.stages'),
                'breeds' => config('cat-breeds'),
                'lifestyles' => config('cat-age.lifestyles'),
            ],
            'schema' => Schema::graph([
                [
                    // WebApplication rather than SoftwareApplication: it runs
                    // in the browser and there is nothing to install.
                    '@type' => 'WebApplication',
                    '@id' => $url.$path.'#app',
                    'name' => 'Cat Age Calculator',
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
                    'Cat Age Calculator' => null,
                ]),
            ]),
        ]);
    }
}

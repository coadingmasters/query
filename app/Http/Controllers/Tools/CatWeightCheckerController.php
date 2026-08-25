<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Support\Schema;
use Illuminate\Contracts\View\View;

class CatWeightCheckerController extends Controller
{
    public function __invoke(): View
    {
        $url = rtrim(config('app.url'), '/');
        $path = '/tools/cat-weight-checker';

        $title = 'Cat Weight Checker: BCS, Ideal Weight & Food Adjustment';

        $description = 'Free cat weight checker: score body condition, see an ideal '
            .'weight estimate, and get a safe food adjustment. Log weight with a '
            .'chart, no sign-up.';

        $bcs = config('cat-weight.bcs');
        $faq = config('cat-weight-faq');

        return view('tools.cat-weight-checker', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.$path,
            'bcs' => $bcs,
            'weighMethods' => config('cat-weight.weigh_methods'),
            'sources' => config('cat-weight.sources'),
            'faq' => $faq,
            'model' => [
                'bcs' => $bcs,
                'seniorAge' => config('cat-weight.senior_age_years'),
                'maxSafeLossPercentPerWeek' => config('cat-weight.max_safe_loss_percent_per_week'),
            ],
            'schema' => Schema::graph([
                [
                    '@type' => 'WebApplication',
                    '@id' => $url.$path.'#app',
                    'name' => 'Cat Weight Checker',
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
                Schema::faq($path.'#faq', collect($faq)
                    ->map(fn (array $i): array => ['q' => $i['q'], 'a' => $i['a']])->all()),
                Schema::breadcrumbs($path, [
                    'Home' => '/',
                    'Tools' => '/tools',
                    'Cat Weight Checker' => null,
                ]),
            ]),
        ]);
    }
}

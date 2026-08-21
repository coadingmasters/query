<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Support\Schema;
use Illuminate\Contracts\View\View;

class VaccinationTrackerController extends Controller
{
    public function __invoke(): View
    {
        $url = rtrim(config('app.url'), '/');
        $path = '/tools/cat-vaccination-tracker';

        // 58 characters, inside what Google will show.
        $title = 'Cat Vaccination Tracker: Schedule & Record | PurrQuery';

        $description = 'Free cat vaccination tracker. Enter a birth date and get a full '
            .'FVRCP, rabies and FeLV schedule with next due dates, plus a printable '
            .'vaccination record.';

        $vaccines = config('vaccination.vaccines');
        $faq = config('vaccination-faq');

        return view('tools.cat-vaccination-tracker', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.$path,
            'vaccines' => $vaccines,
            'injectionSites' => config('vaccination.injection_sites'),
            'sources' => config('vaccination.sources'),
            'faq' => $faq,
            'model' => [
                'vaccines' => $vaccines,
                'injectionSites' => config('vaccination.injection_sites'),
            ],
            'schema' => Schema::graph([
                [
                    '@type' => 'WebApplication',
                    '@id' => $url.$path.'#app',
                    'name' => 'Cat Vaccination Tracker',
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
                    'Cat Vaccination Tracker' => null,
                ]),
            ]),
        ]);
    }
}

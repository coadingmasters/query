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

        $toolMeta = collect(config('catalog.tools'))->firstWhere('slug', 'vaccination-tracker');

        return view('tools.cat-vaccination-tracker', [
            'publishedAt' => $toolMeta['published_at'] ?? null,
            'updatedAt' => $toolMeta['updated_at'] ?? null,
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
                    // Plain WebPage, not WebApplication: Google requires an
                    // aggregateRating or review on any SoftwareApplication
                    // subtype, which this tool has no genuine data for —
                    // claiming the type without one is what Search Console
                    // flags as invalid structured data.
                    '@type' => 'WebPage',
                    '@id' => $url.$path.'#page',
                    'url' => $url.$path,
                    'name' => $title,
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ] + (config('author.founder.name') ? [
                    'author' => ['@id' => $url.'/#founder'],
                ] : []),
                Schema::faq($path.'#faq', collect($faq)
                    ->map(fn (array $i): array => ['q' => $i['q'], 'a' => $i['a']])->all()),
                Schema::breadcrumbs($path, [
                    'Home' => '/',
                    'Tools' => '/tools',
                    'Cat Vaccination Tracker' => null,
                ]),
            ]),
        ]);
    }
}

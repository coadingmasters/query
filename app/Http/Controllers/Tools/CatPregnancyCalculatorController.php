<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Support\Schema;
use Illuminate\Contracts\View\View;

class CatPregnancyCalculatorController extends Controller
{
    public function __invoke(): View
    {
        $url = rtrim(config('app.url'), '/');
        $path = '/tools/cat-pregnancy-calculator';
        $canonical = $url.$path;

        // 158 characters. Google truncates a description around 160, and one
        // that is cut mid-sentence reads as carelessness in the one place a
        // searcher is deciding whether to click.
        $description = 'Free cat pregnancy calculator. Enter the mating date, or '
            .'just the signs you have noticed, for a due date, birth window and '
            .'a week-by-week timeline.';

        return view('tools.cat-pregnancy-calculator', [
            'title' => 'Cat Pregnancy Calculator | Due Date & Gestation Timeline',
            'description' => $description,
            'canonical' => $canonical,
            'schema' => Schema::graph([
                [
                    /*
                     | The subject matter, marked as veterinary and carrying the
                     | disclaimer the page itself shows. Google's health
                     | guidance expects that disclaimer to exist rather than be
                     | implied, which is why it is repeated here.
                     */
                    '@type' => 'MedicalWebPage',
                    '@id' => $canonical.'#page',
                    'url' => $canonical,
                    'name' => 'Cat Pregnancy Calculator',
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                    'about' => [
                        '@type' => 'MedicalCondition',
                        'name' => 'Feline pregnancy (gestation)',
                    ],
                    'medicalAudience' => [
                        '@type' => 'MedicalAudience',
                        'audienceType' => 'Patient',
                    ],
                    'specialty' => 'https://schema.org/Veterinary',
                    'disclaimer' => 'This tool is for informational purposes only '
                        .'and is not a substitute for professional veterinary '
                        .'advice, diagnosis or treatment. Consult your vet.',
                    'lastReviewed' => '2026-08-18',
                ],
                Schema::faq($path.'#faq', collect(config('pregnancy-faq'))
                    ->map(fn (array $item): array => ['q' => $item['q'], 'a' => $item['a']])
                    ->all()),
                Schema::breadcrumbs($path, [
                    'Home' => '/',
                    'Tools' => '/tools',
                    'Cat Pregnancy Calculator' => null,
                ]),
            ]),
        ]);
    }
}

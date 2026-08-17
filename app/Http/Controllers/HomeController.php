<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');
        $description = config('brand.description');

        return view('home', [
            'title' => $name.' — '.config('brand.tagline'),
            'description' => $description,
            'canonical' => $url.'/',
            'schema' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Organization',
                        '@id' => $url.'/#organization',
                        'name' => $name,
                        'url' => $url,
                        'email' => config('brand.email'),
                        'description' => $description,
                        'logo' => $url.'/og-image.png',
                    ],
                    [
                        '@type' => 'WebSite',
                        '@id' => $url.'/#website',
                        'name' => $name,
                        'url' => $url,
                        'description' => $description,
                        'inLanguage' => str_replace('_', '-', app()->getLocale()),
                        'publisher' => ['@id' => $url.'/#organization'],
                    ],
                    // Marks the food guides as a Q&A set, which is what makes
                    // "can cats eat X" eligible for a rich result.
                    [
                        '@type' => 'FAQPage',
                        '@id' => $url.'/#faq',
                        'mainEntity' => collect(config('catalog.foods'))->map(fn (array $food) => [
                            '@type' => 'Question',
                            'name' => 'Can cats eat '.mb_strtolower($food['title']).'?',
                            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $food['note']],
                        ])->all(),
                    ],
                ],
            ],
        ]);
    }
}

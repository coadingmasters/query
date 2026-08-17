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
            'title' => config('brand.home_title').' | '.$name,
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
                    /*
                     | The food guides as a Q&A set. Google requires FAQ
                     | markup to mirror content the visitor can actually see,
                     | so this pulls the same question and answer strings the
                     | cards render — not a paraphrase of them.
                     |
                     | Worth being clear-eyed: since 2023 Google shows FAQ rich
                     | results only for authoritative health and government
                     | sites, so this is unlikely to produce stars in the SERP.
                     | It still helps Google understand what the page answers.
                     */
                    [
                        '@type' => 'FAQPage',
                        '@id' => $url.'/#faq',
                        'mainEntity' => collect(config('catalog.foods'))->map(fn (array $food) => [
                            '@type' => 'Question',
                            'name' => $food['question'],
                            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $food['answer']],
                        ])->all(),
                    ],
                ],
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class FaqController extends Controller
{
    public function __invoke(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');
        $groups = config('faq.groups');

        $count = collect($groups)->sum(fn (array $g): int => count($g['items']));

        $description = 'Answers to '.$count.' common cat care questions — feeding '
            .'amounts, health warning signs, age and weight, litter tray problems '
            .'and more. Free, and written from published veterinary sources.';

        return view('faq', [
            'title' => 'Cat Care FAQ — '.$count.' Questions Answered',
            'description' => $description,
            'canonical' => $url.'/faq',
            'groups' => $groups,
            'count' => $count,
            'schema' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    /*
                     | Every question and answer below is rendered on the page
                     | inside details/summary, which keeps it in the DOM whether
                     | open or shut. That is what makes this markup honest —
                     | Google requires FAQ structured data to describe content
                     | the visitor can actually reach.
                     */
                    [
                        '@type' => 'FAQPage',
                        '@id' => $url.'/faq#faq',
                        'url' => $url.'/faq',
                        'name' => 'Cat Care FAQ',
                        'description' => $description,
                        'isPartOf' => ['@id' => $url.'/#website'],
                        'mainEntity' => collect($groups)
                            ->flatMap(fn (array $group): array => $group['items'])
                            ->map(fn (array $item): array => [
                                '@type' => 'Question',
                                'name' => $item['q'],
                                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
                            ])->all(),
                    ],
                    [
                        '@type' => 'BreadcrumbList',
                        '@id' => $url.'/faq#breadcrumb',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $url.'/'],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'FAQ'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Support\Schema;
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

            // Counted from the catalogue and from this page's own questions,
            // so the strip cannot claim more than the site actually holds.
            'stats' => [
                [$count, 'Questions answered'],
                [count(config('catalog.tools')), 'Free cat care tools'],
                [count(config('catalog.foods')) + count(config('catalog.posts')), 'Guides and articles'],
                ['$0', 'Cost to use, always'],
            ],
            'schema' => Schema::graph([
                [
                    '@type' => 'WebPage',
                    '@id' => $url.'/faq#page',
                    'url' => $url.'/faq',
                    'name' => 'Cat Care FAQ',
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                /*
                 | Every question and answer is rendered inside details/summary,
                 | which keeps it in the DOM whether open or shut — that is what
                 | makes this markup honest, since Google requires FAQ data to
                 | describe content the visitor can reach.
                 */
                Schema::faq('/faq#faq', collect($groups)
                    ->flatMap(fn (array $group): array => $group['items'])
                    ->map(fn (array $item): array => ['q' => $item['q'], 'a' => $item['a']])
                    ->all()),
                Schema::breadcrumbs('/faq', ['Home' => '/', 'FAQ' => null]),
            ]),
        ]);
    }
}

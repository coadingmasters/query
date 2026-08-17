<?php

namespace App\Http\Controllers;

use App\Support\Schema;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');
        $description = config('brand.description');
        $title = config('brand.home_title').' | '.$name;

        return view('home', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.'/',
            'schema' => Schema::graph([
                [
                    '@type' => 'WebPage',
                    '@id' => $url.'/#webpage',
                    'url' => $url.'/',
                    'name' => $title,
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                    'about' => ['@id' => $url.'/#organization'],
                ],
                // Names the tools and the guides as two collections rather
                // than leaving Google to infer it from a wall of cards.
                Schema::itemList('/#tools', 'Free cat care tools',
                    collect(config('catalog.tools'))->map(fn (array $t): array => [
                        'name' => $t['title'], 'description' => $t['blurb'],
                    ])->all()),
                Schema::itemList('/#food-guides', 'Cat food safety guides',
                    collect(config('catalog.foods'))->map(fn (array $f): array => [
                        'name' => $f['question'], 'description' => $f['answer'],
                    ])->all()),
                /*
                 | The food guides as a Q&A set. Every question and answer here
                 | is rendered on the cards, which is what Google requires of
                 | FAQ markup — it must describe content the visitor can read.
                 |
                 | Worth being clear-eyed: since 2023 Google has shown FAQ rich
                 | results only for authoritative health and government sites,
                 | so this is unlikely to produce an expandable result. It still
                 | tells Google what the page answers.
                 */
                Schema::faq('/#faq', collect(config('catalog.foods'))->map(fn (array $f): array => [
                    'q' => $f['question'], 'a' => $f['answer'],
                ])->all()),
            ]),
        ]);
    }
}

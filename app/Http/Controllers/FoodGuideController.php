<?php

namespace App\Http\Controllers;

use App\Support\Schema;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FoodGuideController extends Controller
{
    private const VERDICT_LABEL = [
        'safe' => 'Safe to feed.',
        'caution' => 'Feed only in moderation.',
        'unsafe' => 'Never feed this.',
    ];

    private const SOURCES = [
        ['name' => 'ASPCA Animal Poison Control Center', 'url' => 'https://www.aspca.org/pet-care/animal-poison-control/people-foods-avoid-feeding-your-pets', 'note' => 'The reference list behind which human foods are genuinely toxic to cats.'],
        ['name' => 'Cornell Feline Health Center', 'url' => 'https://www.vet.cornell.edu/departments-centers-and-institutes/cornell-feline-health-center', 'note' => 'Background on feline nutrition and why cats need a meat-based diet.'],
        ['name' => 'WSAVA Global Nutrition Guidelines', 'url' => 'https://wsava.org/global-guidelines/global-nutrition-guidelines/', 'note' => 'The nutritional framework behind why extras cannot replace a complete diet.'],
    ];

    public function index(): View
    {
        $url = rtrim(config('app.url'), '/');
        $foods = collect(config('catalog.foods'));

        $description = 'What cats can and cannot eat, one food category at a time: safe, in '
            .'moderation, or never, with the reason behind each verdict.';

        return view('food-guides.index', [
            'title' => 'What Can Cats Eat? Cat Food Safety Guides | '.config('app.name'),
            'description' => $description,
            'canonical' => $url.'/food-guides',
            'foods' => $foods,
            'schema' => Schema::graph([
                [
                    '@type' => 'CollectionPage',
                    '@id' => $url.'/food-guides#page',
                    'url' => $url.'/food-guides',
                    'name' => 'Cat Food Safety Guides',
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                Schema::itemList('/food-guides#guides', 'Cat food safety guides',
                    $foods->map(fn (array $f): array => [
                        'name' => $f['question'], 'description' => $f['answer'],
                    ])->all()),
                Schema::breadcrumbs('/food-guides', ['Home' => '/', 'Food Guides' => null]),
            ]),
        ]);
    }

    public function show(string $slug): View
    {
        $food = collect(config('catalog.foods'))->firstWhere('slug', $slug);

        if (! $food) {
            throw new NotFoundHttpException;
        }

        $url = rtrim(config('app.url'), '/');
        $path = '/food-guides/'.$slug;

        // Same real fields the card already shows, joined into one
        // paragraph — nothing here is a claim beyond what is on the page.
        $description = $food['question'].' '.$food['answer'].' '.self::VERDICT_LABEL[$food['verdict']];

        $related = collect(config('catalog.foods'))
            ->reject(fn (array $f): bool => $f['slug'] === $slug)
            ->take(4);

        return view('food-guides.show', [
            'title' => $food['question'].' | '.config('app.name'),
            'description' => $description,
            'canonical' => $url.$path,
            'food' => $food,
            'related' => $related,
            'sources' => self::SOURCES,
            'schema' => Schema::graph([
                [
                    '@type' => 'WebPage',
                    '@id' => $url.$path.'#page',
                    'url' => $url.$path,
                    'name' => $food['question'],
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                Schema::faq($path.'#faq', [
                    ['q' => $food['question'], 'a' => $food['answer']],
                ]),
                Schema::breadcrumbs($path, [
                    'Home' => '/',
                    'Food Guides' => '/food-guides',
                    $food['title'] => null,
                ]),
            ]),
        ]);
    }
}

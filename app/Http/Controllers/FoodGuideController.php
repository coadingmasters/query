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

        $description = 'Find out exactly what cats can and cannot eat: 10 food categories, each rated '
            .'safe, caution or never, with the reasoning behind every verdict.';

        $faqs = [
            ['q' => 'How does the safe, caution and never system work on this site?', 'a' => 'Every food guide opens with one of three verdicts. Safe means fine for a healthy adult cat in a normal amount. Caution means not toxic, but with a real catch, a prep step, a small portion limit, or a part of the food to remove first. Never means there is no safe amount, and the guide covers what to do if it already happened.'],
            ['q' => 'What human foods can cats eat?', 'a' => 'Plain cooked meat and fish are the safest human foods for a cat, since they\'re closest to a cat\'s normal diet. Small amounts of a few fruits and vegetables, like melon, blueberries, cooked carrot or pumpkin, work as an occasional extra. Each category guide on this page covers exactly which foods qualify and how much is reasonable to offer.'],
            ['q' => 'What can cats not eat?', 'a' => 'A short list of ordinary kitchen ingredients is dangerous to cats in any amount: onion, garlic, chocolate, grapes, raisins, alcohol and xylitol among them. These are not a matter of moderation the way many other foods are. The toxic foods guide covers the full list, why each one is dangerous, and what to do if your cat eats one.'],
            ['q' => 'What should I do if my cat eats something on the never list?', 'a' => 'Call your vet or an animal poison control line right away, rather than waiting to see if symptoms show up. Have the packaging, or a rough idea of what and how much your cat ate, ready to describe. Do not try to induce vomiting on your own unless a vet specifically tells you to, since that is not safe for every ingredient.'],
            ['q' => 'What are the signs my cat ate something toxic?', 'a' => 'Vomiting, diarrhea, drooling, weakness and pale gums are common early signs across most toxic foods, though the exact pattern depends on what was eaten. Tremors, a fast heart rate or rapid breathing can follow chocolate, caffeine or alcohol specifically. Any known or suspected ingestion is worth a call to your vet immediately, even before symptoms appear.'],
            ['q' => 'Do treats count differently than a regular meal?', 'a' => 'Treats and human food extras are meant to stay a small slice of a cat\'s day, not a habit that competes with a balanced diet. A common guideline keeps treats and extras under about 10 percent of daily calories, with the rest coming from a complete cat food. The cat calorie calculator can work out what that 10 percent looks like for your cat.'],
            ['q' => 'Why does a cat need a different food safety list than a dog?', 'a' => 'Cats are obligate carnivores with a smaller body and a more sensitive response to certain compounds than dogs have. Onion and garlic, for example, are more dangerous to a cat than to a similarly sized dog, because a cat processes the sulfur compounds involved less efficiently. A food safety list built for dogs does not transfer safely to a cat.'],
            ['q' => 'Where does the safety information on this site come from?', 'a' => 'Each guide draws on established veterinary and feline-nutrition sources, including the ASPCA Animal Poison Control Center and the Cornell Feline Health Center, both named at the bottom of every guide. Nothing here is written as a diagnosis, and anything that looks like a real emergency is a reason to call your vet directly rather than rely on a website.'],
        ];

        return view('food-guides.index', [
            'title' => 'What Can Cats Eat? Complete Safe & Toxic Guide | '.config('app.name'),
            'description' => $description,
            'canonical' => $url.'/food-guides',
            'foods' => $foods,
            'faqs' => $faqs,
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
                Schema::faq('/food-guides#faq', $faqs),
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
        // paragraph, nothing here is a claim beyond what is on the page.
        // A guide can supply its own meta_description instead, written for
        // search intent and click-through rather than assembled from parts.
        $description = $food['meta_description']
            ?? $food['question'].' '.$food['answer'].' '.self::VERDICT_LABEL[$food['verdict']];

        $related = collect(config('catalog.foods'))
            ->reject(fn (array $f): bool => $f['slug'] === $slug)
            ->take(4);

        // Most food pages answer one question, so the single q/a pair is
        // FAQ enough. A category page like fruits or vegetables covers many
        // foods at once and earns a real FAQ block, so it supplies its own.
        $faq = $food['faq'] ?? [['q' => $food['question'], 'a' => $food['answer']]];

        // Built from the sections that actually render for this food, so a
        // guide with no per-item table never advertises one in its contents.
        $toc = collect([
            ['id' => 'why', 'label' => 'Why', 'when' => ! empty($food['why'])],
            ['id' => 'each-one', 'label' => $food['title'].', one at a time', 'when' => ! empty($food['items'])],
            ['id' => 'how-much', 'label' => $food['verdict'] === 'unsafe' ? 'If your cat ate this' : 'How much is safe', 'when' => ! empty($food['guidance'])],
            ['id' => 'introduce', 'label' => $food['verdict'] === 'unsafe' ? 'If it already happened' : 'Introducing it safely', 'when' => ! empty($food['introduce'])],
            ['id' => 'avoid', 'label' => 'What to avoid entirely', 'when' => ! empty($food['avoid'])],
            ['id' => 'signs', 'label' => 'Signs to watch for', 'when' => ! empty($food['watch_for'])],
            ['id' => 'faq', 'label' => 'Questions, answered', 'when' => count($faq) > 1],
        ])->where('when', true)->values();

        // The at-a-glance card. Pulled from the per-item table when a guide
        // has one, so it can never drift from the table below it.
        $items = collect($food['items'] ?? []);
        $safeList = $items->where('verdict', 'safe')->pluck('name');
        $avoidList = $items->where('verdict', 'unsafe')->pluck('name');

        $recommendedTools = collect(config('catalog.tools'))
            ->whereIn('slug', ['cat-age-calculator', 'cat-weight-checker', 'cat-calorie-calculator'])
            ->values();

        return view('food-guides.show', [
            'title' => $food['question'].' | '.config('app.name'),
            'description' => $description,
            'canonical' => $url.$path,
            'food' => $food,
            'related' => $related,
            'sources' => self::SOURCES,
            'faq' => $faq,
            'publishedAt' => $food['published_at'] ?? null,
            'updatedAt' => $food['updated_at'] ?? null,
            'toc' => $toc,
            'safeList' => $safeList,
            'avoidList' => $avoidList,
            'recommendedTools' => $recommendedTools,
            'schema' => Schema::graph([
                [
                    '@type' => 'WebPage',
                    '@id' => $url.$path.'#page',
                    'url' => $url.$path,
                    'name' => $food['question'],
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                Schema::faq($path.'#faq', $faq),
                Schema::breadcrumbs($path, [
                    'Home' => '/',
                    'Food Guides' => '/food-guides',
                    $food['title'] => null,
                ]),
            ]),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Support\Schema;
use Illuminate\Contracts\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $url = rtrim(config('app.url'), '/');
        $categories = collect(config('shop.categories'));
        $picks = collect(config('shop.picks'));

        $description = 'Carefully selected cat products for feeding, grooming, health, '
            .'comfort, play and litter care, organized by category.';

        return view('shop.index', [
            'title' => 'Recommended Cat Products & Supplies | '.config('app.name'),
            'description' => $description,
            'canonical' => $url.'/shop',
            'categories' => $categories,
            'picks' => $picks,
            'schema' => Schema::graph([
                [
                    '@type' => 'CollectionPage',
                    '@id' => $url.'/shop#page',
                    'url' => $url.'/shop',
                    'name' => 'Recommended Cat Products & Supplies',
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                Schema::itemList('/shop#categories', 'Cat product categories',
                    $categories->map(fn (array $c): array => [
                        'name' => $c['title'], 'description' => $c['description'],
                    ])->all()),
                Schema::breadcrumbs('/shop', ['Home' => '/', 'Shop' => null]),
            ]),
        ]);
    }
}

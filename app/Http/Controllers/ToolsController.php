<?php

namespace App\Http\Controllers;

use App\Support\Schema;
use Illuminate\Contracts\View\View;

class ToolsController extends Controller
{
    public function index(): View
    {
        $url = rtrim(config('app.url'), '/');
        $tools = collect(config('catalog.tools'));

        $description = 'Every free cat care calculator and checker on '.config('app.name')
            .' in one place: age, calories, pregnancy, vaccinations and more.';

        return view('tools.index', [
            'title' => 'Free Cat Care Tools | '.config('app.name'),
            'description' => $description,
            'canonical' => $url.'/tools',
            'tools' => $tools,
            'schema' => Schema::graph([
                [
                    '@type' => 'CollectionPage',
                    '@id' => $url.'/tools#page',
                    'url' => $url.'/tools',
                    'name' => 'Free Cat Care Tools',
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                Schema::itemList('/tools#tools', 'Free cat care tools',
                    $tools->filter(fn (array $t): bool => isset($t['url']))
                        ->map(fn (array $t): array => [
                            'name' => $t['title'], 'description' => $t['blurb'],
                        ])->all()),
                Schema::breadcrumbs('/tools', ['Home' => '/', 'Tools' => null]),
            ]),
        ]);
    }
}

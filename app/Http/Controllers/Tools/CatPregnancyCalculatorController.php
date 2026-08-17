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

        $description = 'Free cat pregnancy calculator — enter the mating date to '
            .'estimate your cat’s due date, the likely birth window and which '
            .'week she is in. No sign-up needed.';

        return view('tools.cat-pregnancy-calculator', [
            'title' => 'Cat Pregnancy Calculator — Due Date & Week by Week',
            'description' => $description,
            'canonical' => $url.$path,
            'schema' => Schema::graph([
                [
                    '@type' => 'WebPage',
                    '@id' => $url.$path.'#page',
                    'url' => $url.$path,
                    'name' => 'Cat Pregnancy Calculator',
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                Schema::breadcrumbs($path, [
                    'Home' => '/',
                    'Tools' => '/#tools',
                    'Cat Pregnancy Calculator' => null,
                ]),
            ]),
        ]);
    }
}

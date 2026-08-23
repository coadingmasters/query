<?php

namespace App\Http\Controllers;

use App\Support\Schema;
use Illuminate\Contracts\View\View;

class HowItWorksController extends Controller
{
    public function __invoke(): View
    {
        $url = rtrim(config('app.url'), '/');
        $name = config('app.name');

        $title = 'How PurrQuery Works | Free Cat Care Tools';
        $description = 'How PurrQuery\'s tools work: pick one, enter your cat\'s details, get an '
            .'instant answer. Nothing you enter is sent anywhere or stored.';

        return view('how-it-works', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.'/how-it-works',
            'schema' => Schema::graph([
                [
                    '@type' => 'WebPage',
                    '@id' => $url.'/how-it-works#page',
                    'url' => $url.'/how-it-works',
                    'name' => $title,
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                ],
                Schema::breadcrumbs('/how-it-works', ['Home' => '/', 'How It Works' => null]),
            ]),
        ]);
    }
}

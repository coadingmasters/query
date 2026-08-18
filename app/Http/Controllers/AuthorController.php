<?php

namespace App\Http\Controllers;

use App\Support\Schema;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorController extends Controller
{
    public function __invoke(): View
    {
        $author = config('author.founder');

        // No configured author, no page. A profile page for nobody is worse
        // than a 404: it is an authorship claim with nothing behind it.
        if (! $author['name']) {
            throw new NotFoundHttpException;
        }

        $url = rtrim(config('app.url'), '/');
        $title = $author['name'].' | '.config('app.name');

        $description = $author['name'].' writes and builds '.config('app.name').'. '
            .'Not a veterinarian: guides here are researched from published '
            .'veterinary sources, and those sources are named.';

        return view('author', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.'/author',
            'author' => $author,
            'reviewer' => config('author.reviewer'),
            'schema' => Schema::graph([
                [
                    // ProfilePage is what Google reads for an author page, and
                    // mainEntity is the part that ties it to the Person node
                    // every other page already references.
                    '@type' => 'ProfilePage',
                    '@id' => $url.'/author#page',
                    'url' => $url.'/author',
                    'name' => $title,
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                    'mainEntity' => ['@id' => $url.'/#founder'],
                ],
                Schema::breadcrumbs('/author', [
                    'Home' => '/',
                    'About' => '/about',
                    $author['name'] => null,
                ]),
            ]),
        ]);
    }
}

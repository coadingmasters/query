<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class TermsController extends Controller
{
    public function __invoke(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');

        $description = 'The terms that govern use of PurrQuery — what the site '
            .'provides, what it does not, and the limits of relying on general '
            .'cat care information.';

        return view('terms', [
            'title' => 'Terms & Conditions — '.$name,
            'description' => $description,
            'canonical' => $url.'/terms',
            'sections' => config('legal.terms'),
            'effective' => config('legal.terms_effective'),
            'schema' => [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        // WebPage rather than anything more specific: there is no
                        // schema type for terms of use, and inventing a fit would
                        // describe the page as something it is not.
                        '@type' => 'WebPage',
                        '@id' => $url.'/terms#page',
                        'url' => $url.'/terms',
                        'name' => 'Terms & Conditions',
                        'description' => $description,
                        'isPartOf' => ['@id' => $url.'/#website'],
                        'dateModified' => config('legal.terms_effective'),
                    ],
                    [
                        '@type' => 'BreadcrumbList',
                        '@id' => $url.'/terms#breadcrumb',
                        'itemListElement' => [
                            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $url.'/'],
                            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Terms & Conditions'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}

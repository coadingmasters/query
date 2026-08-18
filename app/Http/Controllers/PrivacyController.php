<?php

namespace App\Http\Controllers;

use App\Support\Schema;
use Illuminate\Contracts\View\View;

class PrivacyController extends Controller
{
    public function __invoke(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');

        $description = 'What PurrQuery collects and why: an email if you '
            .'subscribe, your message if you write in, and nothing else. No '
            .'analytics, no tracking cookies, no sharing.';

        return view('privacy', [
            'title' => 'Privacy Policy | '.$name,
            'description' => $description,
            'canonical' => $url.'/privacy',
            'sections' => config('legal.privacy'),
            'summary' => config('legal.privacy_summary'),
            'assurances' => config('legal.privacy_assurances'),
            'effective' => config('legal.privacy_effective'),
            'schema' => Schema::graph([
                [
                    '@type' => 'WebPage',
                    '@id' => $url.'/privacy#page',
                    'url' => $url.'/privacy',
                    'name' => 'Privacy Policy',
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                    'dateModified' => config('legal.privacy_effective'),
                ],
                Schema::breadcrumbs('/privacy', ['Home' => '/', 'Privacy Policy' => null]),
            ]),
        ]);
    }
}

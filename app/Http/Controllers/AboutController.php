<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\Schema;
use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        $name = config('app.name');
        $url = rtrim(config('app.url'), '/');

        // Counted from the catalogue and the posts table rather than typed
        // in, so the page cannot drift out of step with the site as tools
        // and guides are added. catalog.tools only ever holds tools with a
        // real page — see config/catalog.php — so every one of these is
        // already live; nothing here needs filtering by hand.
        //
        // Published only. A visitor who clicks through from a number
        // expecting a working page and finds "coming soon" learns not to
        // trust the next number on the page, which is the opposite of what
        // this section is for.
        $liveTools = count(config('catalog.tools'));
        $livePosts = Post::published()->count();
        $foods = count(config('catalog.foods'));

        // Tools still waiting to be built, plus posts still being written
        // (draft or scheduled), rather than published yet.
        $inProgress = count(config('catalog.tools_upcoming'))
            + Post::query()->whereIn('status', ['draft', 'scheduled'])->count();

        $title = 'About '.$name.' | Trusted Cat Care Tools & Guides';
        $description = 'PurrQuery is a free hub of cat care tools and '
            .'research-backed food-safety guides, built for cat owners who want '
            .'clear, honest answers without a sign-up.';

        return view('about', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.'/about',
            'stats' => [
                [$liveTools, 'Free cat care tools, live today'],
                [$foods, 'Food safety categories'],
                [$livePosts, 'Guides published so far'],
                ['$0', 'Cost to use, always'],
            ],
            'inProgress' => $inProgress,
            'schema' => Schema::graph([
                [
                    '@type' => 'AboutPage',
                    '@id' => $url.'/about#page',
                    'url' => $url.'/about',
                    'name' => $title,
                    'description' => $description,
                    'isPartOf' => ['@id' => $url.'/#website'],
                    'about' => ['@id' => $url.'/#organization'],
                ] + (config('author.founder.name') ? [
                    'author' => ['@id' => $url.'/#founder'],
                    'mainEntity' => ['@id' => $url.'/#founder'],
                ] : []) + [
                ],
                Schema::breadcrumbs('/about', ['Home' => '/', 'About' => null]),
            ]),
        ]);
    }
}

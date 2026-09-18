<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Post;
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

        $posts = Post::published()->get(['sources']);
        $citations = $posts->flatMap(fn (Post $p) => $p->sources ?? []);

        // Counted, not written in, matching the same rule the About page's
        // own numbers follow. A "100+" here would outrun what is actually
        // published, which is exactly the overclaim this site is built to
        // avoid making about itself.
        $stats = [
            ['value' => Post::published()->count(), 'label' => 'Guides Written', 'body' => 'Well-researched cat care articles and guides'],
            ['value' => count(config('catalog.tools')), 'label' => 'Smart Tools', 'body' => 'Interactive calculators and trackers built for cat parents'],
            ['value' => $citations->pluck('url')->unique()->count(), 'label' => 'Sources Cited', 'body' => 'Named on the guide that uses them, not just claimed here'],
            ['value' => 'Cat-First', 'label' => 'Approach', 'body' => 'Everything we do is for the health and happiness of cats'],
        ];

        // The organizations actually cited, ranked by how often a guide
        // draws on them — not a claim about "trusted sources", a count of
        // the real ones. Taking the name before its first comma or colon
        // collapses a source's several differently-titled citations (e.g.
        // "Cornell Feline Health Center: vaccinations" and "..., Feline
        // Pancreatitis") into one entry, without a hand-maintained list
        // that drifts out of step with what the guides actually cite.
        $topSources = $citations
            ->map(fn (array $s) => trim(preg_replace('/[:,].*/', '', $s['name'])))
            ->countBy()
            ->sortDesc()
            ->take(8)
            ->keys();

        $url = rtrim(config('app.url'), '/');
        $title = $author['name'].', Founder | '.config('app.name');

        $description = $author['name'].' writes and builds '.config('app.name').'. '
            .'Not a veterinarian: guides here are researched from published '
            .'veterinary sources, and those sources are named.';

        return view('author', [
            'title' => $title,
            'description' => $description,
            'canonical' => $url.'/author',
            'author' => $author,
            'reviewer' => config('author.reviewer'),
            'stats' => $stats,
            'topSources' => $topSources,
            'catImage' => Media::where('name', 'cat-with-flower')->first(),
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

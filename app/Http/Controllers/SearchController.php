<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $query = trim((string) $request->query('q', ''));
        $results = $query !== '' ? $this->search($query, 30) : collect();

        // An exact title match ("Cat Age Calculator", word for word) is not
        // ambiguous — send the visitor straight to that one page instead of
        // making them pick it back out of a list. Anything short of an exact
        // match still gets the results page, since guessing wrong there is
        // worse than one extra click.
        $exact = $results->where('rank', 100);
        if ($exact->count() === 1) {
            return redirect($exact->first()['url']);
        }

        $name = config('app.name');

        return view('search', [
            'title' => ($query !== '' ? "Search results for \u{201c}{$query}\u{201d}" : 'Search').' | '.$name,
            'description' => 'Search PurrQuery\'s free cat care tools, food guides and blog.',
            'canonical' => rtrim(config('app.url'), '/').'/search',
            'query' => $query,
            'results' => $results,
        ]);
    }

    /** Feeds the hero search box's live dropdown — a handful of top matches, not a full page. */
    public function suggest(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        return response()->json([
            'results' => $query !== '' ? $this->search($query, 6)->values() : [],
        ]);
    }

    /** Searches live tools, food guides and published posts by title/body substring, best matches first. */
    private function search(string $query, int $limit): Collection
    {
        $needle = Str::lower($query);

        $tools = collect(config('catalog.tools'))
            ->filter(fn (array $tool): bool => isset($tool['url'])) // tools without a page yet aren't linkable
            ->map(fn (array $tool): array => [
                'type' => 'Tool',
                'title' => $tool['title'],
                'excerpt' => $tool['blurb'],
                'url' => $tool['url'],
                'rank' => $this->rank($needle, $tool['title'], $tool['blurb']),
            ]);

        $foods = collect(config('catalog.foods'))->map(fn (array $food): array => [
            'type' => 'Food guide',
            'title' => $food['question'],
            'excerpt' => $food['answer'],
            'url' => route('food-guides.show', $food['slug']),
            'rank' => $this->rank($needle, $food['title'].' '.$food['question'], $food['answer']),
        ]);

        $posts = Post::published()->get()->map(fn (Post $post): array => [
            'type' => 'Blog',
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'url' => route('blog.show', $post->slug),
            'rank' => $this->rank($needle, $post->title, $post->excerpt),
        ]);

        return $tools->concat($foods)->concat($posts)
            ->filter(fn (array $result): bool => $result['rank'] > 0)
            ->sortByDesc('rank')
            ->take($limit)
            ->values();
    }

    /** A title match outranks a body match, and an exact title match outranks a partial one. */
    private function rank(string $needle, string $title, string $body): int
    {
        $title = Str::lower($title);

        if ($title === $needle) {
            return 100;
        }

        if (str_starts_with($title, $needle)) {
            return 80;
        }

        if (str_contains($title, $needle)) {
            return 60;
        }

        if (str_contains(Str::lower($body), $needle)) {
            return 30;
        }

        return 0;
    }
}

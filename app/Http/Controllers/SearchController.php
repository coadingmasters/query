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

        // Nothing matched as typed. Before giving up, check whether it's a
        // typo of the title rather than a different word — "chikin" for
        // "chicken" — so a misspelled search still finds the right page
        // instead of a blank results screen. Ranked below every real
        // substring match, so a genuine hit is never displaced by a guess.
        if ($this->isTypoOfTitle($needle, $title)) {
            return 20;
        }

        return 0;
    }

    /** Every word in the query must be a close-enough spelling of some word in the title. */
    private function isTypoOfTitle(string $needle, string $title): bool
    {
        // Split on anything that isn't a letter or number, so a title's own
        // punctuation ("Chicken?") doesn't count against it in the edit
        // distance below.
        $queryWords = preg_split('/[^\p{L}\p{N}]+/u', $needle, -1, PREG_SPLIT_NO_EMPTY);
        $titleWords = preg_split('/[^\p{L}\p{N}]+/u', $title, -1, PREG_SPLIT_NO_EMPTY);

        if (! $queryWords || ! $titleWords) {
            return false;
        }

        foreach ($queryWords as $queryWord) {
            $isCloseToAnyTitleWord = collect($titleWords)
                ->contains(fn (string $titleWord): bool => $this->isCloseSpelling($queryWord, $titleWord));

            if (! $isCloseToAnyTitleWord) {
                return false;
            }
        }

        return true;
    }

    /**
     * How many edits (letters swapped, missing or extra) two words can
     * differ by and still count as the same word, typed wrong. Scales with
     * length: one edit is already a big share of a 3-letter word, so short
     * words require an exact match or they'd fuzz-match half the dictionary.
     */
    private function isCloseSpelling(string $a, string $b): bool
    {
        if (strlen($a) < 4 || strlen($b) < 4) {
            return $a === $b;
        }

        // A typo overwhelmingly preserves the first letter someone actually
        // typed - it's transpositions, doubled letters and missing letters
        // later in the word that cause most misspellings. Requiring it is
        // what keeps a short, common word elsewhere in the site's titles
        // (e.g. "feeding") from being pulled in as a false match for an
        // unrelated word within the same edit distance ("kneding").
        if ($a[0] !== $b[0]) {
            return false;
        }

        $longest = max(strlen($a), strlen($b));
        $allowedEdits = match (true) {
            $longest <= 5 => 1,
            $longest <= 9 => 2,
            default => 3,
        };

        return levenshtein($a, $b) <= $allowedEdits;
    }
}

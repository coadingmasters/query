<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Support\Str;
use Tests\TestCase;

class SearchTest extends TestCase
{
    public function test_the_search_page_renders_with_no_query(): void
    {
        $this->get('/search')
            ->assertOk()
            ->assertSee('Type something above to search');
    }

    public function test_a_matching_tool_title_is_found(): void
    {
        $this->get('/search?q=calorie')
            ->assertOk()
            ->assertSee('Cat Calorie Calculator');
    }

    public function test_a_matching_food_guide_is_found(): void
    {
        $this->get('/search?q=chocolate')
            ->assertOk()
            ->assertSee('Toxic Foods');
    }

    public function test_a_matching_blog_post_is_found(): void
    {
        $post = Post::published()->firstOrFail();

        // A partial title, not the full one — the exact title is an exact
        // match, which redirects straight to the post instead of listing it;
        // that behaviour is covered on its own below.
        $partial = Str::of($post->title)->words(2, '')->trim();

        $this->get('/search?q='.urlencode($partial))
            ->assertOk()
            ->assertSee($post->title);
    }

    /** An exact title match is unambiguous: skip the list and go straight there. */
    public function test_an_exact_title_match_redirects_straight_to_the_page(): void
    {
        $post = Post::published()->firstOrFail();

        $this->get('/search?q='.urlencode($post->title))
            ->assertRedirect(route('blog.show', $post->slug));
    }

    public function test_an_exact_tool_title_match_redirects_straight_to_the_tool(): void
    {
        $this->get('/search?q='.urlencode('Cat Calorie Calculator'))
            ->assertRedirect('/tools/cat-calorie-calculator');
    }

    public function test_a_tool_with_no_page_yet_is_not_a_result(): void
    {
        // "Breed Quiz" has no `url` in config('catalog.tools') — it cannot
        // be a search result with nowhere for the link to go. Checked
        // against the JSON endpoint since the page itself also carries a
        // "coming soon" tools widget that legitimately names it.
        $titles = collect($this->getJson('/search/suggest?q=breed+quiz')->json('results'))
            ->pluck('title');

        $this->assertNotContains('Breed Quiz', $titles);
    }

    public function test_no_matches_shows_the_empty_state(): void
    {
        $this->get('/search?q=xyznonexistentquery')
            ->assertOk()
            ->assertSee('Nothing matches');
    }

    public function test_the_search_page_is_not_indexed(): void
    {
        $this->get('/search?q=cat')->assertSee('noindex', false);
    }

    public function test_the_suggest_endpoint_returns_json(): void
    {
        $response = $this->getJson('/search/suggest?q=calorie');

        $response->assertOk()->assertJsonStructure(['results']);
        $this->assertNotEmpty($response->json('results'));
    }

    public function test_the_suggest_endpoint_is_empty_for_a_blank_query(): void
    {
        $this->getJson('/search/suggest?q=')
            ->assertOk()
            ->assertExactJson(['results' => []]);
    }
}

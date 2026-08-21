<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostCategory;
use Tests\TestCase;

/**
 * The blog had article pages before it had a page listing them, which meant
 * the nav item pointed at a section of the home page and the articles were
 * reachable only from there.
 */
class BlogTest extends TestCase
{
    public function test_the_index_lists_every_post(): void
    {
        $html = $this->get('/blog')->assertOk()->getContent();

        foreach (Post::published()->get() as $post) {
            $this->assertStringContainsString($post->title, $html, "the index is missing {$post->slug}");
        }
    }

    /** Every published post is a real link to its own page. */
    public function test_every_post_is_linked(): void
    {
        $html = $this->get('/blog')->getContent();

        foreach (Post::published()->get() as $post) {
            $this->assertStringContainsString('href="'.route('blog.show', $post->slug).'"', $html);
        }
    }

    /**
     * The list describes what exists. A draft should never show up on the
     * public index, which is what "published" is for.
     */
    public function test_structured_data_counts_only_published_posts(): void
    {
        Post::factory()->create(['status' => 'draft']);

        $html = $this->get('/blog')->getContent();
        $published = Post::published()->count();

        $this->assertStringContainsString('"numberOfItems":'.$published, $html);
    }

    public function test_an_article_renders_with_its_structured_data(): void
    {
        $this->get('/blog/why-do-cats-knead')
            ->assertOk()
            ->assertSee('Why Do Cats Knead?', false)
            ->assertSee('"@type":"Article"', false)
            ->assertSee('"@type":"FAQPage"', false);
    }

    public function test_an_unknown_article_is_a_404(): void
    {
        $this->get('/blog/not-a-real-post')->assertNotFound();
    }

    /** A draft post is not reachable from its public URL. */
    public function test_a_draft_post_is_a_404(): void
    {
        $post = Post::factory()->create(['status' => 'draft']);

        $this->get('/blog/'.$post->slug)->assertNotFound();
    }

    public function test_the_article_faq_markup_matches_what_is_on_the_page(): void
    {
        $html = $this->get('/blog/why-do-cats-knead')->getContent();
        $post = Post::where('slug', 'why-do-cats-knead')->firstOrFail();

        foreach ($post->faqs as $item) {
            $this->assertStringContainsString(e($item->question), $html);
            $this->assertStringContainsString(e($item->answer), $html);
        }
    }

    public function test_the_sitemap_lists_the_blog_and_the_article(): void
    {
        $base = rtrim(config('app.url'), '/');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee($base.'/blog</loc>', false)
            ->assertSee($base.'/blog/why-do-cats-knead', false);
    }

    /** Every page should be able to reach the blog. */
    public function test_the_blog_is_reachable_from_the_footer(): void
    {
        foreach (['/', '/about', '/contact'] as $path) {
            $this->get($path)->assertSee('href="'.route('blog.index').'"', false);
        }
    }

    /**
     * A "was this helpful" widget that thanks the reader and stores nothing is
     * worse than not asking, so the vote is a real row.
     */
    public function test_a_vote_is_recorded(): void
    {
        $this->postJson('/blog/feedback', ['slug' => 'why-do-cats-knead', 'helpful' => true])
            ->assertOk()
            ->assertJson(['recorded' => true]);

        $this->assertDatabaseHas('article_feedback', [
            'slug' => 'why-do-cats-knead',
            'helpful' => true,
        ]);
    }

    /** Without this the endpoint is a table anyone can write anything into. */
    public function test_a_vote_for_an_unknown_article_is_rejected(): void
    {
        $this->postJson('/blog/feedback', ['slug' => 'made-up', 'helpful' => true])
            ->assertStatus(422);

        $this->assertDatabaseCount('article_feedback', 0);
    }

    /** A vote for a real but unpublished post is rejected the same way. */
    public function test_a_vote_for_a_draft_article_is_rejected(): void
    {
        $post = Post::factory()->create(['status' => 'draft']);

        $this->postJson('/blog/feedback', ['slug' => $post->slug, 'helpful' => true])
            ->assertStatus(422);

        $this->assertDatabaseCount('article_feedback', 0);
    }

    public function test_a_vote_needs_both_fields(): void
    {
        $this->postJson('/blog/feedback', ['slug' => 'why-do-cats-knead'])->assertStatus(422);
        $this->postJson('/blog/feedback', ['helpful' => false])->assertStatus(422);

        $this->assertDatabaseCount('article_feedback', 0);
    }

    /**
     * The privacy policy says a vote is a vote. Storing an address or a user
     * agent alongside it would make that untrue the moment it shipped.
     */
    public function test_a_vote_identifies_nobody(): void
    {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('article_feedback');

        $this->assertSame(
            ['id', 'slug', 'helpful', 'created_at', 'updated_at'],
            $columns,
            'article_feedback gained a column'
        );
    }

    /** The article links here with ?topic=, so those links must do something. */
    public function test_the_index_accepts_a_topic_in_the_query(): void
    {
        $this->get('/blog?topic=Health')->assertOk();
    }

    /** Categories with no published posts do not appear as filters. */
    public function test_the_topic_filters_come_from_published_posts(): void
    {
        PostCategory::factory()->create(['name' => 'Empty Topic']);

        $this->get('/blog')->assertDontSee('Empty Topic');
    }
}

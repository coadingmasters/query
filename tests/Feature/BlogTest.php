<?php

namespace Tests\Feature;

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

        foreach (config('catalog.posts') as $post) {
            $this->assertStringContainsString($post['title'], $html, "the index is missing {$post['slug']}");
        }
    }

    /** Written ones are links. The rest say so rather than leading nowhere. */
    public function test_only_published_posts_are_linked(): void
    {
        $html = $this->get('/blog')->getContent();

        foreach (config('catalog.posts') as $post) {
            if (isset($post['url'])) {
                $this->assertStringContainsString('href="'.$post['url'].'"', $html);
            }
        }

        $unwritten = collect(config('catalog.posts'))->reject(fn (array $p): bool => isset($p['url']));

        if ($unwritten->isNotEmpty()) {
            $this->assertStringContainsString('Coming soon', $html);
        }
    }

    /**
     * The list describes what exists. Including the unwritten ones would
     * advertise a library that is not there.
     */
    public function test_structured_data_counts_only_published_posts(): void
    {
        $html = $this->get('/blog')->getContent();
        $published = collect(config('catalog.posts'))->filter(fn (array $p): bool => isset($p['url']))->count();

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

    /**
     * A slug in config with no view behind it would render an empty article
     * shell rather than failing, which is worse than a 404.
     */
    public function test_a_post_without_a_body_is_a_404(): void
    {
        config(['blog.ghost-post' => ['slug' => 'ghost-post']]);

        $this->get('/blog/ghost-post')->assertNotFound();
    }

    public function test_the_article_faq_markup_matches_what_is_on_the_page(): void
    {
        $html = $this->get('/blog/why-do-cats-knead')->getContent();

        foreach (config('blog.why-do-cats-knead.faq') as $item) {
            $this->assertStringContainsString(e($item['q']), $html);
            $this->assertStringContainsString(e($item['a']), $html);
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
}

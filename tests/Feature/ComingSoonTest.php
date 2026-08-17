<?php

namespace Tests\Feature;

use Tests\TestCase;

class ComingSoonTest extends TestCase
{
    public function test_the_launch_page_renders(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(config('app.name'))
            ->assertSee(config('brand.description'));
    }

    /**
     * The page has to be indexable for the domain to start ranking, so the
     * status must be 200 rather than the 503 a maintenance page would send.
     */
    public function test_the_launch_page_is_indexable(): void
    {
        $response = $this->get('/');

        $this->assertSame(200, $response->status());
        $response->assertHeaderMissing('X-Robots-Tag');
        $response->assertDontSee('noindex');
    }

    public function test_the_launch_page_carries_its_seo_tags(): void
    {
        $url = rtrim(config('app.url'), '/');

        $this->get('/')
            ->assertSee('<link rel="canonical" href="'.$url.'/">', false)
            ->assertSee('<meta property="og:image" content="'.$url.'/og-image.png">', false)
            ->assertSee('"@type":"WebSite"', false);
    }

    /**
     * The rotating tagline is decorative, so the full wording has to exist in
     * the markup for screen readers and crawlers that do not run scripts.
     */
    public function test_the_rotating_tagline_has_a_static_equivalent(): void
    {
        $response = $this->get('/');

        foreach (config('brand.rotates') as $word) {
            $response->assertSee($word, false);
        }
    }

    /**
     * The rotator reads its words from a data attribute. Blade's @js helper
     * emits a JS expression rather than JSON, which parses as HTML but throws
     * in the browser, so the attribute is checked as real JSON here.
     */
    public function test_the_rotator_words_are_valid_json(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertMatchesRegularExpression("/data-rotate='([^']*)'/", $html);
        preg_match("/data-rotate='([^']*)'/", $html, $m);

        $words = json_decode(html_entity_decode($m[1]), true);

        $this->assertSame(JSON_ERROR_NONE, json_last_error());
        $this->assertSame(config('brand.rotates'), $words);
    }

    public function test_the_sitemap_lists_the_home_page(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(rtrim(config('app.url'), '/').'/', false);
    }
}

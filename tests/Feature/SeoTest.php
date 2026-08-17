<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Guards the things search engines read.
 *
 * These are easy to break without noticing: a title creeps past the length
 * Google will display, a canonical points at the wrong host after a config
 * change, or structured data starts describing content that is no longer on
 * the page. None of it shows up in the browser.
 */
class SeoTest extends TestCase
{
    private function html(): string
    {
        return $this->get('/')->getContent();
    }

    public function test_the_title_is_present_and_a_sensible_length(): void
    {
        preg_match('/<title>(.*?)<\/title>/s', $this->html(), $m);

        $this->assertNotEmpty($m[1] ?? '', 'the page has no title');

        $length = mb_strlen(html_entity_decode($m[1]));
        $this->assertGreaterThanOrEqual(30, $length, "title is too short: {$m[1]}");
        $this->assertLessThanOrEqual(60, $length, "title will be truncated in results: {$m[1]}");
    }

    public function test_the_description_is_present_and_a_sensible_length(): void
    {
        preg_match('/<meta name="description" content="(.*?)">/s', $this->html(), $m);

        $this->assertNotEmpty($m[1] ?? '', 'the page has no meta description');

        $length = mb_strlen(html_entity_decode($m[1]));
        $this->assertGreaterThanOrEqual(110, $length, 'description is too short to be useful');
        $this->assertLessThanOrEqual(165, $length, 'description will be truncated in results');
    }

    public function test_the_canonical_matches_the_configured_origin(): void
    {
        $this->get('/')->assertSee(
            '<link rel="canonical" href="'.rtrim(config('app.url'), '/').'/">',
            false
        );
    }

    /**
     * The page must stay indexable. A stray noindex is the single most
     * expensive mistake available here, and nothing on screen would show it.
     */
    public function test_the_page_is_indexable(): void
    {
        preg_match('/<meta name="robots" content="(.*?)">/', $this->html(), $m);

        $this->assertNotEmpty($m[1] ?? '', 'no robots directive');
        $this->assertStringContainsString('index', $m[1]);
        $this->assertStringNotContainsString('noindex', $m[1]);

        // Without this Google is limited to a thumbnail for a photo-led site.
        $this->assertStringContainsString('max-image-preview:large', $m[1]);
    }

    public function test_social_sharing_tags_are_complete(): void
    {
        $html = $this->html();

        foreach ([
            'og:type', 'og:site_name', 'og:title', 'og:description',
            'og:url', 'og:image', 'og:image:width', 'og:image:height',
            'og:image:alt', 'og:locale',
        ] as $property) {
            $this->assertMatchesRegularExpression(
                '/<meta property="'.preg_quote($property, '/').'" content="[^"]+">/',
                $html,
                "missing or empty: $property"
            );
        }

        $this->assertStringContainsString('name="twitter:card" content="summary_large_image"', $html);
    }

    /** The social image has to resolve, or shares render with a blank card. */
    public function test_the_social_image_exists(): void
    {
        preg_match('/<meta property="og:image" content="(.*?)">/', $this->html(), $m);

        $path = public_path(parse_url($m[1], PHP_URL_PATH));

        $this->assertFileExists($path, 'og:image points at a file that is not there');
        $this->assertGreaterThan(1000, filesize($path));
    }

    public function test_there_is_exactly_one_h1(): void
    {
        preg_match_all('/<h1\b/i', $this->html(), $m);

        $this->assertCount(1, $m[0], 'a page should have exactly one h1');
    }

    /**
     * The important one. Google requires FAQ markup to describe content the
     * visitor can see; markup that claims questions the page does not answer
     * is a structured-data violation, not just wasted effort.
     */
    public function test_faq_structured_data_matches_visible_content(): void
    {
        $html = $this->html();

        preg_match('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $html, $m);
        $graph = json_decode($m[1], true);

        $this->assertSame(JSON_ERROR_NONE, json_last_error(), 'structured data is not valid JSON');

        $types = array_column($graph['@graph'], '@type');
        $this->assertContains('Organization', $types);
        $this->assertContains('WebSite', $types);
        $this->assertContains('FAQPage', $types);

        $faq = $graph['@graph'][array_search('FAQPage', $types, true)];
        $text = html_entity_decode(strip_tags($html));

        foreach ($faq['mainEntity'] as $entry) {
            $this->assertStringContainsString(
                $entry['name'], $text,
                'question is in the markup but not on the page: '.$entry['name']
            );
            $this->assertStringContainsString(
                $entry['acceptedAnswer']['text'], $text,
                'answer is in the markup but not on the page: '.$entry['name']
            );
        }
    }

    public function test_the_sitemap_and_robots_agree(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<lastmod>', false);

        $robots = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Sitemap:', $robots);
        $this->assertStringNotContainsString('Disallow: /', $robots);
    }
}

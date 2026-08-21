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
 *
 * The whole class used to check '/' only, which is exactly how a 168-character
 * description on the blog article shipped without a single test catching it:
 * every page-length check only ever looked at the home page. Every real page
 * runs through these now.
 */
class SeoTest extends TestCase
{
    public static function pages(): array
    {
        return [
            'home' => ['/'],
            'about' => ['/about'],
            'author' => ['/author'],
            'contact' => ['/contact'],
            'faq' => ['/faq'],
            'terms' => ['/terms'],
            'privacy' => ['/privacy'],
            'blog index' => ['/blog'],
            'blog article' => ['/blog/why-do-cats-knead'],
            'sneezing article' => ['/blog/why-is-my-cat-sneezing'],
            'signs article' => ['/blog/signs-your-cat-is-sick'],
            'feeding article' => ['/blog/how-much-should-i-feed-my-cat'],
            'broccoli article' => ['/blog/can-cats-eat-broccoli'],
            'indoor food article' => ['/blog/best-food-for-indoor-cats'],
            'chicken article' => ['/blog/can-cats-eat-chicken'],
            'new owner article' => ['/blog/new-cat-owner-guide'],
            'age calculator' => ['/tools/cat-age-calculator'],
            'pregnancy calculator' => ['/tools/cat-pregnancy-calculator'],
            'calorie calculator' => ['/tools/cat-calorie-calculator'],
            'vaccination tracker' => ['/tools/cat-vaccination-tracker'],
        ];
    }

    /** Pages whose structured data declares an FAQPage node. */
    public static function pagesWithFaq(): array
    {
        return [
            'home' => ['/'],
            'faq' => ['/faq'],
            'blog article' => ['/blog/why-do-cats-knead'],
            'sneezing article' => ['/blog/why-is-my-cat-sneezing'],
            'signs article' => ['/blog/signs-your-cat-is-sick'],
            'feeding article' => ['/blog/how-much-should-i-feed-my-cat'],
            'broccoli article' => ['/blog/can-cats-eat-broccoli'],
            'indoor food article' => ['/blog/best-food-for-indoor-cats'],
            'chicken article' => ['/blog/can-cats-eat-chicken'],
            'new owner article' => ['/blog/new-cat-owner-guide'],
            'age calculator' => ['/tools/cat-age-calculator'],
            'pregnancy calculator' => ['/tools/cat-pregnancy-calculator'],
            'calorie calculator' => ['/tools/cat-calorie-calculator'],
            'vaccination tracker' => ['/tools/cat-vaccination-tracker'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_the_title_is_present_and_a_sensible_length(string $path): void
    {
        $html = $this->get($path)->assertOk()->getContent();
        preg_match('/<title>(.*?)<\/title>/s', $html, $m);

        $this->assertNotEmpty($m[1] ?? '', "$path has no title");

        $length = mb_strlen(html_entity_decode($m[1]));
        $this->assertGreaterThanOrEqual(30, $length, "$path title is too short: {$m[1]}");
        $this->assertLessThanOrEqual(60, $length, "$path title will be truncated in results: {$m[1]}");
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_the_description_is_present_and_a_sensible_length(string $path): void
    {
        $html = $this->get($path)->getContent();
        preg_match('/<meta name="description" content="(.*?)">/s', $html, $m);

        $this->assertNotEmpty($m[1] ?? '', "$path has no meta description");

        $length = mb_strlen(html_entity_decode($m[1]));
        $this->assertGreaterThanOrEqual(110, $length, "$path description is too short to be useful");

        // 155, not the 165 this used to allow. Google's cutoff is closer to
        // 155-160 in practice, and 165 had already let one page ship over it.
        $this->assertLessThanOrEqual(155, $length, "$path description will be truncated in results ({$length} chars): {$m[1]}");
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_the_canonical_matches_the_page_it_is_on(string $path): void
    {
        $expected = rtrim(config('app.url'), '/').$path;

        $this->get($path)->assertSee(
            '<link rel="canonical" href="'.$expected.'">',
            false
        );
    }

    /**
     * Every page must stay indexable. A stray noindex is the single most
     * expensive mistake available here, and nothing on screen would show it.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_the_page_is_indexable(string $path): void
    {
        $html = $this->get($path)->getContent();
        preg_match('/<meta name="robots" content="(.*?)">/', $html, $m);

        $this->assertNotEmpty($m[1] ?? '', "$path has no robots directive");
        $this->assertStringContainsString('index', $m[1]);
        $this->assertStringNotContainsString('noindex', $m[1]);

        // Without this Google is limited to a thumbnail for a photo-led site.
        $this->assertStringContainsString('max-image-preview:large', $m[1]);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_social_sharing_tags_are_complete(string $path): void
    {
        $html = $this->get($path)->getContent();

        foreach ([
            'og:type', 'og:site_name', 'og:title', 'og:description',
            'og:url', 'og:image', 'og:image:width', 'og:image:height',
            'og:image:alt', 'og:locale',
        ] as $property) {
            $this->assertMatchesRegularExpression(
                '/<meta property="'.preg_quote($property, '/').'" content="[^"]+">/',
                $html,
                "$path is missing or has an empty: $property"
            );
        }

        $this->assertStringContainsString('name="twitter:card" content="summary_large_image"', $html);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_there_is_exactly_one_h1(string $path): void
    {
        $html = $this->get($path)->getContent();
        preg_match_all('/<h1\b/i', $html, $m);

        $this->assertCount(1, $m[0], "$path should have exactly one h1");
    }

    /**
     * The important one. Google requires FAQ markup to describe content the
     * visitor can see; markup that claims questions the page does not answer
     * is a structured-data violation, not just wasted effort.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('pagesWithFaq')]
    public function test_faq_structured_data_matches_visible_content(string $path): void
    {
        $html = $this->get($path)->getContent();

        preg_match('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $html, $m);
        $graph = json_decode($m[1], true);

        $this->assertSame(JSON_ERROR_NONE, json_last_error(), "$path structured data is not valid JSON");

        $types = array_column($graph['@graph'], '@type');
        $this->assertContains('Organization', $types, $path);
        $this->assertContains('WebSite', $types, $path);
        $this->assertContains('FAQPage', $types, $path);

        $faq = $graph['@graph'][array_search('FAQPage', $types, true)];
        $text = html_entity_decode(strip_tags($html));

        foreach ($faq['mainEntity'] as $entry) {
            $this->assertStringContainsString(
                $entry['name'], $text,
                "$path: question is in the markup but not on the page: ".$entry['name']
            );
            $this->assertStringContainsString(
                $entry['acceptedAnswer']['text'], $text,
                "$path: answer is in the markup but not on the page: ".$entry['name']
            );
        }
    }

    /**
     * Redundant with the meta tag on an ordinary page, and that is fine: this
     * is what covers a response a meta tag cannot reach, like a PDF or an
     * image, the day the site starts serving one.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_the_robots_header_matches_the_meta_tag(string $path): void
    {
        $response = $this->get($path);

        $this->assertSame(
            'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            $response->headers->get('X-Robots-Tag'),
            "$path is missing or has the wrong X-Robots-Tag header"
        );
    }

    public function test_the_sitemap_and_robots_agree(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<lastmod>', false);

        $robots = $this->get('/robots.txt')->assertOk()->getContent();

        $this->assertStringContainsString('Sitemap:', $robots);
        $this->assertStringNotContainsString('Disallow: /', $robots);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Structured data is invisible. It can be malformed, contradict the page, or
 * point at nodes that do not exist, and the site will look perfectly fine —
 * the cost lands weeks later in how search engines read it. So it is checked
 * here rather than trusted.
 */
class StructuredDataTest extends TestCase
{
    /** @return array<int, array<int, string>> */
    public static function pages(): array
    {
        return [['/'], ['/faq'], ['/about'], ['/contact'], ['/terms'], ['/privacy']];
    }

    private function graph(string $path): array
    {
        $html = $this->get($path)->getContent();

        preg_match('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $html, $m);

        $this->assertNotEmpty($m[1] ?? '', "$path has no structured data");

        $decoded = json_decode($m[1], true);

        $this->assertSame(JSON_ERROR_NONE, json_last_error(), "$path has malformed JSON-LD");
        $this->assertSame('https://schema.org', $decoded['@context'] ?? null, "$path is missing @context");

        return $decoded['@graph'];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_every_page_identifies_the_publisher_and_the_site(string $path): void
    {
        $types = array_column($this->graph($path), '@type');

        $this->assertContains('Organization', $types, "$path does not say who publishes it");
        $this->assertContains('WebSite', $types, "$path does not say which site it belongs to");
    }

    /**
     * The bug this was written for: five pages pointed isPartOf at
     * "/#website", a node only ever defined on the home page. A crawler
     * reading one of those pages alone had nothing to resolve it against.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_no_reference_points_at_a_node_that_is_not_there(string $path): void
    {
        $graph = $this->graph($path);

        // A node that carries @type is a definition, wherever it sits — the
        // logo, for instance, is defined nested inside the publisher. A bare
        // ['@id' => ...] with nothing else is a reference to one.
        $defined = [];
        $referenced = [];

        $walk = function (array $node) use (&$walk, &$defined, &$referenced): void {
            if (isset($node['@type'], $node['@id'])) {
                $defined[] = $node['@id'];
            } elseif (array_keys($node) === ['@id']) {
                $referenced[] = $node['@id'];
            }

            foreach ($node as $value) {
                if (is_array($value)) {
                    $walk($value);
                }
            }
        };

        $walk($graph);

        $dangling = array_diff(array_unique($referenced), $defined);

        $this->assertEmpty($dangling, "$path references undefined nodes: ".implode(', ', $dangling));
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pages')]
    public function test_the_page_node_matches_the_canonical(string $path): void
    {
        $html = $this->get($path)->getContent();

        preg_match('/<link rel="canonical" href="([^"]+)">/', $html, $m);
        $canonical = rtrim($m[1], '/');

        $urls = array_filter(array_column($this->graph($path), 'url'));

        $this->assertContains($canonical === rtrim(config('app.url'), '/') ? $canonical.'/' : $canonical,
            array_map(fn (string $u): string => $u, $urls),
            "$path: no node in the graph matches the canonical URL");
    }

    /**
     * Google requires FAQ markup to describe content a visitor can read. Both
     * FAQ sets render their answers on the page, so this compares the markup
     * against the rendered text rather than assuming they agree.
     */
    public function test_faq_markup_describes_content_that_is_on_the_page(): void
    {
        foreach (['/', '/faq'] as $path) {
            $html = $this->get($path)->getContent();
            $text = html_entity_decode(strip_tags($html));

            $types = array_column($graph = $this->graph($path), '@type');
            $faq = $graph[array_search('FAQPage', $types, true)];

            foreach ($faq['mainEntity'] as $entry) {
                $this->assertStringContainsString($entry['name'], $text,
                    "$path: question in the markup is not on the page");
                $this->assertStringContainsString($entry['acceptedAnswer']['text'], $text,
                    "$path: answer in the markup is not on the page");
            }
        }
    }

    /**
     * A SearchAction tells Google it can send queries to a URL. There is no
     * such endpoint here — the home page search filters what is already
     * rendered — so claiming one would point Google at a page that ignores it.
     */
    public function test_it_claims_no_search_endpoint_that_does_not_exist(): void
    {
        foreach (self::pages() as [$path]) {
            $types = [];
            // array_walk_recursive takes its array by reference, so the graph
            // has to be a variable rather than a call result.
            $graph = $this->graph($path);

            array_walk_recursive($graph, function ($value, $key) use (&$types): void {
                if ($key === '@type') {
                    $types[] = $value;
                }
            });

            $this->assertNotContains('SearchAction', $types, "$path claims a search endpoint");
        }
    }

    public function test_the_tools_and_guides_are_described_as_collections(): void
    {
        $graph = $this->graph('/');
        $lists = array_values(array_filter($graph, fn (array $n): bool => ($n['@type'] ?? '') === 'ItemList'));

        $this->assertCount(2, $lists, 'expected an ItemList for the tools and one for the guides');

        foreach ($lists as $list) {
            $this->assertSame(count($list['itemListElement']), $list['numberOfItems'],
                'numberOfItems disagrees with the list it describes');
        }
    }
}

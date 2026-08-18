<?php

namespace Tests\Feature;

use Tests\TestCase;

class FaqTest extends TestCase
{
    public function test_the_faq_page_renders(): void
    {
        $this->get('/faq')
            ->assertOk()
            // The headline is split across a line break and a span, so it
            // is asserted in the two pieces the markup actually renders.
            ->assertSee('Cat care questions,')
            ->assertSee('answered');
    }

    public function test_every_question_and_answer_is_on_the_page(): void
    {
        $response = $this->get('/faq');

        foreach (config('faq.groups') as $group) {
            $response->assertSee($group['title']);

            foreach ($group['items'] as $item) {
                $response->assertSee($item['q'], false);
                $response->assertSee($item['a'], false);
            }
        }
    }

    /**
     * Google requires FAQ markup to describe content the visitor can reach.
     * details/summary keeps the answer in the DOM whether open or shut, so
     * this checks the markup against the rendered text rather than assuming.
     */
    public function test_the_structured_data_matches_the_visible_questions(): void
    {
        $html = $this->get('/faq')->getContent();

        preg_match('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/s', $html, $m);
        $graph = json_decode($m[1], true);

        $this->assertSame(JSON_ERROR_NONE, json_last_error());

        $types = array_column($graph['@graph'], '@type');
        $faq = $graph['@graph'][array_search('FAQPage', $types, true)];

        $text = html_entity_decode(strip_tags($html));

        foreach ($faq['mainEntity'] as $entry) {
            $this->assertStringContainsString($entry['name'], $text);
            $this->assertStringContainsString($entry['acceptedAnswer']['text'], $text);
        }
    }

    /**
     * The home page already carries FAQ markup for the food guides. Asking
     * the same question on two URLs splits the signal between them, so the
     * two sets must not overlap.
     */
    public function test_it_does_not_repeat_the_home_page_questions(): void
    {
        $home = collect(config('catalog.foods'))->pluck('question')
            ->map(fn (string $q): string => mb_strtolower($q));

        $faq = collect(config('faq.groups'))->flatMap(fn (array $g): array => $g['items'])
            ->pluck('q')->map(fn (string $q): string => mb_strtolower($q));

        $this->assertEmpty($home->intersect($faq)->all(), 'a question appears on both the home page and the FAQ');
    }

    public function test_the_category_links_all_resolve(): void
    {
        $html = $this->get('/faq')->getContent();

        foreach (config('faq.groups') as $group) {
            $this->assertStringContainsString('href="#'.$group['id'].'"', $html);
            $this->assertStringContainsString('id="'.$group['id'].'"', $html);
        }
    }

    public function test_the_headline_count_matches_the_questions(): void
    {
        $count = collect(config('faq.groups'))->sum(fn (array $g): int => count($g['items']));

        $this->get('/faq')->assertSee($count.' answers to');
    }

    public function test_it_is_reachable_from_the_header_and_footer(): void
    {
        $this->get('/')->assertSee('href="'.route('faq').'"', false);
    }

    public function test_the_sitemap_lists_the_faq(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(rtrim(config('app.url'), '/').'/faq', false);
    }
}

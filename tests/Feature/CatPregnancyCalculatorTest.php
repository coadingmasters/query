<?php

namespace Tests\Feature;

use Tests\TestCase;

class CatPregnancyCalculatorTest extends TestCase
{
    private const PATH = '/tools/cat-pregnancy-calculator';

    public function test_the_tool_page_renders(): void
    {
        $this->get(self::PATH)
            ->assertOk()
            ->assertSee('Cat Pregnancy Calculator');
    }

    public function test_the_slug_is_what_was_asked_for(): void
    {
        $this->assertSame(self::PATH, parse_url(route('tools.cat-pregnancy-calculator'), PHP_URL_PATH));
    }

    public function test_it_has_every_input_the_brief_asked_for(): void
    {
        $html = $this->get(self::PATH)->getContent();

        foreach (['id="cat-name"', 'id="cat-breed"', 'id="mating-date"', 'id="unknown-date"', 'data-calculate'] as $needle) {
            $this->assertStringContainsString($needle, $html, "missing from the form: $needle");
        }

        $this->assertStringContainsString('type="date"', $html, 'the mating date is not a date picker');
    }

    /**
     * Every breed offered must have a gestation figure in the script, or it
     * silently falls back to the 65-day default and the tool quietly lies.
     */
    public function test_every_breed_offered_has_a_gestation_value(): void
    {
        $html = $this->get(self::PATH)->getContent();

        preg_match('/<select id="cat-breed".*?<\/select>/s', $html, $select);
        preg_match_all('/<option value="([^"]+)"/', $select[0], $options);

        preg_match('/const GESTATION_DAYS = \{(.*?)\};/s', $html, $table);
        preg_match_all("/'([a-z-]+)':\s*(\d+)/", $table[1], $entries);

        $offered = $options[1];
        $known = $entries[1];

        $this->assertNotEmpty($offered, 'the breed select has no options');
        $this->assertSame([], array_diff($offered, $known),
            'a breed is offered with no gestation value behind it');

        // The eleven from the brief.
        $this->assertCount(11, $known);
    }

    public function test_the_gestation_values_match_the_brief(): void
    {
        $html = $this->get(self::PATH)->getContent();

        preg_match('/const GESTATION_DAYS = \{(.*?)\};/s', $html, $table);

        foreach ([
            'persian' => 65, 'siamese' => 63, 'maine-coon' => 66,
            'british-shorthair' => 65, 'bengal' => 65, 'ragdoll' => 65,
            'oriental-shorthair' => 66, 'burmese' => 64, 'abyssinian' => 66,
            'devon-rex' => 65, 'mixed' => 65,
        ] as $breed => $days) {
            $this->assertMatchesRegularExpression(
                "/'".preg_quote($breed, '/')."':\s*$days\b/", $table[1],
                "$breed should be $days days"
            );
        }
    }

    public function test_the_results_carry_slots_for_every_figure(): void
    {
        $html = $this->get(self::PATH)->getContent();

        foreach (['due-date', 'window', 'week', 'days', 'trimester', 'pinking'] as $slot) {
            $this->assertStringContainsString('data-result-'.$slot, $html, "no slot for $slot");
        }
    }

    public function test_the_results_section_starts_hidden(): void
    {
        $this->get(self::PATH)->assertSee('<section id="results" hidden', false);
    }

    public function test_the_secondary_input_starts_hidden(): void
    {
        $this->get(self::PATH)->assertSee('id="unknown-date-panel" hidden', false);
    }

    public function test_the_timeline_covers_week_one_to_nine(): void
    {
        $response = $this->get(self::PATH);

        for ($week = 1; $week <= 9; $week++) {
            $response->assertSee('data-week-card="'.$week.'"', false);
        }
    }

    /**
     * The week writing is the substantial content on this page and the reason
     * it can rank for "cat pregnancy week by week". Held in a JavaScript
     * object it would be content a crawler may never read, so it has to be in
     * the markup — served, not injected.
     */
    public function test_every_week_renders_its_content_into_the_html(): void
    {
        $response = $this->get(self::PATH);

        foreach (config('pregnancy-weeks') as $entry) {
            $response->assertSee('Week '.$entry['week'].' — '.$entry['title'], false);
            $response->assertSee($entry['what_happens'], false);
            $response->assertSee($entry['visible_signs'], false);

            foreach ($entry['care_tips'] as $tip) {
                $response->assertSee($tip, false);
            }

            if ($entry['vet_action']) {
                $response->assertSee($entry['vet_action'], false);
            }
        }
    }

    public function test_each_week_has_every_field_the_brief_asked_for(): void
    {
        $weeks = config('pregnancy-weeks');

        $this->assertCount(9, $weeks);

        foreach ($weeks as $entry) {
            $this->assertArrayHasKey('title', $entry);
            $this->assertArrayHasKey('what_happens', $entry);
            $this->assertArrayHasKey('visible_signs', $entry);
            $this->assertArrayHasKey('vet_action', $entry);
            $this->assertCount(2, $entry['care_tips'], 'week '.$entry['week'].' should have two care tips');
        }
    }

    /** The cards expand without JavaScript, so the content is never trapped. */
    public function test_the_weeks_expand_without_javascript(): void
    {
        $html = $this->get(self::PATH)->getContent();

        $this->assertSame(9, substr_count($html, '<details data-week-details'));
    }

    public function test_it_carries_the_veterinary_note(): void
    {
        $this->get(self::PATH)
            ->assertSee('This tool is for informational purposes only. Consult your vet.');
    }

    /** The card on the home page has to actually reach the tool. */
    public function test_the_home_page_card_links_to_it(): void
    {
        $this->get('/')->assertSee('href="'.self::PATH.'"', false);
    }

    /**
     * The other six tools have no pages yet. Their cards must not pretend
     * otherwise — a card that looks clickable and goes nowhere is worse than
     * one that says it is not ready.
     */
    public function test_tools_without_pages_are_not_links(): void
    {
        $html = $this->get('/')->getContent();

        foreach (config('catalog.tools') as $tool) {
            if (! isset($tool['url'])) {
                $this->assertStringNotContainsString('href="/tools/'.$tool['slug'].'"', $html);
            }
        }

        $this->assertStringContainsString('Coming soon', $html);
    }

    public function test_the_sitemap_lists_the_tool(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(rtrim(config('app.url'), '/').self::PATH, false);
    }
}

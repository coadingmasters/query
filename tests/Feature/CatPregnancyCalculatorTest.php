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

    /** Five placeholder breeds, as specified. */
    public function test_the_breed_dropdown_has_five_options(): void
    {
        $html = $this->get(self::PATH)->getContent();

        preg_match('/<select id="cat-breed".*?<\/select>/s', $html, $m);

        // The prompt asked for five breeds; the sixth option is the empty
        // "Select a breed" placeholder.
        $this->assertSame(6, substr_count($m[0], '<option'));
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
            $response->assertSee('data-week="'.$week.'"', false);
        }
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

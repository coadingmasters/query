<?php

namespace Tests\Feature;

use Tests\TestCase;

class TermsTest extends TestCase
{
    public function test_the_terms_page_renders(): void
    {
        $this->get('/terms')
            ->assertOk()
            ->assertSee('Terms &amp; Conditions', false);
    }

    public function test_every_section_is_present_with_an_anchor(): void
    {
        $html = $this->get('/terms')->getContent();

        foreach (config('legal.terms') as $section) {
            $this->assertStringContainsString('id="'.$section['id'].'"', $html);
            $this->assertStringContainsString($section['heading'], $html);
        }
    }

    /** Every contents entry must point at a section that exists. */
    public function test_the_table_of_contents_has_no_dead_links(): void
    {
        $html = $this->get('/terms')->getContent();

        preg_match_all('/href="#([a-z-]+)"/', $html, $links);

        foreach (array_unique($links[1]) as $anchor) {
            $this->assertStringContainsString('id="'.$anchor.'"', $html, "contents links to a missing section: #$anchor");
        }
    }

    /**
     * The clause that matters most on a pet health site. If this ever goes
     * missing the page still looks complete, which is exactly the problem.
     */
    public function test_the_veterinary_clause_is_present(): void
    {
        $this->get('/terms')
            ->assertSee('Not veterinary advice')
            ->assertSee('emergency animal clinic');
    }

    public function test_it_is_reachable_from_the_footer(): void
    {
        $this->get('/')->assertSee('href="'.route('terms').'"', false);
    }

    public function test_the_slug_stays_short(): void
    {
        $this->assertSame('/terms', parse_url(route('terms'), PHP_URL_PATH));
    }

    public function test_it_states_when_the_terms_took_effect(): void
    {
        $this->get('/terms')
            ->assertSee('Last updated')
            ->assertSee('datetime="'.config('legal.terms_effective').'"', false);
    }

    public function test_it_carries_structured_data(): void
    {
        $this->get('/terms')
            ->assertSee('"@type":"WebPage"', false)
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_the_sitemap_lists_the_terms_page(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(rtrim(config('app.url'), '/').'/terms', false);
    }

    /**
     * The governing-law clause names a place when one is configured. Without
     * LEGAL_JURISDICTION set it falls back to generic wording, which is weak,
     * so this documents the difference rather than letting it pass unnoticed.
     */
    public function test_the_governing_law_clause_names_the_jurisdiction_when_set(): void
    {
        $jurisdiction = config('legal.jurisdiction');

        if (! $jurisdiction) {
            $this->markTestSkipped('LEGAL_JURISDICTION is not set — the clause uses generic wording.');
        }

        $this->get('/terms')->assertSee($jurisdiction);
    }
}

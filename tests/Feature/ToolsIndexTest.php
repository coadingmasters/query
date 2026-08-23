<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * "Smart Tools" on the home page, and "Try Free Tools" in the header, used to
 * point at #tools — a same-page anchor that does nothing from anywhere but
 * home. /tools is a real, indexable hub for every tool, live or not yet built.
 */
class ToolsIndexTest extends TestCase
{
    public function test_the_index_lists_every_tool(): void
    {
        $response = $this->get('/tools')->assertOk();

        foreach (config('catalog.tools') as $tool) {
            $response->assertSee($tool['title']);
        }
    }

    public function test_every_live_tool_is_a_real_link(): void
    {
        $html = $this->get('/tools')->getContent();

        foreach (config('catalog.tools') as $tool) {
            if (! isset($tool['url'])) {
                continue;
            }

            $this->assertStringContainsString(
                'href="'.$tool['url'].'"', $html,
                "the index does not link to {$tool['slug']}"
            );
        }
    }

    /** Breed Quiz and Name Generator have no page yet — catalog.tools_upcoming keeps them out of every listing until they do. */
    public function test_a_tool_with_no_page_yet_is_not_shown_anywhere(): void
    {
        foreach (['Breed Quiz', 'Name Generator'] as $upcoming) {
            $this->get('/tools')->assertDontSee($upcoming);
            $this->get('/')->assertDontSee($upcoming);
        }

        $this->assertStringNotContainsString('Coming soon', $this->get('/tools')->getContent());
    }

    /** The header nav's CTA buttons and category card should point at the real index, not a same-page anchor. */
    public function test_the_header_and_home_page_link_to_the_real_tools_index(): void
    {
        $html = $this->get('/about')->getContent();

        $this->assertStringContainsString('href="'.route('tools.index').'"', $html);
        $this->assertStringNotContainsString('href="/#tools"', $html);
    }
}

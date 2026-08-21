<?php

namespace Tests\Feature;

use App\Models\Post;
use Tests\TestCase;

class AboutTest extends TestCase
{
    public function test_the_about_page_renders(): void
    {
        $this->get('/about')
            ->assertOk()
            ->assertSee('About '.config('app.name'))
            ->assertSee('Our mission')
            ->assertSee('Our story');
    }

    public function test_it_is_reachable_from_the_header_and_footer(): void
    {
        $this->get('/')
            ->assertSee('href="'.route('about').'"', false)
            ->assertSee('About');
    }

    /**
     * Fragment links have to be rooted at "/". A bare "#tools" in the header
     * does nothing on /about, which would leave the whole nav dead on every
     * page except the home page.
     */
    public function test_navigation_works_away_from_the_home_page(): void
    {
        $html = $this->get('/about')->getContent();

        // Blog is no longer among them: it has a page of its own now, so the
        // nav points at /blog rather than at a section of the home page.
        foreach (['/#tools', '/#food-guides', '/#how-it-works'] as $href) {
            $this->assertStringContainsString('href="'.$href.'"', $html, "nav is missing $href");
        }

        $this->assertStringContainsString('href="'.route('blog.index').'"', $html, 'nav is missing the blog');

        // A bare fragment would be a link that silently goes nowhere here.
        $this->assertStringNotContainsString('href="#tools"', $html);
    }

    /**
     * The counts come from the catalogue, so adding a tool updates the page.
     * A hard-coded figure is one that eventually becomes untrue.
     */
    public function test_the_stats_match_the_catalogue(): void
    {
        $response = $this->get('/about');

        $tools = count(config('catalog.tools'));
        $foods = count(config('catalog.foods'));
        $guides = $foods + Post::published()->count();

        $response->assertSee((string) $tools)
            ->assertSee((string) $foods)
            ->assertSee((string) $guides);
    }

    /**
     * Numbers the site cannot back must not appear. These were in the supplied
     * copy; the site is new, has no ratings and no traffic history.
     */
    public function test_it_makes_no_claims_that_cannot_be_backed(): void
    {
        // Visible text only. Searching the raw markup matches asset hashes
        // and inline styles, which say nothing about what a reader sees.
        $text = html_entity_decode(strip_tags($this->get('/about')->getContent()));

        foreach (['50,000', '100+', '4.9', 'thousands of cat owners'] as $claim) {
            $this->assertStringNotContainsString($claim, $text, "unverifiable claim on the page: $claim");
        }
    }

    public function test_it_carries_about_and_breadcrumb_structured_data(): void
    {
        $this->get('/about')
            ->assertSee('"@type":"AboutPage"', false)
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    /**
     * This once asserted the opposite. The founder section had been removed to
     * keep the focus off personal branding, and the test held that line.
     *
     * That changed for a reason: cat health is YMYL, and an anonymous site
     * asking people to act on health information is the weakest position it
     * can take. The intent behind the old test survives in the new one, which
     * is that the only person described is the configured one. An invented
     * author is still the thing being guarded against.
     */
    public function test_the_only_person_it_describes_is_the_configured_one(): void
    {
        $configured = config('author.founder.name');

        if (! $configured) {
            $this->get('/about')->assertDontSee('"@type":"Person"', false);

            return;
        }

        $html = $this->get('/about')->assertSee($configured)->getContent();

        preg_match_all('/"@type":"Person".*?"name":"([^"]+)"/', $html, $matches);

        $this->assertNotEmpty($matches[1], 'the Person node is missing its name');

        foreach ($matches[1] as $name) {
            $this->assertSame(
                $configured, $name,
                "the page describes \"$name\", who is not the configured author"
            );
        }
    }

    public function test_the_sitemap_lists_the_about_page(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(rtrim(config('app.url'), '/').'/about', false);
    }

    public function test_the_title_and_description_are_a_sensible_length(): void
    {
        $html = $this->get('/about')->getContent();

        preg_match('/<title>(.*?)<\/title>/s', $html, $t);
        preg_match('/<meta name="description" content="(.*?)">/s', $html, $d);

        $this->assertLessThanOrEqual(60, mb_strlen(html_entity_decode($t[1])), "title too long: {$t[1]}");
        $this->assertLessThanOrEqual(165, mb_strlen(html_entity_decode($d[1])), 'description too long');
        $this->assertGreaterThanOrEqual(110, mb_strlen(html_entity_decode($d[1])), 'description too short');
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PrivacyTest extends TestCase
{
    public function test_the_privacy_page_renders(): void
    {
        $this->get('/privacy')
            ->assertOk()
            ->assertSee('Privacy Policy')
            ->assertSee('The short version');
    }

    public function test_every_section_is_present_with_an_anchor(): void
    {
        $html = $this->get('/privacy')->getContent();

        foreach (config('legal.privacy') as $section) {
            $this->assertStringContainsString('id="'.$section['id'].'"', $html);
        }
    }

    public function test_the_table_of_contents_has_no_dead_links(): void
    {
        $html = $this->get('/privacy')->getContent();

        preg_match_all('/href="#([a-z-]+)"/', $html, $links);

        foreach (array_unique($links[1]) as $anchor) {
            $this->assertStringContainsString('id="'.$anchor.'"', $html, "contents links to a missing section: #$anchor");
        }
    }

    /**
     * The policy says the subscriber record holds an email and nothing else,
     * and that contact messages hold a name, email, subject and message. If a
     * column is ever added, the policy becomes untrue the moment it ships —
     * and a privacy policy is the one document people may rely on.
     */
    public function test_the_policy_still_matches_what_the_tables_hold(): void
    {
        $ignored = ['id', 'created_at', 'updated_at'];

        $subscribers = array_diff(Schema::getColumnListing('subscribers'), [...$ignored, 'unsubscribed_at']);
        $this->assertSame(['email'], array_values($subscribers),
            'subscribers gained a column — the privacy policy says it holds an email and nothing else');

        $messages = array_diff(Schema::getColumnListing('contact_messages'), [...$ignored, 'handled_at']);
        $this->assertSame(['name', 'email', 'subject', 'message'], array_values($messages),
            'contact_messages gained a column — the privacy policy lists exactly what it holds');
    }

    /**
     * The policy states there is no analytics and no third-party request. If
     * a script or an external asset is ever added, this fails before anyone
     * has to notice the policy went stale.
     */
    public function test_no_third_party_assets_are_loaded(): void
    {
        foreach (['/', '/privacy', '/contact'] as $path) {
            $html = $this->get($path)->getContent();

            preg_match_all('/(?:src|href)="(https?:\/\/[^"]+)"/i', $html, $matches);

            foreach ($matches[1] as $url) {
                $host = parse_url($url, PHP_URL_HOST);
                $this->assertStringContainsString(
                    parse_url(config('app.url'), PHP_URL_HOST), $host,
                    "$path loads a third-party asset, which the privacy policy says it does not: $url"
                );
            }
        }
    }

    public function test_it_states_the_cookies_that_are_actually_set(): void
    {
        $this->get('/privacy')
            ->assertSee('cross-site request forgery')
            ->assertSee('We do not use analytics');
    }

    public function test_it_is_reachable_from_the_footer(): void
    {
        $this->get('/')->assertSee('href="'.route('privacy').'"', false);
    }

    public function test_the_slug_stays_short(): void
    {
        $this->assertSame('/privacy', parse_url(route('privacy'), PHP_URL_PATH));
    }

    public function test_it_carries_structured_data(): void
    {
        $this->get('/privacy')
            ->assertSee('"@type":"WebPage"', false)
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_the_sitemap_lists_the_privacy_page(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee(rtrim(config('app.url'), '/').'/privacy', false);
    }

    public function test_the_two_legal_pages_link_to_each_other(): void
    {
        $this->get('/privacy')->assertSee('href="'.route('terms').'"', false);
        $this->get('/terms')->assertSee('href="'.route('privacy').'"', false);
    }
}

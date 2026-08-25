<?php

namespace Tests\Feature\Admin;

use App\Models\ClickEvent;
use App\Models\IpRange;
use App\Models\PageView;
use App\Models\User;
use App\Models\Visitor;
use App\Support\VisitorIdentity;
use DeviceDetector\DeviceDetector;
use Illuminate\Support\Str;
use Tests\TestCase;

class VisitorsTest extends TestCase
{
    public function test_a_first_visit_sets_the_visitor_cookie(): void
    {
        $this->get('/')->assertCookie(VisitorIdentity::COOKIE);
    }

    public function test_a_returning_visitor_reuses_the_same_visitor_row_instead_of_creating_a_new_one(): void
    {
        $first = $this->get('/');
        $token = $first->getCookie(VisitorIdentity::COOKIE, false)->getValue();

        $this->assertSame(1, Visitor::count());

        $this->withUnencryptedCookie(VisitorIdentity::COOKIE, $token)->get('/about');

        $this->assertSame(1, Visitor::count(), 'a second visit with the same cookie should not create a second visitor');
        $this->assertSame(2, Visitor::first()->visits_count);
    }

    public function test_a_page_view_is_linked_to_the_resolved_visitor(): void
    {
        $this->get('/');

        $view = PageView::first();
        $this->assertNotNull($view);
        $this->assertNotNull($view->visitor_id);
        $this->assertSame(Visitor::first()->id, $view->visitor_id);
    }

    public function test_the_click_endpoint_stores_an_event_tied_to_the_visitor(): void
    {
        // Establish the cookie first, the way a real page load would.
        $first = $this->get('/');
        $token = $first->getCookie(VisitorIdentity::COOKIE, false)->getValue();

        $this->withUnencryptedCookie(VisitorIdentity::COOKIE, $token)->withCredentials()->postJson('/visit/click', [
            'path' => '/blog/why-do-cats-purr',
            'selector' => 'a.btn-primary',
            'label' => 'Read the full guide',
            'href' => '/tools/cat-weight-checker',
        ])->assertStatus(204);

        $click = ClickEvent::first();
        $this->assertNotNull($click);
        $this->assertSame(Visitor::first()->id, $click->visitor_id);
        $this->assertSame('/blog/why-do-cats-purr', $click->path);
        $this->assertSame('Read the full guide', $click->label);
        $this->assertSame('/tools/cat-weight-checker', $click->href);
    }

    public function test_the_click_endpoint_is_throttled(): void
    {
        for ($i = 0; $i < 120; $i++) {
            $this->postJson('/visit/click', ['path' => '/']);
        }

        $this->postJson('/visit/click', ['path' => '/'])->assertStatus(429);
    }

    public function test_ip_to_country_resolves_a_known_range(): void
    {
        IpRange::create(['ip_from' => ip2long('1.1.1.0'), 'ip_to' => ip2long('1.1.1.255'), 'country_code' => 'AU', 'country_name' => 'Australia']);

        $this->assertSame('Australia', IpRange::countryFor('1.1.1.1')->country_name);
        $this->assertNull(IpRange::countryFor('8.8.8.8'), 'an address outside every imported range should resolve to nothing');
    }

    public function test_ip_to_country_returns_null_for_ipv6(): void
    {
        $this->assertNull(IpRange::countryFor('2001:4860:4860::8888'));
    }

    public function test_device_detection_parses_a_known_user_agent(): void
    {
        $dd = new DeviceDetector('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        $dd->parse();

        $this->assertSame('desktop', $dd->getDeviceName());
        $this->assertSame('Chrome', $dd->getClient('name'));
        $this->assertSame('Windows', $dd->getOs('name'));
    }

    public function test_the_visitors_index_is_behind_auth(): void
    {
        $this->get('/admin/visitors')->assertRedirect(route('admin.login'));
    }

    public function test_the_visitors_index_renders_for_a_logged_in_admin(): void
    {
        Visitor::create([
            'token' => (string) Str::uuid(), 'ip_address' => '203.0.113.4',
            'country_name' => 'Testland', 'device_type' => 'desktop', 'browser' => 'Chrome',
            'first_seen_at' => now(), 'last_seen_at' => now(), 'visits_count' => 3,
        ]);

        $this->actingAs(User::factory()->create())
            ->get('/admin/visitors')
            ->assertOk()
            ->assertSee('Testland')
            ->assertSee('203.0.113.4');
    }

    public function test_a_visitor_with_many_pages_in_seconds_is_flagged_a_likely_bot(): void
    {
        $visitor = Visitor::create([
            'token' => (string) Str::uuid(), 'ip_address' => '34.169.76.90',
            'country_name' => 'United States', 'device_type' => 'mobile', 'browser' => 'Chrome',
            'first_seen_at' => now()->subSeconds(3), 'last_seen_at' => now(), 'visits_count' => 5,
        ]);

        for ($i = 0; $i < 5; $i++) {
            PageView::create(['visitor_id' => $visitor->id, 'path' => "/page-{$i}", 'source' => 'direct', 'created_at' => now()]);
        }

        $this->assertTrue($visitor->loadCount('pageViews')->is_likely_bot);
    }

    public function test_a_normal_browsing_pace_is_not_flagged(): void
    {
        $visitor = Visitor::create([
            'token' => (string) Str::uuid(), 'ip_address' => '203.0.113.9',
            'country_name' => 'Testland', 'device_type' => 'desktop', 'browser' => 'Chrome',
            'first_seen_at' => now()->subMinutes(5), 'last_seen_at' => now(), 'visits_count' => 5,
        ]);

        for ($i = 0; $i < 5; $i++) {
            PageView::create(['visitor_id' => $visitor->id, 'path' => "/page-{$i}", 'source' => 'direct', 'created_at' => now()]);
        }

        $this->assertFalse($visitor->loadCount('pageViews')->is_likely_bot);
    }

    public function test_the_traffic_quality_card_and_bot_badge_show_on_the_index(): void
    {
        $bot = Visitor::create([
            'token' => (string) Str::uuid(), 'ip_address' => '34.169.76.90',
            'country_name' => 'United States', 'device_type' => 'mobile', 'browser' => 'Chrome',
            'first_seen_at' => now()->subSeconds(3), 'last_seen_at' => now(), 'visits_count' => 5,
        ]);
        for ($i = 0; $i < 5; $i++) {
            PageView::create(['visitor_id' => $bot->id, 'path' => "/page-{$i}", 'source' => 'direct', 'created_at' => now()]);
        }

        Visitor::create([
            'token' => (string) Str::uuid(), 'ip_address' => '203.0.113.9',
            'country_name' => 'Testland', 'device_type' => 'desktop', 'browser' => 'Firefox',
            'first_seen_at' => now(), 'last_seen_at' => now(), 'visits_count' => 1,
        ]);

        $this->actingAs(User::factory()->create())
            ->get('/admin/visitors')
            ->assertOk()
            ->assertSee('Traffic quality')
            ->assertSee('Likely bot');
    }

    public function test_a_visitor_show_page_lists_its_timeline(): void
    {
        $visitor = Visitor::create([
            'token' => (string) Str::uuid(), 'ip_address' => '203.0.113.4',
            'country_name' => 'Testland', 'device_type' => 'desktop', 'browser' => 'Chrome',
            'first_seen_at' => now(), 'last_seen_at' => now(), 'visits_count' => 1,
        ]);
        PageView::create(['visitor_id' => $visitor->id, 'path' => '/blog/why-do-cats-purr', 'source' => 'direct', 'created_at' => now()]);
        ClickEvent::create(['visitor_id' => $visitor->id, 'path' => '/blog/why-do-cats-purr', 'label' => 'See the tool', 'href' => '/tools/cat-weight-checker', 'created_at' => now()]);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.visitors.show', $visitor))
            ->assertOk()
            ->assertSee('/blog/why-do-cats-purr')
            ->assertSee('See the tool');
    }
}

<?php

namespace Tests\Feature\Admin;

use App\Models\SearchConsoleUrl;
use App\Models\User;
use Tests\TestCase;

class SearchConsoleTest extends TestCase
{
    public function test_the_search_console_page_is_behind_auth(): void
    {
        $this->get('/admin/search-console')->assertRedirect(route('admin.login'));
    }

    public function test_it_renders_for_a_logged_in_admin_with_no_data(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/search-console')
            ->assertOk()
            ->assertSee('No data yet');
    }

    public function test_it_renders_indexed_and_not_indexed_counts(): void
    {
        SearchConsoleUrl::create([
            'url' => 'https://purrquery.com/blog/example',
            'coverage_state' => 'Submitted and indexed',
            'checked_at' => now(),
        ]);
        SearchConsoleUrl::create([
            'url' => 'https://purrquery.com/blog/other',
            'coverage_state' => 'Crawled - currently not indexed',
            'checked_at' => now(),
        ]);

        $this->actingAs(User::factory()->create())
            ->get('/admin/search-console')
            ->assertOk()
            ->assertSee('/blog/example')
            ->assertSee('/blog/other')
            ->assertSee('Submitted and indexed')
            ->assertSee('Crawled - currently not indexed');
    }
}

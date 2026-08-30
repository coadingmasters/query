<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

/**
 * The byline shows who wrote a page, but not when, which matters for
 * anything time-sensitive: a reader has no way to tell a guide from launch
 * day apart from one updated last week. Every real date shown here is
 * genuine, sourced from either the database (blog posts) or the actual git
 * history of the content (food guides, tools), never invented.
 *
 * The "Published"/"Updated" text always sits beside a <time> tag, not
 * concatenated with the date as one string, so assertions check for the
 * datetime attribute's real value rather than a literal contiguous phrase.
 */
class BylineDatesTest extends TestCase
{
    public function test_a_food_guide_shows_its_published_and_updated_dates(): void
    {
        $html = $this->get('/food-guides/vegetables')->assertOk()->getContent();

        $this->assertStringContainsString('Published', $html);
        $this->assertStringContainsString('<time datetime="2026-08-17">August 17, 2026</time>', $html);
        $this->assertStringContainsString('Updated', $html);
        $this->assertStringContainsString('<time datetime="2026-08-30">August 30, 2026</time>', $html);
    }

    /**
     * The byline must not claim a page was "updated" on the same day it was
     * published, since that tells a reader nothing true the published date
     * did not already say.
     */
    public function test_the_byline_hides_a_same_day_update(): void
    {
        $html = Blade::render('<x-byline published-at="2026-08-17" updated-at="2026-08-17"/>');

        $this->assertStringContainsString('Published', $html);
        $this->assertStringContainsString('<time datetime="2026-08-17">August 17, 2026</time>', $html);
        $this->assertStringNotContainsString('Updated', $html);
    }

    public function test_the_byline_shows_a_genuine_update(): void
    {
        $html = Blade::render('<x-byline published-at="2026-08-17" updated-at="2026-08-25"/>');

        $this->assertStringContainsString('<time datetime="2026-08-17">August 17, 2026</time>', $html);
        $this->assertStringContainsString('Updated', $html);
        $this->assertStringContainsString('<time datetime="2026-08-25">August 25, 2026</time>', $html);
    }

    /** With neither date given, the byline shows the name only, exactly as it always has. */
    public function test_the_byline_shows_no_date_line_when_none_is_given(): void
    {
        $html = Blade::render('<x-byline/>');

        $this->assertStringContainsString('Ahsan Nawaz', $html);
        $this->assertStringNotContainsString('Published', $html);
    }

    public function test_every_food_guide_and_live_tool_carries_real_dates(): void
    {
        foreach (config('catalog.foods') as $food) {
            $this->assertNotEmpty($food['published_at'] ?? null, "{$food['slug']} has no published_at");
        }

        foreach (config('catalog.tools') as $tool) {
            $this->assertNotEmpty($tool['published_at'] ?? null, "{$tool['slug']} has no published_at");
        }
    }

    public function test_a_blog_post_shows_its_real_published_date(): void
    {
        $post = Post::first();

        $html = $this->get(route('blog.show', $post->slug))->getContent();

        $this->assertStringContainsString(
            '<time datetime="'.$post->published_at->toDateString().'">'.$post->published_at->format('F j, Y').'</time>',
            $html
        );
    }

    /** Rendering must not crash on food-only pages with no per-item dates, like the single-answer guides. */
    public function test_a_single_answer_food_guide_still_renders(): void
    {
        $this->get('/food-guides/dairy-and-eggs')->assertOk()->assertSee('Ahsan Nawaz');
    }
}

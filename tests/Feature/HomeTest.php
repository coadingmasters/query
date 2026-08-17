<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_the_home_page_renders(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Everything You Need')
            ->assertSee(config('brand.description'));
    }

    public function test_every_tool_and_guide_is_listed(): void
    {
        $response = $this->get('/');

        foreach (config('catalog.tools') as $tool) {
            $response->assertSee($tool['title']);
        }

        foreach (config('catalog.foods') as $food) {
            $response->assertSee($food['question']);
        }

        foreach (config('catalog.posts') as $post) {
            $response->assertSee($post['title']);
        }
    }

    /**
     * Every image needs width and height in the markup. Without them the
     * browser cannot reserve space, and the page shifts as each one loads —
     * which is the single easiest way to fail Cumulative Layout Shift.
     */
    public function test_every_image_declares_its_dimensions(): void
    {
        $html = $this->get('/')->getContent();

        preg_match_all('/<img\b[^>]*>/i', $html, $matches);

        $this->assertNotEmpty($matches[0], 'the page rendered no images at all');

        foreach ($matches[0] as $tag) {
            $this->assertMatchesRegularExpression('/\bwidth="\d+"/', $tag, "missing width: $tag");
            $this->assertMatchesRegularExpression('/\bheight="\d+"/', $tag, "missing height: $tag");
            $this->assertMatchesRegularExpression('/\balt="[^"]*"/', $tag, "missing alt: $tag");
        }
    }

    /**
     * Exactly one image — the hero — should load eagerly. If a second one
     * loses its lazy attribute, the page silently starts downloading
     * below-the-fold artwork before it paints.
     */
    public function test_only_the_hero_image_loads_eagerly(): void
    {
        $html = $this->get('/')->getContent();

        preg_match_all('/<img\b[^>]*>/i', $html, $matches);

        $eager = array_filter($matches[0], fn (string $tag): bool => ! str_contains($tag, 'loading="lazy"'));

        $this->assertCount(1, $eager, 'expected exactly one eagerly loaded image');
        $this->assertStringContainsString('fetchpriority="high"', reset($eager));
    }

    public function test_the_hero_image_is_preloaded(): void
    {
        $this->get('/')->assertSee('rel="preload" as="image"', false);
    }

    public function test_the_page_exposes_structured_data(): void
    {
        $this->get('/')
            ->assertSee('"@type":"Organization"', false)
            ->assertSee('"@type":"FAQPage"', false);
    }
}

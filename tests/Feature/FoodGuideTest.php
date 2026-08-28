<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Food guides used to exist only as anchors on the home page (#food-fruits
 * and so on) — no real URL, nothing Google could rank on its own, and
 * nothing a link elsewhere on the site could point at cleanly. Each one now
 * has its own page.
 */
class FoodGuideTest extends TestCase
{
    public function test_the_index_lists_every_food_guide(): void
    {
        $response = $this->get('/food-guides')->assertOk();

        foreach (config('catalog.foods') as $food) {
            $response->assertSee($food['question']);
        }
    }

    public function test_every_food_guide_on_the_index_is_a_real_link(): void
    {
        $html = $this->get('/food-guides')->getContent();

        foreach (config('catalog.foods') as $food) {
            $this->assertStringContainsString(
                'href="'.route('food-guides.show', $food['slug']).'"', $html,
                "the index does not link to {$food['slug']}"
            );
        }
    }

    public function test_a_food_guide_page_renders(): void
    {
        $this->get('/food-guides/toxic-foods')
            ->assertOk()
            ->assertSee('What Foods Are Toxic to Cats?')
            ->assertSee('Onion, garlic, chocolate');
    }

    public function test_an_unknown_slug_is_a_404(): void
    {
        $this->get('/food-guides/not-a-real-food')->assertNotFound();
    }

    /** The home page's own food cards should link out to the real pages, not just carry an anchor id. */
    public function test_the_home_page_food_cards_link_to_the_real_pages(): void
    {
        $html = $this->get('/')->getContent();

        foreach (config('catalog.foods') as $food) {
            $this->assertStringContainsString(
                'href="'.route('food-guides.show', $food['slug']).'"', $html,
                "the home page does not link to {$food['slug']}"
            );
        }
    }

    /** The header's "Cat Foods" dropdown should do the same. */
    public function test_the_header_dropdown_links_to_the_real_pages(): void
    {
        $html = $this->get('/about')->getContent();

        foreach (config('catalog.foods') as $food) {
            $this->assertStringContainsString(
                'href="'.route('food-guides.show', $food['slug']).'"', $html,
                "the header does not link to {$food['slug']}"
            );
        }

        $this->assertStringNotContainsString('/#food-guides', $html);
    }

    /**
     * Fruits covers many individual items rather than one food, so it
     * carries a real reference table and FAQ that the other, single-answer
     * guides do not.
     */
    public function test_the_fruits_guide_has_a_full_safety_table_and_faq(): void
    {
        $response = $this->get('/food-guides/fruits')->assertOk();

        $fruits = collect(config('catalog.foods'))->firstWhere('slug', 'fruits');

        foreach ($fruits['items'] as $item) {
            $response->assertSee($item['name']);
        }

        foreach ($fruits['faq'] as $entry) {
            $response->assertSee($entry['q']);
        }

        $response->assertSee('Fruits to avoid entirely')
            ->assertSee('Introducing a new fruit safely');
    }

    /** Only fruits should have grown this content — every other guide stays exactly as it was. */
    public function test_other_food_guides_do_not_show_a_faq_accordion(): void
    {
        $html = $this->get('/food-guides/vegetables')->getContent();

        $this->assertStringNotContainsString('group rounded-xl border border-line bg-surface px-5', $html);
    }
}

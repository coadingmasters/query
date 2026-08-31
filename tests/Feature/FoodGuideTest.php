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
     * Fruits and vegetables each cover many individual items rather than one
     * food, so both carry a real reference table and FAQ that the other,
     * single-answer guides do not.
     */
    public static function categoryGuides(): array
    {
        return [
            'fruits' => ['fruits'],
            'vegetables' => ['vegetables'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('categoryGuides')]
    public function test_a_category_guide_has_a_full_safety_table_and_faq(string $slug): void
    {
        $response = $this->get("/food-guides/{$slug}")->assertOk();

        $food = collect(config('catalog.foods'))->firstWhere('slug', $slug);

        foreach ($food['items'] as $item) {
            $response->assertSee($item['name']);
        }

        foreach ($food['faq'] as $entry) {
            $response->assertSee($entry['q']);
        }

        $response->assertSee($food['title'].' to avoid entirely')
            ->assertSee('Introducing a new '.\Illuminate\Support\Str::of($food['title'])->lower()->rtrim('s').' safely');
    }

    /** Only a category guide supplies its own FAQ set, so only those get the accordion. */
    public function test_other_food_guides_do_not_show_a_faq_accordion(): void
    {
        config(['catalog.foods' => [...config('catalog.foods'), $this->minimalFoodGuide()]]);

        $this->get('/food-guides/test-minimal-guide')->assertDontSee('Frequently asked questions');
        $this->get('/food-guides/fruits')->assertSee('Frequently asked questions');
        $this->get('/food-guides/vegetables')->assertSee('Frequently asked questions');
    }

    /** Every guide carries the sidebar, and its contents list only real sections. */
    public function test_the_sidebar_renders_on_every_guide(): void
    {
        foreach (config('catalog.foods') as $food) {
            $response = $this->get(route('food-guides.show', $food['slug']))->assertOk();

            $response->assertSee('On This Page')
                ->assertSee('Quick Safety Guide')
                ->assertSee('Related Guides')
                ->assertSee('Recommended Tools');
        }
    }

    /**
     * A guide with no per-item table must not list one in its contents, and a
     * guide that has one must.
     */
    public function test_the_contents_list_matches_the_sections_that_exist(): void
    {
        config(['catalog.foods' => [...config('catalog.foods'), $this->minimalFoodGuide()]]);

        $this->get('/food-guides/fruits')->assertSee('Fruits, one at a time');
        $this->get('/food-guides/test-minimal-guide')->assertDontSee(', one at a time');
    }

    /**
     * A guide with only the single-answer fields, no items table, avoid list,
     * introduce section or FAQ, none of which every guide is guaranteed to
     * carry going forward now that all real guides have been filled in.
     */
    private function minimalFoodGuide(): array
    {
        return [
            'slug' => 'test-minimal-guide',
            'published_at' => '2026-08-17',
            'updated_at' => '2026-08-17',
            'title' => 'Test Minimal Guide',
            'question' => 'Can Cats Eat Test Minimal Guide?',
            'answer' => 'Yes, for the purposes of this test.',
            'verdict' => 'safe',
            'note' => 'Test fixture only',
            'image' => 'can-cats-eat-meat-seafood-chicken-fish',
            'alt' => 'A test fixture image',
            'why' => 'This entry exists only so a test can assert the sections a guide with no items table or FAQ does not render, without depending on real site content staying incomplete.',
            'guidance' => 'Not real guidance, this is a test fixture.',
            'watch_for' => ['Not a real symptom, this is a test fixture.'],
        ];
    }
}

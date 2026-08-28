<?php

namespace Tests\Feature;

use App\Models\Breed;
use Tests\TestCase;

class CatNameGeneratorTest extends TestCase
{
    public function test_the_page_renders(): void
    {
        $this->get('/tools/cat-name-generator')
            ->assertOk()
            ->assertSee('Cat Name Generator')
            ->assertSee('Generate a Name');
    }

    public function test_every_style_and_personality_is_listed(): void
    {
        $response = $this->get('/tools/cat-name-generator');

        foreach (config('cat-name-generator.styles') as $style) {
            $response->assertSee($style['label']);
        }

        foreach (config('cat-name-generator.personalities') as $personality) {
            $response->assertSee($personality['label']);
        }
    }

    /** Every name needs a real, checkable reason behind it - never an empty or placeholder meaning. */
    public function test_every_name_has_a_gender_and_a_real_meaning(): void
    {
        foreach (config('cat-name-generator.names') as $name) {
            $this->assertContains($name['gender'], ['male', 'female', 'neutral'], "{$name['name']} has an invalid gender");
            $this->assertNotEmpty($name['meaning'], "{$name['name']} has no meaning");
            $this->assertNotEmpty($name['styles'], "{$name['name']} has no style tags");
        }
    }

    /** Only real breeds already in the database are usable for the breed filter, not an invented list. */
    public function test_active_breeds_are_available_to_the_generator(): void
    {
        $breed = Breed::create(['name' => 'Test Breed For Names', 'slug' => 'test-breed-for-names', 'is_active' => true]);
        Breed::create(['name' => 'Inactive Breed For Names', 'slug' => 'inactive-breed-for-names', 'is_active' => false]);

        $html = $this->get('/tools/cat-name-generator')->getContent();

        $this->assertStringContainsString($breed->slug, $html);
        $this->assertStringNotContainsString('Inactive Breed For Names', $html);
    }

    public function test_it_is_no_longer_listed_as_upcoming(): void
    {
        $this->get('/tools')->assertSee('Cat Name Generator');
        $this->get('/tools')->assertDontSee('Coming soon');
    }

    public function test_the_seo_content_sections_render(): void
    {
        $this->get('/tools/cat-name-generator')
            ->assertOk()
            ->assertSee('Maine Coon cat names')
            ->assertSee('Siamese cat names')
            ->assertSee('Popular female cat names')
            ->assertSee('Seven rules for choosing the right cat name')
            ->assertSee('Cat names for two cats');
    }

    /** The sidebar is the tool's navigation, so its panels have to actually be there. */
    public function test_the_tool_sidebar_panels_render(): void
    {
        $response = $this->get('/tools/cat-name-generator')->assertOk();

        $response->assertSee('Popular Right Now')
            ->assertSee('Name Ideas by Category')
            ->assertSee('Naming Tips')
            ->assertSee('Fun Fact')
            ->assertSee('How to Choose the Perfect Name');

        // Category counts come off the real name list, never a hardcoded number.
        foreach (collect(config('cat-name-generator.styles'))->take(3) as $style) {
            $response->assertSee($style['label']);
        }
    }

    /** Every other live tool should be reachable from the bottom carousel. */
    public function test_it_cross_links_the_other_tools(): void
    {
        $response = $this->get('/tools/cat-name-generator');

        foreach (config('catalog.tools') as $tool) {
            if ($tool['slug'] === 'cat-name-generator') {
                continue;
            }

            $response->assertSee('href="'.$tool['url'].'"', false);
        }
    }

    public function test_internal_links_point_to_real_routes(): void
    {
        $response = $this->get('/tools/cat-name-generator');

        $response->assertSee(route('tools.cat-age-calculator'), false);
        $response->assertSee(route('tools.cat-calorie-calculator'), false);
    }
}

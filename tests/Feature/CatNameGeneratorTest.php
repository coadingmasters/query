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
}

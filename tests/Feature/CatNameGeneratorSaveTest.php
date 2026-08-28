<?php

namespace Tests\Feature;

use App\Models\NameGeneratorSave;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatNameGeneratorSaveTest extends TestCase
{
    use RefreshDatabase;

    public function test_saving_a_real_name_increments_its_count(): void
    {
        $name = config('cat-name-generator.names')[0]['name'];

        $this->postJson('/tools/cat-name-generator/save', ['name' => $name])
            ->assertOk()
            ->assertJson(['ok' => true]);

        $this->assertDatabaseHas('name_generator_saves', ['name' => $name, 'save_count' => 1]);

        $this->postJson('/tools/cat-name-generator/save', ['name' => $name]);

        $this->assertDatabaseHas('name_generator_saves', ['name' => $name, 'save_count' => 2]);
    }

    /** Only names that actually exist in the generator can be saved, not an arbitrary string. */
    public function test_a_name_outside_the_generator_is_rejected(): void
    {
        $this->postJson('/tools/cat-name-generator/save', ['name' => 'Not A Real Generator Name'])
            ->assertStatus(422);

        $this->assertDatabaseCount('name_generator_saves', 0);
    }

    public function test_the_trending_list_reflects_the_most_saved_names(): void
    {
        $names = collect(config('cat-name-generator.names'))->pluck('name')->take(3);

        foreach ($names as $i => $name) {
            NameGeneratorSave::create(['name' => $name, 'save_count' => $i + 1]);
        }

        $trending = $this->get('/tools/cat-name-generator')->viewData('trendingNames');

        // Highest save_count (the last name seeded above) should lead the list.
        $this->assertSame($names->last(), $trending->first()['name']);
    }
}

<?php

namespace Database\Seeders;

use App\Models\PostCategory;
use Illuminate\Database\Seeder;

/**
 * The five categories the blog already writes under. PostSeeder assigns
 * each migrated article to one of these by name.
 */
class PostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Health', 'color' => '#F47C6B', 'icon' => 'M8 3v5a4 4 0 0 0 8 0V3M6 3h4M14 3h4M12 12v3a4 4 0 0 0 8 0v-.5M20 12.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z', 'sort_order' => 1],
            ['name' => 'Behavior', 'color' => '#5DA0E4', 'icon' => 'M12 20.5c-3.6-2.2-7-4.6-7-8.4A3.9 3.9 0 0 1 12 9.6a3.9 3.9 0 0 1 7 2.5c0 3.8-3.4 6.2-7 8.4Z', 'sort_order' => 2],
            ['name' => 'Feeding', 'color' => '#A9C3A0', 'icon' => 'M3.5 12.5h17a8.5 8.5 0 0 1-17 0Z', 'sort_order' => 3],
            ['name' => 'Food Safety', 'color' => '#EF9F27', 'icon' => 'M20 12.5c0 4.5-3.2 6.9-7.1 8.2a1 1 0 0 1-.7 0C8.2 19.4 5 17 5 12.5V6.2a1 1 0 0 1 .9-1c1.9-.2 4.1-1.2 5.5-2.4a1 1 0 0 1 1.3 0c1.4 1.2 3.6 2.2 5.5 2.4a1 1 0 0 1 .8 1Z', 'sort_order' => 4],
            ['name' => 'Getting Started', 'color' => '#EA7B7A', 'icon' => 'M12 21.5a9.5 9.5 0 1 0 0-19 9.5 9.5 0 0 0 0 19Z', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            PostCategory::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}

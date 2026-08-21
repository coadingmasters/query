<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Model events stay on (WithoutModelEvents was here, and suppressing
     * events globally silently skipped the slug, reading-time and image
     * dimension logic PostCategorySeeder and PostSeeder depend on below).
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            PostCategorySeeder::class,
            PostSeeder::class,
        ]);
    }
}

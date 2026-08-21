<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('breeds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('origin_country')->nullable();

            // Registry recognition, informational rather than gating.
            $table->boolean('registry_tica')->default(false);
            $table->boolean('registry_cfa')->default(false);
            $table->boolean('registry_fife')->default(false);

            // Physical.
            $table->enum('size_category', ['small', 'medium', 'large'])->default('medium');
            $table->decimal('weight_min_kg', 4, 1)->nullable();
            $table->decimal('weight_max_kg', 4, 1)->nullable();
            $table->enum('coat_length', ['hairless', 'short', 'medium', 'long'])->nullable();

            // Traits, 1 (low) to 5 (high). Nullable: an incomplete entry
            // should not force a guessed rating into the database.
            $table->unsignedTinyInteger('energy_level')->nullable();
            $table->unsignedTinyInteger('affection_level')->nullable();
            $table->unsignedTinyInteger('child_friendly')->nullable();
            $table->unsignedTinyInteger('grooming_needs')->nullable();
            $table->unsignedTinyInteger('shedding_level')->nullable();
            $table->unsignedTinyInteger('intelligence')->nullable();

            // Health.
            $table->unsignedTinyInteger('lifespan_min')->nullable();
            $table->unsignedTinyInteger('lifespan_max')->nullable();
            $table->json('health_watch')->nullable();
            $table->boolean('hypoallergenic')->default(false);

            // Lifestyle fit.
            $table->boolean('good_for_apartments')->default(false);
            $table->boolean('good_for_beginners')->default(false);

            // Content.
            $table->text('description')->nullable();
            $table->string('temperament_summary')->nullable();
            $table->text('fun_fact')->nullable();
            $table->string('image')->nullable();

            // Meta.
            $table->unsignedInteger('popularity_rank')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('popularity_rank');
            $table->index('size_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('breeds');
    }
};

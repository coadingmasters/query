<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // A real counter, incremented once per browser per name (the client
        // only fires this the first time a name is saved as a favorite, not
        // on every click), so "trending" reflects actual visitors rather
        // than a claim with nothing behind it.
        Schema::create('name_generator_saves', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('save_count')->default(0);
            $table->timestamps();

            $table->index('save_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('name_generator_saves');
    }
};

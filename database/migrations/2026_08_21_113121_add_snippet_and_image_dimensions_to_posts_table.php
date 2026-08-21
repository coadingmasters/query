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
        Schema::table('posts', function (Blueprint $table) {
            // Captured once, from whatever file ends up on disk, rather than
            // trusted to an editor: this is what lets the featured image carry
            // width/height in the markup without a request-time filesystem
            // read, which is what keeps Cumulative Layout Shift at zero.
            $table->unsignedSmallInteger('featured_image_width')->nullable()->after('featured_image_alt');
            $table->unsignedSmallInteger('featured_image_height')->nullable()->after('featured_image_width');

            // The featured-snippet answer and the cited sources. Optional:
            // most posts will not set them, and the page sections that use
            // them disappear rather than render empty when they are null.
            $table->text('quick_answer')->nullable()->after('excerpt');
            $table->json('sources')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'featured_image_width', 'featured_image_height', 'quick_answer', 'sources',
            ]);
        });
    }
};

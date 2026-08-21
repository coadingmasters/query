<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('seo_site_name')->nullable();
            $table->string('seo_og_image')->nullable();
            $table->string('seo_twitter_card')->nullable();

            $table->string('schema_org_logo')->nullable();
            $table->string('schema_facebook_url')->nullable();
            $table->string('schema_instagram_url')->nullable();
            $table->string('schema_twitter_url')->nullable();
            $table->string('schema_youtube_url')->nullable();

            $table->text('robots_txt')->nullable();
            $table->text('sitemap_excluded_paths')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'seo_site_name', 'seo_og_image', 'seo_twitter_card',
                'schema_org_logo', 'schema_facebook_url', 'schema_instagram_url', 'schema_twitter_url', 'schema_youtube_url',
                'robots_txt', 'sitemap_excluded_paths',
            ]);
        });
    }
};

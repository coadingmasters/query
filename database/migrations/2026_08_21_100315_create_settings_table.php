<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Single row. Every column is nullable and, when null, the site
        // falls back to the existing config/env value exactly as before —
        // this table only ever overrides, never replaces, that fallback.
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('brand_email')->nullable();
            $table->string('brand_tagline')->nullable();
            $table->text('brand_description')->nullable();

            $table->string('author_name')->nullable();
            $table->string('author_role')->nullable();
            $table->string('author_tagline')->nullable();
            $table->text('author_bio')->nullable();
            $table->string('author_linkedin_url')->nullable();
            $table->string('author_twitter_url')->nullable();
            $table->string('author_github_url')->nullable();
            $table->string('author_website_url')->nullable();

            $table->string('reviewer_name')->nullable();
            $table->string('reviewer_credentials')->nullable();
            $table->date('reviewer_reviewed_on')->nullable();
            $table->string('reviewer_profile_url')->nullable();

            $table->string('legal_jurisdiction')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

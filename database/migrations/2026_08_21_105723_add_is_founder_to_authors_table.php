<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Marks the one Author row (if any) kept in sync with the site's
        // founder identity on the Settings page — see
        // SettingsController::update(). Editing that identity stays on
        // Settings; this just lets the same person be picked as a post's
        // byline without re-entering their bio and socials a second time.
        Schema::table('authors', function (Blueprint $table) {
            $table->boolean('is_founder')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn('is_founder');
        });
    }
};

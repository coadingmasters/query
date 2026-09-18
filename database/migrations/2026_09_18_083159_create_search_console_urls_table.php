<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_console_urls', function (Blueprint $table) {
            $table->id();
            $table->string('url')->unique();
            $table->string('coverage_state')->nullable();
            $table->string('verdict')->nullable();
            $table->string('indexing_state')->nullable();
            $table->string('robots_txt_state')->nullable();
            $table->timestamp('last_crawl_time')->nullable();
            $table->timestamp('checked_at')->useCurrent();
            $table->timestamps();

            $table->index('coverage_state');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_console_urls');
    }
};

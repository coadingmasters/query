<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_feedback', function (Blueprint $table) {
            $table->id();

            // The article slug rather than a foreign key: articles live in
            // config and in Blade files, not in a table.
            $table->string('slug')->index();
            $table->boolean('helpful');

            // No IP, no user agent, no identifier of any kind. The privacy
            // policy says a vote is a vote, and this is what makes that true.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_feedback');
    }
};

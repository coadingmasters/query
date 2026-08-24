<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('click_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('selector')->nullable();
            $table->string('label')->nullable();
            $table->string('href')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('created_at');
            $table->index('visitor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('click_events');
    }
};

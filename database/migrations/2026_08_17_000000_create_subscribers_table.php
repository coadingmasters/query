<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();

            // Unique so a repeat sign-up updates rather than duplicates, and
            // so the list cannot mail the same person twice.
            $table->string('email')->unique();

            // Set when someone unsubscribes; the row stays so the address is
            // not silently re-added by a later sign-up form.
            $table->timestamp('unsubscribed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};

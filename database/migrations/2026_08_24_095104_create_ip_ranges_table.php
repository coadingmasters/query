<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * IPv4 only, stored as plain integers (fits comfortably in an unsigned
     * bigint). IPv6 ranges in the source dataset are skipped on import —
     * a visitor on IPv6 simply gets no country rather than a wrong one.
     */
    public function up(): void
    {
        Schema::create('ip_ranges', function (Blueprint $table) {
            $table->unsignedBigInteger('ip_from');
            $table->unsignedBigInteger('ip_to');
            $table->string('country_code', 2);
            $table->string('country_name');

            $table->index(['ip_from', 'ip_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_ranges');
    }
};

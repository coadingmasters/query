<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * IPv6's 128 bits don't fit any native MySQL integer type, so this
     * stores each bound as the raw 16-byte binary form (inet_pton()) rather
     * than a number. A plain byte-wise comparison on that binary string
     * still sorts and ranges correctly, since inet_pton() is big-endian —
     * the same trick used to avoid a bigint overflow, without needing one.
     */
    public function up(): void
    {
        Schema::create('ipv6_ranges', function (Blueprint $table) {
            $table->binary('ip_from', 16, fixed: true);
            $table->binary('ip_to', 16, fixed: true);
            $table->string('country_code', 2);
            $table->string('country_name');

            $table->index(['ip_from', 'ip_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipv6_ranges');
    }
};

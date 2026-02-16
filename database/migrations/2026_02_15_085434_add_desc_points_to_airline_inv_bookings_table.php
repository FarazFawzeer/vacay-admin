<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('airline_inv_bookings', function (Blueprint $table) {
            // If your DB supports JSON, use json. Otherwise use longText.
            $table->json('desc_points')->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('airline_inv_bookings', function (Blueprint $table) {
            $table->dropColumn('desc_points');
        });
    }
};

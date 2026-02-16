<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_desc_points_to_rent_vehicle_bookings.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rent_vehicle_bookings', function (Blueprint $table) {
            $table->json('desc_points')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('rent_vehicle_bookings', function (Blueprint $table) {
            $table->dropColumn('desc_points');
        });
    }
};

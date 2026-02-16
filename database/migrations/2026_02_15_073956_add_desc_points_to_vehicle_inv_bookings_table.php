<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vehicle_inv_bookings', function (Blueprint $table) {
            $table->json('desc_points')->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_inv_bookings', function (Blueprint $table) {
            $table->dropColumn('desc_points');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('airline_booking_trips', function (Blueprint $table) {
            // First drop the old foreign key
            $table->dropForeign(['airline_booking_id']);

            // Now add the correct foreign key pointing to airline_inv_bookings
            $table->foreign('airline_booking_id')
                  ->references('id')
                  ->on('airline_inv_bookings')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('airline_booking_trips', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['airline_booking_id']);

            // Restore old foreign key to airline_bookings (if needed)
            $table->foreign('airline_booking_id')
                  ->references('id')
                  ->on('airline_bookingss')
                  ->cascadeOnDelete();
        });
    }
};

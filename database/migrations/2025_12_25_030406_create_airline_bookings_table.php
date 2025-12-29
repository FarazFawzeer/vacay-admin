<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('airline_inv_booking_trips', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('airline_inv_booking_id');

            // Passenger
            $table->unsignedBigInteger('customer_id');
            $table->string('passport_no');

            // Agent & airline
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('airline')->nullable();
            $table->string('airline_no')->nullable();
            $table->string('pnr')->nullable();

            // Route
            $table->string('from_country');
            $table->string('to_country');

            // Timing
            $table->dateTime('departure_datetime')->nullable();
            $table->dateTime('arrival_datetime')->nullable();

            // Baggage
            $table->integer('baggage_qty')->nullable();
            $table->integer('handluggage_qty')->nullable();

            // Trip type
            $table->enum('trip_type', ['one_way', 'going', 'return', 'round_trip']);

            $table->timestamps();

            // Foreign keys
            $table->foreign('airline_inv_booking_id')
                ->references('id')
                ->on('airline_inv_bookings')
                ->onDelete('cascade');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->foreign('agent_id')
                ->references('id')
                ->on('agents')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airline_inv_booking_trips');
    }
};

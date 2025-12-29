<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('airline_booking_trips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('airline_booking_id')
                ->constrained('airline_bookings')
                ->cascadeOnDelete();

            $table->enum('trip_type', ['one_way', 'going', 'return', 'round_trip']);

            $table->foreignId('passport_id')
                ->nullable()
                ->constrained('passports')
                ->nullOnDelete();

            $table->string('passport_no')->nullable();

            $table->foreignId('agent_id')
                ->nullable()
                ->constrained('agents')
                ->nullOnDelete();

            $table->string('airline')->nullable();
            $table->string('airline_no')->nullable();

            $table->string('from_country')->nullable();
            $table->string('to_country')->nullable();

            $table->string('pnr')->nullable();
            $table->dateTime('departure_datetime')->nullable();
            $table->dateTime('arrival_datetime')->nullable();

            $table->integer('baggage_qty')->default(0);
            $table->integer('handluggage_qty')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airline_booking_trips');
    }
};

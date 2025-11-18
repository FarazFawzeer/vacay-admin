<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_inv_bookings', function (Blueprint $table) {
            $table->id();

            $table->string('inv_no')->unique();

            // Foreign keys
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('vehicle_id');

            // Booking details
            $table->string('pickup_location')->nullable();
            $table->dateTime('pickup_datetime')->nullable();

            $table->string('dropoff_location')->nullable();
            $table->dateTime('dropoff_datetime')->nullable();

            $table->integer('mileage')->nullable();
            $table->integer('total_km')->nullable();

            // Pricing details
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('additional_charges', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();

            $table->text('note')->nullable();

            // Status fields
            $table->string('status')->default('pending'); // pending, confirmed, canceled, completed
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, partial
            $table->string('payment_method')->nullable(); // card, cash, bank, etc.
            $table->string('currency')->default('LKR');

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicle_details')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_bookings');
    }
};

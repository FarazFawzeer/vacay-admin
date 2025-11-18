<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rent_vehicle_bookings', function (Blueprint $table) {
            $table->id();
            
            // Booking info
            $table->string('inv_no')->unique()->comment('Invoice number');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicle_details')->onDelete('cascade');

            // Booking status & payment
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable()->comment('Cash, Card, Bank Transfer, etc.');
            $table->string('currency', 5)->default('USD');

            // Pricing
            $table->decimal('price', 10, 2)->default(0); // Base price
            $table->decimal('additional_price', 10, 2)->nullable()->default(0); // Extra charges
            $table->decimal('discount', 10, 2)->nullable()->default(0);
            $table->decimal('tax', 10, 2)->nullable()->default(0); // optional professional field
            $table->decimal('total_price', 10, 2)->default(0);

            // Booking duration
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');

            // Optional professional fields
            $table->text('notes')->nullable()->comment('Additional notes for booking');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_vehicle_bookings');
    }
};

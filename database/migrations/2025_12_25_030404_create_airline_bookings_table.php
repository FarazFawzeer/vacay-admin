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
        Schema::create('airline_inv_bookings', function (Blueprint $table) {
            $table->id();

            // Invoice number
            $table->string('invoice_no')->unique();

            // Customer
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            // Agent (nullable)
            $table->string('agent')->nullable();

            // Route
            $table->string('from_country');
            $table->string('to_country');

            // Departure and arrival
            $table->dateTime('departure_datetime');
            $table->dateTime('arrival_datetime');

            // Airline / Flight
            $table->string('airline')->nullable()->comment('Selected airline/flight');

            // Pricing
            $table->string('currency', 10);
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('additional_price', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('advanced_paid', 12, 2)->default(0);
            $table->decimal('balance', 12, 2);

            // Status and payment
            $table->string('status')->default('pending'); // e.g., pending, confirmed, cancelled
            $table->string('payment_status')->default('unpaid'); // unpaid, partial, paid

            // Auth user
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airline_bookings');
    }
};

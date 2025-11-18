<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_bookings', function (Blueprint $table) {
            $table->id();

            // 🔹 Foreign keys
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            // 🔹 Booking details
            $table->string('booking_ref_no')->unique();
            $table->date('travel_date')->nullable();
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            $table->integer('total_guests')->virtualAs('adults + children + infants');

            // 🔹 Pricing & quotation details
            $table->decimal('package_price', 10, 2)->nullable(); // base package price
            $table->decimal('total_price', 12, 2)->nullable();   // final quoted price
            $table->string('currency', 10)->default('LKR');
            $table->text('special_requirements')->nullable();

            // 🔹 Status (includes both booking and invoice flow)
            $table->enum('status', [
                'quotation',     // Just created quote
                'pending',       // Awaiting confirmation
                'confirmed',     // Booking confirmed
                'invoiced',      // Invoice created/sent
                'paid',          // Payment received
                'cancelled',     // Booking cancelled
                'completed'      // Tour completed
            ])->default('quotation');

            // 🔹 Meta
            $table->string('created_by')->nullable(); // staff user or system
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_bookings');
    }
};

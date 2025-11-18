<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('inv_no')->unique(); // Invoice number
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('visa_id')->constrained('visa')->onDelete('cascade');
            $table->string('passport_number');
            $table->string('type'); // e.g., Single/Multiple
            $table->string('agent')->nullable();
            $table->date('visa_issue_date')->nullable();
            $table->date('visa_expiry_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_bookings');
    }
};

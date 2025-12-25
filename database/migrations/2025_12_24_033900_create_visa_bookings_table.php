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

            // Relations
            $table->foreignId('passport_id')
                ->constrained('passports')
                ->cascadeOnDelete();

            $table->foreignId('visa_id')
                ->constrained('visas')
                ->cascadeOnDelete();

            $table->foreignId('visa_category_id')
                ->constrained('visa_categories')
                ->cascadeOnDelete();

            $table->foreignId('agent_id')
                ->nullable()
                ->constrained('agents')
                ->nullOnDelete();

            // Pricing snapshot
            $table->string('currency', 10);
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('additional_price', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('advanced_paid', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);

            // Visa dates
            $table->date('visa_issue_date')->nullable();
            $table->date('visa_expiry_date')->nullable();

            // Status
            $table->string('status')->default('pending'); 
            $table->string('payment_status')->default('unpaid');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_bookings');
    }
};

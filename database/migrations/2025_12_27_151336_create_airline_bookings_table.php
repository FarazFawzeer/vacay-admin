<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('airline_inv_bookings', function (Blueprint $table) {
            $table->id();

            $table->enum('business_type', ['corporate', 'individual']);
            $table->string('company_name')->nullable();

            $table->enum('ticket_type', ['one_way', 'return']);
            $table->enum('return_type', ['return_ticket', 'round_trip'])->nullable();

            $table->enum('status', [
                'Quotation',
                'Accepted',
                'Invoiced',
                'Partially Paid',
                'Paid',
                'Cancelled'
            ]);

            $table->enum('payment_status', ['unpaid', 'partial', 'paid']);

            $table->string('currency', 10);
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('additional_price', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('advanced_paid', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airline_bookings');
    }
};

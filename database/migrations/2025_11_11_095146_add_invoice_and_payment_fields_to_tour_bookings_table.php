<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_bookings', function (Blueprint $table) {

            // 🔹 Add discount and tax
            if (!Schema::hasColumn('tour_bookings', 'discount')) {
                $table->decimal('discount', 10, 2)->nullable()->after('package_price');
            }

            if (!Schema::hasColumn('tour_bookings', 'tax')) {
                $table->decimal('tax', 10, 2)->nullable()->after('discount');
            }

            // 🔹 Add invoice fields
            if (!Schema::hasColumn('tour_bookings', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->unique()->after('special_requirements');
            }

            if (!Schema::hasColumn('tour_bookings', 'invoice_date')) {
                $table->date('invoice_date')->nullable()->after('invoice_number');
            }

            // 🔹 Add payment tracking fields
            if (!Schema::hasColumn('tour_bookings', 'amount_paid')) {
                $table->decimal('amount_paid', 12, 2)->default(0)->after('invoice_date');
            }

            if (!Schema::hasColumn('tour_bookings', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->after('amount_paid');
            }

            if (!Schema::hasColumn('tour_bookings', 'payment_method')) {
                $table->enum('payment_method', [
                    'cash', 'bank_transfer', 'credit_card', 'online', 'other'
                ])->nullable()->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tour_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'discount',
                'tax',
                'invoice_number',
                'invoice_date',
                'amount_paid',
                'payment_status',
                'payment_method',
            ]);
        });
    }
};

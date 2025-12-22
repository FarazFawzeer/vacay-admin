<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('vehicle_inv_bookings', function (Blueprint $table) {
            $table->decimal('advance_paid', 12, 2)->default(0)->after('total_price');
            $table->string('auth_id')->nullable()->after('advance_paid');
        });
    }

    public function down()
    {
        Schema::table('vehicle_inv_bookings', function (Blueprint $table) {
            $table->dropColumn(['advance_paid', 'auth_id']);
        });
    }
};

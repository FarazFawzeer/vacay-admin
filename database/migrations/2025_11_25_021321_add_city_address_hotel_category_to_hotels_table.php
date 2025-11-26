<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up()
{
    Schema::table('hotels', function (Blueprint $table) {
        $table->string('city')->nullable();
        $table->string('address')->nullable();
        $table->string('hotel_category')->nullable(); // ex: Luxury, Budget, Boutique etc.
    });
}

public function down()
{
    Schema::table('hotels', function (Blueprint $table) {
        $table->dropColumn(['city', 'address', 'hotel_category']);
    });
}

};

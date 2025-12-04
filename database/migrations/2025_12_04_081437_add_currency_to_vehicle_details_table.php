<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vehicle_details', function (Blueprint $table) {
            $table->string('currency')->default('LKR')->after('price');
        });
    }

    public function down()
    {
        Schema::table('vehicle_details', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};

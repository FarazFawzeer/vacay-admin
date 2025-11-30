<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('hotels', function (Blueprint $table) {
        $table->string('contact_person')->nullable();
        $table->string('landline_number')->nullable();
        $table->string('mobile_number')->nullable();
    });
}

public function down()
{
    Schema::table('hotels', function (Blueprint $table) {
        $table->dropColumn(['contact_person', 'landline_number', 'mobile_number']);
    });
}

};

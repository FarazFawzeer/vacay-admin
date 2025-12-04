<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            // Change service field to JSON
            $table->json('service')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            // Revert back to string (or text)
            $table->string('service')->nullable()->change();
        });
    }
};

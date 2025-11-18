<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('room_type')->nullable();
            $table->string('meal_plan')->nullable();
            $table->text('description')->nullable();
            $table->text('facilities')->nullable();
            $table->text('entertainment')->nullable();
            $table->json('pictures')->nullable(); // to store multiple image paths
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn([
                'room_type',
                'meal_plan',
                'description',
                'facilities',
                'entertainment',
                'pictures',
            ]);
        });
    }
};

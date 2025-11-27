<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->json('room_type')->nullable()->change();
            $table->json('facilities')->nullable()->change();
            $table->json('entertainment')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('room_type')->nullable()->change();
            $table->string('facilities')->nullable()->change();
            $table->string('entertainment')->nullable()->change();
        });
    }
};


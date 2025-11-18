<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cancellation_points', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->nullable();
            $table->json('points')->nullable(); // multiple cancellation points
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cancellation_points');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_id')->constrained('visas')->onDelete('cascade');
            $table->string('visa_type');
            $table->string('state')->nullable();
            $table->integer('days')->nullable();
            $table->string('visa_validity')->nullable();
            $table->integer('how_many_days')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency')->nullable();
            $table->string('processing_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_categories');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_inclusions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('inclusion_id')->nullable(); // optional link to master inclusion
            $table->string('heading')->nullable();
            $table->json('points')->nullable();
            $table->text('note')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_inclusions');
    }
};

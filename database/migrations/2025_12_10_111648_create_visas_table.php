<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visas', function (Blueprint $table) {
            $table->id();
            $table->string('from_country');
            $table->string('to_country');
            $table->string('visa_type'); // dropdown selection
            $table->string('custom_visa_type')->nullable(); // optional custom type
            $table->json('documents')->nullable(); // store multiple file paths
            $table->unsignedBigInteger('agent_id')->nullable(); // link to agent
            $table->unsignedBigInteger('auth_id')->nullable(); // user/admin who created
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->text('note')->nullable(); // optional note
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');
            $table->foreign('auth_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visas');
    }
};

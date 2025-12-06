<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id'); // Foreign key to customers table

            $table->string('first_name');
            $table->string('second_name')->nullable();
            $table->string('passport_number')->unique();
            $table->date('passport_expire_date');
            $table->string('nationality');
            $table->date('dob'); // Date of birth
            $table->enum('sex', ['male', 'female', 'other'])->nullable();
            $table->date('issue_date')->nullable();
            $table->string('id_number')->nullable();
            $table->string('id_photo')->nullable(); // store image path

            $table->timestamps();

            // FK constraint
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};

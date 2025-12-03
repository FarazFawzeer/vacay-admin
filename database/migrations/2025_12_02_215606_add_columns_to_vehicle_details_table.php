<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_details', function (Blueprint $table) {
            // Add new columns
            $table->unsignedBigInteger('agent_id')->nullable()->after('id');
            $table->string('fuel_type')->nullable()->after('agent_id');
            $table->string('insurance_type')->nullable()->after('fuel_type');

            // Add foreign key constraint
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_details', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['agent_id']);

            // Drop the columns
            $table->dropColumn(['agent_id', 'fuel_type', 'insurance_type']);
        });
    }
};

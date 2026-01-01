<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tour_bookings', function (Blueprint $table) {
            $table->foreignId('agent_id')
                ->nullable()
                ->after('customer_id')
                ->constrained('agents')
                ->nullOnDelete();

            $table->date('published_at')
                ->nullable()
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('tour_bookings', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn(['agent_id', 'published_at']);
        });
    }
};

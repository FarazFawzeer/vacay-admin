<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reminders', function (Blueprint $table) {

            // Is this reminder visible to all users?
            $table->boolean('is_global')
                ->default(false)
                ->after('user_id');

            // Who created this reminder (important for global reminders)
            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->after('is_global');

            // Optional but recommended indexes
            $table->index(['is_global', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropIndex(['is_global', 'user_id']);
            $table->dropColumn(['is_global', 'created_by']);
        });
    }
};

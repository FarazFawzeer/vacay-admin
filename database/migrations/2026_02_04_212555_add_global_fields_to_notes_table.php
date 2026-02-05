<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // allow NULL for global notes
            $table->unsignedBigInteger('user_id')->nullable()->change();

            $table->boolean('is_global')->default(false)->after('user_id');
            $table->unsignedBigInteger('created_by')->nullable()->after('is_global');

            $table->index(['is_global', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex(['is_global', 'user_id']);
            $table->dropColumn(['is_global', 'created_by']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};

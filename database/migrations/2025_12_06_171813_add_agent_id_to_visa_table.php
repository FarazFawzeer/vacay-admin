<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('visa', function (Blueprint $table) {
        $table->unsignedBigInteger('agent_id')->nullable()->after('id');

        // If you want a foreign key (optional)
        // $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('visa', function (Blueprint $table) {
        // If foreign key was added, drop FK first
        // $table->dropForeign(['agent_id']);

        $table->dropColumn('agent_id');
    });
}

};

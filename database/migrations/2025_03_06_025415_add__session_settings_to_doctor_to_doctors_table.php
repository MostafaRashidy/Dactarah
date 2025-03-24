<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->integer('session_duration')->default(30);
            $table->integer('break_duration')->default(10);
            $table->json('schedule_settings')->nullable();
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['session_duration', 'break_duration', 'schedule_settings']);
        });
    }
};

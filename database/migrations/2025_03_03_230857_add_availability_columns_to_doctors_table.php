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
            $table->boolean('is_available')->default(true);
            $table->json('available_days')->nullable();
            $table->time('available_from')->nullable();
            $table->time('available_to')->nullable();
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['is_available', 'available_days', 'available_from', 'available_to']);
        });
    }
};

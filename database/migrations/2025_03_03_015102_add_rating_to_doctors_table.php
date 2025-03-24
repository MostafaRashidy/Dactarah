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
            $table->decimal('rating', 3, 1)->default(5.0); // Allows values like 4.5
            $table->integer('ratings_count')->default(0);  // To track number of ratings
            $table->integer('experience_years')->default(0);
            $table->integer('patients_count')->default(0);
            $table->boolean('is_verified')->default(false);
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['rating', 'ratings_count', 'experience_years', 'patients_count', 'is_verified']);
        });
    }
};

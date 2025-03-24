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
        Schema::create('governorates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // English name
            $table->string('name_ar')->unique(); // Arabic name
            $table->string('name_en')->nullable(); // Optional English transliteration
            $table->string('region')->nullable(); // Optional region classification
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('governorates');
    }
};

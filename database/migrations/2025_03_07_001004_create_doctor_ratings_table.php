<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctor_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->integer('rating');
            $table->text('review')->nullable();
            $table->timestamps();

            // Ensure one rating per user per doctor per appointment
            $table->unique(['doctor_id', 'user_id', 'appointment_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_ratings');
    }
};

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
        // Drop the existing column
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Recreate the column with the new enum values
        Schema::table('doctors', function (Blueprint $table) {
            $table->enum('status', ['pending', 'active', 'rejected', 'inactive'])->default('pending');
        });
    }

    public function down()
    {
        // Rollback method to revert to the previous enum
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending');
        });
    }
};

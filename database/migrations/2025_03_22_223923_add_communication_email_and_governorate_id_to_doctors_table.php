<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommunicationEmailAndGovernorateIdToDoctorsTable extends Migration
{
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Check if communication_email column does not exist before adding
            if (!Schema::hasColumn('doctors', 'communication_email')) {
                $table->string('communication_email')->nullable()->after('email');
            }

            // Check if governorate_id column does not exist before adding
            if (!Schema::hasColumn('doctors', 'governorate_id')) {
                $table->unsignedBigInteger('governorate_id')->nullable()->after('communication_email');

                // Add foreign key constraint
                $table->foreign('governorate_id')
                      ->references('id')
                      ->on('governorates')
                      ->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Only drop columns if they exist
            if (Schema::hasColumn('doctors', 'communication_email')) {
                $table->dropColumn('communication_email');
            }

            if (Schema::hasColumn('doctors', 'governorate_id')) {
                // Drop foreign key first
                $table->dropForeign(['governorate_id']);
                $table->dropColumn('governorate_id');
            }
        });
    }
}

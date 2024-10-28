<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImmunizationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_profiles', function (Blueprint $table) {
            $table->string('immunization_history')->nullable()->after('previous_symp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_profiles', function (Blueprint $table) {
            $table->dropColumn('immunization_history');

        });
    }
}

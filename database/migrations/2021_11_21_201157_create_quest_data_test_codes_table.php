<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestDataTestCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_data_test_codes', function (Blueprint $table) {
            $table->id();
            $table->string('LEGAL_ENTITY', 255)->nullable();
            $table->string('TEST_CD', 255)->nullable();
            $table->string('STATE', 255)->nullable();
            $table->string('UNIT_CD', 255)->nullable();
            $table->string('ACTIVE_IND', 255)->nullable();
            $table->string('INSERT_DATETIME', 255)->nullable();
            $table->string('DESCRIPTION', 255)->nullable();
            $table->string('SPECIMEN_TYPE', 255)->nullable();
            $table->string('NBS_SERVICE_CODE', 255)->nullable();
            $table->string('TOPLAB_PERFORMING_SITE', 255)->nullable();
            $table->string('UPDATE_DATETIME', 255)->nullable();
            $table->string('UPDATE_USER', 255)->nullable();
            $table->string('SUFFIX', 255)->nullable();
            $table->string('PROFILE_IND', 255)->nullable();
            $table->string('SELECTEST_IND', 255)->nullable();
            $table->string('NBS_PERFORMING_SITE', 255)->nullable();
            $table->string('TEST_FLAG', 255)->nullable();
            $table->string('NO_BILL_INDICATOR', 255)->nullable();
            $table->string('BILL_ONLY_INDICATOR', 255)->nullable();
            $table->string('SEND_OUT_REFLEX_COUNT', 255)->nullable();
            $table->string('CONFORMING_IND', 255)->nullable();
            $table->string('ALTERNATE_TEMP', 255)->nullable();
            $table->string('PAP_IND', 255)->nullable();
            $table->string('UPDATE_TYPE', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quest_data_test_codes');
    }
}

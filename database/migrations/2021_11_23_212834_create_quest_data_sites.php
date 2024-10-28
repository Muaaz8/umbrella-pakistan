<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestDataSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_data_sites', function (Blueprint $table) {
            $table->id();
            $table->string('LABORATORY_CD',255)->nullable();
            $table->string('FACILITY_CD',255)->nullable();
            $table->string('COUNTRY',255)->nullable();
            $table->string('ACTIVE_FLAG',255)->nullable();
            $table->string('INHOUSE_FLAG',255)->nullable();
            $table->string('FACILITY_NAME',255)->nullable();
            $table->string('ADDR_LINE_1',255)->nullable();
            $table->string('ADDR_LINE_2',255)->nullable();
            $table->string('CITY',255)->nullable();
            $table->string('STATE',255)->nullable();
            $table->string('ZIP_CD',255)->nullable();
            $table->string('PHONE_NO',255)->nullable();
            $table->string('PC_MES_FLAG',255)->nullable();
            $table->string('SPECIMEN_GROUP',255)->nullable();
            $table->string('REQ_LABEL',255)->nullable();
            $table->string('SPECIMEN_LABEL',255)->nullable();
            $table->string('SEND_IN_LABEL',255)->nullable();
            $table->string('SEND_OUT_LABEL',255)->nullable();
            $table->string('COMBO_LABEL',255)->nullable();
            $table->string('SORT_DEVICE',255)->nullable();
            $table->string('ALIQUOTER_LABEL',255)->nullable();
            $table->string('MEDICAL_DIRECTOR',255)->nullable();
            $table->string('UPDATE_TYPE',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quest_data_sites');
    }
}

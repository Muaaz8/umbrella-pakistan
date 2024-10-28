<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestDataAoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_data_aoes', function (Blueprint $table) {
            $table->id();
            $table->string('LEGAL_ENTITY',255)->nullable();
            $table->string('TOPLAB_PERFORMING_SITE',255)->nullable();
            $table->string('UNIT_CD',255)->nullable();
            $table->string('TEST_CD',255)->nullable();
            $table->string('ANALYTE_CD',255)->nullable();
            $table->string('AOE_QUESTION',255)->nullable();
            $table->string('ACTIVE_IND',255)->nullable();
            $table->string('PROFILE_COMPONENT',255)->nullable();
            $table->string('INSERT_DATETIME',255)->nullable();
            $table->string('AOE_QUESTION_DESC',255)->nullable();
            $table->string('SUFFIX',255)->nullable();
            $table->string('RESULT_FILTER',255)->nullable();
            $table->string('TEST_CD_MNEMONIC',255)->nullable();
            $table->string('TEST_FLAG',255)->nullable();
            $table->string('UPDATE_DATETIME',255)->nullable();
            $table->string('UPDATE_USER',255)->nullable();
            $table->string('COMPONENT_NAME',255)->nullable();
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
        Schema::dropIfExists('quest_data_aoes');
    }
}

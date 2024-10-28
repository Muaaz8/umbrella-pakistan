<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAOESExistToQuestDataTestCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest_data_test_codes', function (Blueprint $table) {
            $table->text('AOES_exist')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quest_data_test_codes', function (Blueprint $table) {
            $table->dropColumn('AOES_exist');
        });
    }
}

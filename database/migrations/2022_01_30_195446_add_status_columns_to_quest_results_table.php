<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnsToQuestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest_results', function (Blueprint $table) {
            $table->longText('pat_dob')->after('pat_gender');
            $table->longText('patient_matching')->after('status');
            $table->longText('provider_matching')->after('patient_matching');
            $table->longText('order_matching')->after('provider_matching');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quest_results', function (Blueprint $table) {
            $table->dropColumn('pat_dob');
            $table->dropColumn('patient_matching');
            $table->dropColumn('provider_matching');
            $table->dropColumn('order_matching');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPatientFieldsToQuestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest_results', function (Blueprint $table) {
            $table->string('pat_id')->after('get_request_id')->nullable();
            $table->string('pat_first_name')->after('pat_id')->nullable();
            $table->string('pat_last_name')->after('pat_first_name')->nullable();
            $table->string('pat_gender')->after('pat_last_name')->nullable();
            $table->string('doc_id')->after('pat_gender')->nullable();
            $table->string('doc_npi')->after('doc_id')->nullable();
            $table->string('doc_name')->after('doc_npi')->nullable();
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
            $table->dropColumn('pat_id');
            $table->dropColumn('pat_first_name');
            $table->dropColumn('pat_last_name');
            $table->dropColumn('pat_gender');
            $table->dropColumn('doc_id');
            $table->dropColumn('doc_npi');
            $table->dropColumn('doc_name');
        });
    }
}

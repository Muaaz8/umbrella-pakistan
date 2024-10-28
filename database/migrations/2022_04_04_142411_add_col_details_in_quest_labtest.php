<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColDetailsInQuestLabtest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest_data_test_codes', function (Blueprint $table) {
            $table->renameColumn('FULL_NAME', 'TEST_NAME');
            $table->longText('DETAILS')->nullable()->after('PARENT_CATEGORY');
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
            $table->renameColumn('TEST_NAME', 'FULL_NAME');
            $table->dropColumn('DETAILS');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPRICEColumnQuestLabTestCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest_data_test_codes', function (Blueprint $table) {
            $table->float('PRICE', 5, 2)->nullable()->after('DESCRIPTION');
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
            $table->dropColumn('PRICE');
        });
    }
}

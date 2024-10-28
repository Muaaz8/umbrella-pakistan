<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColCategoryFullNameInQuestLabtest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest_data_test_codes', function (Blueprint $table) {
            $table->string('FULL_NAME')->nullable()->after('DESCRIPTION');
            $table->string('PARENT_CATEGORY')->nullable()->after('SALE_PRICE');

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
            $table->dropColumn('FULL_NAME');
            $table->dropColumn('PARENT_CATEGORY');
        });
    }
}

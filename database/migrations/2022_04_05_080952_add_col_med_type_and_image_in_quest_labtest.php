<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColMedTypeAndImageInQuestLabtest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest_data_test_codes', function (Blueprint $table) {
            $table->string('featured_image')->default('default-labtest.jpg')->after('DETAILS');
            $table->string('medicine_type')->default('prescribed')->after('featured_image');

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
            $table->dropColumn('featured_image');
            $table->dropColumn('medicine_type');
        });
    }
}

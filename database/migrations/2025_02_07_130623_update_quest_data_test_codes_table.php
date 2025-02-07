<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuestDataTestCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest_data_test_codes', function (Blueprint $table) {
            $table->decimal('actual_price', 10, 2)->nullable();
            $table->decimal('discount_percentage', 10, 2)->nullable();
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
            $table->dropColumn('actual_price');
            $table->dropColumn('discount_percentage');
        });
    }
}

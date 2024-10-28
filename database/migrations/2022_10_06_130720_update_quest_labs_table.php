<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuestLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest_labs', function (Blueprint $table) {
            $table->text('names')->change();
            $table->text('orderedtestcode')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quest_labs', function (Blueprint $table) {
            $table->string('names')->change();
            $table->string('orderedtestcode')->change();
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_results', function (Blueprint $table) {
            $table->id();
            $table->string('get_request_id')->nullable();
            $table->string('get_quest_request_id')->nullable();
            $table->string('control_id')->nullable();
            $table->longText('base64_message')->nullable();
            $table->longText('hl7_message')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quest_results');
    }
}

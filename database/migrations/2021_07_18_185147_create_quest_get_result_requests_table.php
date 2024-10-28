<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestGetResultRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_get_result_requests', function (Blueprint $table) {
            $table->id();
            $table->string('resultServiceType');
            $table->longtext('json_response');
            // $table->string('isMore');
            // $table->string('requestId');
            // $table->string('errorMessages');
            // $table->string('providerAccounts');
            // $table->boolean('status');
            $table->text('json_ack');

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
        Schema::dropIfExists('quest_get_result_requests');
    }
}

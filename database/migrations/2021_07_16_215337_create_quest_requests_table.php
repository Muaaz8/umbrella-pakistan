<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('patient_id')->nullable();
            // request
            $table->string('documentTypes')->nullable();
            $table->longtext('orderHL7')->nullable();
            // response
            $table->string('ackhl7')->nullable();
            $table->string('requestStatus')->nullable();
            $table->string('responseMessage')->nullable();
            $table->string('status')->nullable();
            $table->longtext('orderSupportDocuments')->nullable();

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
        Schema::dropIfExists('quest_requests');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefillRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refill_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pres_id');
            $table->string('prod_id');
            $table->string('patient_id');
            $table->string('doctor_id');
            $table->string('session_id');
            $table->string('granted');
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
        Schema::dropIfExists('refill_requests');
    }
}

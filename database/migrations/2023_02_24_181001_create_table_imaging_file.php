<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableImagingFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imaging_file', function (Blueprint $table) {
            $table->id();
            $table->text('session_id');
            $table->text('doctor_id');
            $table->text('patient_id');
            $table->text('order_id');
            $table->text('filename');
            $table->text('response');
            $table->text('status');
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
        Schema::dropIfExists('imaging_file');
    }
}

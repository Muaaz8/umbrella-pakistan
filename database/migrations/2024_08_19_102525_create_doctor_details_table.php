<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('doctor_id');
            $table->text('certificates');
            $table->text('conditions');
            $table->text('procedures');
            $table->text('location');
            $table->text('latitude');
            $table->text('longitude');
            $table->text('about');
            $table->text('education');
            $table->text('helping');
            $table->text('issue');
            $table->text('specialties');
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
        Schema::dropIfExists('doctor_details');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientHealthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psychiatry_form', function (Blueprint $table) {
            $table->id();
            $table->text('user_id');
            $table->text('user_name');
            $table->text('date');
            $table->text('patient_health');
            $table->text('mood_disorder');
            $table->text('anxiety_scale');
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
        Schema::dropIfExists('patient_health');
    }
}

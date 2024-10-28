<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymptomsCheckersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symptom_checker', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->longText('clinical_evaluation');
            $table->longText('hypothesis_report');
            $table->longText('intake_notes');
            $table->longText('referrals_and_tests');
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
        Schema::dropIfExists('symptom_checker');
    }
}

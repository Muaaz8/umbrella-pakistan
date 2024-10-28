<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DoctorSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('user_type');
            $table->string('user_id');
            $table->string('mon')->default('0');
            $table->string('tues')->default('0');
            $table->string('weds')->default('0');
            $table->string('thurs')->default('0');
            $table->string('fri')->default('0');
            $table->string('sat')->default('0');
            $table->string('sun')->default('0');
            // $table->string('reason');
            // $table->string('from_date');
            // $table->string('to_date');
            $table->string('from_time');
            $table->string('to_time');
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
        Schema::dropIfExists('doctor_schedules');
    }
}

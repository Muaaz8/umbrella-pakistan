<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTherapySession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('therapy_session', function (Blueprint $table) {
            $table->id();
            $table->text('event_id')->nullable();
            $table->text('doctor_id')->nullable();
            $table->text('status')->nullable();
            $table->text('price')->nullable();
            $table->text('channel')->nullable();
            $table->text('date')->nullable();
            $table->text('start_time')->nullable();
            $table->text('end_time')->nullable();
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
        Schema::dropIfExists('therapy_session');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecalizationPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specalization_price', function (Blueprint $table) {
            $table->id();
            $table->integer('state_id');
            $table->integer('spec_id');
            $table->integer('initial_price')->nullable();
            $table->integer('follow_up_price');
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
        Schema::dropIfExists('specalization_price');
    }
}

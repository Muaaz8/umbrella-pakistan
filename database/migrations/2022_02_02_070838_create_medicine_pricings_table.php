<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicinePricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine_pricings', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->integer('days_id')->nullable();
            $table->float('price')->nullable();
            $table->float('sale_price')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('medicine_pricings');
    }
}

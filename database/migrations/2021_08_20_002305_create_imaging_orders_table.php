<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imaging_orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('order_id');
            $table->string('sub_order_id');
            $table->string('location_id');
            $table->string('price');
            $table->string('product_id');
            $table->string('session_id')->nullable();
            $table->string('pres_id')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('report')->nullable();
            $table->string('uploaded_by')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('imaging_orders');
    }
}

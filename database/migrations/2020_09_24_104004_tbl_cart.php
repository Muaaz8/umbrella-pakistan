<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TblCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_cart', function (Blueprint $table) {
            $table->id();
            $table->string('cart_row_id', 255);
            $table->string('product_id', 255);
            $table->string('name', 255);
            $table->string('prescription', 255);
            $table->string('design_view', 255);
            $table->string('strip_per_pack', 255);
            $table->integer('quantity');
            $table->float('price');
            $table->integer('discount');
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
        Schema::dropIfExists('tbl_cart');
    }
}

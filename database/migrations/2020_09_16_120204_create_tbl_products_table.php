<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('parent_category');
            $table->string('sub_category');
            $table->string('featured_image');
            $table->string('gallery');
            $table->string('tags');
            $table->string('sale_price');
            $table->string('regular_price');
            $table->string('quantity');
            $table->string('type');
            $table->string('mode');
            $table->string('sku');
            $table->string('status');
            $table->string('short_description');
            $table->string('description');
            $table->string('stock_quantity');
            $table->string('stock_status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_products');
    }
}

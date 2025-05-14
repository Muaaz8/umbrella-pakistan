<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_products', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('vendor_id');
        $table->unsignedBigInteger('product_id');
        $table->integer('available_stock')->default(0);
        $table->decimal('actual_price', 10, 2);
        $table->decimal('selling_price', 10, 2);
        $table->decimal('discount', 5, 2)->nullable();
        $table->string('product_type');
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
        Schema::dropIfExists('vendor_products');
    }
}

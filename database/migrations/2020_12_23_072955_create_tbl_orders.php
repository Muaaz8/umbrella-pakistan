<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_state')->nullable();
            $table->string('order_id')->nullable();
            $table->mediumText('order_sub_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('total')->nullable();
            $table->string('shipping_total')->nullable();
            $table->string('total_tax')->nullable();
            $table->Text('billing')->nullable();
            $table->Text('shipping')->nullable();
            $table->Text('payment')->nullable();
            $table->string('payment_title')->nullable();
            $table->string('payment_method')->nullable();
            $table->Text('cart_items')->nullable();
            $table->Text('tax_lines')->nullable();
            $table->Text('shipping_lines')->nullable();
            $table->Text('coupon_lines')->nullable();
            $table->string('currency')->nullable();
            $table->string('order_status')->nullable();
            
            
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
        Schema::dropIfExists('tbl_orders');
    }
}

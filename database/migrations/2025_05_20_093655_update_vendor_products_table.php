<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVendorProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->string('SKU')->nullable();
            $table->integer('available_stock')->nullable()->change();
            $table->decimal('actual_price', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->dropColumn('SKU');
            $table->integer('available_stock')->default(0)->change();
            $table->decimal('actual_price', 10, 2)->nullable(false)->change();
        });
    }
}

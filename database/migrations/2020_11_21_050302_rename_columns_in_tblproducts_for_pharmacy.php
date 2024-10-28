<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnsInTblproductsForPharmacy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_products', function (Blueprint $table) {
            $table->renameColumn('sku', 'medicine_type');
            $table->renameColumn('stock_quantity', 'medicine_ingredients');
            $table->string('medicine_directions')->after('stock_status');
            $table->string('medicine_warnings')->after('stock_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_products', function (Blueprint $table) {
            $table->renameColumn('medicine_type', 'sku');
            $table->renameColumn('medicine_ingredients', 'stock_quantity');
            $table->dropColumn('medicine_directions')->after('stock_status');
            $table->dropColumn('medicine_warnings')->after('stock_status');
        });
    }
}

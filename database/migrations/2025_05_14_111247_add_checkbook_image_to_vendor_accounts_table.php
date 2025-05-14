<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckbookImageToVendorAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_accounts', function (Blueprint $table) {
            $table->text('checkbook_image')->nullable();
            $table->unsignedBigInteger('location_id')->notNullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_accounts', function (Blueprint $table) {
            $table->dropColumn(['checkbook_image', 'location_id']);
        });
    }
}

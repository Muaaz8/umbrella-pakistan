<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PhysicalLocationworkinghours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('physical_locations', function (Blueprint $table) {
            $table->string('time_from')->nullable();
            $table->string('time_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('physical_location', function (Blueprint $table) {
            $table->dropColumn('time_from');
            $table->dropColumn('time_to');
        });
    }
}

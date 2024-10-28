<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecUserIDToTableAgoraAynalatics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table_agora_aynalatics', function (Blueprint $table) {
            $table->integer('secUserID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_agora_aynalatics', function (Blueprint $table) {
            $table->dropColumn('secUserID');
        });
    }
}

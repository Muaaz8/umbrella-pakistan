<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHl7payloadToQuestRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quest_requests', function (Blueprint $table) {
            $table->longText('hl7_payload')->after('orderHL7');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quest_requests', function (Blueprint $table) {
            $table->dropColumn('hl7_payload');
        });
    }
}
